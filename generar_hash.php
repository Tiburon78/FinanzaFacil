<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST["password"];
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "<strong>Contraseña:</strong> " . htmlspecialchars($password) . "<br>";
    echo "<strong>Hash generado:</strong><br><textarea cols='80' rows='3'>" . htmlspecialchars($hash) . "</textarea>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Generador de Hash - FinanzaFácil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>🔐 Generar Hash Seguro</h2>
  <form method="post">
    <div class="mb-3">
      <label>Contraseña:</label>
      <input type="text" name="password" class="form-control" required />
    </div>
    <button type="submit" class="btn btn-primary">Generar Hash</button>
  </form>
</div>
</body>
</html>