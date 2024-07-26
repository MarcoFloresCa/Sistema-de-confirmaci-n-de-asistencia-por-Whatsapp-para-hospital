<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'somecloud');

try {
    $conexion = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conexion->connect_error) {
        throw new Exception("Conexión fallida: " . $conexion->connect_error);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>