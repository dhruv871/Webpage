# FreelanceHub (PHP + MySQL)

A complete freelancing platform project built with core PHP and MySQL.

## Features
- User registration and login system (secure password hashing)
- Role selection (`client` / `freelancer`)
- Project posting form
- Personal dashboard with project statistics
- My Projects listing
- Browse all projects page
- Download project reports in **CSV** or **TXT**
- SQL schema + demo seed data included

## Folder Structure
```
/Webpage
├── assets/
│   └── css/style.css
├── config/
│   └── database.php
├── includes/
│   ├── auth.php
│   ├── footer.php
│   └── header.php
├── public/
│   ├── .htaccess
│   ├── all_projects.php
│   ├── dashboard.php
│   ├── download_report.php
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   ├── my_projects.php
│   ├── post_project.php
│   └── register.php
├── sql/
│   └── freelance_platform.sql
├── index.php
└── README.md
```

## Setup
1. Create database/tables and seed demo data:
   ```bash
   mysql -u root -p < sql/freelance_platform.sql
   ```
2. Update DB credentials in `config/database.php` if needed.
3. Run app with PHP built-in server:
   ```bash
   php -S localhost:8000
   ```
4. Open in browser:
   - `http://localhost:8000/`

## Demo Accounts
- client@demo.com / password123
- freelancer@demo.com / password123

## Notes
- Report downloads are available on the dashboard as CSV and TXT.
- Users can only export their own project reports.
