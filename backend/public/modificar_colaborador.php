<?php
$title = "Gestor de Colaboradores";

require_once '../config/db.php';

// Obtener el ID del colaborador de la URL
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Verificar si el ID es válido
if (!$id) {
    header('Location: listar_colaboradores.php');
    exit;
}

// Obtener los datos del colaborador para prellenar el formulario
$sql = "SELECT nombre, correo, telefono FROM colaboradores WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$colaborador = $stmt->fetch();

// Manejar la acción de eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $sql = "DELETE FROM colaboradores WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    // Redirigir a la página de lista de colaboradores después de eliminar
    header('Location: listar_colaboradores.php');
    exit;
}

// Manejar el envío del formulario para modificar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete'])) {
    // Sanitizar y validar los datos del formulario
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);

    if ($nombre && $correo && $telefono) {
        // Preparar y ejecutar la consulta para actualizar el colaborador
        $sql = "UPDATE colaboradores SET nombre = :nombre, correo = :correo, telefono = :telefono WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':correo' => $correo,
            ':telefono' => $telefono,
            ':id' => $id
        ]);

        // Redirigir a la página de lista de colaboradores después de modificar
        header('Location: listar_colaboradores.php');
        exit;
    } else {
        $error = 'Todos los campos son obligatorios.';
    }
}

include_once "./../views/theme/header.php";
?>

<h3 class="mb-4">Modificar Colaborador</h3>
<!-- Formulario para modificar un colaborador -->
<form method="POST" action="">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre"
            value="<?= htmlspecialchars($colaborador['nombre']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="correo" class="form-label">Correo</label>
        <input type="email" class="form-control" id="correo" name="correo"
            value="<?= htmlspecialchars($colaborador['correo']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono"
            value="<?= htmlspecialchars($colaborador['telefono']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <button type="submit" name="delete" class="btn btn-danger">Eliminar Colaborador</button>
    <a href="listar_colaboradores.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include_once "./../views/theme/footer.php"; ?>