<?php
require_once '../config/db.php';

class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($nombre, $correo, $contrasena) {
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare('INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)');
        return $stmt->execute([$nombre, $correo, $hash]);
    }

    public function findByEmail($correo) {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE correo = ?');
        $stmt->execute([$correo]);
        return $stmt->fetch();
    }

    public function generateToken($user) {
        $key = "your_secret_key";
        $payload = [
            'id' => $user,
            'exp' => time() + (60 * 60)
        ];
        $json = json_encode($payload);
        return base64_encode($json);
    }
}
?>
