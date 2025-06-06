<?php
session_start();
include('includes/auth.php');
include('includes/db.php');

// Datos generales
$usuario_id = $_SESSION["usuario"];

// Ingresos
$ingresos = $conn->query("SELECT SUM(monto) as total FROM movimientos WHERE tipo = 'ingreso' AND usuario_id = $usuario_id")->fetch_assoc()["total"] ?? 0;

// Egresos
$egresos = $conn->query("SELECT SUM(monto) as total FROM movimientos WHERE tipo = 'egreso' AND usuario_id = $usuario_id")->fetch_assoc()["total"] ?? 0;

// Gastos con tarjeta
$gastos_tarjeta = $conn->query("SELECT SUM(monto) as total FROM movimientos WHERE tipo = 'egreso' AND forma_pago = 'Tarjeta' AND usuario_id = $usuario_id")->fetch_assoc()["total"] ?? 0;

$balance = $ingresos - $egresos;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - FinanzaFácil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .card-rounded { border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
    .chart-box { padding: 15px; border: 1px solid #ddd; border-radius: 15px; margin-bottom: 20px; background: #fff; }
  </style>
</head>
<body>
  <?php include('menu.php'); ?>
  <div class="container mt-4">
    <h2>Resumen financiero</h2>
    <div class="row">
      <div class="col-md-3">
        <div class="card card-rounded text-white bg-success p-3">
          <div>💰 Ingresos: <strong>$<?php echo number_format($ingresos, 2); ?></strong></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-rounded text-white bg-danger p-3">
          <div>💸 Egresos: <strong>$<?php echo number_format($egresos, 2); ?></strong></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-rounded text-white bg-info p-3">
          <div>💳 Gastos Tarjeta: <strong>$<?php echo number_format($gastos_tarjeta, 2); ?></strong></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-rounded text-white bg-primary p-3">
          <div>📊 Balance: <strong>$<?php echo number_format($balance, 2); ?></strong></div>
        </div>
      </div>
    </div>

    <div class="chart-box">
      <h4>Gráfico de ingresos y egresos mensuales</h4>
      <canvas id="barChart"></canvas>
    </div>

    <div class="chart-box">
      <h4>Distribución por categoría</h4>
      <canvas id="pieChart"></canvas>
    </div>
  </div>

  <script>
    const barChart = new Chart(document.getElementById("barChart"), {
      type: "bar",
      data: {
        labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun"], // Ejemplo
        datasets: [
          { label: "Ingresos", data: [1000, 1200, 800, 1500, 1300, 1700], backgroundColor: "green" },
          { label: "Egresos", data: [900, 1100, 700, 1300, 1000, 1400], backgroundColor: "red" }
        ]
      },
      options: { responsive: true }
    });

    const pieChart = new Chart(document.getElementById("pieChart"), {
      type: "pie",
      data: {
        labels: ["Alquiler", "Comida", "Servicios", "Otros"],
        datasets: [{
          data: [500, 300, 200, 150],
          backgroundColor: ["#007bff", "#28a745", "#ffc107", "#dc3545"]
        }]
      },
      options: { responsive: true }
    });
  </script>
</body>
</html>
