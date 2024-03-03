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

        $iconT = ($row['resultado'] != '') ? '<i class="fa fa-check fa-lg" aria-hidden="true" title="PRUEBA TEORICA REALIZADA"></i>' : '';
        $iconD = ($row['doc'] == 'SI') ? '<i class="fa fa-check fa-lg" aria-hidden="true" title="DOCUMENTACION REVISADA"></i>' : ($row['doc'] == 'NO' ? '<i class="fa fa-times fa-lg" aria-hidden="true"></i>' : '');
        $iconP = ($row['informe'] != '') ? '<i class="fa fa-check fa-lg" aria-hidden="true" title="PRUEBA PRACTICA REALIZADA"></i>' : '';

        $informe = "SELECT * FROM `detallle_ot` WHERE id = '$id' AND brecha_s !='' AND brecha_p !='' AND oport_m !=''";
        $rst = $conn->query($informe);

        if ($rst->num_rows > 0) {
            $info = '<i class="fa fa-file-text-o fa-lg abrir-popup" data-informe="'.$id.'" aria-hidden="true" title="INFORME DE BRECHAS"></i>';
            $submit = '<i class="fa fa-upload fa-lg" aria-hidden="true" title="SUBIR EVIDENCIA DE BRECHAS"></i>';

            $submit = '<label for="fileInput_'. $id .'" onclick="handleFileClick('. $id .')">
                            <i id="uploadIcon_'. $id .'" class="fa fa-upload fa-lg upload-icon" aria-hidden="true" title="SUBIR EVIDENCIA DE BRECHAS"></i>
                        </label>';
                  echo '<input type="file" id="fileInput_' . $id . '" name="fileInput_' . $id . '" class="file-input"">';

        }else {
            $info = '';
            $submit = '';
        }

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
                <td></td>
                <td></td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

// Cerrar la conexión
$conn->close();
?>