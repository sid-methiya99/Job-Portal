<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';

class JobSearch {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function searchJobs($params) {
        try {
            $conditions = [];
            $values = [];

            // Base query
            $query = "SELECT 
                        j.id, j.title, j.companyName, j.companyLogo,
                        j.type, j.workMode, j.minSalary, j.maxSalary,
                        j.city, j.skills, j.postedAt, j.currency
                    FROM Job j
                    WHERE j.expired = FALSE AND j.deleted = FALSE";

            // Add search filters
            if (!empty($params['keyword'])) {
                $conditions[] = "(j.title LIKE ? OR j.description LIKE ?)";
                $values[] = "%{$params['keyword']}%";
                $values[] = "%{$params['keyword']}%";
            }

            if (!empty($params['type'])) {
                $conditions[] = "j.type = ?";
                $values[] = $params['type'];
            }

            if (!empty($params['workMode'])) {
                $conditions[] = "j.workMode = ?";
                $values[] = $params['workMode'];
            }

            if (!empty($params['city'])) {
                $conditions[] = "j.city = ?";
                $values[] = $params['city'];
            }

            // Combine conditions
            if (!empty($conditions)) {
                $query .= " AND " . implode(" AND ", $conditions);
            }

            // Add sorting and pagination
            $query .= " ORDER BY j.postedAt DESC LIMIT ?, ?";
            $values[] = ($params['page'] - 1) * $params['limit'];
            $values[] = $params['limit'];

            $stmt = $this->conn->prepare($query);
            $stmt->execute($values);

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
                'message' => 'Failed to search jobs'
            ]);
        }
    }
}

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $database = new Database();
    $db = $database->getConnection();

    $searchParams = [
        'keyword' => $_GET['keyword'] ?? '',
        'type' => $_GET['type'] ?? '',
        'workMode' => $_GET['workMode'] ?? '',
        'city' => $_GET['city'] ?? '',
        'page' => intval($_GET['page'] ?? 1),
        'limit' => intval($_GET['limit'] ?? 10)
    ];

    $jobSearch = new JobSearch($db);
    echo $jobSearch->searchJobs($searchParams);
}
?> 