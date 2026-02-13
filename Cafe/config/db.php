<?php
// Database configuration
$host = 'localhost';
$dbname = 'cafe_management';
$username = 'root'; // Default XAMPP/WAMP username
$password = '';     // Default XAMPP/WAMP password

try {
    // Create PDO connection using the variables defined above
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set error mode to exception to catch database issues
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If connection fails, stop and show error
    die("Database Connection Error: " . $e->getMessage());
}

// Global Sanitization Function
if (!function_exists('sanitize')) {
    function sanitize($data) {
        if (is_null($data)) return "";
        return htmlspecialchars(strip_tags(trim($data)));
    }
}
?>