<?php
include('includes/auth.php');
include('includes/db.php');

$usuario_id = $_SESSION['usuario'];
$mensaje = "";

if (isset($_POST['ahorro_id'], $_POST['monto'])) {
  $ahorro_id = intval($_POST['ahorro_id']);
  $monto = floatval($_POST['monto']);

  // Verificar que el objetivo exista y sea del usuario
  $res = $conn->query("SELECT * FROM ahorros WHERE id = $ahorro_id AND usuario_id = $usuario_id");
  if ($res && $res->num_rows > 0) {
    $conn->query("UPDATE ahorros SET acumulado = acumulado + $monto WHERE id = $ahorro_id");
    $mensaje = "✅ Aporte registrado correctamente.";
  } else {
    $mensaje = "❌ Objetivo no válido.";
  }
}

// Traer objetivos activos
$objetivos = $conn->query("SELECT * FROM ahorros WHERE usuario_id = $usuario_id AND estado = 'activo'");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Aporte</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'menu.php'; ?>
<div class="container mt-4">
  <h2>➕ Registrar Aporte a un Objetivo</h2>
  <?php if ($mensaje): ?><div class="alert alert-info"><?= $mensaje ?></div><?php endif; ?>
  <form method="post" class="grafico-box p-4 rounded bg-light">
    <div class="row g-3">
      <div class="col-md-6">
        <label>Objetivo</label>
        <select name="ahorro_id" class="form-select" required>
          <option value="">Seleccionar</option>
          <?php while($o = $objetivos->fetch_assoc()): ?>
            <option value="<?= $o['id'] ?>"><?= htmlspecialchars($o['nombre']) ?> ($<?= number_format($o['acumulado'],2) ?> / $<?= number_format($o['monto_objetivo'],2) ?>)</option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label>Monto a aportar</label>
        <input type="number" step="0.01" name="monto" class="form-control" required>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-success w-100">Registrar</button>
      </div>
    </div>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
