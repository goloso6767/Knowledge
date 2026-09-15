<?php
$host = "localhost";
$db   = "knowledge_db"; // la base de datos 
$user = "admin";        
$pass = "6v1EdzAP9WxIwzNe";           

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['status' => 'error', 'mensaje' => 'Error de conexión a la BD']));
}
?>