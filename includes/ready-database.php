<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "takingnoteapp";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->select_db($dbname);

// Create tables
$usersTable = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    country VARCHAR(50) NOT NULL
)";

$notesTable = "CREATE TABLE IF NOT EXISTS notes (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if ($conn->query($usersTable) === TRUE && $conn->query($notesTable) === TRUE) {
    echo "Tables created successfully";
} else {
    echo "Error creating tables: " . $conn->error;
}

$conn->close();
?>
