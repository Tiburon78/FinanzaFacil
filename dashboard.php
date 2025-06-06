<?php
include('includes/auth.php');
include('includes/db.php');
$usuario_id = $_SESSION['usuario'];

$user = $conn->query("SELECT simbolo_moneda FROM usuarios WHERE id = $usuario_id")->fetch_assoc();
$simbolo = $user['simbolo_moneda'] ?? '$';

$ingresos = $conn->query("SELECT SUM(monto) FROM movimientos WHERE usuario_id = $usuario_id AND tipo = 'ingreso'")->fetch_row()[0] ?? 0;
$egresos = $conn->query("SELECT SUM(monto) FROM movimientos WHERE usuario_id = $usuario_id AND tipo = 'egreso'")->fetch_row()[0] ?? 0;
$tarjeta = $conn->query("SELECT SUM(monto) FROM movimientos WHERE usuario_id = $usuario_id AND forma_pago = 'tarjeta'")->fetch_row()[0] ?? 0;
$saldo = $ingresos - $egresos;

// Generar datos mensuales
$datos_mensuales = [];
$result = $conn->query("SELECT DATE_FORMAT(fecha, '%M') AS mes, 
  SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE 0 END) AS ingreso,
  SUM(CASE WHEN tipo = 'egreso' THEN monto ELSE 0 END) AS egreso
  FROM movimientos 
  WHERE usuario_id = $usuario_id
  GROUP BY DATE_FORMAT(fecha, '%Y-%m') ORDER BY fecha");

while($row = $result->fetch_assoc()) {
  $datos_mensuales[] = $row;
}

// Datos de torta por categoría de tarjeta
$datos_torta = [];
$res_torta = $conn->query("SELECT categoria, SUM(monto) as total FROM movimientos 
  WHERE usuario_id = $usuario_id AND forma_pago = 'tarjeta' 
  GROUP BY categoria ORDER BY total DESC");

while($row = $res_torta->fetch_assoc()) {
  $datos_torta[] = $row;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard | FinanzaFácil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { background-color: #e5f4f0; font-family: 'Segoe UI', sans-serif; }
    .main-container { margin-left: 260px; padding: 2rem; }
    .resumen-grid { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem; }
    .resumen-box {
      flex: 1;
      min-width: 200px;
      background: #fff;
      border-radius: 16px;
      padding: 1rem;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    .resumen-box h6 { font-weight: 600; margin-bottom: 0.5rem; }
    .resumen-box .valor { font-size: 1.5rem; font-weight: bold; }
    .graficos-grid { display: flex; gap: 1rem; flex-wrap: wrap; }
    .grafico-card {
      background: white;
      border-radius: 16px;
      flex: 1;
      min-width: 300px;
      padding: 1rem;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body>
<?php include 'menu.php'; ?>
<div class="main-container">
  <div class="resumen-grid">
    <div class="resumen-box" style="background:#d7f8ed;">
      <h6>Ingresos</h6>
      <div class="valor text-success"><?= $simbolo . number_format($ingresos, 2) ?></div>
    </div>
    <div class="resumen-box" style="background:#fde2e2;">
      <h6>Egresos</h6>
      <div class="valor text-danger"><?= $simbolo . number_format($egresos, 2) ?></div>
    </div>
    <div class="resumen-box" style="background:#dbf0f7;">
      <h6>Saldo</h6>
      <div class="valor text-primary"><?= $simbolo . number_format($saldo, 2) ?></div>
    </div>
  </div>

  <div class="graficos-grid">
    <div class="grafico-card">
      <h6>Ingresos vs Egresos (Mensual)</h6>
      <canvas id="graficoLineas"></canvas>
    </div>
    <div class="grafico-card">
      <h6>Gastos con tarjeta por categoría</h6>
      <canvas id="graficoTorta"></canvas>
    </div>
  </div>
</div>

<script>
const dataMensual = <?= json_encode($datos_mensuales) ?>;
const labels = dataMensual.map(x => x.mes);
const ingresos = dataMensual.map(x => parseFloat(x.ingreso));
const egresos = dataMensual.map(x => parseFloat(x.egreso));

new Chart(document.getElementById('graficoLineas'), {
  type: 'line',
  data: {
    labels: labels,
    datasets: [{
      label: 'Ingresos',
      data: ingresos,
      borderColor: '#2ecc71',
      fill: false
    }, {
      label: 'Egresos',
      data: egresos,
      borderColor: '#e74c3c',
      fill: false
    }]
  }
});

const dataTorta = <?= json_encode($datos_torta) ?>;
new Chart(document.getElementById('graficoTorta'), {
  type: 'doughnut',
  data: {
    labels: dataTorta.map(x => x.categoria),
    datasets: [{
      data: dataTorta.map(x => parseFloat(x.total)),
      backgroundColor: ['#3498db', '#1abc9c', '#f39c12', '#9b59b6', '#e74c3c']
    }]
  }
});
</script>
</body>
</html>
