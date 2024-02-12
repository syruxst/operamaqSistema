<?php

$servername = "localhost";
$username = "u992209295_acreditasys";
$password = "8Oe|oUPedx!";
$dbname = "u992209295_acreditasys";

// Establece la conexión con la base de datos
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verifica si la conexión fue exitosa
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Establece el conjunto de caracteres a utf8
mysqli_set_charset($conn, "utf8");

?>