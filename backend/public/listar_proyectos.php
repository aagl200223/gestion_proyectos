<?php
$title = "Gestor de Proyectos";
require_once '../config/db.php';

// Consulta para obtener los proyectos
$sql = "SELECT p.id, p.nombre, p.descripcion, p.fecha_inicio, p.fecha_fin, p.estado, c.nombre AS responsable
        FROM proyectos p
        LEFT JOIN colaboradores c ON p.responsable_id = c.id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$proyectos = $stmt->fetchAll();

include_once "./../views/theme/header.php";
?>
<!-- Contenido -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Lista de proyectos</h3>
    <a href="agregar_proyecto.php" class="btn btn-success">Agregar proyecto</a>
</div>

<!-- Tabla de proyectos -->
<table class="table table-striped table-hover table-bordered">
    <thead class="table-success">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre</th>
            <th scope="col">Descripción</th>
            <th scope="col">Fecha Inicio</th>
            <th scope="col">Fecha Fin</th>
            <th scope="col">Estado</th>
            <th scope="col">Responsable</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($proyectos): ?>
            <?php foreach ($proyectos as $index => $proyecto): ?>
                <tr>
                    <th scope="row"><?= $index + 1 ?></th>
                    <td><?= htmlspecialchars($proyecto['nombre']) ?></td>
                    <td><?= htmlspecialchars($proyecto['descripcion']) ?></td>
                    <td><?= htmlspecialchars($proyecto['fecha_inicio']) ?></td>
                    <td><?= htmlspecialchars($proyecto['fecha_fin']) ?></td>
                    <td><?= htmlspecialchars($proyecto['estado']) ?></td>
                    <td><?= htmlspecialchars($proyecto['responsable']) ?></td>
                    <td>
                        <a href="modificar_proyecto.php?id=<?= $proyecto['id'] ?>" class="btn btn-warning btn-sm">Modificar</a>
                        <!-- Se puede añadir un botón para eliminar en un futuro, con confirmación JS para evitar eliminaciones accidentales -->
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">No hay proyectos registrados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include_once "./../views/theme/footer.php"; ?>