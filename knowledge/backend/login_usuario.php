<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

$correo   = $data['correo'] ?? '';
$password = $data['password'] ?? '';

if (empty($correo) || empty($password)) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Campos incompletos']);
    exit;
}

try {
    // Buscar el usuario y obtener su rol
    $sql = "SELECT u.id_usuario, u.password, r.nombre_rol 
            FROM usuarios u 
            JOIN roles r ON u.id_rol = r.id_rol 
            WHERE u.correo = :correo LIMIT 1";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['correo' => $correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si existe y si la contraseña es correcta (o compatible)
    if ($usuario && (password_verify($password, $usuario['password']) || $password === $usuario['password'])) {
        echo json_encode([
            'status' => 'success', 
            'rol' => $usuario['nombre_rol'],
            'mensaje' => '¡Bienvenido!'
        ]);
    } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'Correo o contraseña incorrectos']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error en el servidor']);
}
?>