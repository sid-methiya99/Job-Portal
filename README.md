# Job Portal - PHP Application

A comprehensive job portal application built with PHP, MySQL, and Tailwind CSS. This project demonstrates modern web development practices with containerization and CI/CD integration.

## 🚀 Quick Start with Docker (Windows)

The easiest way to run this project on Windows:

```cmd
# Clone the repository
git clone <your-repository-url>
cd Job-Portal

# Start all services
docker-compose up --build

# Access the application
# Job Portal: http://localhost:8080
# phpMyAdmin: http://localhost:8081 (user: jobportal, pass: jobportal123)
```

## 📋 Features

- **User Management**: Registration, login, and role-based access control
- **Job Posting**: Companies can post job opportunities
- **Job Search**: Users can search and apply for jobs
- **Application Management**: Track job applications and status
- **Admin Panel**: Manage users, jobs, and company verifications
- **Responsive Design**: Built with Tailwind CSS for mobile-friendly interface

## 🛠️ Technology Stack

- **Backend**: PHP 8.1
- **Database**: MySQL 8.0
- **Frontend**: HTML, CSS (Tailwind CSS), JavaScript
- **Web Server**: Apache
- **Containerization**: Docker & Docker Compose
- **CI/CD**: Jenkins (optional)

## 🐳 Docker Setup (Windows Optimized)

This project includes Windows-optimized Docker configuration for development:

- **Multi-container setup** with PHP app, MySQL database, and phpMyAdmin
- **Windows-optimized** volume mounting for better performance
- **Development-friendly** with hot reloading
- **Pre-configured** database with sample data

### Services

- **App**: PHP application on port 8080
- **Database**: MySQL on port 3306
- **phpMyAdmin**: Database management on port 8081

## 🔧 Development (Windows)

### Prerequisites

- Docker Desktop for Windows
- Git

### Local Development

1. **Start the application**:
   ```cmd
   docker-compose up -d
   ```

2. **Access the application**:
   - Main app: http://localhost:8080
   - Database: http://localhost:8081

3. **Make changes**: PHP files are mounted as volumes, so changes reflect immediately

4. **Rebuild CSS**: If you modify Tailwind CSS
   ```cmd
   docker-compose exec app npm run dev
   ```

### Database Management

- **phpMyAdmin**: http://localhost:8081
  - Username: `jobportal`
  - Password: `jobportal123`
- **Direct MySQL**: `docker-compose exec db mysql -u jobportal -p job_board`

## 🧪 Testing

### Manual Testing

```cmd
# Start services
docker-compose up -d

# Test application
curl http://localhost:8080

# Check database
docker-compose exec db mysql -u jobportal -p job_board
```

### Automated Testing with Jenkins

The project includes a `Jenkinsfile` for CI/CD pipeline:

1. Install Jenkins and required plugins
2. Create a new pipeline job
3. Point to this repository
4. The pipeline will automatically build, test, and validate the application

## 📁 Project Structure

```
Job-Portal/
├── admin/              # Admin panel files
├── api/                # API endpoints
├── auth/               # Authentication files
├── classes/            # PHP classes
├── company/            # Company dashboard
├── config/             # Configuration files
├── db/                 # Database schema
├── uploads/            # File uploads
├── docker/             # Docker configuration
├── Dockerfile          # PHP application container
├── docker-compose.yml  # Multi-container setup
├── Jenkinsfile         # CI/CD pipeline
└── DEVOPS.md          # Detailed setup guide
```

## 🎓 College Project Features

This project demonstrates:

- **Modern Web Development**: PHP, MySQL, responsive design
- **DevOps Practices**: Docker containerization, CI/CD pipeline
- **Database Design**: Relational database with proper relationships
- **Security**: User authentication and role-based access
- **User Experience**: Intuitive interface with Tailwind CSS

### For Presentation

1. **Setup Demo**: Show `docker-compose up --build`
2. **Application Demo**: Navigate through the job portal
3. **Database Demo**: Show phpMyAdmin interface
4. **Architecture Demo**: Explain Docker containers and services
5. **CI/CD Demo**: Run Jenkins pipeline (if available)
