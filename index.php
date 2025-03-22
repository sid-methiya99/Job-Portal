<?php
session_start();
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal</title>
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
                        <a href="/" class="text-gray-600 hover:text-blue-600 px-3 py-2">Home</a>
                        <a href="/jobs.php" class="text-gray-600 hover:text-blue-600 px-3 py-2">Jobs</a>
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="/auth/login.php" class="text-gray-600 hover:text-blue-600 px-3 py-2">Login</a>
                            <a href="/auth/register.php" class="bg-blue-600 text-white px-4 py-2 rounded-md">Register</a>
                        <?php else: ?>
                            <a href="/dashboard.php" class="text-gray-600 hover:text-blue-600 px-3 py-2">Dashboard</a>
                            <a href="/auth/logout.php" class="text-gray-600 hover:text-blue-600 px-3 py-2">Logout</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-blue-600">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">
                    Find Your Dream Job
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-blue-100 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Browse thousands of job listings from top companies and find the perfect opportunity for your career.
                </p>
                <div class="mt-10">
                    <form action="/jobs.php" method="GET" class="max-w-xl mx-auto">
                        <div class="flex shadow-sm rounded-md">
                            <input type="text" name="search" placeholder="Search jobs..." class="flex-1 rounded-l-md px-4 py-3 border-0 focus:ring-2 focus:ring-blue-500">
                            <button type="submit" class="bg-blue-700 text-white px-6 py-3 rounded-r-md hover:bg-blue-800">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-12 px-4">
        <!-- Content will be dynamically loaded here -->
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">About Us</h3>
                    <p class="text-gray-400">Connecting talented professionals with amazing opportunities.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="/jobs.php" class="text-gray-400 hover:text-white">Browse Jobs</a></li>
                        <li><a href="/companies.php" class="text-gray-400 hover:text-white">Companies</a></li>
                        <li><a href="/contact.php" class="text-gray-400 hover:text-white">Contact Us</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <p class="text-gray-400">Email: info@jobportal.com</p>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center">
                <p class="text-gray-400">&copy; <?php echo date('Y'); ?> Job Portal. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html> 