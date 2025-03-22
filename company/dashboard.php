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

// Get HR user details
$hrUser = $user->getUser($_SESSION['user_id']);

// Check if company profile exists
$query = "SELECT * FROM Company WHERE userId = :userId LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(":userId", $_SESSION['user_id']);
$stmt->execute();
$companyProfile = $stmt->fetch(PDO::FETCH_ASSOC);

// Get company's jobs
$companyJobs = $job->getUserJobs($_SESSION['user_id']);

// Calculate statistics
$totalJobs = count($companyJobs);
$activeJobs = 0;
$expiredJobs = 0;
$pendingJobs = 0;

foreach ($companyJobs as $job) {
    if ($job['expired']) {
        $expiredJobs++;
    } elseif (!$job['isVerifiedJob']) {
        $pendingJobs++;
    } else {
        $activeJobs++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard - Job Portal</title>
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
                        <a href="dashboard.php" class="text-blue-600 px-3 py-2">Dashboard</a>
                        <a href="jobs.php" class="text-gray-600 hover:text-blue-600 px-3 py-2">My Jobs</a>
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
        <?php if (!$companyProfile): ?>
        <!-- Company Profile Not Set Up Warning -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        You haven't set up your company profile yet. Please set up your profile before posting jobs.
                    </p>
                    <p class="mt-3">
                        <a href="profile.php" class="font-medium text-yellow-700 underline hover:text-yellow-600">
                            Set up company profile →
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Welcome Section -->
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-3xl font-bold text-gray-900">Welcome, <?php echo htmlspecialchars($hrUser['name']); ?>!</h1>
            <p class="mt-2 text-gray-600">Manage your job postings and view applications from your dashboard.</p>
        </div>

        <!-- Stats Grid -->
        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Jobs Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-briefcase text-blue-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Jobs</dt>
                                <dd class="text-3xl font-semibold text-gray-900"><?php echo $totalJobs; ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Jobs Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Jobs</dt>
                                <dd class="text-3xl font-semibold text-gray-900"><?php echo $activeJobs; ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Jobs Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Jobs</dt>
                                <dd class="text-3xl font-semibold text-gray-900"><?php echo $pendingJobs; ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expired Jobs Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-times text-red-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Expired Jobs</dt>
                                <dd class="text-3xl font-semibold text-gray-900"><?php echo $expiredJobs; ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($companyProfile): ?>
        <!-- Recent Jobs Section -->
        <div class="mt-8">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900">Recent Job Postings</h2>
                <a href="jobs.php" class="text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-md">
                <?php if (empty($companyJobs)): ?>
                <div class="p-4 text-center text-gray-500">
                    No jobs posted yet. 
                    <a href="post-job.php" class="text-blue-600 hover:text-blue-800">Post your first job</a>
                </div>
                <?php else: ?>
                <ul class="divide-y divide-gray-200">
                    <?php 
                    $recentJobs = array_slice($companyJobs, 0, 5); // Get last 5 jobs
                    foreach ($recentJobs as $job): 
                    ?>
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-medium text-blue-600 truncate">
                                        <?php echo htmlspecialchars($job['title']); ?>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        <?php echo htmlspecialchars($job['type']); ?> • 
                                        <?php echo htmlspecialchars($job['workMode']); ?> • 
                                        <?php echo htmlspecialchars($job['city']); ?>
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <?php if ($job['expired']): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Expired
                                        </span>
                                    <?php elseif (!$job['isVerifiedJob']): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mt-2 flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    Posted on <?php echo date('M d, Y', strtotime($job['postedAt'])); ?>
                                </div>
                                <div>
                                    <a href="edit-job.php?id=<?php echo $job['id']; ?>" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                    <a href="view-applications.php?job_id=<?php echo $job['id']; ?>" class="text-green-600 hover:text-green-800">View Applications</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900">Quick Actions</h2>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <?php if ($companyProfile): ?>
                <a href="post-job.php" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center">
                        <i class="fas fa-plus-circle text-blue-500 text-2xl"></i>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Post New Job</h3>
                            <p class="text-gray-500">Create a new job listing</p>
                        </div>
                    </div>
                </a>
                <?php endif; ?>
                <a href="profile.php" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center">
                        <i class="fas fa-user-edit text-blue-500 text-2xl"></i>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900"><?php echo $companyProfile ? 'Update Profile' : 'Set Up Profile'; ?></h3>
                            <p class="text-gray-500"><?php echo $companyProfile ? 'Edit your company information' : 'Complete your company profile'; ?></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
</html> 