
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FinanzaFácil - Tu Economía en Orden</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f5f7fa; }
    .hero { background: linear-gradient(135deg, #0052D4, #4364F7); color: white; padding: 5rem 2rem; text-align: center; }
    .hero h1 { font-size: 3rem; }
    .plan { border-radius: 1rem; border: 1px solid #ddd; transition: 0.3s; }
    .plan:hover { box-shadow: 0 0 15px rgba(0,0,0,0.1); }
    .screenshots img { border-radius: 1rem; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
  </style>
</head>
<body>

<section class="hero">
  <h1>FinanzaFácil</h1>
  <p class="lead">Tu sistema inteligente para manejar tus gastos, ingresos y ahorrar con propósito.</p>
  <a href="login.php" class="btn btn-light btn-lg">Ingresar / Registrarse</a>
</section>

<section class="container text-center my-5">
  <h2 class="mb-4">¡Transformá tu economía desde hoy!</h2>
  <div class="row g-4">
    <div class="col-md-4">
      <h4>✨ Visual Claro</h4>
      <p>Dashboard atractivo, gráficos, alertas y resúmenes en segundos.</p>
    </div>
    <div class="col-md-4">
      <h4>💵 Ahorros y Presupuestos</h4>
      <p>Creá objetivos y controlá tus gastos. Alcanzá tus metas más rápido.</p>
    </div>
    <div class="col-md-4">
      <h4>🤖 Asistente Financiero</h4>
      <p>Sugerencias automáticas y análisis de hábitos (solo Premium).</p>
    </div>
  </div>
</section>

<section class="container my-5">
  <h2 class="text-center mb-4">🌍 Vistas del Sistema</h2>
  <div class="row screenshots text-center">
    <div class="col-md-6 mb-4">
      <img src="screens/dashboard.jpg" class="img-fluid" alt="Dashboard">
      <p>Dashboard inteligente</p>
    </div>
    <div class="col-md-6 mb-4">
      <img src="screens/reportes.jpg" class="img-fluid" alt="Reportes">
      <p>Reportes visuales</p>
    </div>
  </div>
</section>

<section class="container my-5">
  <h2 class="text-center mb-4">📅 Planes</h2>
  <div class="row g-4">
    <div class="col-md-4">
      <div class="plan p-4 text-center bg-white">
        <h4>Gratis</h4>
        <p class="text-muted">7 días de prueba</p>
        <ul class="list-unstyled">
          <li>✔ Dashboard completo</li>
          <li>✔ Registro de movimientos</li>
          <li>❌ Reportes PDF/Excel</li>
        </ul>
        <span class="text-primary fw-bold">$0</span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="plan p-4 text-center bg-white">
        <h4>Standard</h4>
        <p class="text-muted">Ideal para el día a día</p>
        <ul class="list-unstyled">
          <li>✔ Todo lo de Gratis</li>
          <li>✔ Exportar reportes</li>
          <li>✔ Presupuestos por grupo</li>
        </ul>
        <span class="text-success fw-bold">USD 10 / mes</span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="plan p-4 text-center bg-white border-primary border-2">
        <h4>Premium</h4>
        <p class="text-muted">Para quienes quieren más</p>
        <ul class="list-unstyled">
          <li>✔ Todo lo anterior</li>
          <li>✔ Asistente financiero</li>
          <li>✔ Proyecciones de ahorro</li>
          <li>✔ Idioma y moneda personalizados</li>
        </ul>
        <span class="text-danger fw-bold">USD 99 / año</span>
      </div>
    </div>
  </div>
</section>

<footer class="bg-dark text-white text-center p-3">
  <p>FinanzaFácil &copy; 2025 - Todos los derechos reservados</p>
  <small>Disponible en Español | English | Português (versión multilenguaje en desarrollo)</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
