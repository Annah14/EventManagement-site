<?php
$host = '127.0.0.1';
$dbName = 'event_management';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Check if api_token column exists
    try {
        $pdo->query("SELECT api_token FROM users LIMIT 1");
        echo "Column 'api_token' already exists in 'users' table.\n";
    } catch (Exception $e) {
        // If it throws, column likely doesn't exist
        $pdo->exec("ALTER TABLE users ADD COLUMN api_token VARCHAR(64) UNIQUE DEFAULT NULL AFTER password");
        echo "Successfully added 'api_token' column to 'users' table.\n";
    }

} catch (PDOException $e) {
    die("Database connection or setup failed: " . $e->getMessage() . "\n");
}
