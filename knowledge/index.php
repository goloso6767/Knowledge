<?php
require_once 'config/database.php';

$mensajeError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = trim($_POST['correo'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($correo) && !empty($password)) {
        try {
            $sql = "SELECT u.id_usuario, u.password, r.nombre_rol 
                    FROM usuarios u 
                    JOIN roles r ON u.id_rol = r.id_rol 
                    WHERE u.correo = :correo LIMIT 1";
                    
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['correo' => $correo]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && (password_verify($password, $usuario['password']) || $password === $usuario['password'])) {
                $rol = strtolower($usuario['nombre_rol']);
                
                if ($rol === 'alumno') {
                    header("Location: dashboards/dashboard-alumno.php");
                } elseif ($rol === 'docente') {
                    header("Location: dashboards/dashboard-docente.php");
                } elseif ($rol === 'padre') {
                    header("Location: dashboards/dashboard-padre.php");
                } else {
                    header("Location: dashboards/dashboard-admin.php");
                }
                exit;
            } else {
                $mensajeError = "Correo o contraseña incorrectos 🙀";
            }
        } catch (PDOException $e) {
            $mensajeError = "Error de conexión con la base de datos 😿";
        }
    } else {
        $mensajeError = "Por favor llena todos los campos 😿";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge - Login</title>

    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/index_style.css">
</head>
<body>

    <main class="index-wrapper">
        
        <div class="index-card">
            
            <header class="index-header">
                <img src="img/logo.png" alt="Logo Gatito" class="logo-gatito">
                <h1>Knowledge</h1>
            </header>

            <form id="form-index" action="index.php" method="POST" class="index-form">
                
                <div class="input-group">
                    <label for="correo">CORREO</label>
                    <input type="email" id="correo" name="correo" placeholder="ejemplo@cecyteqroo.edu.mx" required>
                </div>

                <div class="input-group">
                    <label for="password">CONTRASEÑA</label>
                    <div class="password-box">
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <span class="toggle-eye" onclick="verPassword('password', this)">👁️</span>
                    </div>
                </div>

                <button type="submit" class="btn-ingresar">Ingresar</button>

            </form>

            <footer class="index-footer">
                <a href="registro.php" class="btn-registrar">REGISTRAR CUENTA</a>
            </footer>

            <!-- Alerta flotante animada del gatito (al lado del contenedor) -->
            <aside id="alerta-gato" class="alerta-gato-lateral <?php echo !empty($mensajeError) ? 'mostrar_alerta alerta-error' : 'oculto'; ?>">
                <div class="gato-burbuja">
                    <img id="gato-img" src="img/gato_x1.jpg" alt="Gato Alerta" class="gato-alerta-grande">
                    <div class="mensaje-globo">
                        <p id="gato-mensaje"><?php echo htmlspecialchars($mensajeError); ?></p>
                    </div>
                </div>
            </aside>

        </div>

    </main>

    <script src="js/index_script.js"></script>
</body>
</html>