<?php
include('includes/auth.php');
include('includes/db.php');
$usuario_id = $_SESSION['usuario'];

if (isset($_POST['categoria'], $_POST['monto'])) {
  $categoria = $_POST['categoria'];
  $monto = floatval($_POST['monto']);
  $conn->query("INSERT INTO presupuestos (usuario_id, categoria, monto) VALUES ($usuario_id, '$categoria', $monto)");
}

if (isset($_GET['eliminar'])) {
  $id = intval($_GET['eliminar']);
  $conn->query("DELETE FROM presupuestos WHERE id = $id AND usuario_id = $usuario_id");
  header("Location: presupuestos.php");
  exit;
}

$presupuestos = $conn->query("SELECT * FROM presupuestos WHERE usuario_id = $usuario_id");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Presupuestos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { background: #f0f4f8; font-family: 'Segoe UI', sans-serif; }
    .content { margin-left: 260px; padding: 2rem; }
    .card-style {
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
  <h4 class="mb-4">Presupuestos por Categoría</h4>

  <div class="card-style mb-4">
    <form method="post">
      <div class="row g-3 align-items-end">
        <div class="col-md-6">
          <label class="form-label">Categoría</label>
          <input type="text" name="categoria" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Monto Límite</label>
          <input type="number" step="0.01" name="monto" class="form-control" required>
        </div>
        <div class="col-md-2">
          <button class="btn btn-success w-100">Agregar</button>
        </div>
      </div>
    </form>
  </div>

  <?php while ($p = $presupuestos->fetch_assoc()): 
    $cat = $p['categoria'];
    $limite = $p['monto'];
    $res = $conn->query("SELECT SUM(monto) as usado FROM movimientos WHERE usuario_id = $usuario_id AND tipo = 'egreso' AND categoria = '$cat'");
    $usado = $res->fetch_assoc()['usado'] ?? 0;
    $progreso = min(100, ($usado / $limite) * 100);
  ?>
  <div class="card-style">
    <h6><?= $cat ?></h6>
    <p class="mb-1">$<?= number_format($usado, 2) ?> usados de $<?= number_format($limite, 2) ?></p>
    <div class="progress mb-2">
      <div class="progress-bar <?= $progreso > 90 ? 'bg-danger' : ($progreso > 70 ? 'bg-warning' : 'bg-success') ?>" role="progressbar" style="width: <?= $progreso ?>%"></div>
    </div>
    <a href="?eliminar=<?= $p['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar este presupuesto?')">Eliminar</a>
  </div>
  <?php endwhile; ?>
</div>
</body>
</html>
