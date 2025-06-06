<?php
include('includes/auth.php');
include('includes/db.php');
$usuario_id = $_SESSION['usuario'];

// Insertar nueva cuenta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['tipo'], $_POST['saldo'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $tipo = $conn->real_escape_string($_POST['tipo']);
    $saldo = floatval($_POST['saldo']);
    $conn->query("INSERT INTO cuentas (usuario_id, nombre, tipo, saldo) VALUES ($usuario_id, '$nombre', '$tipo', $saldo)");
    header("Location: cuentas.php");
    exit;
}

// Eliminar cuenta
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM cuentas WHERE id = $id AND usuario_id = $usuario_id");
    header("Location: cuentas.php");
    exit;
}

// Obtener cuentas
$result = $conn->query("SELECT * FROM cuentas WHERE usuario_id = $usuario_id");

// Calcular total de saldos
$total_saldo = 0;
$cuentas = [];
while ($row = $result->fetch_assoc()) {
    $cuentas[] = $row;
    $total_saldo += floatval($row['saldo']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cuentas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { background: #f2f4f8; font-family: 'Segoe UI', sans-serif; }
    .content { margin-left: 260px; padding: 2rem; }
    .card-style {
      background: white;
      border-radius: 15px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      padding: 20px;
      margin-bottom: 30px;
    }
    .form-label { font-weight: 500; color: #0a6b5a; }
    .btn-sm { font-size: 0.8rem; }
  </style>
</head>
<body>
<?php include 'menu.php'; ?>
<div class="content">
  <div class="card-style mb-4">
    <h4 class="mb-3">Agregar Nueva Cuenta</h4>
    <form method="post">
      <div class="row g-2">
        <div class="col-md-4">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Tipo</label>
          <select name="tipo" class="form-control" required>
            <option value="Efectivo">Efectivo</option>
            <option value="Banco">Banco</option>
            <option value="Digital">Digital (MercadoPago, etc)</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Saldo Inicial</label>
          <input type="number" name="saldo" step="0.01" class="form-control" required>
        </div>
      </div>
      <button class="btn btn-success mt-3">Guardar Cuenta</button>
    </form>
  </div>

  <div class="card-style">
    <h5 class="mb-3">Mis Cuentas</h5>
    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Tipo</th>
          <th>Saldo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cuentas as $cuenta): ?>
        <tr>
          <td><?= htmlspecialchars($cuenta['nombre']) ?></td>
          <td><?= htmlspecialchars($cuenta['tipo']) ?></td>
          <td>$<?= number_format($cuenta['saldo'], 2) ?></td>
          <td>
            <a href="?eliminar=<?= $cuenta['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar esta cuenta?')">Eliminar</a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($cuentas)): ?>
        <tr><td colspan="4" class="text-center">No hay cuentas registradas aún.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <p class="text-end fw-bold">Total Saldo: $<?= number_format($total_saldo, 2) ?></p>
  </div>
</div>
</body>
</html>
