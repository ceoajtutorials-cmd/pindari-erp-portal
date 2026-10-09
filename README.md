# Pindari Enterprises - Workforce Solutions ERP System

A complete 2-in-1 web application built with **Pure PHP 8, MySQL, HTML, CSS, and JavaScript**. No frameworks, no React, no Node.js — runs entirely on XAMPP.

## Features

### Part A: Public Website
- **Home** — Hero section, animated stats counters, services preview, clients showcase, CTA banner
- **About Us** — Company story, values, why-choose-us section
- **Our Services** — Manpower Outsourcing, Contract Staffing, Payroll Management, Compliance Management + additional services
- **Clients** — Client grid (Tata Motors, Bajaj Auto, Mahindra, Force Motors) with testimonials
- **Contact Us** — Contact form that saves messages to database, company info, business hours
- Professional corporate design: Blue (#0a2a5e), Red (#d71921), White cards
- Responsive layout with mobile navigation

### Part B: ERP Client Portal
- **4-role login system** with password hashing (PHP `password_hash`)
- **Admin Dashboard** — Total manpower hired, active clients, pending payroll, compliance pending, client-wise bar chart, department distribution, recent employees, contact messages
- **HR Dashboard** — Employees, pending payroll, compliance overview, department stats
- **Client Dashboard** (Tata Motors) — Deployed workforce, active workers, on-leave count, contract info, department breakdown
- **Employee Dashboard** — Personal info, compliance status, payslip history

### ERP Modules
- **Employees** — List, add, edit, view (with payroll & placement history), search/filter
- **Clients** — List, add, edit, view (with deployed workforce)
- **Payroll** — List, add (auto net-pay calculation), edit, mark-as-paid, filter by month/status
- **Compliance** — PF/ESI/PAN/bank tracking, verification status management
- **Placements** — Track employee-to-client placements with status
- **Reports** — Client-wise cost analysis, department breakdown, payroll summary, compliance stats, activity log, contact messages

## Setup Instructions

### 1. Install XAMPP
Download and install XAMPP from https://www.apachefriends.org/

### 2. Copy Project
Copy the entire `pindari-enterprises` folder to your XAMPP `htdocs` directory:
```
C:\xampp\htdocs\pindari-enterprises
```

### 3. Run Setup
1. Start **Apache** and **MySQL** from XAMPP Control Panel
2. Open your browser and go to:
```
http://localhost/pindari-enterprises/setup.php
```
This will:
- Create the `pindari_erp` database
- Create all tables
- Insert sample data (clients, employees, payroll, compliance, placements)
- Create 4 user accounts with properly hashed passwords

### 4. Delete Setup File
**IMPORTANT:** Delete `setup.php` after setup is complete for security.

### 5. Access the Application
- **Public Website:** `http://localhost/pindari-enterprises/public/`
- **ERP Portal Login:** `http://localhost/pindari-enterprises/app/login.php`

## Login Credentials

| Role     | Email                     | Password    |
|----------|---------------------------|-------------|
| Admin    | admin@pindari.com         | admin123    |
| HR       | hr@pindari.com            | hr123       |
| Client   | client@tatamotors.com     | client123   |
| Employee | employee@gmail.com        | emp123      |

## Technology Stack
- **Backend:** Pure PHP 8 (no framework)
- **Database:** MySQL (via PDO)
- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Server:** Apache (XAMPP)
- **Fonts:** Google Fonts (Inter)

## Project Structure
```
pindari-enterprises/
├── config/
│   ├── config.php          # App config, helpers, session
│   └── database.php        # PDO database connection
├── database/
│   └── schema.sql          # Full database schema + seed data
├── includes/
│   ├── header.php          # Public site header
│   ├── footer.php          # Public site footer
│   ├── auth.php            # Auth guard + role helpers
│   ├── portal_header.php   # ERP sidebar + topbar
│   └── portal_footer.php   # ERP footer
├── public/                 # Public website
│   ├── index.php           # Home
│   ├── about.php           # About Us
│   ├── services.php        # Our Services
│   ├── clients.php         # Clients
│   ├── contact.php         # Contact Us
│   └── assets/
│       ├── css/style.css
│       └── js/main.js
├── app/                    # ERP Portal
│   ├── login.php           # Login page
│   ├── logout.php          # Logout
│   ├── dashboard.php       # Role-based dashboard
│   ├── employees/          # Employee CRUD
│   ├── clients/            # Client CRUD
│   ├── payroll/            # Payroll management
│   ├── compliance/         # Compliance tracking
│   ├── placements/         # Placement tracking
│   ├── reports/            # Analytics & reports
│   └── assets/
│       ├── css/portal.css
│       └── js/portal.js
├── setup.php               # One-time setup script
└── README.md
```

## Security Features
- Password hashing with `password_hash()` (bcrypt)
- PDO prepared statements (SQL injection prevention)
- `htmlspecialchars()` output escaping (XSS prevention)
- Session-based authentication with HttpOnly cookies
- Role-based access control (RBAC)
- CSRF protection via same-site cookie policy

## License
Proprietary - Pindari Enterprises (c) 2026
