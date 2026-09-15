<?php
header('Content-Type: application/json');
require_once 'conexion.php';

// Lectura de los datos enviados desde JS
$data = json_decode(file_get_contents('php://input'), true);

$nombre   = $data['nombre'] ?? '';
$correo   = $data['correo'] ?? '';
$password = $data['password'] ?? '';
$rol_slug = $data['rol'] ?? 'alumno';

// Validación básica de campos vacíos
if (empty($nombre) || empty($correo) || empty($password)) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Todos los campos son obligatorios']);
    exit;
}

try {
    // Busca el ID del rol seleccionado en la tabla 'roles'
    $stmtRol = $pdo->prepare("SELECT id_rol FROM roles WHERE nombre_rol = :rol LIMIT 1");
    $stmtRol->execute(['rol' => $rol_slug]);
    $rolRow = $stmtRol->fetch(PDO::FETCH_ASSOC);

    if (!$rolRow) {
        echo json_encode(['status' => 'error', 'mensaje' => 'El rol seleccionado no es válido']);
        exit;
    }

    $id_rol = $rolRow['id_rol'];

    // Encriptado de la contraseña
    $passHash = password_hash($password, PASSWORD_BCRYPT);

    // Inserción en la tabla 'usuarios'
    $sql = "INSERT INTO usuarios (nombre_completo, correo, password, id_rol) 
            VALUES (:nombre, :correo, :password, :id_rol)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nombre'   => $nombre,
        'correo'   => $correo,
        'password' => $passHash,
        'id_rol'   => $id_rol
    ]);

    echo json_encode(['status' => 'success', 'mensaje' => '¡Cuenta creada con éxito!']);

} catch (PDOException $e) {
    // Código 23000 detecta si el correo ya existe en MySQL (UNIQUE)
    if ($e->getCode() == 23000) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Este correo ya está registrado']);
    } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'Error al guardar el usuario']);
    }
}
?>