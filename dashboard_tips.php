
<?php
// Sección de Tips y Sugerencias
$tips = [];

// Tips fijos
$tips[] = "💡 Registrá tus ingresos apenas los recibas.";
$tips[] = "📌 Creá presupuestos para mantener tus finanzas bajo control.";
$tips[] = "💳 Revisá los gastos con tarjeta al final del mes.";

// Tipos dinámicos
if ($egresos > $ingresos) {
    $tips[] = "⚠️ Estás gastando más de lo que ingresás este mes. Considerá ajustar tus egresos.";
}

$presupuestos = $conn->query("SELECT COUNT(*) AS total FROM presupuestos WHERE usuario_id = $usuario_id");
$row_presup = $presupuestos->fetch_assoc();
if ($row_presup['total'] == 0) {
    $tips[] = "📝 Aún no creaste presupuestos este mes. ¡Creá uno para planificar mejor!";
}

// Verificamos si hay ingresos este mes
$mes_actual = date('Y-m');
$res_ing = $conn->query("SELECT COUNT(*) AS total FROM movimientos WHERE usuario_id = $usuario_id AND tipo = 'ingreso' AND DATE_FORMAT(fecha, '%Y-%m') = '$mes_actual'");
$row_ing = $res_ing->fetch_assoc();
if ($row_ing['total'] == 0) {
    $tips[] = "📭 No registraste ingresos este mes. ¿Olvidaste alguno?";
}
?>
<div class="grafico-box mt-3">
  <h5>🧠 Consejos Financieros</h5>
  <ul>
    <?php foreach ($tips as $tip): ?>
      <li><?= $tip ?></li>
    <?php endforeach; ?>
  </ul>
</div>
