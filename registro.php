<?php
include('includes/db.php');
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $fecha_vencimiento = date("Y-m-d", strtotime("+7 days"));

    $sql = "INSERT INTO usuarios (nombre, email, password, fecha_registro, prueba_activa, fecha_vencimiento)
            VALUES ('$nombre', '$email', '$password', NOW(), 1, '$fecha_vencimiento')";

    if ($conn->query($sql) === TRUE) {
        $mensaje = "<div class='alert alert-success'>Registro exitoso. Ya podés iniciar sesión.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al registrar: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro - FinanzaFácil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>Registrarse</h2>
  <?= $mensaje ?>
  <form method="post">
    <div class="mb-3">
      <label>Nombre</label>
      <input type="text" name="nombre" class="form-control" required />
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required />
    </div>
    <div class="mb-3">
      <label>Contraseña</label>
      <input type="password" name="password" class="form-control" required />
    </div>
    <button type="submit" class="btn btn-primary">Crear cuenta</button>
    <a href="login.php" class="btn btn-link">Ya tengo una cuenta</a>
  </form>
</div>
</body>
</html>