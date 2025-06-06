<?php
include('includes/auth.php');
include('includes/db.php');
$usuario_id = $_SESSION['usuario'];
$mensaje = '';

// Crear nueva meta de ahorro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nueva_meta'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $objetivo = floatval($_POST['monto_objetivo']);
    $fecha = $conn->real_escape_string($_POST['fecha_objetivo']);
    $conn->query("INSERT INTO ahorros (usuario_id, nombre, monto_objetivo, fecha_objetivo, acumulado, estado, creado_en)
                  VALUES ($usuario_id, '$nombre', $objetivo, '$fecha', 0, 'activo', NOW())");
    $mensaje = "✅ Meta creada exitosamente.";
}

// Aportar a una meta existente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aportar'])) {
    $ahorro_id = intval($_POST['ahorro_id']);
    $monto = floatval($_POST['monto']);
    $conn->query("INSERT INTO aportes (ahorro_id, monto, fecha) VALUES ($ahorro_id, $monto, NOW())");
    $conn->query("UPDATE ahorros SET acumulado = acumulado + $monto WHERE id = $ahorro_id");
    $mensaje = "✅ Aporte realizado correctamente.";
}

// Obtener metas existentes
$ahorros = $conn->query("SELECT * FROM ahorros WHERE usuario_id = $usuario_id");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ahorros</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { background: #eef2f7; font-family: 'Segoe UI', sans-serif; }
    .content { margin-left: 260px; padding: 2rem; }
    .card-style {
      background: white;
      border-radius: 15px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      padding: 20px;
      max-width: 800px;
      margin: 0 auto 2rem auto;
    }
    .form-label { font-weight: 500; color: #007866; }
  </style>
</head>
<body>
<?php include 'menu.php'; ?>
<div class="content">
  <?php if ($mensaje): ?>
    <div class="alert alert-success"><?= $mensaje ?></div>
  <?php endif; ?>

  <div class="card-style">
    <h4>➕ Crear Nueva Meta de Ahorro</h4>
    <form method="post">
      <input type="hidden" name="nueva_meta" value="1">
      <div class="mb-3">
        <label class="form-label">Nombre de la Meta</label>
        <input type="text" name="nombre" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Monto Objetivo</label>
        <input type="number" name="monto_objetivo" step="0.01" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Fecha Objetivo</label>
        <input type="date" name="fecha_objetivo" class="form-control" required>
      </div>
      <button class="btn btn-primary w-100">Crear Meta</button>
    </form>
  </div>

  <div class="card-style">
    <h4>💰 Aportar a una Meta Existente</h4>
    <form method="post">
      <input type="hidden" name="aportar" value="1">
      <div class="mb-3">
        <label class="form-label">Seleccionar Meta</label>
        <select name="ahorro_id" class="form-control" required>
          <?php while ($ahorro = $ahorros->fetch_assoc()): ?>
            <option value="<?= $ahorro['id'] ?>"><?= htmlspecialchars($ahorro['nombre']) ?> (Actual: $<?= number_format($ahorro['acumulado'], 2) ?> / $<?= number_format($ahorro['monto_objetivo'], 2) ?>)</option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Monto a Aportar</label>
        <input type="number" name="monto" step="0.01" class="form-control" required>
      </div>
      <button class="btn btn-success w-100">Aportar</button>
    </form>
  </div>
</div>
</body>
</html>
