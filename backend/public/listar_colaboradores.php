<?php
$title = "Gestor de Colaboradores";
require_once './../config/db.php';

// Consulta para obtener los colaboradores
$sql = "SELECT id, nombre, correo, telefono FROM colaboradores";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$colaboradores = $stmt->fetchAll();

include_once "./../views/theme/header.php";
?>

<!-- Contenido -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Lista de Colaboradores</h3>
    <a href="agregar_colaborador.php" class="btn btn-success">Agregar Colaborador</a>
</div>

<!-- Tabla de colaboradores -->
<table class="table table-striped table-hover table-bordered">
    <thead class="table-success">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre</th>
            <th scope="col">Correo</th>
            <th scope="col">Teléfono</th>
            <th scope="col">Acciones</th> <!-- Columna para botones de acciones -->
        </tr>
    </thead>
    <tbody>
        <?php if ($colaboradores): ?>
            <?php foreach ($colaboradores as $index => $colaborador): ?>
                <tr>
                    <th scope="row"><?= $index + 1 ?></th>
                    <td><?= htmlspecialchars($colaborador['nombre']) ?></td>
                    <td><?= htmlspecialchars($colaborador['correo']) ?></td>
                    <td><?= htmlspecialchars($colaborador['telefono']) ?></td>
                    <td>
                        <a href="modificar_colaborador.php?id=<?= $colaborador['id'] ?>"
                            class="btn btn-warning btn-sm">Modificar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">No hay colaboradores registrados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include_once "./../views/theme/footer.php"; ?>