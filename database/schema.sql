-- Pindari Enterprises ERP Database Schema
-- Pure MySQL for XAMPP

CREATE DATABASE IF NOT EXISTS pindari_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pindari_erp;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','hr','client','employee') NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    client_id INT DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Clients table
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(150) NOT NULL,
    contact_person VARCHAR(100) DEFAULT NULL,
    email VARCHAR(150) DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    address TEXT,
    contract_start DATE DEFAULT NULL,
    contract_end DATE DEFAULT NULL,
    status ENUM('active','inactive','pending') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Employees table
CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emp_code VARCHAR(50) NOT NULL UNIQUE,
    first_name VARCHAR(80) NOT NULL,
    last_name VARCHAR(80) NOT NULL,
    email VARCHAR(150) DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    client_id INT DEFAULT NULL,
    department VARCHAR(100) DEFAULT NULL,
    designation VARCHAR(100) DEFAULT NULL,
    shift VARCHAR(50) DEFAULT NULL,
    salary DECIMAL(10,2) DEFAULT 0.00,
    hire_date DATE DEFAULT NULL,
    status ENUM('active','inactive','terminated','on_leave') DEFAULT 'active',
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Payroll table
CREATE TABLE IF NOT EXISTS payroll (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    month VARCHAR(20) NOT NULL,
    year INT NOT NULL,
    basic_salary DECIMAL(10,2) DEFAULT 0.00,
    hra DECIMAL(10,2) DEFAULT 0.00,
    allowances DECIMAL(10,2) DEFAULT 0.00,
    deductions DECIMAL(10,2) DEFAULT 0.00,
    pf DECIMAL(10,2) DEFAULT 0.00,
    esi DECIMAL(10,2) DEFAULT 0.00,
    net_pay DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('pending','processed','paid') DEFAULT 'pending',
    processed_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

-- Compliance table
CREATE TABLE IF NOT EXISTS compliance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    pf_number VARCHAR(50) DEFAULT NULL,
    esi_number VARCHAR(50) DEFAULT NULL,
    pan_number VARCHAR(20) DEFAULT NULL,
    aadhaar VARCHAR(20) DEFAULT NULL,
    bank_account VARCHAR(30) DEFAULT NULL,
    ifsc_code VARCHAR(20) DEFAULT NULL,
    contract_type ENUM('permanent','contract','temporary') DEFAULT 'contract',
    verification_status ENUM('pending','verified','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

-- Placements table
CREATE TABLE IF NOT EXISTS placements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    client_id INT NOT NULL,
    position VARCHAR(100) DEFAULT NULL,
    start_date DATE DEFAULT NULL,
    end_date DATE DEFAULT NULL,
    status ENUM('active','completed','terminated') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- Contact messages (from public website)
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    company VARCHAR(150) DEFAULT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Activity log
CREATE TABLE IF NOT EXISTS activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert default users (passwords are hashed with password_hash)
-- admin@pindari.com / admin123
-- hr@pindari.com / hr123
-- client@tatamotors.com / client123
-- employee@gmail.com / emp123
INSERT INTO users (name, email, password, role, phone, client_id) VALUES
('System Admin', 'admin@pindari.com', '$2y$10$YourHashHere1', 'admin', '9876543210', NULL),
('HR Manager', 'hr@pindari.com', '$2y$10$YourHashHere2', 'hr', '9876543211', NULL),
('Tata Client', 'client@tatamotors.com', '$2y$10$YourHashHere3', 'client', '9876543212', 1),
('Test Employee', 'employee@gmail.com', '$2y$10$YourHashHere4', 'employee', '9876543213', NULL);

-- Insert sample clients
INSERT INTO clients (company_name, contact_person, email, phone, address, contract_start, contract_end, status) VALUES
('Tata Motors', 'Rajesh Kumar', 'rajesh@tatamotors.com', '02066018200', 'Pimpri, Pune, Maharashtra 411018', '2024-01-01', '2025-12-31', 'active'),
('Bajaj Auto', 'Priya Sharma', 'priya@bajajauto.com', '02027471031', 'Waluj, Aurangabad, Maharashtra 431136', '2024-03-01', '2025-12-31', 'active'),
('Mahindra & Mahindra', 'Amit Deshpande', 'amit@mahindra.com', '0206735000', 'Kothrud, Pune, Maharashtra 411035', '2024-02-15', '2026-02-14', 'active'),
('Force Motors', 'Sunita Patil', 'sunita@forcemotors.com', '02066455000', 'Akaluj, Pune, Maharashtra 412501', '2024-06-01', '2025-12-31', 'active');

-- Insert sample employees
INSERT INTO employees (emp_code, first_name, last_name, email, phone, client_id, department, designation, shift, salary, hire_date, status, address) VALUES
('PE-001', 'Rahul', 'Patil', 'rahul.p@gmail.com', '9923001122', 1, 'Production', 'Assembly Worker', 'General', 18500.00, '2024-01-15', 'active', 'Pune, Maharashtra'),
('PE-002', 'Sachin', 'More', 'sachin.m@gmail.com', '9923001123', 1, 'Production', 'Machine Operator', 'General', 22000.00, '2024-01-20', 'active', 'Pimpri, Pune'),
('PE-003', 'Deepak', 'Jadhav', 'deepak.j@gmail.com', '9923001124', 1, 'Quality', 'QC Inspector', 'General', 28000.00, '2024-02-01', 'active', 'Chinchwad, Pune'),
('PE-004', 'Nilesh', 'Shinde', 'nilesh.s@gmail.com', '9923001125', 1, 'Logistics', 'Warehouse Supervisor', 'General', 32000.00, '2024-02-10', 'active', 'Pimpri, Pune'),
('PE-005', 'Prakash', 'Bhosale', 'prakash.b@gmail.com', '9923001126', 2, 'Production', 'Assembly Worker', 'General', 17500.00, '2024-03-05', 'active', 'Aurangabad, Maharashtra'),
('PE-006', 'Vijay', 'Kamble', 'vijay.k@gmail.com', '9923001127', 2, 'Maintenance', 'Technician', 'General', 25000.00, '2024-03-15', 'active', 'Waluj, Aurangabad'),
('PE-007', 'Sandip', 'Gaikwad', 'sandip.g@gmail.com', '9923001128', 3, 'Production', 'Welder', 'General', 30000.00, '2024-02-20', 'active', 'Kothrud, Pune'),
('PE-008', 'Amol', 'Desai', 'amol.d@gmail.com', '9923001129', 3, 'Quality', 'QA Engineer', 'General', 38000.00, '2024-02-25', 'active', 'Kothrud, Pune'),
('PE-009', 'Rohit', 'Pawar', 'rohit.p@gmail.com', '9923001130', 1, 'Production', 'Forklift Operator', 'General', 21000.00, '2024-03-01', 'on_leave', 'Pimpri, Pune'),
('PE-010', 'Karan', 'Salunkhe', 'karan.s@gmail.com', '9923001131', 4, 'Production', 'Assembly Worker', 'General', 19500.00, '2024-06-10', 'active', 'Akaluj, Pune');

-- Insert sample payroll records
INSERT INTO payroll (employee_id, month, year, basic_salary, hra, allowances, deductions, pf, esi, net_pay, status) VALUES
(1, 'September', 2026, 12000.00, 3000.00, 3500.00, 500.00, 1440.00, 837.00, 15723.00, 'paid'),
(2, 'September', 2026, 14000.00, 3500.00, 4500.00, 500.00, 1680.00, 924.00, 17796.00, 'paid'),
(3, 'September', 2026, 18000.00, 4500.00, 5500.00, 500.00, 2160.00, 1188.00, 22152.00, 'processed'),
(4, 'September', 2026, 20000.00, 5000.00, 7000.00, 500.00, 2400.00, 1320.00, 25280.00, 'processed'),
(5, 'September', 2026, 11000.00, 2750.00, 3750.00, 500.00, 1320.00, 770.00, 14910.00, 'pending'),
(6, 'September', 2026, 15000.00, 3750.00, 6250.00, 500.00, 1800.00, 990.00, 20710.00, 'pending'),
(1, 'October', 2026, 12000.00, 3000.00, 3500.00, 500.00, 1440.00, 837.00, 15723.00, 'pending'),
(2, 'October', 2026, 14000.00, 3500.00, 4500.00, 500.00, 1680.00, 924.00, 17796.00, 'pending'),
(3, 'October', 2026, 18000.00, 4500.00, 5500.00, 500.00, 2160.00, 1188.00, 22152.00, 'pending'),
(4, 'October', 2026, 20000.00, 5000.00, 7000.00, 500.00, 2400.00, 1320.00, 25280.00, 'pending');

-- Insert sample compliance records
INSERT INTO compliance (employee_id, pf_number, esi_number, pan_number, aadhaar, bank_account, ifsc_code, contract_type, verification_status) VALUES
(1, 'PUNE/12345/001', 'ESI/001/2024', 'ABCPA1234E', 'XXXX-XXXX-1234', '12345678901', 'SBIN0001234', 'contract', 'verified'),
(2, 'PUNE/12345/002', 'ESI/002/2024', 'ABCPA1235F', 'XXXX-XXXX-1235', '12345678902', 'SBIN0001234', 'contract', 'verified'),
(3, 'PUNE/12345/003', 'ESI/003/2024', 'ABCPA1236G', 'XXXX-XXXX-1236', '12345678903', 'HDFC0001234', 'permanent', 'verified'),
(4, 'PUNE/12345/004', 'ESI/004/2024', 'ABCPA1237H', 'XXXX-XXXX-1237', '12345678904', 'HDFC0001234', 'permanent', 'verified'),
(5, 'PUNE/12345/005', 'ESI/005/2024', 'ABCPA1238I', 'XXXX-XXXX-1238', '12345678905', 'ICIC0001234', 'contract', 'pending'),
(6, 'PUNE/12345/006', 'ESI/006/2024', 'ABCPA1239J', 'XXXX-XXXX-1239', '12345678906', 'ICIC0001234', 'contract', 'pending'),
(7, 'PUNE/12345/007', 'ESI/007/2024', 'ABCPA1240K', 'XXXX-XXXX-1240', '12345678907', 'SBIN0001234', 'temporary', 'pending'),
(8, 'PUNE/12345/008', 'ESI/008/2024', 'ABCPA1241L', 'XXXX-XXXX-1241', '12345678908', 'AXIS0001234', 'permanent', 'verified');

-- Insert sample placements
INSERT INTO placements (employee_id, client_id, position, start_date, end_date, status) VALUES
(1, 1, 'Assembly Worker', '2024-01-15', '2025-12-31', 'active'),
(2, 1, 'Machine Operator', '2024-01-20', '2025-12-31', 'active'),
(3, 1, 'QC Inspector', '2024-02-01', '2025-12-31', 'active'),
(4, 1, 'Warehouse Supervisor', '2024-02-10', '2025-12-31', 'active'),
(5, 2, 'Assembly Worker', '2024-03-05', '2025-12-31', 'active'),
(6, 2, 'Technician', '2024-03-15', '2025-12-31', 'active'),
(7, 3, 'Welder', '2024-02-20', '2026-02-14', 'active'),
(8, 3, 'QA Engineer', '2024-02-25', '2026-02-14', 'active'),
(9, 1, 'Forklift Operator', '2024-03-01', '2025-12-31', 'active'),
(10, 4, 'Assembly Worker', '2024-06-10', '2025-12-31', 'active');

-- Insert sample contact messages
INSERT INTO contact_messages (name, email, phone, company, message, is_read) VALUES
('Manish Gupta', 'manish@example.com', '9810012345', 'Gupta Industries', 'We need 50 contract workers for our new plant. Please share details.', 0),
('Sneha Joshi', 'sneha@example.com', '9820098765', 'Joshi Tech', 'Looking for payroll outsourcing services for 200 employees.', 1);

-- Insert activity logs
INSERT INTO activity_log (user_id, action, ip_address) VALUES
(1, 'System initialized', '127.0.0.1'),
(2, 'Added 10 employees', '127.0.0.1'),
(3, 'Viewed Tata Motors dashboard', '127.0.0.1');
