<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $database = new Database();
    $db = $database->getConnection();

    try {
        $query = "SELECT c.*, u.email 
                 FROM Company c 
                 JOIN Users u ON c.userId = u.id 
                 WHERE c.isVerified = FALSE";
        
        $stmt = $db->prepare($query);
        $stmt->execute();

        $companies = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $companies[] = [
                'id' => $row['id'],
                'name' => $row['companyName'],
                'email' => $row['email'],
                'description' => $row['companyBio'],
                'website' => $row['companyWebsite']
            ];
        }

        echo json_encode([
            'status' => 'success',
            'companies' => $companies
        ]);

    } catch(PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to fetch companies: ' . $e->getMessage()
        ]);
    }
}
?> 