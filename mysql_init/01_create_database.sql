
-- Create the database (done automatically but here for reference)
CREATE DATABASE IF NOT EXISTS college_events CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;

-- Create 'user' table
CREATE TABLE IF NOT EXISTS college_events.user 
(
    id INT AUTO_INCREMENT,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    university_email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT unique_user_university_email UNIQUE (university_email)
);

-- Create 'admin' (ISA user) table 
CREATE TABLE IF NOT EXISTS college_events.admin 
(
    id INT,
    PRIMARY KEY (id),
    CONSTRAINT fk_user_id FOREIGN KEY (id) REFERENCES college_events.user(id) ON DELETE CASCADE
);

-- Create 'super_admin' (ISA user) table
CREATE TABLE IF NOT EXISTS college_events.super_admin 
(
    id INT,
    PRIMARY KEY (id),
    CONSTRAINT fk_super_admin_id FOREIGN KEY (id) REFERENCES college_events.user(id) ON DELETE CASCADE
);

-- Create 'location' table
CREATE TABLE IF NOT EXISTS college_events.location 
(
    id INT AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    address_line_01 VARCHAR(255) NOT NULL,
    address_line_02 VARCHAR(255),
    city VARCHAR(255) NOT NULL,
    state VARCHAR(255) NOT NULL,
    zip_code VARCHAR(255) NOT NULL,
    longitude INT,
	latitude INT,
    PRIMARY KEY (id)
);

-- Create 'university' table
CREATE TABLE IF NOT EXISTS college_events.university 
(
    id INT AUTO_INCREMENT,
    location_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    email_domain VARCHAR(255) NOT NULL,
    num_students INT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_university_location_id FOREIGN KEY (location_id) REFERENCES college_events.location(id) ON DELETE CASCADE
);

-- Create 'event' table
CREATE TABLE IF NOT EXISTS college_events.event 
(
    id INT AUTO_INCREMENT,
    location_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category ENUM('academic', 'social', 'philanthropy', 'fundraising', 'tech-talk') NOT NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    phone_number VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_event_location_id FOREIGN KEY (location_id) REFERENCES college_events.location(id) ON DELETE CASCADE,
    CONSTRAINT unique_event_date_time_location UNIQUE (date, start_time, end_time, location_id)
);

-- Create 'public_event' (ISA event) table
CREATE TABLE IF NOT EXISTS college_events.public_event 
(
    id INT,
    is_approved BOOLEAN NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_public_event_id FOREIGN KEY (id) REFERENCES college_events.event(id) ON DELETE CASCADE
);

-- Create 'rso' table
CREATE TABLE IF NOT EXISTS college_events.rso 
(
    id INT AUTO_INCREMENT,
    admin_id INT NOT NULL,
    university_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    is_approved BOOLEAN NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_rso_admin_id FOREIGN KEY (admin_id) REFERENCES college_events.admin(id) ON DELETE CASCADE,
    CONSTRAINT fk_rso_university_id FOREIGN KEY (university_id) REFERENCES college_events.university(id) ON DELETE CASCADE
);

-- Create 'private_event' (ISA event) table
CREATE TABLE IF NOT EXISTS college_events.private_event 
(
    id INT,
    rso_id INT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_private_event_id FOREIGN KEY (id) REFERENCES college_events.event(id) ON DELETE CASCADE,
    CONSTRAINT fk_private_event_rso_id FOREIGN KEY (rso_id) REFERENCES college_events.rso(id) ON DELETE CASCADE
);

-- Create 'rso_event' (ISA event) table
CREATE TABLE IF NOT EXISTS college_events.rso_event 
(
    id INT,
    rso_id INT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_rso_event_id FOREIGN KEY (id) REFERENCES college_events.event(id) ON DELETE CASCADE,
    CONSTRAINT fk_rso_event_rso_id FOREIGN KEY (rso_id) REFERENCES college_events.rso(id) ON DELETE CASCADE
);

-- Create 'user_in_rso' table
CREATE TABLE IF NOT EXISTS college_events.user_in_rso 
(
    user_id INT NOT NULL,
    rso_id INT NOT NULL,
    PRIMARY KEY (user_id, rso_id),
    CONSTRAINT fk_user_in_rso_user_id FOREIGN KEY (user_id) REFERENCES college_events.user(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_in_rso_rso_id FOREIGN KEY (rso_id) REFERENCES college_events.rso(id) ON DELETE CASCADE
);

-- Create 'user_event_comment' table
CREATE TABLE IF NOT EXISTS college_events.user_event_comment 
(
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    comment TEXT NOT NULL,
    rating ENUM('1', '2', '3', '4', '5') NOT NULL,
    PRIMARY KEY (user_id, event_id),
    CONSTRAINT fk_user_event_comment_user_id FOREIGN KEY (user_id) REFERENCES college_events.user(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_event_comment_event_id FOREIGN KEY (event_id) REFERENCES college_events.event(id) ON DELETE CASCADE
);
