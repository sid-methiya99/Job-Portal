<?php
class Job {
    private $conn;
    private $table_name = "Job";

    public $id;
    public $companyId;
    public $title;
    public $description;
    public $requirements;
    public $location;
    public $salary;
    public $type;
    public $workMode;
    public $isActive;
    public $expired;
    public $deleted;
    public $createdAt;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getActiveJobs($limit = 10, $offset = 0, $search = "", $type = "", $workMode = "", $minSalary = 0) {
        $query = "SELECT j.*, c.companyName, c.companyLogo, c.isVerified 
                 FROM " . $this->table_name . " j
                 JOIN Company c ON j.companyId = c.id
                 WHERE j.isActive = TRUE 
                 AND j.expired = FALSE 
                 AND j.deleted = FALSE";

        if (!empty($search)) {
            $query .= " AND (j.title LIKE :search OR j.description LIKE :search OR j.location LIKE :search)";
        }
        if (!empty($type)) {
            $query .= " AND j.type = :type";
        }
        if (!empty($workMode)) {
            $query .= " AND j.workMode = :workMode";
        }
        if ($minSalary > 0) {
            $query .= " AND j.salary >= :minSalary";
        }

        $query .= " ORDER BY j.createdAt DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);

        if (!empty($search)) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(":search", $searchParam);
        }
        if (!empty($type)) {
            $stmt->bindParam(":type", $type);
        }
        if (!empty($workMode)) {
            $stmt->bindParam(":workMode", $workMode);
        }
        if ($minSalary > 0) {
            $stmt->bindParam(":minSalary", $minSalary);
        }

        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt;
    }

    public function getCompanyJobs($companyId) {
        $query = "SELECT j.*, 
                 (SELECT COUNT(*) FROM Applications a WHERE a.jobId = j.id) as applicationCount,
                 (SELECT COUNT(*) FROM Applications a WHERE a.jobId = j.id AND a.status = 'PENDING') as pendingCount
                 FROM " . $this->table_name . " j
                 WHERE j.companyId = :companyId AND j.deleted = FALSE
                 ORDER BY j.createdAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":companyId", $companyId);
        $stmt->execute();
        return $stmt;
    }

    public function getJob($id) {
        $query = "SELECT j.*, c.companyName, c.companyLogo, c.companyBio, c.companyWebsite, c.isVerified 
                 FROM " . $this->table_name . " j
                 JOIN Company c ON j.companyId = c.id
                 WHERE j.id = :id AND j.deleted = FALSE";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt;
    }

    public function countActiveJobs($search = "", $type = "", $workMode = "", $minSalary = 0) {
        $query = "SELECT COUNT(*) as total 
                 FROM " . $this->table_name . " j
                 WHERE j.isActive = TRUE AND j.expired = FALSE AND j.deleted = FALSE";

        if (!empty($search)) {
            $query .= " AND (j.title LIKE :search OR j.description LIKE :search OR j.location LIKE :search)";
        }
        if (!empty($type)) {
            $query .= " AND j.type = :type";
        }
        if (!empty($workMode)) {
            $query .= " AND j.workMode = :workMode";
        }
        if ($minSalary > 0) {
            $query .= " AND j.salary >= :minSalary";
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($search)) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(":search", $searchParam);
        }
        if (!empty($type)) {
            $stmt->bindParam(":type", $type);
        }
        if (!empty($workMode)) {
            $stmt->bindParam(":workMode", $workMode);
        }
        if ($minSalary > 0) {
            $stmt->bindParam(":minSalary", $minSalary);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getAllJobsWithPagination($limit = 10, $offset = 0) {
        $query = "SELECT j.*, c.companyName, c.companyLogo, c.isVerified,
                 (SELECT COUNT(*) FROM Applications a WHERE a.jobId = j.id) as applicationCount
                 FROM " . $this->table_name . " j
                 LEFT JOIN Company c ON j.companyId = c.id
                 ORDER BY j.createdAt DESC
                 LIMIT :limit OFFSET :offset";
                 
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get total count for pagination
        $countQuery = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $countStmt = $this->conn->prepare($countQuery);
        $countStmt->execute();
        $totalJobs = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalJobs / $limit);
        
        return [
            'jobs' => $jobs,
            'currentPage' => ($offset / $limit) + 1,
            'totalPages' => $totalPages,
            'totalJobs' => $totalJobs
        ];
    }

    public function getTotalJobs() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE deleted = FALSE";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function expireJob($jobId) {
        if (!$this->conn) {
            throw new Exception("Database connection not established");
        }

        $query = "UPDATE " . $this->table_name . " 
                 SET expired = TRUE, 
                     expiryDate = NOW() 
                 WHERE id = :id AND deleted = FALSE";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $jobId);
        return $stmt->execute();
    }

    public function activateJob($jobId) {
        if (!$this->conn) {
            throw new Exception("Database connection not established");
        }

        $query = "UPDATE " . $this->table_name . " 
                 SET expired = FALSE, 
                     expiryDate = NULL 
                 WHERE id = :id AND deleted = FALSE";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $jobId);
        return $stmt->execute();
    }

    public function verifyJob($jobId) {
        if (!$this->conn) {
            throw new Exception("Database connection not established");
        }

        $query = "UPDATE " . $this->table_name . " 
                 SET isVerifiedJob = TRUE 
                 WHERE id = :id AND deleted = FALSE";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $jobId);
        return $stmt->execute();
    }

    public function delete($jobId) {
        if (!$this->conn) {
            throw new Exception("Database connection not established");
        }

        $query = "UPDATE " . $this->table_name . " 
                 SET deleted = TRUE, 
                     deletedAt = NOW() 
                 WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $jobId);
        return $stmt->execute();
    }
}
?> 