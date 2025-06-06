<?php
include('includes/auth.php');
include('includes/db.php');
$usuario_id = $_SESSION['usuario'];

$mensaje = "";
$res = $conn->query("SELECT * FROM usuarios WHERE id = $usuario_id");
$usuario = $res->fetch_assoc();

$paises = $conn->query("SELECT * FROM paises ORDER BY nombre");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["guardar"])) {
  $nombre = $conn->real_escape_string($_POST["nombre"]);
  $email = $conn->real_escape_string($_POST["email"]);
  $pais = $conn->real_escape_string($_POST["pais"]);
  $idioma = $conn->real_escape_string($_POST["idioma"]);

  // Obtener moneda correspondiente al país
  $query_moneda = $conn->query("SELECT codigo_moneda FROM paises WHERE nombre = '$pais' LIMIT 1");
  
  $moneda_row = $query_moneda->fetch_assoc();
  $moneda = $moneda_row['codigo_moneda'];
  $simbolo_moneda = $conn->query("SELECT simbolo_moneda FROM paises WHERE nombre = '$pais' LIMIT 1")->fetch_assoc()['simbolo_moneda'];
    

  if (!empty($_POST["password"])) {
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $conn->query("UPDATE usuarios SET nombre='$nombre', email='$email', password='$password', pais='$pais', moneda='$moneda', simbolo_moneda='$simbolo_moneda', idioma='$idioma' WHERE id=$usuario_id");
  } else {
    $conn->query("UPDATE usuarios SET nombre='$nombre', email='$email', pais='$pais', moneda='$moneda', simbolo_moneda='$simbolo_moneda', idioma='$idioma' WHERE id=$usuario_id");
  }

  $mensaje = "✅ Cambios guardados correctamente.";
  $res = $conn->query("SELECT * FROM usuarios WHERE id = $usuario_id");
  $usuario = $res->fetch_assoc();
  $paises = $conn->query("SELECT * FROM paises ORDER BY nombre");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Perfil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f1f4f8; font-family: 'Segoe UI', sans-serif; }
    .content { margin-left: 260px; padding: 2rem; }
    .card-style {
      background: white;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
      max-width: 900px;
      margin: auto;
    }
    .form-label { font-weight: 500; }
  </style>
</head>
<body>
<?php include 'menu.php'; ?>
<div class="content">
  <div class="card-style">
    <h4>👤 Mi Perfil</h4>
    <?php if ($mensaje): ?>
      <div class="alert alert-success"><?= $mensaje ?></div>
    <?php endif; ?>
    <form method="post">
      <input type="hidden" name="guardar" value="1">
      <div class="row mb-3">
        <div class="col">
          <label class="form-label">Nombre completo</label>
          <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        </div>
        <div class="col">
          <label class="form-label">Correo electrónico</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Nueva contraseña (solo si querés cambiarla)</label>
        <input type="password" name="password" class="form-control" placeholder="********">
      </div>
      <div class="row mb-3">
        <div class="col">
          <label class="form-label">País</label>
          <select name="pais" class="form-select">
            <?php while ($row = $paises->fetch_assoc()): ?>
              <option value="<?= $row['nombre'] ?>" <?= ($usuario['pais'] === $row['nombre']) ? 'selected' : '' ?>>
                <?= $row['nombre'] ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col">
          <label class="form-label">Idioma</label>
          <select name="idioma" class="form-control">
            <option value="es" <?= ($usuario['idioma'] ?? '') === 'es' ? 'selected' : '' ?>>Español</option>
            <option value="en" <?= ($usuario['idioma'] ?? '') === 'en' ? 'selected' : '' ?>>Inglés</option>
            <option value="pt" <?= ($usuario['idioma'] ?? '') === 'pt' ? 'selected' : '' ?>>Portugués</option>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Moneda actual:</label>
        <input type="text" class="form-control" value="<?= $usuario['moneda'] ?>" disabled>
      </div>

      <div class="mb-3">
        <label class="form-label">Plan actual:</label>
        <div class="form-control-plaintext">
          <?= $usuario['plan'] ?? 'Gratuito' ?> - 
          <?php
            if (!empty($usuario['fecha_vencimiento'])) {
              $dias = (strtotime($usuario['fecha_vencimiento']) - time()) / 86400;
              echo "te quedan " . max(0, floor($dias)) . " días";
            } else {
              echo "sin vencimiento definido";
            }
          ?>
          | <a href="planes.php">Cambiar de plan</a>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Métodos de pago</label>
        <input type="text" class="form-control" disabled placeholder="Próximamente disponible">
      </div>

      <button class="btn btn-success w-100">Guardar Cambios</button>
    </form>
  </div>
</div>
</body>
</html>
