CREATE DATABASE IF NOT EXISTS cafe_management;
USE cafe_management;

-- Users Table (Customers)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admins Table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tables Table
CREATE TABLE IF NOT EXISTS cafe_tables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_number VARCHAR(10) NOT NULL UNIQUE,
    capacity INT NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active'
);

-- Menu Items Table
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reservations Table
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    table_id INT NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    num_guests INT NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (table_id) REFERENCES cafe_tables(id) ON DELETE CASCADE
);


-- Default Admin Account (admin / admin123)
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Sample Tables
INSERT INTO cafe_tables (table_number, capacity) VALUES 
('M1', 2), ('M2', 2), ('M3', 4), ('M4', 4), ('M5', 6);

-- Sample Indian Menu Items
INSERT INTO menu_items (name, category, price, description) VALUES 
('South Indian Filter Coffee', 'Coffee', 180.00, 'Traditional decoction coffee served in a brass dabarah and tumbler.'),
('Masala Chai Latte', 'Tea', 220.00, 'Hand-pounded spices with premium Assam tea and steamed milk.'),
('Cardamom Cold Brew', 'Coffee', 280.00, '12-hour steep with a hint of green cardamom and jaggery syrup.'),
('Vada Pav Sliders', 'Food', 250.00, 'Mumbai’s favorite batata vada in mini brioche buns with spicy garlic chutney.'),
('Irani Bun Maska', 'Food', 150.00, 'Soft bun with whipped salted butter and a touch of jam.'),
('Paneer Tikka Croissant', 'Food', 320.00, 'Flaky buttery pastry filled with charcoal-grilled paneer and mint chutney.');