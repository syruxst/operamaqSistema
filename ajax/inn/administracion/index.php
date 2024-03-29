<?php session_start(); error_reporting(1);
    // Verificar si la variable de sesión para el usuario existe
    if (isset($_SESSION['usuario'])) {
        // Obtener el usuario de la variable de sesión
        $usuario = $_SESSION['usuario'];
    } else {
        // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
        header("Location: ../login.php");
        exit();
    }
    // Conectarse a la base de datos
    require_once('../../../admin/conex.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
    <link rel="stylesheet" href="../css/style.operador.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>:: Sistema Gestión Calidad ::</title>
    <style>
        body{
            background-color: #f5f5f5;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }
        .cabecera{
            position: absolute;
            background-color: #f5f5f5;
            width: 100%;
            height: 80px;
            padding: 10px;
            box-shadow: 0 12px 28px 0 rgba(0, 0, 0, 0.2), 0 2px 4px 0 rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
            border: 1px solid #e5e5e5;
            z-index: 1000;
        }
        .contenedor {
            position: absolute;
            top: 80px; 
            width: 100%;
            height: calc(100% - 80px); 
            display: flex;
            flex-direction: row; 
        }
        #menu {
            width: 100%;
            background-color: #f2f2f2;
            padding: 1px;
            box-sizing: border-box;
            flex-basis: 220px; /* Puedes mantener esto si es necesario */
        }

        #menu .bienvenido {
            display: flex; /* Usar flexbox */
            align-items: center; /* Alinear verticalmente al centro */
            position: relative;
            width: 100%;
            height: 70px;
            background-color: white;
            border-bottom: 1px solid #E5E8E9;
            cursor: pointer;
            overflow: hidden;
            padding: 20px;
        }

        #menu .bienvenido::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background-color: #04C9FA;
            transition: width 0.3s ease-in-out;
        }

        #menu .bienvenido:hover::after {
            width: 100%;
            transition: width 0.3s ease-in-out;
        }
        #menu .bienvenido i {
            margin-right: 10px; /* Espacio entre el ícono y el texto (ajusta según sea necesario) */
        }
        #pantalla {
            flex-grow: 1;
            padding: 10px;
            box-sizing: border-box;
        }
        #centrado {
            position: absolute;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
        }

        @media (max-width: 666px) {
            .contenedor {
                flex-direction: column;
            }
            #menu {
                max-width: 100%;
                flex-basis: auto;
                height: 100px;
            }
            #menu .bienvenido {
                height: 50px;
            }
            #pantalla {
                flex-grow: 1;
                height: 100%; /* Cambiar la altura a 100% para ocupar todo el espacio disponible */
                overflow-y: auto; /* Agregar un scroll vertical si es necesario */
            }
        }
    </style>
</head>
<body>
<div class="cabecera">
    <img src="https://operamaq.cl/nuevo/img/logoFinal2.png" width="350" alt="">
    <center id="centrado"><h3>Sistema de Gestión Administración</h3></center>
</div>
<div class="contenedor">
    <div id="menu">
        <div class="bienvenido" id="personal">
            <i class="fa fa-folder-open" aria-hidden="true"></i> Contratos Personal
        </div>
        <div class="bienvenido" id="evaluadores">
            <i class="fa fa-handshake-o" aria-hidden="true"></i> Convenios Inspectores
        </div>
        <div class="bienvenido" id="sociedad">
            <i class="fa fa-university" aria-hidden="true"></i> Constitución Sociedad
        </div>
    </div>
    <div id="pantalla">
        <iframe id="iframe"
            width="100%"
            height="100%"
            frameborder="0"
            src="">
         </iframe>
    </div>
</div>
<script>

document.addEventListener("DOMContentLoaded", function() {
    var personal = document.getElementById("personal");
    var evaluadores = document.getElementById("evaluadores");
    var sociedad = document.getElementById("sociedad");
    var iframe = document.getElementById("iframe");

    var urlpersonal = "personal.php";
    var urlevaluadores = "evaluadores.php";
    var urlsociedad = "sociedad.php";

    personal.addEventListener("click", function() {
        iframe.src = urlpersonal;
    });

    evaluadores.addEventListener("click", function(){
        iframe.src = urlevaluadores;
    });

    sociedad.addEventListener("click", function(){
        iframe.src = urlsociedad;
    });
});

function esDispositivoMovil() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

if (esDispositivoMovil()) {
    console.log("Estás viendo desde un dispositivo móvil.");
} else {
    console.log("Estás viendo desde una PC.");
}

</script>
</body>
</html>