<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';

class RecentJobs {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getRecentJobs() {
        try {
            $query = "SELECT 
                        j.id, j.title, j.companyName, j.companyLogo,
                        j.type, j.workMode, j.minSalary, j.maxSalary,
                        j.city, j.skills, j.postedAt, j.currency,
                        j.minExperience, j.maxExperience
                    FROM Job j
                    WHERE j.expired = FALSE 
                    AND j.deleted = FALSE
                    ORDER BY j.postedAt DESC
                    LIMIT 6";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $jobs = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $job = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'companyName' => $row['companyName'],
                    'companyLogo' => $row['companyLogo'],
                    'type' => $row['type'],
                    'workMode' => $row['workMode'],
                    'salary' => [
                        'min' => $row['minSalary'],
                        'max' => $row['maxSalary'],
                        'currency' => $row['currency']
                    ],
                    'experience' => [
                        'min' => $row['minExperience'],
                        'max' => $row['maxExperience']
                    ],
                    'location' => $row['city'],
                    'skills' => json_decode($row['skills']),
                    'postedAt' => $row['postedAt']
                ];
                array_push($jobs, $job);
            }

            return json_encode([
                'status' => 'success',
                'data' => $jobs
            ]);

        } catch(PDOException $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Failed to fetch recent jobs'
            ]);
        }
    }
}

// Handle the request
$database = new Database();
$db = $database->getConnection();

$recentJobs = new RecentJobs($db);
echo $recentJobs->getRecentJobs();
?> 