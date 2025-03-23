<?php
require_once '../config/database.php';
require_once '../classes/Job.php';
require_once '../classes/Company.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();
    $job = new Job($db);

    // Get query parameters
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $search = isset($_GET['search']) ? $_GET['search'] : "";
    $type = isset($_GET['type']) ? $_GET['type'] : "";
    $workMode = isset($_GET['workMode']) ? $_GET['workMode'] : "";
    $minSalary = isset($_GET['minSalary']) ? (float)$_GET['minSalary'] : 0;

    // Calculate offset
    $offset = ($page - 1) * $limit;

    // Get jobs
    $stmt = $job->getActiveJobs($limit, $offset, $search, $type, $workMode, $minSalary);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total count for pagination
    $totalJobs = $job->countActiveJobs($search, $type, $workMode, $minSalary);

    // Calculate total pages
    $totalPages = ceil($totalJobs / $limit);

    // Prepare response
    $response = [
        'status' => 'success',
        'data' => [
            'jobs' => $jobs,
            'pagination' => [
                'total' => $totalJobs,
                'totalPages' => $totalPages,
                'currentPage' => $page,
                'limit' => $limit
            ]
        ]
    ];

    echo json_encode($response);

} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to load jobs: ' . $e->getMessage()
    ]);
}
?> 