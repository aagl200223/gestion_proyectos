<?php
$title = "Gestor de Proyectos";
require_once '../config/db.php';

// Obtener el ID del proyecto de la URL
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Verificar si el ID es válido
if (!$id) {
    header('Location: listar_proyectos.php');
    exit;
}

// Obtener los datos del proyecto para prellenar el formulario
$sql = "SELECT nombre, descripcion, fecha_inicio, fecha_fin, estado, responsable_id FROM proyectos WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$proyecto = $stmt->fetch();

// Manejar el envío del formulario para modificar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar los datos del formulario
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
    $fecha_inicio = filter_input(INPUT_POST, 'fecha_inicio', FILTER_SANITIZE_STRING);
    $fecha_fin = filter_input(INPUT_POST, 'fecha_fin', FILTER_SANITIZE_STRING);
    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);
    $responsable_id = filter_input(INPUT_POST, 'responsable_id', FILTER_SANITIZE_NUMBER_INT);

    if ($nombre && $descripcion && $fecha_inicio && $fecha_fin && $estado && $responsable_id) {
        // Preparar y ejecutar la consulta para actualizar el proyecto
        $sql = "UPDATE proyectos SET nombre = :nombre, descripcion = :descripcion, fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin, estado = :estado, responsable_id = :responsable_id WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin,
            ':estado' => $estado,
            ':responsable_id' => $responsable_id,
            ':id' => $id
        ]);

        // Redirigir a la página de lista de proyectos después de modificar
        header('Location: listar_proyectos.php');
        exit;
    } else {
        $error = 'Todos los campos son obligatorios.';
    }
}

// Manejar la acción de eliminación
if (isset($_POST['delete'])) {
    $sql = "DELETE FROM proyectos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    // Redirigir a la página de lista de proyectos después de eliminar
    header('Location: listar_proyectos.php');
    exit;
}

// Obtener la lista de responsables
$sqlResponsables = "SELECT id, nombre FROM colaboradores";
$stmtResponsables = $pdo->prepare($sqlResponsables);
$stmtResponsables->execute();
$responsables = $stmtResponsables->fetchAll();

include_once "./../views/theme/header.php";
?>
<h3 class="mb-4">Modificar Proyecto</h3>
<!-- Formulario para modificar un proyecto -->
<form method="POST" action="">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre"
            value="<?= htmlspecialchars($proyecto['nombre']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
            required><?= htmlspecialchars($proyecto['descripcion']) ?></textarea>
    </div>
    <div class="mb-3">
        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
            value="<?= htmlspecialchars($proyecto['fecha_inicio']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
            value="<?= htmlspecialchars($proyecto['fecha_fin']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="estado" class="form-label">Estado</label>
        <select class="form-control" id="estado" name="estado" required>
            <option value="Pendiente" <?= $proyecto['estado'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente
            </option>
            <option value="En progreso" <?= $proyecto['estado'] === 'En progreso' ? 'selected' : '' ?>>En progreso
            </option>
            <option value="Completado" <?= $proyecto['estado'] === 'Completado' ? 'selected' : '' ?>>Completado
            </option>
        </select>
    </div>
    <div class="mb-3">
        <label for="responsable_id" class="form-label">Responsable</label>
        <select class="form-control" id="responsable_id" name="responsable_id" required>
            <?php foreach ($responsables as $responsable): ?>
                <option value="<?= $responsable['id'] ?>" <?= $proyecto['responsable_id'] == $responsable['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($responsable['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <!-- Botón para eliminar el proyecto -->
    <!-- <button type="submit" name="delete" class="btn btn-danger">Eliminar Proyecto</button> -->
    <a href="listar_proyectos.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include_once "./../views/theme/footer.php"; ?>