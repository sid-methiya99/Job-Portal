<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get query parameters
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$workMode = isset($_GET['workMode']) ? $_GET['workMode'] : '';
$minSalary = isset($_GET['minSalary']) ? (int)$_GET['minSalary'] : null;
$maxSalary = isset($_GET['maxSalary']) ? (int)$_GET['maxSalary'] : null;

// Build query
$query = "SELECT * FROM Job WHERE expired = FALSE AND deleted = FALSE";
$params = array();

if (!empty($search)) {
    $query .= " AND (title LIKE :search OR description LIKE :search OR companyName LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($category)) {
    $query .= " AND category = :category";
    $params[':category'] = $category;
}

if (!empty($type)) {
    $query .= " AND type = :type";
    $params[':type'] = $type;
}

if (!empty($workMode)) {
    $query .= " AND workMode = :workMode";
    $params[':workMode'] = $workMode;
}

if ($minSalary !== null) {
    $query .= " AND minSalary >= :minSalary";
    $params[':minSalary'] = $minSalary;
}

if ($maxSalary !== null) {
    $query .= " AND maxSalary <= :maxSalary";
    $params[':maxSalary'] = $maxSalary;
}

// Add sorting
$query .= " ORDER BY postedAt DESC";

// Add pagination
$offset = ($page - 1) * $limit;
$query .= " LIMIT :limit OFFSET :offset";
$params[':limit'] = $limit;
$params[':offset'] = $offset;

try {
    $stmt = $db->prepare($query);
    
    // Bind parameters
    foreach ($params as $key => $value) {
        if (strpos($key, 'salary') !== false) {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
    }
    
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Process jobs data
    foreach ($jobs as &$job) {
        // Convert skills from JSON string to array
        $job['skills'] = json_decode($job['skills'], true);
        
        // Format dates
        $job['postedAt'] = date('Y-m-d H:i:s', strtotime($job['postedAt']));
        if ($job['expiryDate']) {
            $job['expiryDate'] = date('Y-m-d', strtotime($job['expiryDate']));
        }
        
        // Remove sensitive information
        unset($job['deleted']);
        unset($job['deletedAt']);
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => $jobs,
        'page' => $page,
        'limit' => $limit
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error occurred'
    ]);
}
?> 