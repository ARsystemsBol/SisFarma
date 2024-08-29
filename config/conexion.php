<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Ajusta la ruta según la ubicación de tu archivo `autoload.php`

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$clave = $_ENV['DB_PASSWORD'];
$bd = $_ENV['DB_NAME'];

// Crear conexión
$conexion = mysqli_connect($host, $user, $clave, $bd);

// Verificar la conexión
if (!$conexion) {
    die( "Error de conexión: " . mysqli_connect_error());
} 

?>
