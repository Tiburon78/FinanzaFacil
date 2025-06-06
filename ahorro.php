<?php
include('includes/auth.php');
include('includes/db.php');
$usuario_id = $_SESSION['usuario'];

$ahorros = $conn->query("SELECT * FROM ahorros WHERE usuario_id = $usuario_id");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Ahorros</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { background: #eef2f7; font-family: 'Segoe UI', sans-serif; }
    .content { margin-left: 260px; padding: 2rem; }
    .card-style {
      background: white;
      border-radius: 15px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      padding: 20px;
      margin-bottom: 30px;
    }
    .form-label { font-weight: 500; color: #00685e; }
  </style>
</head>
<body>
<?php include 'menu.php'; ?>
<div class="content">
  <h4 class="mb-4">Metas de Ahorro</h4>
  <a href="aportar_ahorro.php" class="btn btn-success mb-4">➕ Agregar Meta / Aportar</a>

  <?php while ($ahorro = $ahorros->fetch_assoc()):
    $ahorro_id = $ahorro['id'];
    $meta = floatval($ahorro['monto_objetivo']);
    $aportado_result = $conn->query("SELECT SUM(monto) AS total FROM aportes WHERE ahorro_id = $ahorro_id");
    $aportado = floatval($aportado_result->fetch_assoc()['total']);
    $porcentaje = $meta > 0 ? min(100, ($aportado / $meta) * 100) : 0;
  ?>
    <div class="card-style">
      <h5><?= htmlspecialchars($ahorro['nombre']) ?></h5>
      <p>Meta: $<?= number_format($meta, 2) ?> &nbsp; | &nbsp; Aportado: $<?= number_format($aportado, 2) ?></p>
      <p class="mb-1">Fecha objetivo: <?= htmlspecialchars($ahorro['fecha_objetivo']) ?></p>
      <div class="progress mb-2">
        <div class="progress-bar <?= $porcentaje > 90 ? 'bg-danger' : ($porcentaje > 70 ? 'bg-warning' : 'bg-success') ?>" role="progressbar" style="width: <?= $porcentaje ?>%"></div>
      </div>
      <a href="aportar_ahorro.php?id=<?= $ahorro_id ?>" class="btn btn-outline-primary btn-sm">Aportar</a>
      <a href="?eliminar=<?= $ahorro_id ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar esta meta?')">Eliminar</a>
    </div>
  <?php endwhile; ?>
</div>
</body>
</html>
