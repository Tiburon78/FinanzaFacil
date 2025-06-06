<?php
$conn = new mysqli('localhost', 'root', '', 'finanzafacil');
if ($conn->connect_error) { die('Error de conexión: ' . $conn->connect_error); }
?>