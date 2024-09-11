<?php
require_once '../models/Usuario.php';

$usuarioModel = new Usuario($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_GET['action'] === 'register') {
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $contrasena = $_POST['contrasena'];

        if ($usuarioModel->create($nombre, $correo, $contrasena)) {
            echo json_encode(['message' => 'Usuario registrado!']);
        } else {
            echo json_encode(['message' => 'Error al registrar usuario']);
        }
    }

    if ($_GET['action'] === 'login') {
        $correo = $_POST['correo'];
        $contrasena = $_POST['contrasena'];

        $usuario = $usuarioModel->findByEmail($correo);

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            $token = $usuarioModel->generateToken($usuario['id']);
            echo json_encode(['token' => $token]);
        } else {
            echo json_encode(['message' => 'Credenciales incorrectas']);
        }
    }
}
?>
