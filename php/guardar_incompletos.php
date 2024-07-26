<?php
include '../bd/conexion.php';

if (isset($_POST["rut"]) && isset($_POST["nombre"]) && isset($_POST["tipo_error"]) && isset($_POST["fecha_registro"])) {
    $rut = $_POST["rut"];
    $nombre = $_POST["nombre"];
    $tipoError = $_POST["tipo_error"];
    $fechaRegistro = $_POST["fecha_registro"];

    // Verificar si el paciente ya existe en la tabla datos_incompletos
    $sqlVerificar = "SELECT COUNT(*) AS count FROM datos_incompletos WHERE rut = '$rut'";
    $resultado = $conexion->query($sqlVerificar);
    $row = $resultado->fetch_assoc();
    $existeEnIncompletos = $row['count'] > 0;

    if (!$existeEnIncompletos) {
        // Si no existe, insertar en la tabla datos_incompletos
        $sqlInsert = "INSERT INTO datos_incompletos (rut, nombre, tipo_error, fecha_registro) VALUES ('$rut', '$nombre', '$tipoError', '$fechaRegistro')";
        if ($conexion->query($sqlInsert) === TRUE) {
            echo "Datos incompletos insertados correctamente";
        } else {
            echo "Error al insertar datos incompletos: " . $conexion->error;
        }
    } else {
        echo "El paciente ya está registrado como incompleto";
    }
} else {
    echo "Error: Se requieren todos los parámetros";
}

$conexion->close();
?>
