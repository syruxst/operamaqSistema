<?php
session_start();
error_reporting(0);

// Verificar si la variable de sesión para el usuario existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión
    $usuario = $_SESSION['usuario'];
} else {
    // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
    header("Location: ../login.php");
    exit();
}

$fechaHoraActual = date('dmYHis');
$contenidoAEncriptar = $usuario . $fechaHoraActual;

$hash = md5($contenidoAEncriptar);

$qr = $hash;

// Conectarse a la base de datos
require_once('../admin/conex.php');

// Recuperar los datos enviados por JavaScript
$dataInforme = isset($_POST['dataInforme']) ? $_POST['dataInforme'] : null;
$accion = isset($_POST['accion']) ? $_POST['accion'] : null;
$obs = isset($_POST['obs']) ? $_POST['obs'] : null;
$brechas = isset($_POST['brechas']) ? $_POST['brechas'] : null;
$oport = isset($_POST['oport']) ? $_POST['oport'] : null;
$resolucionValue = isset($_POST['resolucion']) ? $_POST['resolucion'] : null;

if ($accion === 'aprobar') {
    $estado = "APROBADO"; // Cambia esto al valor que desees
    $sql = "UPDATE detallle_ot SET estado = '$estado', qr = '$qr', brecha_s = '$brechas', brecha_p = '$obs', oport_m = '$oport', certificate = '$resolucionValue' WHERE id = '$dataInforme'";

    if (mysqli_query($conn, $sql)) {
        $response = array('status' => 'success', 'message' => 'Se ha aprobado la orden con éxito.');
    } else {
        $response = array('status' => 'error', 'message' => 'Error al aprobar la orden: ' . mysqli_error($conn));
    }
} elseif ($accion === 'rechazar') {
    $estado = "RECHAZADO"; // Cambia esto al valor que desees
    $sql = "UPDATE detallle_ot SET estado = '$estado', brecha_s = '$brechas', brecha_p = '$obs', oport_m = '$oport', certificate = '$resolucionValue' WHERE id = '$dataInforme'";

    if (mysqli_query($conn, $sql)) {
        $response = array('status' => 'success', 'message' => 'Se ha rechazado la orden con éxito.');
    } else {
        $response = array('status' => 'error', 'message' => 'Error al rechazar la orden: ' . mysqli_error($conn));
    }
} else {
    $response = array('status' => 'error', 'message' => 'Acción desconocida: ' . $accion);
}

// Enviar la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cierra la conexión a la base de datos
mysqli_close($conn);
?>