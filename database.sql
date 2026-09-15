-- Reset database structure
DROP DATABASE IF EXISTS hiresmart_db;
CREATE DATABASE hiresmart_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE hiresmart_db;

-- 1. Job seekers (Candidate accounts)
CREATE TABLE job_seekers (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    username       VARCHAR(100)  NOT NULL,
    email          VARCHAR(150)  NOT NULL UNIQUE,
    contact_email  VARCHAR(150)  NOT NULL,
    password       VARCHAR(255)  NOT NULL,   
    dob            DATE          NOT NULL,
    mobile_number  VARCHAR(20)   NOT NULL,
    profile_image  VARCHAR(255)  DEFAULT NULL,
    cv_path        VARCHAR(255)  DEFAULT NULL,
    bio            TEXT          DEFAULT NULL,
    created_at     TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Recruiters / Employers
CREATE TABLE recruiters (
    id                   INT AUTO_INCREMENT PRIMARY KEY,
    company_name         VARCHAR(150)  NOT NULL,
    company_type         VARCHAR(50)   NOT NULL,
    company_email        VARCHAR(150)  NOT NULL UNIQUE,
    password             VARCHAR(255)  NOT NULL,  
    company_description  TEXT          NOT NULL,
    profile_image        VARCHAR(255)  DEFAULT NULL,  
    created_at           TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Job Postings
CREATE TABLE jobs (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    recruiter_id      INT NOT NULL,
    title             VARCHAR(150) NOT NULL,
    category          VARCHAR(100) NOT NULL,
    job_type          VARCHAR(50)  NOT NULL,
    location          VARCHAR(150) NOT NULL,
    experience_level  VARCHAR(50)  NOT NULL,
    salary_min        INT DEFAULT NULL,
    salary_max        INT DEFAULT NULL,
    description       TEXT NOT NULL,
    requirements      TEXT DEFAULT NULL,
    status            ENUM('open','closed') NOT NULL DEFAULT 'open',
    posted_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recruiter_id) REFERENCES recruiters(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Job Applications & Live Status Tracking
CREATE TABLE applications (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    job_id            INT NOT NULL,
    seeker_id         INT NOT NULL,
    current_status    ENUM('Applied', 'In Review', 'HR Screening', 'Interview', 'Selected', 'Rejected') NOT NULL DEFAULT 'Applied',
    applied_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_application (job_id, seeker_id),
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (seeker_id) REFERENCES job_seekers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Progress Tracking Audit Log & Rejection Details
CREATE TABLE application_status_history (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    application_id    INT NOT NULL,
    status            ENUM('Applied', 'In Review', 'HR Screening', 'Interview', 'Selected', 'Rejected') NOT NULL,
    rejection_reason  VARCHAR(100) DEFAULT NULL,
    rejection_comment TEXT DEFAULT NULL,
    changed_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Cached Employer Trust Scores & Badges
CREATE TABLE employer_trust_scores (
    recruiter_id          INT PRIMARY KEY,
    m1_response_time      DECIMAL(5,2) DEFAULT 0.00,
    m2_acknowledgement    DECIMAL(5,2) DEFAULT 0.00,
    m3_rejection_transp   DECIMAL(5,2) DEFAULT 0.00,
    m4_timeline_updates   DECIMAL(5,2) DEFAULT 0.00,
    overall_trust_score   DECIMAL(5,2) DEFAULT 0.00,
    display_badge         ENUM('New Employer', 'Insufficient Data', 'Active') DEFAULT 'New Employer',
    last_calculated       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (recruiter_id) REFERENCES recruiters(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. Independent Applicant Reviews
CREATE TABLE employer_reviews (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    recruiter_id  INT NOT NULL,
    seeker_id     INT NOT NULL,
    rating        TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment       TEXT DEFAULT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_review (recruiter_id, seeker_id),
    FOREIGN KEY (recruiter_id) REFERENCES recruiters(id) ON DELETE CASCADE,
    FOREIGN KEY (seeker_id) REFERENCES job_seekers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. Public Holidays Reference Table (Empty by default)
CREATE TABLE public_holidays (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    holiday_date DATE NOT NULL UNIQUE,
    description  VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB;