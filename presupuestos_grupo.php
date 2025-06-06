<?php
include('includes/auth.php');
include('includes/db.php');
$usuario_id = $_SESSION['usuario'];

if (isset($_POST['nombre'], $_POST['monto'], $_POST['categorias'])) {
  $nombre = $_POST['nombre'];
  $monto = floatval($_POST['monto']);
  $categorias = implode(',', $_POST['categorias']);
  $conn->query("INSERT INTO presupuesto_grupos (usuario_id, nombre, monto, categorias) VALUES ($usuario_id, '$nombre', $monto, '$categorias')");
}

if (isset($_GET['eliminar'])) {
  $id = intval($_GET['eliminar']);
  $conn->query("DELETE FROM presupuesto_grupos WHERE id = $id AND usuario_id = $usuario_id");
  header("Location: presupuestos_grupo.php");
  exit;
}

$presupuestos = $conn->query("SELECT * FROM presupuesto_grupos WHERE usuario_id = $usuario_id");

$res_categorias = $conn->query("SELECT DISTINCT categoria FROM movimientos WHERE usuario_id = $usuario_id");
$categorias_disponibles = [];
while ($row = $res_categorias->fetch_assoc()) {
  $categorias_disponibles[] = $row['categoria'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Presupuestos por Grupo</title>
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
  <h4 class="mb-4">Presupuestos por Grupo de Categorías</h4>

  <div class="card-style mb-4">
    <form method="post">
      <div class="row g-3 align-items-end">
        <div class="col-md-4">
          <label class="form-label">Nombre del Grupo</label>
          <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Monto Límite</label>
          <input type="number" step="0.01" name="monto" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Categorías</label>
          <select name="categorias[]" class="form-control" multiple required>
            <?php foreach ($categorias_disponibles as $cat): ?>
              <option value="<?= $cat ?>"><?= $cat ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-12 mt-3">
          <button class="btn btn-success w-100">Agregar Grupo</button>
        </div>
      </div>
    </form>
  </div>

  <?php while ($p = $presupuestos->fetch_assoc()): 
    $nombre = $p['nombre'];
    $limite = $p['monto'];
    $grupo_cats = explode(',', $p['categorias']);
    $cat_list = "'" . implode("','", $grupo_cats) . "'";
    $res = $conn->query("SELECT SUM(monto) as usado FROM movimientos 
                         WHERE usuario_id = $usuario_id AND tipo = 'egreso' 
                         AND categoria IN ($cat_list)");
    $usado = $res->fetch_assoc()['usado'] ?? 0;
    $progreso = min(100, ($usado / $limite) * 100);
  ?>
  <div class="card-style">
    <h6><?= $nombre ?> (<?= implode(', ', $grupo_cats) ?>)</h6>
    <p class="mb-1">$<?= number_format($usado, 2) ?> usados de $<?= number_format($limite, 2) ?></p>
    <div class="progress mb-2">
      <div class="progress-bar <?= $progreso > 90 ? 'bg-danger' : ($progreso > 70 ? 'bg-warning' : 'bg-success') ?>" role="progressbar" style="width: <?= $progreso ?>%"></div>
    </div>
    <a href="?eliminar=<?= $p['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar este grupo?')">Eliminar</a>
  </div>
  <?php endwhile; ?>
</div>
</body>
</html>
