<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Asegúrate de ajustar la ruta según la ubicación de tu archivo `autoload.php`

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
    // Si hay un error en la conexión, mostrar el mensaje de error
    echo "Error de conexión (" . mysqli_connect_errno() . "): " . mysqli_connect_error();
} else {
    // Si la conexión es exitosa, mostrar un mensaje de éxito
    echo "Conexión exitosa a la base de datos.";
}

// Cerrar la conexión
mysqli_close($conexion);

?>
