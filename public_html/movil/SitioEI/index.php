<?php
session_start();
error_reporting(0);
require_once('../../admin/conex.php');

// Verificar si alguna de las dos variables de sesión existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión que exista
    if (isset($_SESSION['usuario'])) {
       $usuario = $_SESSION['usuario'];
       $query = "SELECT * FROM insp_eva WHERE user = '$usuario'";
         $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            $nombre = $row['name'];
            $ev = $row['ev'];
            $ip = $row['ip'];
    } 
} else {
    header("Location: ../index.php");
    exit();
}
//$Pendiente = mysqli_query($conn, "SELECT * FROM detallle_ot WHERE (ip = '$ev' OR ip = '$ip') AND resultado = 'APROBADO' AND estado = ''");
//$numResultados = mysqli_num_rows($Pendiente);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <!--icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="../../img/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../../img/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../../img/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../../img/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../../img/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../../img/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../../img/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../../img/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../../img/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="../../img/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../../img/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../img/icons/favicon-16x16.png">
    <link rel="manifest" href="manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../../img/icons/ms-icon-144x144.png">

    <title>:: Mi Sitio Evaluadores ::</title>

    <style>
        body {
        margin: 0;
        font-family: 'Arial', sans-serif;
        }

        header {
        background-color: #333;
        padding: 15px;
        text-align: center;
        }

        .menu-toggle {
        cursor: pointer;
        color: #fff;
        font-size: 18px;
        }

        .navbar {
        display: none;
        }

        .menu {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        }

        .menu li {
        margin-bottom: 10px;
        }

        .menu a {
        text-decoration: none;
        color: white;
        font-size: 16px;
        transition: color 0.3s ease-in-out;
        text-align: left; /* Alinea el texto a la izquierda */
        }

        .menu a:hover {
        color: #ff4500; /* Cambia el color al pasar el ratón */
        }

        .logo{
            position: absolute;
        }
        @media screen and (min-width: 768px) {
        .menu-toggle {
            display: none;
        }

        .navbar {
            display: block;
            text-align: left;
        }

        .menu {
            flex-direction: row;
        }
        .menu a {
            text-align: left;
        }
        .menu li {
            margin-right: 20px;
            margin-bottom: 0;
        }
        }
    </style>
</head>
<body>
<header>
    <div class="menu-toggle" id="mobile-menu">
      ☰ Menú
    </div>
    <nav class="navbar">
      <ul class="menu">
        <li><a href="#"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Mi Perfil</a></li>
        <li><a href="#"><i class="fa fa-file-text" aria-hidden="true"></i> Orde de Trabajo</a></li>
        <li><a href="#"><i class="fa fa-list-ol" aria-hidden="true"></i> Servicios</a></li>
        <li><a href="#"><i class="fa fa-check" aria-hidden="true"></i> <img src="https://acreditasys.tech/img/logo_chilevalora.png" width="120"> </a></li>
        <li><a href=""><i class="fa fa-ils" aria-hidden="true"></i> <img src="https://acreditasys.tech/img/Logo-INN.png" width="120"></a></li>
        <li><a href=""><i class="fa fa-file-text-o" aria-hidden="true"></i> Gestión de Calidad</a></li>
    </ul>
    </nav>
</header>
<script>
    document.getElementById('mobile-menu').addEventListener('click', function () {
        var navbar = document.querySelector('.navbar');
        navbar.style.display = (navbar.style.display === 'block' ? 'none' : 'block');
    });
</script>
<div class="logo">
    <img src="https://acreditasys.tech/img/logoOperamaq.png" width="100">
</div>
</body>
</html>