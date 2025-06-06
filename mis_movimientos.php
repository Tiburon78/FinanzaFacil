<?php
include('includes/auth.php');
include('includes/db.php');

$usuario_id = $_SESSION['usuario'];
$where = "WHERE usuario_id = $usuario_id";
$params = [];

if (!empty($_GET['tipo'])) {
  $tipo = $_GET['tipo'];
  $params[] = "tipo = '$tipo'";
}
if (!empty($_GET['forma_pago'])) {
  $forma_pago = $_GET['forma_pago'];
  $params[] = "forma_pago = '$forma_pago'";
}
if (!empty($_GET['desde']) && !empty($_GET['hasta'])) {
  $desde = $_GET['desde'];
  $hasta = $_GET['hasta'];
  $params[] = "fecha BETWEEN '$desde' AND '$hasta'";
}
if (!empty($_GET['monto'])) {
  $monto = floatval($_GET['monto']);
  $params[] = "monto >= $monto";
}
if (!empty($params)) {
  $where .= " AND " . implode(" AND ", $params);
}

$movimientos = $conn->query("SELECT * FROM movimientos $where ORDER BY fecha DESC");

if (isset($_GET['eliminar'])) {
  $id = intval($_GET['eliminar']);
  $conn->query("DELETE FROM movimientos WHERE id = $id AND usuario_id = $usuario_id");
  header("Location: mis_movimientos.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Mis Movimientos</title>
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
    .btn-delete { color: red; text-decoration: none; font-weight: bold; }
    .btn-delete:hover { text-decoration: underline; }
  </style>
</head>
<body>
<?php include 'menu.php'; ?>
<div class="content">
  <h4 class="mb-4">Mis Movimientos</h4>

  <div class="card-style mb-4">
    <form method="get">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Tipo</label>
          <select name="tipo" class="form-select">
            <option value="">Todos</option>
            <option value="ingreso">Ingreso</option>
            <option value="egreso">Egreso</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Forma de Pago</label>
          <select name="forma_pago" class="form-select">
            <option value="">Todas</option>
            <option value="efectivo">Efectivo</option>
            <option value="tarjeta">Tarjeta</option>
            <option value="transferencia">Transferencia</option>
            <option value="otro">Otro</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Desde</label>
          <input type="date" name="desde" class="form-control" />
        </div>
        <div class="col-md-3">
          <label class="form-label">Hasta</label>
          <input type="date" name="hasta" class="form-control" />
        </div>
        <div class="col-md-3 mt-3">
          <label class="form-label">Monto Mínimo</label>
          <input type="number" name="monto" step="0.01" class="form-control" />
        </div>
        <div class="col-md-3 mt-4">
          <button class="btn btn-primary mt-2">Filtrar</button>
        </div>
      </div>
    </form>
  </div>

  <div class="card-style">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Tipo</th>
          <th>Monto</th>
          <th>Forma de Pago</th>
          <th>Categoría</th>
          <th>Nota</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $movimientos->fetch_assoc()): ?>
        <tr>
          <td><?= date("d/m/Y", strtotime($row['fecha'])) ?></td>
          <td><?= ucfirst($row['tipo']) ?></td>
          <td>$<?= number_format($row['monto'], 2) ?></td>
          <td><?= ucfirst($row['forma_pago']) ?></td>
          <td><?= $row['categoria'] ?></td>
          <td><?= $row['nota'] ?? '-' ?></td>
          <td><a href="?eliminar=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('¿Eliminar este movimiento?')">Eliminar</a></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
