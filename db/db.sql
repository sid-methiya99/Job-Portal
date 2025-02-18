
CREATE TABLE User (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name TEXT NOT NULL,
    password TEXT,
    isVerified BOOLEAN DEFAULT FALSE,
    role ENUM('USER', 'ADMIN', 'HR') DEFAULT 'USER',
    email TEXT UNIQUE NOT NULL,
    emailVerified DATETIME,
    skills JSON,
    resume TEXT,
    createdAt DATETIME DEFAULT NOW(),
    blockedByAdmin DATETIME,
    onBoard BOOLEAN DEFAULT FALSE,
    githubLink TEXT,
    portfolioLink TEXT,
    linkedinLink TEXT,
    twitterLink TEXT,
    contactEmail TEXT,
    aboutMe TEXT,
    resumeUpdateDate DATETIME,
    companyId CHAR(36) UNIQUE,
    FOREIGN KEY (companyId) REFERENCES Company(id) ON DELETE SET NULL
);

CREATE TABLE Company (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    companyName TEXT NOT NULL,
    companyLogo TEXT,
    companyEmail TEXT NOT NULL,
    companyBio TEXT NOT NULL
);

CREATE TABLE Job (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    userId CHAR(36) NOT NULL,
    title TEXT NOT NULL,
    description TEXT,
    companyName TEXT NOT NULL,
    companyBio TEXT NOT NULL,
    companyEmail TEXT NOT NULL,
    category TEXT NOT NULL,
    type ENUM('Full_time', 'Part_time', 'Internship', 'Contract') NOT NULL,
    workMode ENUM('remote', 'hybrid', 'office') NOT NULL,
    currency ENUM('INR', 'USD') DEFAULT 'INR',
    city TEXT NOT NULL,
    address TEXT NOT NULL,
    application TEXT NOT NULL,
    companyLogo TEXT NOT NULL,
    skills JSON,
    expired BOOLEAN DEFAULT FALSE,
    hasExpiryDate BOOLEAN DEFAULT FALSE,
    expiryDate DATETIME,
    hasSalaryRange BOOLEAN DEFAULT FALSE,
    minSalary INT,
    maxSalary INT,
    hasExperiencerange BOOLEAN DEFAULT FALSE,
    minExperience INT,
    maxExperience INT,
    isVerifiedJob BOOLEAN DEFAULT FALSE,
    deleted BOOLEAN DEFAULT FALSE,
    deletedAt DATETIME,
    postedAt DATETIME DEFAULT NOW(),
    updatedAt DATETIME DEFAULT NOW() ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE
);

CREATE TABLE Bookmark (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    jobId CHAR(36) NOT NULL,
    userId CHAR(36) NOT NULL,
    FOREIGN KEY (jobId) REFERENCES Job(id) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE
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
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE
);

CREATE TABLE Education (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instituteName TEXT NOT NULL,
    degree ENUM('BTech', 'MTech', 'BCA', 'MCA') NOT NULL,
    startDate DATETIME NOT NULL,
    endDate DATETIME,
    userId CHAR(36) NOT NULL,
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE
);


