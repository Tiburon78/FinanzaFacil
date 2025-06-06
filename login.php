<?php
session_start();
include('includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Debug output para verificar el hash y la comparación
        echo "<h3>Debug Login:</h3>";
        echo "<p>Email: " . htmlspecialchars($email) . "</p>";
        echo "<p>Contraseña ingresada: " . htmlspecialchars($password) . "</p>";
        echo "<p>Hash guardado en BD: " . htmlspecialchars($row['password']) . "</p>";

        if (password_verify($password, $row["password"])) {
            echo "<p style='color:green;'>✅ password_verify pasó correctamente.</p>";
            $_SESSION["usuario"] = $row["id"];
            $_SESSION["plan"] = $row["prueba_activa"] ? 'prueba' : 'premium';
            $_SESSION["fecha_vencimiento"] = $row["fecha_vencimiento"];
            echo "<p>Redirigiendo al dashboard...</p>";
            header("Refresh:2; url=dashboard.php");
            exit();
        } else {
            echo "<p style='color:red;'>❌ password_verify falló. Contraseña incorrecta.</p>";
        }
    } else {
        echo "<p style='color:red;'>❌ Usuario no encontrado.</p>";
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login - FinanzaFácil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Iniciar Sesión</h2>
    <form method="post">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required />
      </div>
      <div class="mb-3">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-success">Ingresar</button>
    </form>
</div>
</body>
</html>