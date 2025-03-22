<?php
class Job {
    private $conn;
    private $table = "Job";

    // Job properties
    public $id;
    public $userId;
    public $title;
    public $description;
    public $companyName;
    public $companyBio;
    public $companyEmail;
    public $category;
    public $type;
    public $workMode;
    public $currency;
    public $city;
    public $address;
    public $application;
    public $companyLogo;
    public $skills;
    public $expired;
    public $hasExpiryDate;
    public $expiryDate;
    public $hasSalaryRange;
    public $minSalary;
    public $maxSalary;
    public $hasExperiencerange;
    public $minExperience;
    public $maxExperience;
    public $isVerifiedJob;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . "
                (userId, title, description, companyName, companyBio, companyEmail,
                category, type, workMode, currency, city, address, application,
                companyLogo, skills, hasExpiryDate, expiryDate, hasSalaryRange,
                minSalary, maxSalary, hasExperiencerange, minExperience, maxExperience)
                VALUES
                (:userId, :title, :description, :companyName, :companyBio, :companyEmail,
                :category, :type, :workMode, :currency, :city, :address, :application,
                :companyLogo, :skills, :hasExpiryDate, :expiryDate, :hasSalaryRange,
                :minSalary, :maxSalary, :hasExperiencerange, :minExperience, :maxExperience)";

        $stmt = $this->conn->prepare($query);

        // Clean and sanitize data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->companyName = htmlspecialchars(strip_tags($this->companyName));
        $this->companyBio = htmlspecialchars(strip_tags($this->companyBio));
        $this->companyEmail = htmlspecialchars(strip_tags($this->companyEmail));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->workMode = htmlspecialchars(strip_tags($this->workMode));
        $this->currency = htmlspecialchars(strip_tags($this->currency));
        $this->city = htmlspecialchars(strip_tags($this->city));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->application = htmlspecialchars(strip_tags($this->application));
        $this->companyLogo = htmlspecialchars(strip_tags($this->companyLogo));
        $this->skills = json_encode($this->skills);

        // Bind data
        $stmt->bindParam(":userId", $this->userId);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":companyName", $this->companyName);
        $stmt->bindParam(":companyBio", $this->companyBio);
        $stmt->bindParam(":companyEmail", $this->companyEmail);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":workMode", $this->workMode);
        $stmt->bindParam(":currency", $this->currency);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":application", $this->application);
        $stmt->bindParam(":companyLogo", $this->companyLogo);
        $stmt->bindParam(":skills", $this->skills);
        $stmt->bindParam(":hasExpiryDate", $this->hasExpiryDate);
        $stmt->bindParam(":expiryDate", $this->expiryDate);
        $stmt->bindParam(":hasSalaryRange", $this->hasSalaryRange);
        $stmt->bindParam(":minSalary", $this->minSalary);
        $stmt->bindParam(":maxSalary", $this->maxSalary);
        $stmt->bindParam(":hasExperiencerange", $this->hasExperiencerange);
        $stmt->bindParam(":minExperience", $this->minExperience);
        $stmt->bindParam(":maxExperience", $this->maxExperience);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . "
                SET title = :title,
                    description = :description,
                    category = :category,
                    type = :type,
                    workMode = :workMode,
                    currency = :currency,
                    city = :city,
                    address = :address,
                    application = :application,
                    skills = :skills,
                    hasExpiryDate = :hasExpiryDate,
                    expiryDate = :expiryDate,
                    hasSalaryRange = :hasSalaryRange,
                    minSalary = :minSalary,
                    maxSalary = :maxSalary,
                    hasExperiencerange = :hasExperiencerange,
                    minExperience = :minExperience,
                    maxExperience = :maxExperience,
                    updatedAt = NOW()
                WHERE id = :id AND userId = :userId";

        $stmt = $this->conn->prepare($query);

        // Clean and sanitize data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->workMode = htmlspecialchars(strip_tags($this->workMode));
        $this->currency = htmlspecialchars(strip_tags($this->currency));
        $this->city = htmlspecialchars(strip_tags($this->city));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->application = htmlspecialchars(strip_tags($this->application));
        $this->skills = json_encode($this->skills);

        // Bind data
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":userId", $this->userId);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":workMode", $this->workMode);
        $stmt->bindParam(":currency", $this->currency);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":application", $this->application);
        $stmt->bindParam(":skills", $this->skills);
        $stmt->bindParam(":hasExpiryDate", $this->hasExpiryDate);
        $stmt->bindParam(":expiryDate", $this->expiryDate);
        $stmt->bindParam(":hasSalaryRange", $this->hasSalaryRange);
        $stmt->bindParam(":minSalary", $this->minSalary);
        $stmt->bindParam(":maxSalary", $this->maxSalary);
        $stmt->bindParam(":hasExperiencerange", $this->hasExperiencerange);
        $stmt->bindParam(":minExperience", $this->minExperience);
        $stmt->bindParam(":maxExperience", $this->maxExperience);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "UPDATE " . $this->table . "
                SET deleted = TRUE,
                    deletedAt = NOW()
                WHERE id = :id AND userId = :userId";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":userId", $this->userId);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read($id) {
        $query = "SELECT * FROM " . $this->table . "
                WHERE id = :id AND deleted = FALSE";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $id);
        
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verifyJob($id) {
        $query = "UPDATE " . $this->table . "
                SET isVerifiedJob = TRUE
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getUserJobs($userId) {
        if (!$this->conn) {
            throw new Exception("Database connection not established");
        }

        // First get the company ID for this user
        $query = "SELECT id FROM Company WHERE userId = :userId LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        $company = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$company) {
            // If no company found, return empty array
            return array();
        }

        // Now get all jobs for this company
        $query = "SELECT * FROM " . $this->table . "
                WHERE companyId = :companyId AND deleted = FALSE
                ORDER BY postedAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":companyId", $company['id']);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllJobsWithPagination($limit, $offset) {
        if (!$this->conn) {
            throw new Exception("Database connection not established");
        }

        $query = "SELECT j.*, u.name as postedByName, u.email as postedByEmail 
                FROM " . $this->table . " j
                LEFT JOIN Users u ON j.userId = u.id
                WHERE j.deleted = FALSE
                ORDER BY j.postedAt DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalJobs() {
        if (!$this->conn) {
            throw new Exception("Database connection not established");
        }

        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE deleted = FALSE";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function expireJob($jobId) {
        if (!$this->conn) {
            throw new Exception("Database connection not established");
        }

        $query = "UPDATE " . $this->table . "
                SET expired = TRUE,
                    expiryDate = NOW()
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $jobId);
        
        return $stmt->execute();
    }

    public function activateJob($jobId) {
        if (!$this->conn) {
            throw new Exception("Database connection not established");
        }

        $query = "UPDATE " . $this->table . "
                SET expired = FALSE,
                    expiryDate = NULL
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $jobId);
        
        return $stmt->execute();
    }

    public function getJobStats() {
        if (!$this->conn) {
            throw new Exception("Database connection not established");
        }

        $stats = array();

        // Total active jobs
        $query = "SELECT COUNT(*) as count FROM " . $this->table . "
                WHERE deleted = FALSE AND expired = FALSE";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['activeJobs'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Jobs by type
        $query = "SELECT type, COUNT(*) as count FROM " . $this->table . "
                WHERE deleted = FALSE
                GROUP BY type";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['jobsByType'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Jobs posted in last 7 days
        $query = "SELECT COUNT(*) as count FROM " . $this->table . "
                WHERE postedAt >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                AND deleted = FALSE";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['newJobsLastWeek'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Jobs by work mode
        $query = "SELECT workMode, COUNT(*) as count FROM " . $this->table . "
                WHERE deleted = FALSE
                GROUP BY workMode";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['jobsByWorkMode'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    public function searchJobs($searchTerm) {
        if (!$this->conn) {
            throw new Exception("Database connection not established");
        }

        $query = "SELECT j.*, u.name as postedByName, u.email as postedByEmail 
                FROM " . $this->table . " j
                LEFT JOIN Users u ON j.userId = u.id
                WHERE j.deleted = FALSE 
                AND (j.title LIKE :search 
                    OR j.description LIKE :search 
                    OR j.companyName LIKE :search)
                ORDER BY j.postedAt DESC";
        
        $searchTerm = "%$searchTerm%";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":search", $searchTerm);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getJobsByStatus($status) {
        if (!$this->conn) {
            throw new Exception("Database connection not established");
        }

        $query = "SELECT j.*, u.name as postedByName, u.email as postedByEmail 
                FROM " . $this->table . " j
                LEFT JOIN Users u ON j.userId = u.id
                WHERE j.deleted = FALSE";

        switch ($status) {
            case 'active':
                $query .= " AND j.expired = FALSE AND j.isVerifiedJob = TRUE";
                break;
            case 'expired':
                $query .= " AND j.expired = TRUE";
                break;
            case 'pending':
                $query .= " AND j.isVerifiedJob = FALSE";
                break;
        }

        $query .= " ORDER BY j.postedAt DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 