<?php
include('includes/auth.php');
include('includes/db.php');

$usuario_id = $_SESSION['usuario'];

$categorias = $conn->query("SELECT DISTINCT nombre FROM categorias ORDER BY nombre ASC");
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $tipo = $_POST['tipo'];
  $monto = $_POST['monto'];
  $forma_pago = $_POST['forma_pago'];
  $categoria = $_POST['categoria'] ?: $_POST['nueva_categoria'];
  $nota = $_POST['nota'] ?? '';

  if (!empty($categoria)) {
    $conn->query("INSERT INTO categorias (nombre) SELECT '$categoria' WHERE NOT EXISTS (SELECT 1 FROM categorias WHERE nombre = '$categoria')");
    $stmt = $conn->prepare("INSERT INTO movimientos (usuario_id, tipo, monto, forma_pago, categoria, nota, fecha) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("isdsss", $usuario_id, $tipo, $monto, $forma_pago, $categoria, $nota);
    $stmt->execute();
    $mensaje = "✅ Movimiento registrado correctamente.";
  } else {
    $mensaje = "❌ Ingresá una categoría.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Agregar Movimiento</title>
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
    .alert { margin-top: 1rem; }
  </style>
</head>
<body>
<?php include 'menu.php'; ?>
<div class="content">
  <h4 class="mb-4">Registrar Movimiento</h4>

  <?php if (!empty($mensaje)): ?>
    <div class="alert alert-info"><?= $mensaje ?></div>
  <?php endif; ?>

  <div class="card-style">
    <form method="post">
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Tipo de Movimiento</label>
          <select name="tipo" class="form-select" required>
            <option value="ingreso">Ingreso</option>
            <option value="egreso">Egreso</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Monto</label>
          <input type="number" step="0.01" name="monto" class="form-control" required />
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Forma de Pago</label>
          <select name="forma_pago" class="form-select" required>
            <option value="efectivo">Efectivo</option>
            <option value="tarjeta">Tarjeta</option>
            <option value="transferencia">Transferencia</option>
            <option value="otro">Otro</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Seleccionar Categoría</label>
          <select name="categoria" class="form-select">
            <option value="">-- Elige una existente --</option>
            <?php while ($cat = $categorias->fetch_assoc()): ?>
              <option value="<?= $cat['nombre'] ?>"><?= $cat['nombre'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">O crear nueva categoría</label>
        <input type="text" name="nueva_categoria" class="form-control" placeholder="Ej. Clases de Karate" />
      </div>

      <div class="mb-3">
        <label class="form-label">Nota (opcional)</label>
        <input type="text" name="nota" class="form-control" placeholder="Ej. Pago mensual de..." />
      </div>

      <button type="submit" class="btn btn-success">Guardar Movimiento</button>
    </form>
  </div>
</div>
</body>
</html>
