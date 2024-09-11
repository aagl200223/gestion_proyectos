<?php
session_start();
require_once './../models/Usuario.php';

$usuarioModel = new Usuario($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar los datos recibidos
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    // Validar que los campos no estén vacíos
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'El nombre de usuario y la contraseña son obligatorios.';
    } else {
        // Buscar usuario por email
        $usuario = $usuarioModel->findByEmail($username);
        echo "'" . password_verify($password, $usuario['contrasena']) . "'";

        if ($usuario && $password == $usuario['contrasena']) {
            // Generar token
            $token = $usuarioModel->generateToken($usuario);
            if ($token) {
                $_SESSION['token'] = $token;
                unset($_SESSION['error']); // Limpiar el mensaje de error si existe
                header('Location: panel_control.php');
                exit;
            } else {
                $_SESSION['error'] = 'Error al generar el token. Inténtalo de nuevo.';
            }
        } else {
            $_SESSION['error'] = 'Credenciales incorrectas. Por favor, inténtalo de nuevo.';
        }
    }
}

// Mostrar mensaje de error si existe
if (isset($_SESSION['error'])) {
    echo "<script type='text/javascript'>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']); // Limpiar el mensaje de error después de mostrarlo
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proyectos</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1 class="mt-5">Gestión de Proyectos</h1>
        <div id="app">
            <h2>Bienvenido al Sistema de Gestión de Proyectos</h2>
            <form id="login-form" method="POST" action="">
                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" class="form-control" id="username" name="username"
                        value="andygironloaiza@gmail.com" required>
                </div>
                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" value="pato" required>
                </div>
                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>