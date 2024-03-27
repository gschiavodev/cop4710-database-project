
-- Create the database
CREATE DATABASE IF NOT EXISTS college_events CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;

-- Create 'user' table
CREATE TABLE IF NOT EXISTS college_events.user 
(
    id INT AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

-- Create 'admin' (ISA user) table 
CREATE TABLE IF NOT EXISTS college_events.admin 
(
    id INT,
    PRIMARY KEY (id),
    CONSTRAINT fk_user_id FOREIGN KEY (id) REFERENCES college_events.user(id)
);

-- Create 'super_admin' (ISA user) table
CREATE TABLE IF NOT EXISTS college_events.super_admin 
(
    id INT,
    PRIMARY KEY (id),
    CONSTRAINT fk_super_admin_id FOREIGN KEY (id) REFERENCES college_events.user(id)
);

-- Create 'location' table
CREATE TABLE IF NOT EXISTS college_events.location 
(
    id INT AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    address_line_1 VARCHAR(255) NOT NULL,
    address_line_2 VARCHAR(255),
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
    PRIMARY KEY (id),
    CONSTRAINT fk_university_location_id FOREIGN KEY (location_id) REFERENCES college_events.location(id)
);

-- Create 'event' table
CREATE TABLE IF NOT EXISTS college_events.event 
(
    id INT AUTO_INCREMENT,
    location_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_location_id FOREIGN KEY (location_id) REFERENCES college_events.location(id),
    CONSTRAINT unique_event_date_time UNIQUE (date, time)
);

-- Create 'private_event' (ISA event) table
CREATE TABLE IF NOT EXISTS college_events.private_event 
(
    id INT,
    admin_id INT NOT NULL,
    super_admin_id INT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_private_event_id FOREIGN KEY (id) REFERENCES college_events.event(id),
    CONSTRAINT fk_private_event_admin_id FOREIGN KEY (admin_id) REFERENCES college_events.admin(id),
    CONSTRAINT fk_private_event_super_admin_id FOREIGN KEY (super_admin_id) REFERENCES college_events.super_admin(id)
);

-- Create 'public_event' (ISA event) table
CREATE TABLE IF NOT EXISTS college_events.public_event 
(
    id INT,
    admin_id INT NOT NULL,
    super_admin_id INT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_public_event_id FOREIGN KEY (id) REFERENCES college_events.event(id),
    CONSTRAINT fk_public_event_admin_id FOREIGN KEY (admin_id) REFERENCES college_events.admin(id),
    CONSTRAINT fk_public_event_super_admin_id FOREIGN KEY (super_admin_id) REFERENCES college_events.super_admin(id)
);

-- Create 'rso' table
CREATE TABLE IF NOT EXISTS college_events.rso 
(
    id INT AUTO_INCREMENT,
    admin_id INT NOT NULL,
    university_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_rso_admin_id FOREIGN KEY (admin_id) REFERENCES college_events.admin(id),
    CONSTRAINT fk_rso_university_id FOREIGN KEY (university_id) REFERENCES college_events.university(id)
);

-- Create 'rso_event' (ISA event) table
CREATE TABLE IF NOT EXISTS college_events.rso_event 
(
    id INT,
    rso_id INT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_rso_event_id FOREIGN KEY (id) REFERENCES college_events.event(id),
    CONSTRAINT fk_rso_event_rso_id FOREIGN KEY (rso_id) REFERENCES college_events.rso(id)
);

-- Create 'user_in_rso' table
CREATE TABLE IF NOT EXISTS college_events.user_in_rso 
(
    user_id INT NOT NULL,
    rso_id INT NOT NULL,
    PRIMARY KEY (user_id, rso_id),
    CONSTRAINT fk_user_in_rso_user_id FOREIGN KEY (user_id) REFERENCES college_events.user(id),
    CONSTRAINT fk_user_in_rso_rso_id FOREIGN KEY (rso_id) REFERENCES college_events.rso(id)
);

-- Create 'user_event_comment' table
CREATE TABLE IF NOT EXISTS college_events.user_event_comment 
(
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    comment TEXT NOT NULL,
    rating ENUM('1', '2', '3', '4', '5') NOT NULL,
    PRIMARY KEY (user_id, event_id),
    CONSTRAINT fk_user_event_comment_user_id FOREIGN KEY (user_id) REFERENCES college_events.user(id),
    CONSTRAINT fk_user_event_comment_event_id FOREIGN KEY (event_id) REFERENCES college_events.event(id)
);
