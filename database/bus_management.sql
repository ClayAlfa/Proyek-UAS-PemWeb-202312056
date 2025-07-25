-- Database: bus_management
CREATE DATABASE IF NOT EXISTS bus_management;
USE bus_management;

-- Table users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table buses
CREATE TABLE buses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plate_number VARCHAR(20) UNIQUE NOT NULL,
    brand VARCHAR(50) NOT NULL,
    seat_count INT NOT NULL,
    status ENUM('available', 'maintenance') DEFAULT 'available'
);

-- Table routes
CREATE TABLE routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    origin VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    distance_km INT NOT NULL,
    estimated_time VARCHAR(20) NOT NULL
);

-- Table schedules
CREATE TABLE schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_id INT NOT NULL,
    route_id INT NOT NULL,
    departure_time DATETIME NOT NULL,
    arrival_time DATETIME NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (bus_id) REFERENCES buses(id),
    FOREIGN KEY (route_id) REFERENCES routes(id)
);

-- Table drivers
CREATE TABLE drivers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    license_number VARCHAR(50) UNIQUE NOT NULL
);

-- Table bus_driver
CREATE TABLE bus_driver (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_id INT NOT NULL,
    driver_id INT NOT NULL,
    FOREIGN KEY (bus_id) REFERENCES buses(id),
    FOREIGN KEY (driver_id) REFERENCES drivers(id)
);

-- Table passengers
CREATE TABLE passengers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL
);

-- Table tickets
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    schedule_id INT NOT NULL,
    passenger_id INT NOT NULL,
    seat_number VARCHAR(10) NOT NULL,
    status ENUM('booked', 'cancelled') DEFAULT 'booked',
    FOREIGN KEY (schedule_id) REFERENCES schedules(id),
    FOREIGN KEY (passenger_id) REFERENCES passengers(id)
);

-- Table transactions
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_status ENUM('paid', 'pending', 'failed') DEFAULT 'pending',
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id)
);

-- Table activity_logs
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert sample data
INSERT INTO users (username, password, role) VALUES 
('admin', MD5('admin123'), 'admin'),
('operator', MD5('operator123'), 'operator');
('user', MD5('user123'), 'user');

INSERT INTO buses (plate_number, brand, seat_count, status) VALUES 
('B 1234 CD', 'Mercedes-Benz', 40, 'available'),
('B 5678 EF', 'Isuzu', 35, 'available'),
('B 9012 GH', 'Hino', 45, 'maintenance');

INSERT INTO routes (origin, destination, distance_km, estimated_time) VALUES 
('Jakarta', 'Bandung', 150, '3 jam'),
('Jakarta', 'Surabaya', 800, '12 jam'),
('Bandung', 'Yogyakarta', 450, '8 jam');

INSERT INTO drivers (name, phone, license_number) VALUES 
('Budi Santoso', '08123456789', 'SIM123456'),
('Ahmad Wijaya', '08234567890', 'SIM234567'),
('Siti Nurhaliza', '08345678901', 'SIM345678');

INSERT INTO bus_driver (bus_id, driver_id) VALUES 
(1, 1),
(2, 2),
(3, 3);

INSERT INTO schedules (bus_id, route_id, departure_time, arrival_time, price) VALUES 
(1, 1, '2024-01-15 08:00:00', '2024-01-15 11:00:00', 75000),
(2, 2, '2024-01-15 20:00:00', '2024-01-16 08:00:00', 250000),
(1, 3, '2024-01-16 09:00:00', '2024-01-16 17:00:00', 150000);
