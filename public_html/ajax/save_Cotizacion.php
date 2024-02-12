<?php
require_once('../admin/conex.php'); 
session_start();

// Verificar si la variable de sesión para el usuario existe
if (!isset($_SESSION['usuario'])) {
    // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
    header("Location: formulario_inicio_sesion.php");
    exit();
}

$usuario = $_SESSION['usuario'];

$fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING);
$numberCot = filter_input(INPUT_POST, 'numberCot', FILTER_SANITIZE_STRING);
$empresa = filter_input(INPUT_POST, 'empresa', FILTER_SANITIZE_STRING);
$contacto = filter_input(INPUT_POST, 'contacto', FILTER_SANITIZE_STRING);
$mail = filter_input(INPUT_POST, 'mail', FILTER_VALIDATE_EMAIL);
$faena = filter_input(INPUT_POST, 'faena', FILTER_SANITIZE_STRING);
$telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
$estado = "PENDIENTE";
$user = $usuario;
$formaPago = filter_input(INPUT_POST, 'formaPago', FILTER_SANITIZE_STRING);
$tipo = filter_input(INPUT_POST, 'tipes', FILTER_SANITIZE_STRING);

// Insertar datos en la tabla serviceCot
$cantidades = $_POST["cantidad"];
$servicios = $_POST["service"];
$precios = $_POST["precio"];
$porcentajes = $_POST["porcentaje"];
$totales = $_POST["total"];
$detalles = $_POST["detalle"];
$valor = $_POST["valorNumerico"];

// Insertar datos en la tabla cotiz
$stmt = $conn->prepare("INSERT INTO cotiz (folio, name_cliente, faena, contacto, telefono, correo, estado, fecha_creacion, user_creacion, pago, tipo, valor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssssssss", $numberCot, $empresa, $faena, $contacto, $telefono, $mail, $estado, $fecha, $user, $formaPago, $tipo, $valor);

if($stmt->execute()) {
    for ($i = 0; $i < count($cantidades); $i++) {
        $Stmt = $conn->prepare("INSERT INTO serviceCot (id_cotiz, cantidad, servicio, unitario, descuento, total, detalle, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $Stmt->bind_param("isssssss", $numberCot, $cantidades[$i], $servicios[$i], $precios[$i], $porcentajes[$i], $totales[$i], $detalles[$i], $tipo);
    
        $response = array();

        if($Stmt->execute()) {

            enviarCotizacion($numberCot, $detalles);
            $response = [
                    'success' => true,
                    'message' => 'La Cotizacion ha sido creada correctamente!'
                ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Error al crear la Cotizacion: ' . $Stmt->error
            ];
        }
    }
} else {
    $response = [
        'success' => false,
        'message' => 'Error al crear la Cotizacion: ' . $Stmt->error
    ];
}

function enviarCotizacion($numberCot, $detalles) {
    require_once('PHPMailer/PHPMailer.php');
    require_once('PHPMailer/SMTP.php');
    require_once('PHPMailer/Exception.php');

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'venta@operamaq.cl';
    $mail->Password = 'Operamaq2023#';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    //Destinatarios
    $mail->setFrom('venta@operamaq.cl', 'Operamaq Empresa Spa');
    $mail->addAddress('daniel.ugalde@operamaq.cl');

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Se ha creado una nueva cotización N° ' . $numberCot;
    $body = 'Por favor revisa la Cotización para su validación. <br>
    <img src="https://acreditasys.tech/img/FirmaDeCotizacion.png" alt="Logo Operamaq" width="50%">';
    foreach ($detalles as $detalle) {
        $body .= $detalle . "<br>";
    }

    $mail->Body = $body;

    // Enviar el correo
    return $mail->send();
}
$Stmt->close();
$stmt->close();
$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
?>