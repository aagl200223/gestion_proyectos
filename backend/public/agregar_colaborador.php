<?php
$title = "Gestor de Colaboradores";
require_once '../config/db.php';

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar los datos del formulario
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);

    if ($nombre && $correo && $telefono) {
        // Preparar y ejecutar la consulta para insertar el nuevo colaborador
        $sql = "INSERT INTO colaboradores (nombre, correo, telefono) VALUES (:nombre, :correo, :telefono)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':correo' => $correo,
            ':telefono' => $telefono
        ]);

        // Redirigir a la página de lista de colaboradores después de agregar
        header('Location: listar_colaboradores.php');
        exit;
    } else {
        $error = 'Todos los campos son obligatorios.';
    }
}

include_once "./../views/theme/header.php";
?>

<!-- Contenido -->
<h3 class="mb-4">Agregar Colaborador</h3>
<!-- Formulario para agregar un nuevo colaborador -->
<form method="POST" action="">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="mb-3">
        <label for="correo" class="form-label">Correo</label>
        <input type="email" class="form-control" id="correo" name="correo" required>
    </div>
    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" required>
    </div>
    <button type="submit" class="btn btn-primary">Agregar</button>
    <a href="listar_colaboradores.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include_once "./../views/theme/footer.php"; ?>