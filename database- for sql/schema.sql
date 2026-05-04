-- ============================================
-- Hostel Management System - Database Schema
-- ============================================
-- Database: Profexphostelmanagement
-- Created: May 2026

-- Drop existing database if exists
DROP DATABASE IF EXISTS Profexphostelmanagement;

-- Create database
CREATE DATABASE IF NOT EXISTS Profexphostelmanagement;
USE Profexphostelmanagement;

-- ============================================
-- Table: admins
-- Purpose: Store admin/staff credentials
-- ============================================
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username)
);

-- ============================================
-- Table: students
-- Purpose: Store student hostel records
-- ============================================
CREATE TABLE students (
    enrollment_no INT PRIMARY KEY NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    dob DATE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    room_no INT NOT NULL,
    sem INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_full_name (full_name),
    INDEX idx_room_no (room_no),
    INDEX idx_sem (sem)
);

-- ============================================
-- Sample Admin Data (Optional - For Testing)
-- ============================================
-- INSERT INTO admins (username, email, password) VALUES 
-- ('admin', 'admin@hostel.com', 'admin123'),
-- ('staff1', 'staff1@hostel.com', 'staff123');

-- ============================================
-- Sample Student Data (Optional - For Testing)
-- ============================================
-- INSERT INTO students VALUES 
-- (101, 'Rajesh Kumar', 'Male', '2002-05-15', '9876543210', 'rajesh@email.com', 201, 4, NOW(), NOW()),
-- (102, 'Priya Singh', 'Female', '2003-08-22', '9123456789', 'priya@email.com', 202, 3, NOW(), NOW()),
-- (103, 'Amit Sharma', 'Male', '2002-12-10', '9988776655', 'amit@email.com', 203, 5, NOW(), NOW());
