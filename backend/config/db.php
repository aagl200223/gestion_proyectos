<?php
$host = 'localhost';
$db = 'gestion_proyectos';
$user = 'root';
$pass = '';
$port = 3307;

$dsn = "mysql:host=$host;dbname=$db;port=$port";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>