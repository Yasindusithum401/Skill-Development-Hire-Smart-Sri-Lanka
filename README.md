# Hire Smart Sri Lanka

Hire Smart Sri Lanka is a web-based recruitment platform built with PHP and MySQL. It helps job seekers find career opportunities and provides employers with an **Employer Trust Rating System (ETSR)** to promote transparent hiring practices.

---

## Features

* **Dual User Roles**: Register and log in as either a **Job Seeker** or a **Recruiter / Employer**.
* **Job Postings**: Employers can publish, view, and close job vacancies with custom salary ranges and requirements.
* **Job Applications**: Job seekers can upload PDF resumes, browse open positions, and apply online.
* **Application Tracking**: Job seekers can monitor their application progress through different recruitment stages (Applied, HR Screening, Interview, Selected, Rejected).
* **Employer Trust Rating System (ETSR)**: Calculates employer responsiveness, status updates, and candidate ratings to display an overall trust score.
* **Employer Reviews**: Candidates can rate and write feedback for employers after applying.

---

## Technology Stack

* **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
* **Backend**: PHP 8.x
* **Database**: MySQL / MariaDB
* **Web Server**: Apache (XAMPP / WAMP / LAMP)

---

## Installation Guide

Follow these simple steps to set up and run the project locally on your machine.

### 1. Requirements
* Install **XAMPP** (or any local server environment with PHP 8.0+ and MySQL).
* Download or clone this repository.

### 2. File Setup
1. Copy or move the project folder into your web server root directory:
   * **XAMPP**: `C:\xampp\htdocs\hire-smart-srilanka`
   * **WAMP**: `C:\wamp64\www\hire-smart-srilanka`

2. Ensure the following directories exist inside the project root and have write permissions for uploaded files:
   * `uploads/avatars/`
   * `uploads/cvs/`

### 3. Database Setup
1. Open **phpMyAdmin** in your browser (`http://localhost/phpmyadmin`).
2. Create a new database named `hire_smart` (or preferred name).
3. Import the SQL file (`schema.sql` or database dump) into your newly created database.

### 4. Configuration
1. Open `config.php` in your code editor.
2. Update the database credentials to match your local setup:
