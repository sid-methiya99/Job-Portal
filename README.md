# Job Portal Application

A comprehensive job portal web application built with PHP and MySQL that connects job seekers with employers. The platform facilitates job postings, applications, and hiring processes with a clean, modern interface.

## Features

### For Job Seekers (Users)
- Create and manage professional profiles
- Search and apply for jobs
- Track application status
- Upload resumes and cover letters
- View job recommendations
- Manage application history

### For Employers (HR)
- Company profile management
- Post and manage job listings
- Review applications
- Shortlist and hire candidates
- Track applicant status
- Verify company credentials

### For Administrators
- User management
- Job posting moderation
- Company verification
- Platform statistics
- Content moderation

## Technology Stack

- PHP 7.4+
- MySQL 5.7+
- HTML5/CSS3
- TailwindCSS
- JavaScript
- PDO for database operations

## Directory Structure

```
Job-Portal/
├── admin/                  # Admin dashboard and management
│   ├── dashboard.php      # Admin main dashboard
│   ├── jobs.php          # Job moderation
│   └── users.php         # User management
├── api/                   # API endpoints
│   └── jobs.php          # Jobs API
├── auth/                  # Authentication
│   ├── login.php         # User login
│   ├── logout.php        # Logout handler
│   └── register.php      # User registration
├── classes/              # Core classes
│   ├── Company.php       # Company management
│   ├── Job.php          # Job operations
│   └── User.php         # User operations
├── company/              # HR/Company features
│   ├── dashboard.php     # Company dashboard
│   ├── jobs.php         # Job management
│   ├── post-job.php     # Job posting
│   ├── profile.php      # Company profile
│   └── view-applications.php # Application management
├── config/               # Configuration
│   └── database.php     # Database connection
├── uploads/             # File uploads
│   ├── company_logos/   # Company logos
│   └── resumes/         # User resumes
└── index.php            # Main entry point
```

## Key Files and Their Functions

### Core Classes
- `classes/User.php`: Handles user authentication, profile management, and role-based operations
- `classes/Job.php`: Manages job posting, searching, and application processes
- `classes/Company.php`: Handles company profile and verification

### Authentication
- `auth/login.php`: User login with role-based redirection
- `auth/register.php`: User registration with role selection
- `auth/logout.php`: Session termination

### Company Management
- `company/post-job.php`: Job creation and editing
- `company/view-applications.php`: Application review and candidate selection
- `company/update-status.php`: Updates application statuses (shortlist/hire/reject)

### Admin Controls
- `admin/dashboard.php`: Admin overview and statistics
- `admin/verify-company.php`: Company verification process
- `admin/jobs.php`: Job moderation and management

## Database Schema

The application uses the following main tables:
- Users
- Company
- Job
- Applications
- Experience
- Education

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/Job-Portal.git
```

2. Set up the database:
- Create a MySQL database named 'job_board'
- Import the database schema from `db/db.sql`

3. Configure database connection:
- Update `config/database.php` with your credentials:
```php
private $host = "localhost";
private $db_name = "job_board";
private $username = "your_username";
private $password = "your_password";
```

4. Set up the web server:
- Configure your web server (Apache/Nginx) to point to the project directory
- Ensure PHP 7.4+ is installed and configured

5. Set up file permissions:
```bash
chmod 755 -R Job-Portal/
chmod 777 -R Job-Portal/uploads/
```

## Initial Setup

1. Create an admin user:
- The default admin credentials are:
  - Email: admin@100xjobs.com
  - Password: password

2. First-time login:
- Log in as admin
- Verify HR accounts
- Monitor job postings

## Security Considerations

- All user passwords are hashed using PHP's password_hash()
- SQL injection prevention using PDO prepared statements
- XSS protection with output escaping
- CSRF protection on forms
- Role-based access control

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details. 