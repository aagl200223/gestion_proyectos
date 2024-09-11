<?php
$title = 'Panel de control';
require_once '../config/db.php';

// Obtener la cantidad de colaboradores
$sqlColaboradores = "SELECT COUNT(*) AS cantidad FROM colaboradores";
$stmtColaboradores = $pdo->prepare($sqlColaboradores);
$stmtColaboradores->execute();
$cantidadColaboradores = $stmtColaboradores->fetchColumn();

// Obtener la cantidad de proyectos
$sqlProyectos = "SELECT COUNT(*) AS cantidad FROM proyectos";
$stmtProyectos = $pdo->prepare($sqlProyectos);
$stmtProyectos->execute();
$cantidadProyectos = $stmtProyectos->fetchColumn();

// Obtener la cantidad de proyectos por colaborador
$sqlProyectosPorColaborador = "
    SELECT c.nombre, COUNT(p.id) AS cantidad_proyectos
    FROM colaboradores c
    LEFT JOIN proyectos p ON c.id = p.responsable_id
    GROUP BY c.id, c.nombre";
$stmtProyectosPorColaborador = $pdo->prepare($sqlProyectosPorColaborador);
$stmtProyectosPorColaborador->execute();
$proyectosPorColaborador = $stmtProyectosPorColaborador->fetchAll(PDO::FETCH_ASSOC);

include './../views/theme/header.php';
?>

<!-- Contenido -->

<!-- Estadísticas generales -->
<div class="row mb-4 justify-content-md-center">
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        Cantidad de Colaboradores
      </div>
      <div class="card-body">
        <h3><?= htmlspecialchars($cantidadColaboradores) ?></h3>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        Cantidad de Proyectos
      </div>
      <div class="card-body">
        <h3><?= htmlspecialchars($cantidadProyectos) ?></h3>
      </div>
    </div>
  </div>
</div>

<!-- Gráficos -->
<div class="row justify-content-md-center mb-5">
  <!-- Gráfico de proyectos por colaborador -->
  <div class="col-md-10">
    <div class="card">
      <div class="card-header">
        Proyectos por Colaborador
      </div>
      <div class="card-body">
        <canvas id="projectsPerCollaboratorChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Scripts para gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Gráfico de proyectos por colaborador
  var ctxProjects = document.getElementById('projectsPerCollaboratorChart').getContext('2d');
  var projectsPerCollaboratorChart = new Chart(ctxProjects, {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_column($proyectosPorColaborador, 'nombre')) ?>,
      datasets: [{
        label: 'Proyectos',
        data: <?= json_encode(array_column($proyectosPorColaborador, 'cantidad_proyectos')) ?>,
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>

<?php include './../views/theme/footer.php'; ?>