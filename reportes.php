<?php
include('includes/auth.php');
include('includes/db.php');
$usuario_id = $_SESSION['usuario'];
$desde = $_GET['desde'] ?? date('Y-m-01');
$hasta = $_GET['hasta'] ?? date('Y-m-t');

function totalPorMes($conn, $usuario_id, $tipo, $desde, $hasta) {
  $result = $conn->query("SELECT MONTH(fecha) as mes, SUM(monto) as total 
                          FROM movimientos 
                          WHERE usuario_id = $usuario_id AND tipo = '$tipo' 
                          AND fecha BETWEEN '$desde' AND '$hasta' 
                          GROUP BY MONTH(fecha)");
  $data = array_fill(1, 12, 0);
  while ($row = $result->fetch_assoc()) $data[(int)$row['mes']] = (float)$row['total'];
  return $data;
}
function totalPorCategoria($conn, $usuario_id, $tipo, $desde, $hasta) {
  $result = $conn->query("SELECT categoria, SUM(monto) as total 
                          FROM movimientos 
                          WHERE usuario_id = $usuario_id AND tipo = '$tipo' 
                          AND fecha BETWEEN '$desde' AND '$hasta' 
                          GROUP BY categoria");
  $data = [];
  while ($row = $result->fetch_assoc()) $data[] = ['label' => $row['categoria'], 'y' => (float)$row['total']];
  return $data;
}
$ingresos = totalPorMes($conn, $usuario_id, 'ingreso', $desde, $hasta);
$egresos = totalPorMes($conn, $usuario_id, 'egreso', $desde, $hasta);
$cat_ingresos = totalPorCategoria($conn, $usuario_id, 'ingreso', $desde, $hasta);
$cat_egresos = totalPorCategoria($conn, $usuario_id, 'egreso', $desde, $hasta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Reportes</title>
  <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { background: #f0f4f8; font-family: 'Segoe UI', sans-serif; }
    .content { margin-left: 260px; padding: 2rem; }
    .chart-container {
      background: white;
      border-radius: 15px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      padding: 20px;
      margin-bottom: 30px;
    }
    .form-label { font-weight: 500; color: #007a63; }
  </style>
</head>
<body>
<?php include 'menu.php'; ?>
<div class="content">
  <h4 class="mb-4">Reportes Financieros</h4>
  <form class="row g-3 mb-4">
    <div class="col-md-3">
      <label class="form-label">Desde</label>
      <input type="date" name="desde" value="<?= $desde ?>" class="form-control" />
    </div>
    <div class="col-md-3">
      <label class="form-label">Hasta</label>
      <input type="date" name="hasta" value="<?= $hasta ?>" class="form-control" />
    </div>
    <div class="col-md-3 align-self-end">
      <button class="btn btn-primary">Actualizar</button>
    </div>
  </form>

  <div class="row">
    <div class="col-md-6">
      <div class="chart-container">
        <div id="lineIngresosEgresos" style="height: 300px;"></div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="chart-container">
        <div id="tortaEgresos" style="height: 300px;"></div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="chart-container">
        <div id="tortaIngresos" style="height: 300px;"></div>
      </div>
    </div>
  </div>
</div>

<script>
window.onload = function () {
  new CanvasJS.Chart("lineIngresosEgresos", {
    animationEnabled: true,
    theme: "light2",
    title: { text: "Ingresos vs Egresos Mensuales" },
    axisY: { title: "Monto", includeZero: true },
    data: [{
      type: "line",
      name: "Ingresos",
      showInLegend: true,
      dataPoints: <?= json_encode(array_map(fn($v, $i) => ["label" => date('M', mktime(0, 0, 0, $i, 1)), "y" => $v], $ingresos, array_keys($ingresos)), JSON_NUMERIC_CHECK) ?>
    }, {
      type: "line",
      name: "Egresos",
      showInLegend: true,
      dataPoints: <?= json_encode(array_map(fn($v, $i) => ["label" => date('M', mktime(0, 0, 0, $i, 1)), "y" => $v], $egresos, array_keys($egresos)), JSON_NUMERIC_CHECK) ?>
    }]
  }).render();

  new CanvasJS.Chart("tortaEgresos", {
    animationEnabled: true,
    theme: "light2",
    title: { text: "Egresos por Categoría" },
    data: [{ type: "pie", dataPoints: <?= json_encode($cat_egresos, JSON_NUMERIC_CHECK) ?> }]
  }).render();

  new CanvasJS.Chart("tortaIngresos", {
    animationEnabled: true,
    theme: "light2",
    title: { text: "Ingresos por Categoría" },
    data: [{ type: "pie", dataPoints: <?= json_encode($cat_ingresos, JSON_NUMERIC_CHECK) ?> }]
  }).render();
};
</script>
</body>
</html>
