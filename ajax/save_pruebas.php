<?php
session_start();
require_once('../admin/conex.php');

// Verificar si la variable de sesión para el usuario existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión
    $usuario = $_SESSION['usuario'];
} else {
    // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
    header("Location: ../login.php");
    exit();
}

$pregunta = $_POST['pregunta'];
$respuesta1 = $_POST['respuesta1'];
$respuesta2 = $_POST['respuesta2'];
$respuesta3 = $_POST['respuesta3'];
$respuesta4 = $_POST['respuesta4'];
$respuestaCorrecta = $_POST['respuestaCorrecta'];
$tabla = $_POST['tabla'];

$timezone = new DateTimeZone('America/Santiago');
$now = new DateTime("now", $timezone); 
$fecha = $now->format("Y-m-d H:i:s");
$versiones = 1;

// Consulta SQL para verificar si la tabla existe
$checkTableSql = "SHOW TABLES LIKE '$tabla'";
$result = $conn->query($checkTableSql);

if ($result->num_rows == 0) {
    // La tabla no existe, entonces la creamos
    $createTableSql = "CREATE TABLE `u992209295_operamaq`.`$tabla` (
        `id` INT NOT NULL AUTO_INCREMENT ,
        `PREGUNTA` VARCHAR(500) NOT NULL ,
        `R1` VARCHAR(500) NOT NULL ,
        `R2` VARCHAR(500) NOT NULL ,
        `R3` VARCHAR(500) NOT NULL ,
        `R4` VARCHAR(500) NOT NULL ,
        `id_respuesta_correcta` INT NOT NULL ,
        `fecha` DATETIME NOT NULL ,
        `versiones` VARCHAR(10) NOT NULL ,
        PRIMARY KEY (`id`)
    ) ENGINE = InnoDB;";
    
    if ($conn->query($createTableSql) === TRUE) {
        // La tabla se creó correctamente, ahora insertamos los datos
        insertData($conn, $tabla);
    } else {
        echo "Error al crear la tabla: " . $conn->error;
    }
} else {
    // La tabla ya existe, simplemente insertamos los datos
    insertData($conn, $tabla);
}

// Función para insertar datos en la tabla
function insertData($conn, $tabla) {
    global $pregunta, $respuesta1, $respuesta2, $respuesta3, $respuesta4, $respuestaCorrecta, $fecha, $versiones;
    
    $insert = "INSERT INTO $tabla (PREGUNTA, R1, R2, R3, R4 , id_respuesta_correcta, fecha, versiones) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert);
    $stmt->bind_param("sssssiss", $pregunta, $respuesta1, $respuesta2, $respuesta3, $respuesta4, $respuestaCorrecta, $fecha, $versiones);
    
    if ($stmt->execute()) {
        echo "Datos guardados correctamente";
    } else {
        echo "Error al guardar los datos: " . $conn->error;
    }
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>