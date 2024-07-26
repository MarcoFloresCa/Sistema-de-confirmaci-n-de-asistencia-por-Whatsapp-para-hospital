<?php
include '../bd/conexion.php';

// Verificar si se ha enviado el rut por POST
if (isset($_POST["rut"])) {
    $rut = $_POST["rut"];

    // Consulta para verificar si el paciente existe en la tabla hora
    $sqlHora = "SELECT COUNT(*) AS count FROM hora WHERE Rut_Paciente = '$rut'";
    $resultHora = $conexion->query($sqlHora);
    $rowHora = $resultHora->fetch_assoc();
    $existeEnHora = $rowHora['count'] > 0;

    if ($existeEnHora) {
        // Eliminar al paciente de la tabla datos_incompletos
        $sqlEliminar = "DELETE FROM datos_incompletos WHERE rut = '$rut'";
        if ($conexion->query($sqlEliminar) === TRUE) {
            echo "Paciente eliminado de la tabla 'datos_incompletos' correctamente";
        } else {
            echo "Error al eliminar al paciente de la tabla 'datos_incompletos': " . $conexion->error;
        }
    } else {
        echo "El paciente no existe en la tabla 'hora'";
    }
} else {
    echo "Error: Se requiere el rut del paciente";
}

$conexion->close();
?>
