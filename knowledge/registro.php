<?php
require_once 'config/database.php';

$mensaje = "";
$esExito = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre          = trim($_POST['nombre'] ?? '');
    $correo          = trim($_POST['correo'] ?? '');
    $password        = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');
    $rol_slug        = trim($_POST['rol'] ?? 'alumno');

    // Validaciones
    if (empty($nombre) || empty($correo) || empty($password) || empty($confirmPassword)) {
        $mensaje = "Todos los campos son obligatorios 😿";
    } elseif ($password !== $confirmPassword) {
        $mensaje = "Las contraseñas no coinciden 😿";
    } elseif (strlen($password) < 6) {
        $mensaje = "La contraseña debe tener al menos 6 caracteres 😿";
    } else {
        try {
            // Buscar ID de rol
            $stmtRol = $pdo->prepare("SELECT id_rol FROM roles WHERE nombre_rol = :rol LIMIT 1");
            $stmtRol->execute(['rol' => $rol_slug]);
            $rolRow = $stmtRol->fetch(PDO::FETCH_ASSOC);

            if ($rolRow) {
                $id_rol = $rolRow['id_rol'];
                $passHash = password_hash($password, PASSWORD_BCRYPT);

                // Inserción de usuario
                $sql = "INSERT INTO usuarios (nombre_completo, correo, password, id_rol) 
                        VALUES (:nombre, :correo, :password, :id_rol)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'nombre'   => $nombre,
                    'correo'   => $correo,
                    'password' => $passHash,
                    'id_rol'   => $id_rol
                ]);

                $mensaje = "¡Cuenta creada con éxito! Redirigiendo... 😸";
                $esExito = true;

                // Redireccionar al index en 2 segundos
                header("refresh:2;url=index.php");
            } else {
                $mensaje = "El rol seleccionado no es válido 😿";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $mensaje = "Este correo ya está registrado 😿";
            } else {
                $mensaje = "Error al guardar el usuario en la BD 😿";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge - Registro</title>

    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/registro_style.css">
</head>
<body>

    <main class="index-wrapper">
        
        <div class="index-card-wide">
            
            <header class="index-header">
                <img src="img/logo.png" alt="Logo Gatito" class="logo-gatito-sm">
                <h1 class="titulo-sm">Crear Cuenta</h1>
            </header>

            <form id="form-registro" action="registro.php" method="POST" class="index-form">
                
                <div class="input-group">
                    <label>TIPO DE USUARIO</label>
                    <div class="rol-selector">
                        <label class="rol-option active">
                            <input type="radio" name="rol" value="alumno" checked>
                            <span>Alumno</span>
                        </label>
                        <label class="rol-option">
                            <input type="radio" name="rol" value="docente">
                            <span>Docente</span>
                        </label>
                        <label class="rol-option">
                            <input type="radio" name="rol" value="padre">
                            <span>Padre</span>
                        </label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group">
                        <label for="nombre">NOMBRE COMPLETO</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
                    </div>

                    <div class="input-group">
                        <label for="correo">CORREO</label>
                        <input type="email" id="correo" name="correo" placeholder="correo@gmail.com" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group">
                        <label for="password">CONTRASEÑA</label>
                        <div class="password-box">
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                            <span class="toggle-eye" onclick="verPassword('password', this)">👁️</span>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="confirm_password">CONFIRMAR CONTRASEÑA</label>
                        <div class="password-box">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
                            <span class="toggle-eye" onclick="verPassword('confirm_password', this)">👁️</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-ingresar">Registrarse</button>

            </form>

            <footer class="index-footer">
                <a href="index.php" class="btn-registrar">¿YA TIENES CUENTA? INICIA SESIÓN</a>
            </footer>

            <!-- Alerta flotante pegada a la derecha -->
            <aside id="alerta-gato" class="alerta-gato-lateral <?php echo !empty($mensaje) ? ($esExito ? 'mostrar_alerta alerta-exito' : 'mostrar_alerta alerta-error') : 'oculto'; ?>">
                <div class="gato-burbuja">
                    <img id="gato-img" src="<?php echo $esExito ? 'img/gato_x2.jpg' : 'img/gato_x1.jpg'; ?>" alt="Gato Alerta" class="gato-alerta-grande">
                    <div class="mensaje-globo">
                        <p id="gato-mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
                    </div>
                </div>
            </aside>

        </div>

    </main>

    <script src="js/registro_script.js"></script>
</body>
</html>