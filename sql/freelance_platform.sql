CREATE DATABASE IF NOT EXISTS freelance_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE freelance_platform;

DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('freelancer', 'client') NOT NULL DEFAULT 'freelancer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE projects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    description TEXT NOT NULL,
    budget DECIMAL(12,2) NOT NULL,
    deadline DATE NOT NULL,
    skills_required VARCHAR(255) DEFAULT NULL,
    status ENUM('open', 'closed') NOT NULL DEFAULT 'open',
    posted_by INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_projects_user FOREIGN KEY (posted_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (full_name, email, password_hash, role) VALUES
('Demo Client', 'client@demo.com', '$2y$10$QWfFlvDEl89rwJv89hkYUehh6thZkhXqUbozlWVl8EGjWTkQ2QYP2', 'client'),
('Demo Freelancer', 'freelancer@demo.com', '$2y$10$QWfFlvDEl89rwJv89hkYUehh6thZkhXqUbozlWVl8EGjWTkQ2QYP2', 'freelancer');

INSERT INTO projects (title, description, budget, deadline, skills_required, status, posted_by) VALUES
('Build Company Website', 'Need a responsive website with CMS, SEO optimization, and performance tuning.', 1500.00, '2026-06-15', 'PHP,HTML,CSS,MySQL', 'open', 1),
('Mobile App API', 'Create RESTful API for mobile application with authentication and reporting modules.', 3200.00, '2026-07-10', 'PHP,Laravel,API,MySQL', 'open', 1);

-- Demo user password for both accounts: password123
