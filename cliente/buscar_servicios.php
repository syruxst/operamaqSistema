<?php
session_start();
error_reporting(1);

require_once('../admin/conex.php');

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $usuario = $_SESSION['cliente'];
} else {
    header("Location: ../cliente.php");
    exit();
}

// Obtener el valor de búsqueda
$valorBusqueda = $_POST['buscar'];
$empresa = $_POST['empresa'];
$faena = $_POST['faena'];

// Realizar la consulta SQL
$sql = "SELECT * FROM `detallle_ot` 
        WHERE (rut LIKE '%$valorBusqueda%' 
            OR nombre LIKE '%$valorBusqueda%' 
            OR id_ot LIKE '%$valorBusqueda%'
            OR status LIKE '%$valorBusqueda%' 
            OR equipo LIKE '%$valorBusqueda%' 
            OR modelo LIKE '%$valorBusqueda%' 
            OR faena LIKE '%$valorBusqueda%') 
        AND empresa = '$empresa' 
        AND faena = '$faena' AND patente = ''";

$result = $conn->query($sql);

// Mostrar los resultados
if ($result->num_rows > 0) {
    echo "<table width='100%' class='tabla table table-striped' style='font-size: 12px;' border='1'>
            <tr>
                <th>Folio</th>
                <th>Rut</th>
                <th>Nombre</th>
                <th>Equipo</th>
                <th title='ORDEN DE TRABAJO'>OT</th>
                <th title='PRUEBA TERORICA'>T</th>
                <th title='CHEQUEO DOCUMENTAL'>D</th>
                <th title='PRUEBA PRACTICA'>P</th>
                <th title='INFORME DE BRECHAS'>BR</th>
                <th><i class='fa fa-upload' aria-hidden='true'></i> BR</th>
                <th>VB</th>
                <th>CERT</th>
                <th>STATUS</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $ruta = $row['ruta_firma'];
        $certificate = $row['certificate'];

        $iconT = ($row['resultado'] != '') ? '<i class="fa fa-check fa-lg" aria-hidden="true" title="PRUEBA TEORICA REALIZADA"></i>' : '';
        $iconD = ($row['doc'] == 'SI') ? '<i class="fa fa-check fa-lg" aria-hidden="true" title="DOCUMENTACION REVISADA"></i>' : ($row['doc'] == 'NO' ? '<i class="fa fa-times fa-lg" aria-hidden="true"></i>' : '');
        $iconP = ($row['informe'] != '') ? '<i class="fa fa-check fa-lg" aria-hidden="true" title="PRUEBA PRACTICA REALIZADA"></i>' : '';

        $informe = "SELECT * FROM `detallle_ot` WHERE id = '$id' AND brecha_s !='' AND brecha_p !='' AND oport_m !=''";
        $rst = $conn->query($informe);

        if ($rst->num_rows > 0) {
            $info = '<i class="fa fa-file-text-o fa-lg abrir-popup" data-informe="'.$id.'" aria-hidden="true" title="INFORME DE BRECHAS"></i>';
            $submit = '<i class="fa fa-upload fa-lg" aria-hidden="true" title="SUBIR EVIDENCIA DE BRECHAS"></i>';

            $submit = '<label><i class="fa fa-upload fa-lg upload-icon" data-submit aria-hidden="true" title="SUBIR EVIDENCIA DE BRECHAS"></i></label>';
            echo '<div class="modal" id="modal" style="display: none;">
                    <div class="modal-content">
                        <center><span style="font-size: 20px;">SUBIR INFORME DE EVIDENCIA BRECHAS</span></center>
                        <hr>
                        <input type="hidden" name="datos" id="datos" value="'.$id.'">
                        <input type="file" name="file" id="file" accept=".pdf, .doc, .docx" >
                        <hr>
                        <button type="button" class="btn btn-success" id="subirInformeBtn"><i class="fa fa-upload fa-lg" aria-hidden="true" title="SUBIR EVIDENCIA DE BRECHAS"></i> SUBIR INFORME</button>
                    </div>
            </div>';

        }else {
            $info = '';
            $submit = '';
        }

        $imgFirma = ($ruta == '') ? '' : '<a href="'.$ruta.'" target="_blank"><i class="fa fa-file-pdf-o ruta fa-lg" aria-hidden="true" style="color: red;"></i></a>';
        $estado = ($certificate == 'APROBADO') ? '<i class="fa fa-check fa-lg" aria-hidden="true" title="CERTIFICADO APROBADO"></i>' : ($certificate == 'RECHAZADO' ? '<i class="fa fa-times fa-lg" aria-hidden="true" title="CERTIFICADO RECHAZADO"></i>' : '');
        echo "<tr>
                <td>" . $row['folio'] . "</td>
                <td>" . $row['rut'] . "</td>
                <td>" . $row['nombre'] . "</td>
                <td>" . $row['equipo'] . "</td>
                <td style='color: #2ECC71; cursor: pointer;' title='ORDEN DE TRABAJO N° ". $id ." '><b>" . $id . "</b></td>
                <td>" . $iconT . "</td>
                <td>" . $iconD . "</td>
                <td>" . $iconP . "</td>
                <td>" . $info . "</td>
                <td>". $submit ."</td>
                <td></td>
                <td>" . $imgFirma . "</td>
                <td>" . $estado . "</td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

// Cerrar la conexión
$conn->close();
?>