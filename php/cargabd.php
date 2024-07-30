<?php
error_reporting(0);

// Incluir el archivo de conexión a la base de datos
include '../bd/conexion.php';

// Verificar si se han enviado datos por POST
if (isset($_POST["data"])) {
    // Decodificar los datos JSON recibidos
    $data = json_decode($_POST["data"], true);

    // Verificar si los datos decodificados no están vacíos
    if (!empty($data)) {
        // ID de usuario 
        $ID_Usuario = 1234;
        
        // Obtener la fecha y hora actual en formato MySQL
        $fechaHoraActual = date('Y-m-d H:i:s');

        // Consulta para obtener el último ID de envio_mensaje
        $sqlID = "SELECT MAX(ID) AS MaxID FROM envio_mensaje";
        $resultID = $conexion->query($sqlID);

        // Verificar si la consulta se ejecutó correctamente
        if ($resultID) {
            $rowID = $resultID->fetch_assoc();
            $ID = $rowID["MaxID"] + 1; // Incrementar el valor para el próximo ID
        } else {
            // En caso de error o si no hay registros, establecer un valor inicial
            $ID = 1;
        }

        // Insertar datos en la tabla envio_mensaje
        $sqlEnvio = "INSERT INTO envio_mensaje (ID, ID_Usuario, Hora_carga, Fecha_envio) VALUES ('$ID', '$ID_Usuario', NOW(), '$fechaHoraActual')";
        if (!$conexion->query($sqlEnvio)) {
            echo "Error al insertar datos en la tabla 'envio_mensaje': " . $conexion->error;
            exit();
        }

        // Filtrar y procesar los datos recibidos
        foreach ($data as $columna) {
            $numero = $columna[1];
            $nombre = $columna[2];
            $rut = $columna[3];
            $dia = $columna[4];
            $hora = $columna[5];
            $medico = $columna[6];
            $especialidad = $columna[7];
            $tipoconsulta = $columna[8];
            $email =  $columna[11];

            // Verificar si el paciente ya existe en la tabla paciente
            $sqlVerificar = "SELECT COUNT(*) AS count FROM paciente WHERE rut = '$rut'";
            $resultado = $conexion->query($sqlVerificar);
            $row = $resultado->fetch_assoc();
            $existePaciente = $row['count'] > 0;

            if ($existePaciente) {
                // Actualizar datos del paciente si es necesario
                $sqlActualizar = "UPDATE paciente SET nombre = '$nombre', Telefono = '$numero', Corre_electronico = '$email' WHERE rut = '$rut'";
                if (!$conexion->query($sqlActualizar)) {
                    echo "Error al actualizar los datos del paciente: " . $conexion->error;
                    exit();
                }
            } else {
                // Insertar nuevo paciente
                $sqlInsertarPaciente = "INSERT INTO paciente (rut, nombre, Telefono, Corre_electronico) VALUES ('$rut', '$nombre', '$numero', '$email')";
                if (!$conexion->query($sqlInsertarPaciente)) {
                    echo "Error al insertar datos en la tabla 'paciente': " . $conexion->error;
                    exit();
                }
            }

            // Verificar si el paciente ya tiene una hora programada en el mismo día y hora con el mismo médico y tipo de consulta
            $sqlVerificarHora = "SELECT COUNT(*) AS count FROM hora WHERE Rut_Paciente = '$rut' AND Dia = '$dia' AND Hora_Agandada = '$hora' AND Profesional = '$medico' AND Tipo_Atencion = '$tipoconsulta'";
            $resultadoHora = $conexion->query($sqlVerificarHora);
            $rowHora = $resultadoHora->fetch_assoc();
            $existeHora = $rowHora['count'] > 0;

            if ($existeHora) {
                // Actualizar la hora existente si es necesario
                $sqlActualizarHora = "UPDATE hora SET Especialidad = '$especialidad', Asistencia = 'Por Confirmar' WHERE Rut_Paciente = '$rut' AND Dia = '$dia' AND Hora_Agandada = '$hora' AND Profesional = '$medico' AND Tipo_Atencion = '$tipoconsulta'";
                if (!$conexion->query($sqlActualizarHora)) {
                    echo "Error al actualizar la hora existente: " . $conexion->error;
                    exit();
                }
            } else {
                // Insertar nueva hora si no existe duplicado
                $sqlInsertarHora = "INSERT INTO hora (Rut_Paciente, Profesional, Tipo_Atencion, Especialidad, Dia, Hora_Agandada, Asistencia, Fecha_envio, ID_envio) VALUES ('$rut', '$medico', '$tipoconsulta', '$especialidad', '$dia', '$hora', 'Por Confirmar', NOW(), $ID)";
                if (!$conexion->query($sqlInsertarHora)) {
                    echo "Error al insertar datos en la tabla 'hora': " . $conexion->error;
                    exit();
                }
            }

            // Verificar si el paciente estaba en datos_incompletos y eliminarlo si es necesario
            $sqlEliminarIncompletos = "DELETE FROM datos_incompletos WHERE rut = '$rut'";
            if (!$conexion->query($sqlEliminarIncompletos)) {
                echo "Error al eliminar datos incompletos: " . $conexion->error;
                exit();
            }
        }

        // Mostrar resultados si hay pacientes actualizados
        $pacientesActualizadosStr = "";
        if (!empty($pacientesActualizacion)) {
            $pacientesActualizadosStr .= "Pacientes con datos actualizados :\n";
            foreach ($pacientesActualizacion as $paciente) {
                $pacientesActualizadosStr .= "RUT: " . $paciente["rut"] . "\n";
                $pacientesActualizadosStr .= "Campo anterior: " . $paciente["viejo"] . "\n";
                $pacientesActualizadosStr .= "Campo nuevo: " . $paciente["nuevo"] . "\n";
                $pacientesActualizadosStr .= "---------------\n";
            }
        }

        echo $pacientesActualizadosStr;

        // Cerrar conexión a la base de datos
        $conexion->close();
    }
}
?>
