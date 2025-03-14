<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['id']) || !isset($data['approved'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Required fields missing'
        ]);
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();

    try {
        $db->beginTransaction();

        // Update company verification status
        $query = "UPDATE Company SET isVerified = :status WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([
            'status' => $data['approved'],
            'id' => $data['id']
        ]);

        // Update user verification status
        $query = "UPDATE Users u 
                 JOIN Company c ON c.userId = u.id 
                 SET u.isVerified = :status 
                 WHERE c.id = :companyId";
        $stmt = $db->prepare($query);
        $stmt->execute([
            'status' => $data['approved'],
            'companyId' => $data['id']
        ]);

        $db->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Company ' . ($data['approved'] ? 'approved' : 'rejected') . ' successfully'
        ]);

    } catch(PDOException $e) {
        $db->rollBack();
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update company: ' . $e->getMessage()
        ]);
    }
}
?> 