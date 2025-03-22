<?php
session_start();
require_once '../config/database.php';
require_once '../classes/Job.php';
require_once '../classes/User.php';

// Check if user is logged in and is an HR
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'HR') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$job = new Job($db);
$user = new User($db);

// Get company profile
$query = "SELECT * FROM Company WHERE userId = :userId LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(":userId", $_SESSION['user_id']);
$stmt->execute();
$companyProfile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$companyProfile) {
    header("Location: profile.php");
    exit();
}

// Handle job actions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['jobId'])) {
    $jobId = $_POST['jobId'];
    
    switch ($_POST['action']) {
        case 'delete':
            if ($job->delete($jobId)) {
                $message = "Job deleted successfully";
                $messageType = "success";
            }
            break;
        case 'expire':
            if ($job->expireJob($jobId)) {
                $message = "Job marked as expired";
                $messageType = "success";
            }
            break;
        case 'activate':
            if ($job->activateJob($jobId)) {
                $message = "Job activated successfully";
                $messageType = "success";
            }
            break;
    }
}

// Get all jobs for this company
$companyJobs = $job->getUserJobs($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs - Job Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                    <a href="/" class="text-2xl font-bold text-blue-600">Job Portal</a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="dashboard.php" class="text-gray-600 hover:text-blue-600 px-3 py-2">Dashboard</a>
                        <a href="jobs.php" class="text-blue-600 px-3 py-2">My Jobs</a>
                        <a href="post-job.php" class="text-gray-600 hover:text-blue-600 px-3 py-2">Post Job</a>
                        <a href="profile.php" class="text-gray-600 hover:text-blue-600 px-3 py-2">Profile</a>
                        <a href="../auth/logout.php" class="text-gray-600 hover:text-blue-600 px-3 py-2">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="px-4 py-6 sm:px-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Manage Jobs</h1>
                    <p class="mt-2 text-gray-600">View and manage your job listings.</p>
                </div>
                <a href="post-job.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Post New Job
                </a>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="mb-4 p-4 rounded-md <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Jobs List -->
        <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-md">
            <?php if (empty($companyJobs)): ?>
                <div class="p-4 text-center text-gray-500">
                    No jobs posted yet. 
                    <a href="post-job.php" class="text-blue-600 hover:text-blue-800">Post your first job</a>
                </div>
            <?php else: ?>
                <ul class="divide-y divide-gray-200">
                    <?php foreach ($companyJobs as $job): ?>
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-medium text-blue-600 truncate">
                                            <?php echo htmlspecialchars($job['title']); ?>
                                        </h3>
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <i class="fas fa-building mr-1.5"></i>
                                            <?php echo htmlspecialchars($companyProfile['companyName']); ?>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-map-marker-alt mr-1.5"></i>
                                            <?php echo htmlspecialchars($job['city']); ?>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-clock mr-1.5"></i>
                                            <?php echo htmlspecialchars($job['type']); ?>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-laptop-house mr-1.5"></i>
                                            <?php echo htmlspecialchars($job['workMode']); ?>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <?php if ($job['expired']): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Expired
                                            </span>
                                        <?php elseif (!$job['isVerifiedJob']): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending Verification
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-between items-center">
                                    <div class="text-sm text-gray-500">
                                        Posted on <?php echo date('M d, Y', strtotime($job['postedAt'])); ?>
                                        <?php if ($job['expiryDate']): ?>
                                            <span class="mx-2">•</span>
                                            Expires on <?php echo date('M d, Y', strtotime($job['expiryDate'])); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex space-x-3">
                                        <a href="edit-job.php?id=<?php echo $job['id']; ?>" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="view-applications.php?job_id=<?php echo $job['id']; ?>" 
                                           class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-users"></i> Applications
                                        </a>
                                        <form action="jobs.php" method="POST" class="inline">
                                            <input type="hidden" name="jobId" value="<?php echo $job['id']; ?>">
                                            <?php if (!$job['expired']): ?>
                                                <button type="submit" name="action" value="expire" 
                                                    class="text-yellow-600 hover:text-yellow-900 bg-transparent border-none p-0">
                                                    <i class="fas fa-clock"></i> Expire
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" name="action" value="activate" 
                                                    class="text-green-600 hover:text-green-900 bg-transparent border-none p-0">
                                                    <i class="fas fa-check"></i> Activate
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                        <form action="jobs.php" method="POST" class="inline">
                                            <input type="hidden" name="jobId" value="<?php echo $job['id']; ?>">
                                            <button type="submit" name="action" value="delete" 
                                                class="text-red-600 hover:text-red-900 bg-transparent border-none p-0"
                                                onclick="return confirm('Are you sure you want to delete this job? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 