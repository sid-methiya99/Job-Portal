CREATE DATABASE IF NOT EXISTS job_board;
USE job_board;

-- Users table
CREATE TABLE IF NOT EXISTS Users (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('ADMIN', 'HR', 'USER') NOT NULL,
    isVerified BOOLEAN DEFAULT FALSE,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Company table
CREATE TABLE IF NOT EXISTS Company (
    id VARCHAR(36) PRIMARY KEY,
    userId VARCHAR(36),
    companyName VARCHAR(100) NOT NULL,
    companyEmail VARCHAR(100) NOT NULL,
    companyBio TEXT,
    companyWebsite VARCHAR(255),
    companyLogo VARCHAR(255),
    isVerified BOOLEAN DEFAULT FALSE,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES Users(id)
);

-- Job table
CREATE TABLE IF NOT EXISTS Job (
    id VARCHAR(36) PRIMARY KEY,
    companyId VARCHAR(36) NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    type ENUM('FULL_TIME', 'PART_TIME', 'CONTRACT', 'INTERNSHIP') NOT NULL,
    workMode ENUM('REMOTE', 'HYBRID', 'OFFICE') NOT NULL,
    location VARCHAR(100) NOT NULL,
    salary_min INT,
    salary_max INT,
    experience_min INT,
    experience_max INT,
    skills JSON,
    isActive BOOLEAN DEFAULT TRUE,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (companyId) REFERENCES Company(id)
);

-- Create admin user
INSERT INTO Users (id, name, email, password, role, isVerified)
VALUES (
    UUID(), 
    'Admin',
    'admin@100xjobs.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'ADMIN',
    TRUE
);

CREATE TABLE Bookmark (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    jobId CHAR(36) NOT NULL,
    userId CHAR(36) NOT NULL,
    FOREIGN KEY (jobId) REFERENCES Job(id) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES Users(id) ON DELETE CASCADE -- Updated reference
);

CREATE TABLE Experience (
    id INT AUTO_INCREMENT PRIMARY KEY,
    companyName TEXT NOT NULL,
    designation TEXT NOT NULL,
    EmploymentType ENUM('Full_time', 'Part_time', 'Internship', 'Contract') NOT NULL,
    address TEXT NOT NULL,
    workMode ENUM('remote', 'hybrid', 'office') NOT NULL,
    currentWorkStatus BOOLEAN NOT NULL,
    startDate DATETIME NOT NULL,
    endDate DATETIME,
    description TEXT NOT NULL,
    userId CHAR(36) NOT NULL,
    FOREIGN KEY (userId) REFERENCES Users(id) ON DELETE CASCADE -- Updated reference
);

CREATE TABLE Education (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instituteName TEXT NOT NULL,
    degree ENUM('BTech', 'MTech', 'BCA', 'MCA') NOT NULL,
    startDate DATETIME NOT NULL,
    endDate DATETIME,
    userId CHAR(36) NOT NULL,
    FOREIGN KEY (userId) REFERENCES Users(id) ON DELETE CASCADE -- Updated reference
);