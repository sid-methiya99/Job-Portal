<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';

class CreateJob {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        try {
            // Validate required fields
            $requiredFields = ['title', 'description', 'companyName', 'companyEmail'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    return [
                        'status' => 'error',
                        'message' => "Missing required field: $field"
                    ];
                }
            }

            $query = "INSERT INTO Job (
                id, userId, title, description, companyName, companyBio, companyEmail,
                category, type, workMode, currency, city, address, application,
                companyLogo, skills, minSalary, maxSalary, minExperience, maxExperience,
                hasSalaryRange, hasExperiencerange
            ) VALUES (
                UUID(), UUID(), :title, :description, :companyName, :companyBio, :companyEmail,
                :category, :type, :workMode, :currency, :city, :address, 'Apply with resume',
                :companyLogo, :skills, :minSalary, :maxSalary, :minExperience, :maxExperience,
                :hasSalaryRange, :hasExperiencerange
            )";

            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                'title' => $data['title'],
                'description' => $data['description'],
                'companyName' => $data['companyName'],
                'companyBio' => $data['companyBio'] ?? '',
                'companyEmail' => $data['companyEmail'],
                'category' => $data['category'],
                'type' => $data['type'],
                'workMode' => $data['workMode'],
                'currency' => $data['currency'] ?? 'INR',
                'city' => $data['city'],
                'address' => $data['address'],
                'companyLogo' => $data['companyLogo'] ?? 'default-logo.png',
                'skills' => json_encode($data['skills']),
                'minSalary' => $data['minSalary'] ?? 0,
                'maxSalary' => $data['maxSalary'] ?? 0,
                'minExperience' => $data['minExperience'] ?? 0,
                'maxExperience' => $data['maxExperience'] ?? 0,
                'hasSalaryRange' => $data['hasSalaryRange'] ?? true,
                'hasExperiencerange' => $data['hasExperiencerange'] ?? true
            ]);

            return [
                'status' => 'success',
                'message' => 'Job posted successfully'
            ];

        } catch(PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to post job: ' . $e->getMessage()
            ];
        }
    }
}

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $data = json_decode(file_get_contents("php://input"), true);
    
    $createJob = new CreateJob($db);
    $result = $createJob->create($data);
    
    echo json_encode($result);
}
?> 