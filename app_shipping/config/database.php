<?php
// =============================================
// Database Configuration - PT. ITL Shipping Agency
// =============================================

$host = getenv('MYSQLHOST') ?: (getenv('DB_HOST') ?: 'localhost');
$port = getenv('MYSQLPORT') ?: (getenv('DB_PORT') ?: '3306');
$dbname = getenv('MYSQLDATABASE') ?: (getenv('DB_NAME') ?: 'db_itl_shipping');
$username = getenv('MYSQLUSER') ?: (getenv('DB_USER') ?: 'root');
$password = getenv('MYSQLPASSWORD') ?: (getenv('DB_PASS') ?: '');
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die('<div style="text-align:center;padding:50px;font-family:sans-serif;">
        <h2>⚠️ Database Connection Error</h2>
        <p>Pastikan MySQL sudah berjalan dan database <strong>db_itl_shipping</strong> sudah dibuat.</p>
        <p style="color:#888;">' . $e->getMessage() . '</p>
    </div>');
}
