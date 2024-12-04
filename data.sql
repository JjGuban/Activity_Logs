-- Create the applicants table
CREATE TABLE applicants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    job_title VARCHAR(100) NOT NULL,
    skills TEXT NOT NULL,
    status ENUM('Pending', 'Shortlisted', 'Rejected', 'Hired') DEFAULT 'Pending',
    added_by VARCHAR(100) NOT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE user_accounts (
	user_id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(255),
	first_name VARCHAR(255),
	last_name VARCHAR(255),
	password TEXT,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE activity_logs (
	activity_log_id INT AUTO_INCREMENT PRIMARY KEY,
	operation VARCHAR(255),
    id INT,
	first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    job_title VARCHAR(100) NOT NULL,
    skills TEXT NOT NULL,
    status ENUM('Pending', 'Shortlisted', 'Rejected', 'Hired') DEFAULT 'Pending',
	username VARCHAR(255),
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);


-- Insert sample data
INSERT INTO applicants (first_name, last_name, email, phone, address, job_title, skills, status, added_by)
VALUES
('Johnny', 'Doe', 'john.doe@example.com', '09123456789', '123 Main St, Imus, Cavite', 'Nurse', 'First Aid, Patient Care', 'Pending', 'Admin'),
('Jane', 'Smith', 'jane.smith@example.com', '09187654321', '456 Elm St, Dasmariñas, Cavite', 'Teacher', 'Classroom Management, Math', 'Shortlisted', 'Admin'),
('Mark', 'Johnson', 'mark.johnson@example.com', '09213456789', '789 Pine St, Bacoor, Cavite', 'Doctor', 'Surgery, Diagnosis', 'Hired', 'Admin'),
('Emily', 'Davis', 'emily.davis@example.com', '09345678912', '101 Oak St, Tagaytay', 'Engineer', 'AutoCAD, Structural Analysis', 'Rejected', 'Admin'),
('Chris', 'Brown', 'chris.brown@example.com', '09456789123', '202 Maple St, Cavite City', 'Firefighter', 'Emergency Response, CPR', 'Pending', 'Admin'),
('Anna', 'Wilson', 'anna.wilson@example.com', '09567891234', '303 Cedar St, General Trias', 'Police Officer', 'Law Enforcement, Security', 'Shortlisted', 'Admin'),
('Michael', 'Clark', 'michael.clark@example.com', '09678912345', '404 Birch St, Silang', 'Software Developer', 'PHP, MySQL', 'Pending', 'Admin'),
('Laura', 'Harris', 'laura.harris@example.com', '09789123456', '505 Walnut St, Tanza', 'Psychologist', 'Counseling, Behavioral Therapy', 'Hired', 'Admin'),
('David', 'Martinez', 'david.martinez@example.com', '09891234567', '606 Cherry St, Cavite', 'Paramedic', 'Emergency Care, Driving', 'Rejected', 'Admin'),
('Sarah', 'Anderson', 'sarah.anderson@example.com', '09912345678', '707 Chestnut St, Imus', 'Chef', 'Cooking, Kitchen Management', 'Shortlisted', 'Admin');

