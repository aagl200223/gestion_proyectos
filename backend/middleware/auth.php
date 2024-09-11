<?php
require_once '../vendor/autoload.php';
use \Firebase\JWT\JWT;

function authenticate() {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        try {
            $decoded = JWT::decode($token, 'your_secret_key', ['HS256']);
            return $decoded->id;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['message' => 'Token no válido']);
            exit;
        }
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'No se proporcionó token']);
        exit;
    }
}
?>
