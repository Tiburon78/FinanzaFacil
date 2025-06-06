<?php
include('includes/auth.php');
include('includes/db.php');

$usuario_id = $_SESSION['usuario'];
$mensaje = "";

// Eliminar movimiento
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM movimientos WHERE id = $id AND usuario_id = $usuario_id");
    $mensaje = "<div class='alert alert-warning'>Movimiento eliminado.</div>";
}

// Filtros
$tipo = $_GET['tipo'] ?? '';
$forma_pago = $_GET['forma_pago'] ?? '';
$monto_min = $_GET['monto_min'] ?? '';
$monto_max = $_GET['monto_max'] ?? '';
$fecha_desde = $_GET['fecha_desde'] ?? '';
$fecha_hasta = $_GET['fecha_hasta'] ?? '';

$where = "WHERE usuario_id = $usuario_id";
if ($tipo) $where .= " AND tipo = '$tipo'";
if ($forma_pago) $where .= " AND forma_pago = '$forma_pago'";
if ($monto_min != '') $where .= " AND monto >= $monto_min";
if ($monto_max != '') $where .= " AND monto <= $monto_max";
if ($fecha_desde) $where .= " AND fecha >= '$fecha_desde'";
if ($fecha_hasta) $where .= " AND fecha <= '$fecha_hasta'";

$result = $conn->query("SELECT * FROM movimientos $where ORDER BY fecha DESC");
$movimientos = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Movimientos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .ingreso { background-color: #e1fce1; }
    .egreso { background-color: #fce1e1; }
    .tarjeta { font-weight: bold; color: #d9534f; }
  </style>
</head>
<body>
<?php include 'menu.php'; ?>
<div class="container mt-4">
  <h2>Mis Movimientos</h2>
  <?= $mensaje ?>
  <form method="get" class="row g-3 mb-4">
    <div class="col-md-2">
      <label>Desde</label>
      <input type="date" name="fecha_desde" value="<?= $fecha_desde ?>" class="form-control">
    </div>
    <div class="col-md-2">
      <label>Hasta</label>
      <input type="date" name="fecha_hasta" value="<?= $fecha_hasta ?>" class="form-control">
    </div>
    <div class="col-md-2">
      <label>Tipo</label>
      <select name="tipo" class="form-control">
        <option value="">Todos</option>
        <option value="ingreso" <?= $tipo == 'ingreso' ? 'selected' : '' ?>>Ingreso</option>
        <option value="egreso" <?= $tipo == 'egreso' ? 'selected' : '' ?>>Egreso</option>
      </select>
    </div>
    <div class="col-md-2">
      <label>Forma de pago</label>
      <select name="forma_pago" class="form-control">
        <option value="">Todas</option>
        <option value="efectivo" <?= $forma_pago == 'efectivo' ? 'selected' : '' ?>>Efectivo</option>
        <option value="tarjeta" <?= $forma_pago == 'tarjeta' ? 'selected' : '' ?>>Tarjeta</option>
        <option value="transferencia" <?= $forma_pago == 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
      </select>
    </div>
    <div class="col-md-2">
      <label>Monto mínimo</label>
      <input type="number" name="monto_min" value="<?= $monto_min ?>" class="form-control">
    </div>
    <div class="col-md-2">
      <label>Monto máximo</label>
      <input type="number" name="monto_max" value="<?= $monto_max ?>" class="form-control">
    </div>
    <div class="col-md-12 text-end">
      <button type="submit" class="btn btn-primary">Filtrar</button>
      <a href="listar.php" class="btn btn-secondary">Limpiar</a>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>Fecha</th>
          <th>Tipo</th>
          <th>Categoría</th>
          <th>Monto</th>
          <th>Forma de Pago</th>
          <th>Descripción</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($movimientos as $mov): ?>
          <tr class="<?= $mov['tipo'] ?>">
            <td><?= $mov['fecha'] ?></td>
            <td><?= ucfirst($mov['tipo']) ?></td>
            <td><?= $mov['categoria'] ?></td>
            <td>$<?= number_format($mov['monto'], 2) ?></td>
            <td class="<?= $mov['forma_pago'] == 'tarjeta' ? 'tarjeta' : '' ?>"><?= ucfirst($mov['forma_pago']) ?></td>
            <td><?= $mov['descripcion'] ?></td>
            <td>
              <a href="?eliminar=<?= $mov['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que querés eliminar este movimiento?')">🗑 Eliminar</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($movimientos)): ?>
          <tr><td colspan="7" class="text-center">No se encontraron movimientos.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>