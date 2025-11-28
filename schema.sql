CREATE DATABASE research_db;
USE research_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE participants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE survey_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    participant_id INT NOT NULL,
    is_completed BOOLEAN DEFAULT FALSE,
    current_module INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (participant_id) REFERENCES participants(id)
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module VARCHAR(255) NOT NULL,
    `group` VARCHAR(255),
    code VARCHAR(50) UNIQUE NOT NULL,
    text TEXT NOT NULL
);

CREATE TABLE responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    question_id VARCHAR(50) NOT NULL,
    question_text TEXT NOT NULL,
    score TINYINT NULL, -- 1-5 or NULL
    weight DECIMAL(3,1) NULL,
    participant_name VARCHAR(255) NOT NULL,
    participant_email VARCHAR(255) NOT NULL,
    participant_phone VARCHAR(20),
    FOREIGN KEY (session_id) REFERENCES survey_sessions(id),
    UNIQUE KEY unique_response (session_id, question_id)
);