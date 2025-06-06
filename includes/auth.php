<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['plan']) && $_SESSION['plan'] === 'prueba') {
    $vencimiento = strtotime($_SESSION['fecha_vencimiento']);
    if ($vencimiento < time()) {
        header("Location: vencido.php");
        exit();
    }
}
?>