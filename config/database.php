<?php
class Database {
    private $host = "localhost";
    private $db_name = "job_board";
    private $username = "sid";
    private $password = "1234";
    private $conn;

    public function getConnection() {
        try {   
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch(PDOException $e) {
            throw new Exception("Database Connection Error: " . $e->getMessage());
        }
    }
}
?> 