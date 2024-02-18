<?php
session_start();
error_reporting(0);

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header("Location: ../index.php");
    exit();
}

require_once('../admin/conex.php');

$timezone = new DateTimeZone(date_default_timezone_get());
$date = new DateTime('now', $timezone);
$localTime = $date->format('Y-m-d');
$folio = $_POST['folios'];
$estado = $_POST['estado'];
//mes 
//año
//correlativo


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['botonPresionado']) && $_POST['botonPresionado'] === 'btnPendiente') {
        if ($_FILES["pdf"]["error"] === UPLOAD_ERR_OK) {
            $nombre_archivo = $_FILES["pdf"]["name"];
            $tipo_archivo = $_FILES["pdf"]["type"];

            if ($tipo_archivo === "application/pdf") {
                $ruta_almacenamiento = "cotizaciones/COT" . $folio . "-" . $nombre_archivo;

                if (move_uploaded_file($_FILES["pdf"]["tmp_name"], $ruta_almacenamiento)) {

                    $sql = "UPDATE cotiz SET fecha_pendiente = ?, user_pendiente = ?, ruta = ? WHERE folio = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssi", $localTime, $usuario, $ruta_almacenamiento, $folio);

                    if ($stmt->execute()) {
                        echo json_encode(array("success" => true, "message" => "La OC se ha subió exitosamente."));

                        if ($stmt->execute()) {
                            
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
                            $mail->addAddress('carla.villarroel@operamaq.cl');
                            $mail->addAddress('cristhian.baez@operamaq.cl');
                            $mail->addAddress('daniel.ugalde@operamaq.cl');
                        
                            // Contenido
                            $mail->isHTML(true);
                            $mail->Subject = 'Cotización Pendien de Aprobación N° '.$folio.'.';
                            $body = 'Por favor revisa en sistema la orden de compra cliente para su revisión y aprobación. <br>
                            <hr><img src="https://acreditasys.tech/img/FirmaDeCotizacion.png" alt="Logo Operamaq" width="80%">';
                            $mail->Body = $body;
                        
                            // Enviar el correo
                            return $mail->send();

                            echo json_encode(array("success" => true, "message" => "El archivo ha sido revisado."));
                        }

                    } else {
                        echo json_encode(array("success" => false, "message" => "Hubo un problema al subir el archivo."));
                    }
                } else {
                    echo json_encode(array("success" => false, "message" => "Hubo un problema al mover el archivo."));
                }
            } else {
                echo json_encode(array("success" => false, "message" => "El archivo debe ser un PDF."));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Ocurrió un error al subir el archivo."));
        }
    } elseif (isset($_POST['botonPresionado']) && $_POST['botonPresionado'] === 'btnRevisado') {

        if($estado == 'APROBADO'){
            $date_aprobacion = $localTime;

            $sql = "UPDATE cotiz SET estado = ?, fecha_aprobacion = ?, user_aprobacion = ? WHERE folio = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $estado, $localTime, $usuario, $folio);
            
            $buscarTipo = mysqli_query($conn, "SELECT tipo FROM `cotiz` WHERE folio = '$folio'");
            $resultTipo = mysqli_fetch_array($buscarTipo);
            $Tipo = $resultTipo['tipo'];

            // REVISAR //
            $Sql = "INSERT INTO `ot` (`id_cotiz`, `date`, `user`, `tipo`) VALUES (?, ?, ?, ?)";
            $Stmt = $conn->prepare($Sql);
            $Stmt->bind_param("isss", $folio, $localTime, $usuario, $Tipo);
            $Stmt->execute();

            /////////
            
            // Obtener el último ID generado
            $lastInsertedId = $conn->insert_id;

            if ($stmt->execute()) {
                echo json_encode(array("success" => true, "message" => "El archivo ha sido revisado."));

                // enviar correo a usuarios 
                // sebastian.penaloza@operamaq.cl
                // cristhian.baez@operamaq.cl
                // daniel.vera@operamaq.cl
                // oficina.tecnica@operamaq.cl 0FicinaTech2023%
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
                $mail->addAddress('sebastian.penaloza@operamaq.cl');
                $mail->addAddress('cristhian.baez@operamaq.cl');
                $mail->addAddress('daniel.ugalde@operamaq.cl');
                $mail->addAddress('catherine.tejeiro@operamaq.cl');
                $mail->addAddress('yaritza.carrasco@operamaq.cl');
            
                // Contenido
                $mail->isHTML(true);
                $mail->Subject = 'Se ha generado Orden de Trabajo N° ' . $lastInsertedId . ' en sistema.';
                $body = 'Por favor revisa en sistema la nueva Orden de Trabajo. <br>
                <hr><img src="https://acreditasys.tech/img/FirmaDeCotizacion.png" alt="Logo Operamaq" width="80%">';
                $mail->Body = $body;
            
                // Enviar el correo
                return $mail->send();
            } else {
                echo json_encode(array("success" => false, "message" => "Ocurrió un error al actualizar el estado."));
            }

        }else{
  
            $sql = "UPDATE cotiz SET estado = ? WHERE folio = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $estado, $folio);
    
            if ($stmt->execute()) {
                echo json_encode(array("success" => true, "message" => "El archivo ha sido revisado y se encuentra RECHAZADO."));
            } else {
                echo json_encode(array("success" => false, "message" => "Ocurrió un error al actualizar el estado."));
            }
        }

    } else {
        echo json_encode(array("success" => false, "message" => "No se presionó ningún botón."));
    }
} else {
    echo json_encode(array("success" => false, "message" => "No se recibieron datos POST."));
}

$conn->close();
?>