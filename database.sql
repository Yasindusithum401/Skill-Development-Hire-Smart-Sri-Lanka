CREATE DATABASE IF NOT EXISTS hiresmart_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE hiresmart_db;

-- Job seekers
CREATE TABLE IF NOT EXISTS job_seekers (
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

-- Recruiters (from the "recruiter-view" signup form)
CREATE TABLE IF NOT EXISTS recruiters (
    id                   INT AUTO_INCREMENT PRIMARY KEY,
    company_name         VARCHAR(150)  NOT NULL,
    company_type         VARCHAR(50)   NOT NULL,
    company_email        VARCHAR(150)  NOT NULL UNIQUE,
    password             VARCHAR(255)  NOT NULL,  
    company_description  TEXT          NOT NULL,
    profile_image        VARCHAR(255)  DEFAULT NULL,  
    created_at           TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Jobs posted by recruiters
CREATE TABLE IF NOT EXISTS jobs (
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

-- Applications: a seeker applying to a job (one application per seeker per job)
CREATE TABLE IF NOT EXISTS applications (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    job_id      INT NOT NULL,
    seeker_id   INT NOT NULL,
    applied_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_application (job_id, seeker_id),
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (seeker_id) REFERENCES job_seekers(id) ON DELETE CASCADE
) ENGINE=InnoDB;
