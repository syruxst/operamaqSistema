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
                <th>T</th>
                <th>D</th>
                <th>P</th>
                <th>BR</th>
                <th><i class='fa fa-upload' aria-hidden='true'></i> BR</th>
                <th>VB</th>
                <th>CERT</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['folio'] . "</td>
                <td>" . $row['rut'] . "</td>
                <td>" . $row['nombre'] . "</td>
                <td>" . $row['equipo'] . "</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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
