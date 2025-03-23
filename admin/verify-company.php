<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'ADMIN') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: companies.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$userId = $_GET['id'];
$action = $_GET['action'] ?? 'verify';

try {
    $db->beginTransaction();

    // First update the User table
    if ($action === 'verify') {
        $query = "UPDATE Users SET isVerified = TRUE WHERE id = :id AND role = 'HR'";
        $message = "Company verified successfully!";
    } else {
        $query = "UPDATE Users SET isVerified = FALSE WHERE id = :id AND role = 'HR'";
        $message = "Company verification revoked successfully!";
    }
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $userId);
    $stmt->execute();

    // Then update the Company table
    $query = "UPDATE Company SET isVerified = :isVerified WHERE userId = :userId";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":userId", $userId);
    $isVerified = ($action === 'verify') ? true : false;
    $stmt->bindParam(":isVerified", $isVerified, PDO::PARAM_BOOL);
    $stmt->execute();

    $db->commit();
    $_SESSION['success_message'] = $message;
} catch (PDOException $e) {
    $db->rollBack();
    $_SESSION['error_message'] = "Database error occurred: " . $e->getMessage();
}

header("Location: companies.php");
exit();
?> 