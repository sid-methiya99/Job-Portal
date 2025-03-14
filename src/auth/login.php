<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['email']) || !isset($data['password'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email and password are required'
        ]);
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT id, password, role FROM Users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->execute(['email' => $data['email']]);
    
    if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($data['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect based on role
            $redirect = 'explore.html';
            if ($user['role'] === 'ADMIN') {
                $redirect = 'admin/index.html';
            } else if ($user['role'] === 'HR') {
                $redirect = 'companies/dashboard.html';
            }
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Login successful',
                'redirect' => $redirect
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid password'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'User not found'
        ]);
    }
}
?> 