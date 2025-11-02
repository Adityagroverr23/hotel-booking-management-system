CREATE DATABASE IF NOT EXISTS hotel_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE hotel_db;


-- users table (customers)
CREATE TABLE IF NOT EXISTS users (
id INT AUTO_INCREMENT PRIMARY KEY,
fullname VARCHAR(150) NOT NULL,
email VARCHAR(150) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
phone VARCHAR(30),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- admin table
CREATE TABLE IF NOT EXISTS admin (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(100) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
fullname VARCHAR(150)
);


-- rooms table
CREATE TABLE IF NOT EXISTS rooms (
id INT AUTO_INCREMENT PRIMARY KEY,
room_number VARCHAR(50) NOT NULL UNIQUE,
type VARCHAR(100) NOT NULL,
price DECIMAL(10,2) NOT NULL,
description TEXT,
image VARCHAR(255),
status ENUM('available','unavailable') DEFAULT 'available'
);


-- bookings table
CREATE TABLE IF NOT EXISTS bookings (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
room_id INT NOT NULL,
check_in DATE NOT NULL,
check_out DATE NOT NULL,
guests INT DEFAULT 1,
total_amount DECIMAL(10,2),
status ENUM('booked','cancelled','completed') DEFAULT 'booked',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);


-- payments table
CREATE TABLE IF NOT EXISTS payments (
id INT AUTO_INCREMENT PRIMARY KEY,
booking_id INT NOT NULL,
amount DECIMAL(10,2) NOT NULL,
payment_method VARCHAR(50),
paid_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);


-- Seed an admin user (password: admin123 -> hashed value will be different if you change method)
INSERT INTO admin (username, password, fullname) VALUES
('admin', '$2y$10$u1JqN8b0f1e8kQF1x5d6ue1N6Yq9x2l7V7sBf6pK8Pf3f9JrK3YbG', 'Administrator')
ON DUPLICATE KEY UPDATE username=username;


-- Sample rooms
INSERT INTO rooms (room_number, type, price, description, status) VALUES
('101', 'Single', 1200.00, 'Cozy single room with free WiFi.', 'available'),
('102', 'Double', 2200.00, 'Comfortable double bed room.', 'available'),
('201', 'Deluxe', 4500.00, 'Deluxe room with sea view.', 'available')
ON DUPLICATE KEY UPDATE room_number=room_number;