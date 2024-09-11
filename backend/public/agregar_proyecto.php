<?php
$title = "Gestor de Proyectos";

require_once '../config/db.php';

// Manejar el envío del formulario para crear un nuevo proyecto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar los datos del formulario
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
    $fecha_inicio = filter_input(INPUT_POST, 'fecha_inicio', FILTER_SANITIZE_STRING);
    $fecha_fin = filter_input(INPUT_POST, 'fecha_fin', FILTER_SANITIZE_STRING);
    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);
    $responsable_id = filter_input(INPUT_POST, 'responsable_id', FILTER_SANITIZE_NUMBER_INT);

    if ($nombre && $descripcion && $fecha_inicio && $fecha_fin && $estado && $responsable_id) {
        // Preparar y ejecutar la consulta para insertar el nuevo proyecto
        $sql = "INSERT INTO proyectos (nombre, descripcion, fecha_inicio, fecha_fin, estado, responsable_id) 
                VALUES (:nombre, :descripcion, :fecha_inicio, :fecha_fin, :estado, :responsable_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin,
            ':estado' => $estado,
            ':responsable_id' => $responsable_id
        ]);

        // Redirigir a la página de lista de proyectos después de agregar
        header('Location: listar_proyectos.php');
        exit;
    } else {
        $error = 'Todos los campos son obligatorios.';
    }
}

// Obtener la lista de responsables
$sqlResponsables = "SELECT id, nombre FROM colaboradores";
$stmtResponsables = $pdo->prepare($sqlResponsables);
$stmtResponsables->execute();
$responsables = $stmtResponsables->fetchAll();

include_once "./../views/theme/header.php";
?>

<h3 class="mb-4">Agregar Proyecto</h3>

<!-- Formulario para crear un nuevo proyecto -->
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
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
    </div>
    <div class="mb-3">
        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
    </div>
    <div class="mb-3">
        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
    </div>
    <div class="mb-3">
        <label for="estado" class="form-label">Estado</label>
        <select class="form-control" id="estado" name="estado" required>
            <option value="Pendiente">Pendiente</option>
            <option value="En progreso">En progreso</option>
            <option value="Completado">Completado</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="responsable_id" class="form-label">Responsable</label>
        <select class="form-control" id="responsable_id" name="responsable_id" required>
            <?php foreach ($responsables as $responsable): ?>
                <option value="<?= $responsable['id'] ?>">
                    <?= htmlspecialchars($responsable['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Crear Proyecto</button>
    <a href="listar_proyectos.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include_once "./../views/theme/footer.php"; ?>