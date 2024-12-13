-- Create the 'research_centre' database if not exists
CREATE DATABASE IF NOT EXISTS research_centre;

-- Use the 'research_centre' database
USE research_centre;

-- Create the 'users' table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL -- MD5 hashed password
);

-- Insert admin user
INSERT INTO users (username, password)
VALUES ('admin', MD5('admin123'));

-- Create the 'supervisors' table
CREATE TABLE IF NOT EXISTS supervisors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    usn VARCHAR(20) NOT NULL UNIQUE, -- USN is unique for each supervisor
    guide VARCHAR(255) NOT NULL
);

-- Create the 'research_scholars' table
CREATE TABLE IF NOT EXISTS research_scholars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    scholar_name VARCHAR(255) NOT NULL,
    phd_status ENUM('Completed', 'Thesis submitted', 'Course Work Completed', 'Applied for Course Work', 'Registered') NOT NULL,
    supervisor_id INT,
    FOREIGN KEY (supervisor_id) REFERENCES supervisors(id) ON DELETE CASCADE
);

-- Create the 'advisory_committee' table
CREATE TABLE IF NOT EXISTS advisory_committee (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    post VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_name VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) DEFAULT NULL, -- Allow NULL if not always required
    publisher VARCHAR(255) NOT NULL,
    national_international ENUM('National', 'International') NOT NULL,
    year YEAR NOT NULL, -- No CHECK constraint for the year here
    isbn VARCHAR(50) UNIQUE, -- Enforce uniqueness for ISBN
    INDEX (isbn)
);


-- Create the 'book_chapters' table
CREATE TABLE IF NOT EXISTS book_chapters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_name VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) DEFAULT NULL, -- Allow NULL values
    publisher VARCHAR(255) NOT NULL,
    national_international ENUM('National', 'International') NOT NULL,
    year YEAR NOT NULL,
    isbn VARCHAR(50)
);


-- Create the 'journals' table
CREATE TABLE IF NOT EXISTS journals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    publisher VARCHAR(255) NOT NULL,
    year_of_publication YEAR NOT NULL,
    issn VARCHAR(20) NOT NULL UNIQUE
);

-- Create the 'conferences' table
CREATE TABLE IF NOT EXISTS conferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    details TEXT NOT NULL,
    national_international ENUM('National', 'International') NOT NULL,
    year_of_publication YEAR NOT NULL
);

-- Create the 'patents' table
CREATE TABLE IF NOT EXISTS patents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_name VARCHAR(255) NOT NULL,
    department VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    application_no VARCHAR(100) NOT NULL UNIQUE,
    year YEAR NOT NULL,
    status VARCHAR(100) NOT NULL,
    type ENUM('published', 'granted') NOT NULL
);

CREATE TABLE IF NOT EXISTS grants (
    id INT AUTO_INCREMENT PRIMARY KEY,                     -- Unique identifier for each grant
    grant_title VARCHAR(255) NOT NULL,                    -- Title of the grant
    principal_investigator VARCHAR(255) NOT NULL,         -- Name of the principal investigator
    department VARCHAR(255) NOT NULL,                     -- Department of the investigator
    year_of_award INT NOT NULL CHECK (year_of_award >= 1900 AND year_of_award <= YEAR(CURRENT_DATE)), 
                                                          -- Year the grant was awarded (valid year range)
    amount_sanctioned DECIMAL(10, 2) NOT NULL CHECK (amount_sanctioned >= 0), 
                                                          -- Amount sanctioned (non-negative)
    duration VARCHAR(255) NOT NULL,                       -- Duration of the grant/project
    funding_agency VARCHAR(255) NOT NULL,                 -- Funding agency name
    type ENUM('faculty', 'student') NOT NULL,             -- Specifies grant type (faculty/student)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,       -- Timestamp of record creation
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
                                                          -- Timestamp of last update
);

-- Create the 'student_grants' table
CREATE TABLE IF NOT EXISTS student_grants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_name VARCHAR(255) NOT NULL,
    student_name VARCHAR(255) NOT NULL,
    department VARCHAR(255) NOT NULL,
    year_of_award INT NOT NULL,
    amount_sanctioned DECIMAL(10, 2) NOT NULL,
    duration VARCHAR(255) NOT NULL,
    funding_agency VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    grant_id INT,
    FOREIGN KEY (grant_id) REFERENCES grants(id) ON DELETE CASCADE 
);