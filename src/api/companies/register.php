<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['companyName']) || !isset($data['email']) || !isset($data['password'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Required fields missing'
        ]);
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();

    try {
        // First create user account with HR role
        $query = "INSERT INTO Users (id, name, email, password, role, isVerified) 
                 VALUES (UUID(), :name, :email, :password, 'HR', FALSE)";
        
        $stmt = $db->prepare($query);
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt->execute([
            'name' => $data['companyName'],
            'email' => $data['email'],
            'password' => $hashedPassword
        ]);

        $userId = $db->lastInsertId();

        // Then create company profile
        $query = "INSERT INTO Company (id, companyName, companyEmail, companyBio, companyWebsite, isVerified) 
                 VALUES (UUID(), :name, :email, :bio, :website, FALSE)";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            'name' => $data['companyName'],
            'email' => $data['email'],
            'bio' => $data['description'] ?? '',
            'website' => $data['website'] ?? ''
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Company registered successfully. Waiting for admin approval.'
        ]);

    } catch(PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Registration failed: ' . $e->getMessage()
        ]);
    }
}
?> 