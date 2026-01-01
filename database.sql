-- Database Schema for QuickTicket

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin', 'agent') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin User (password: admin123)
INSERT IGNORE INTO users (name, email, password, role) VALUES ('Admin', 'admin@quickticket.com', '$2y$10$8.Xh8.Xh8.Xh8.Xh8.Xh8.Xh8.Xh8.Xh8', 'admin');

-- Buses Table
CREATE TABLE IF NOT EXISTS buses (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bus_name VARCHAR(100) NOT NULL,
    bus_number VARCHAR(50) NOT NULL,
    bus_type VARCHAR(50) NOT NULL,
    total_seats INT(3) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Routes Table
CREATE TABLE IF NOT EXISTS routes (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bus_id INT(6) UNSIGNED,
    source VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    departure_date DATE NOT NULL,
    departure_time TIME NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (bus_id) REFERENCES buses(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings Table (Updated with Passenger Info)
CREATE TABLE IF NOT EXISTS bookings (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    route_id INT(6) UNSIGNED,
    seat_number INT(3) NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_status VARCHAR(20) DEFAULT 'pending',
    transaction_id VARCHAR(50),
    paid_amount DECIMAL(10, 2),
    passenger_name VARCHAR(100) NOT NULL DEFAULT '',
    contact_number VARCHAR(20) NOT NULL DEFAULT '',
    pickup_location VARCHAR(100) NOT NULL DEFAULT '',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (route_id) REFERENCES routes(id)
);

-- Messages Table
CREATE TABLE IF NOT EXISTS messages (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Offers Table
CREATE TABLE IF NOT EXISTS offers (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    promo_tag VARCHAR(50) NOT NULL,
    promo_color VARCHAR(20) NOT NULL,
    icon VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Popular Routes Table
CREATE TABLE IF NOT EXISTS popular_routes (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    source VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    duration VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
