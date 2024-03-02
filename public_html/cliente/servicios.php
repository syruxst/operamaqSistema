<?php
session_start();
error_reporting(1);
$usuario = $_GET['nombre'];

require_once('../admin/conex.php');

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $usuario = $_SESSION['cliente'];
    $sql = "SELECT * FROM `clientes` WHERE user = '$usuario'";
    $rst = $conn->query($sql);
    
    if ($rst->num_rows > 0) {
        $row = $rst->fetch_assoc();
        $name = $row['contacto'];
        $empresa = $row['empresa'];
        $faena = $row['faena'];
    } else {
        header("Location: ../cliente.php");
        exit();
    }
} else {
    header("Location: ../cliente.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>:: Perfil ::</title>
    <style>
        :root {
            --color: #04C9FA;
        }
        body{
            font-family: 'Roboto', sans-serif;
            padding: 50px;
        }
        .container {
            border-radius: 10px;
            border: 1px solid #e5e5e5;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #A6A7A7;
        }
        h1{
            color: var(--color);
        }
        /* Estilos para la clase "tabla" */
        .tabla {
            box-shadow: 0 12px 28px 0 rgba(0, 0, 0, 0.2), 0 2px 4px 0 rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
        }
        /* Estilos para la clase "perfil" */
        .perfil {
            width: 100px; 
            height: 100px; 
            border-radius: 50%; 
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto; 
            border: 1px solid var(--color);
        }
        .perfil i {
            color: var(--color);
        }
        #actualizar {
            width: 200px; 
            height: 40px; 
            background-color: var(--color);
            color: white; 
            border: none; 
            cursor: pointer; 
        }
        #actualizar:hover {
            background-color: #03a4d3; 
        }
        @media (max-width: 600px) {
            body {
                padding: 20px;
            }
            .container {
                width: 100%;
            }
            .row {
                width: 100%; 
                display: block;
            }
            .col {
                width: 100%; 
                float: none;
            }
            .tabla {
                padding: 5px;
            }
        }
        /*loading*/
        /* Estilo para el contenedor del indicador de carga */
        .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
        z-index: 1000; /* Asegura que esté en la parte superior de todos los elementos */
        }

        /* Estilo para el indicador de carga en sí */
        .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin: 15% auto; /* Centra el indicador de carga verticalmente */
        animation: spin 2s linear infinite; /* Agrega una animación de giro */
        }

        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body background="white">
    <div class="container">
        <h4>Faena : <?php echo $faena; ?></h4>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i></span>
            <input type="text" name="buscar" id="buscar" placeholder="Buscar por: faena, nombre, rut, equipo" class="form-control">
        </div>
        <hr>
        <div id="resultado"></div>
        <?php
        $consulta = "SELECT * FROM `cotiz` WHERE name_cliente = ? AND faena = ? AND estado = 'APROBADO'";
        $statement = $conn->prepare($consulta);
        $statement->bind_param("ss", $empresa, $faena);
        $statement->execute();
        $resultado = $statement->get_result();

        if ($resultado->num_rows > 0) {
            while ($ver = $resultado->fetch_assoc()) {
                // Aquí puedes mostrar los resultados iniciales si es necesario
            }
        } else {
            echo "No se encontraron resultados.";
        }

        $statement->close();
        ?>
    </div>

    <script>
        // Función para realizar la búsqueda
        function realizarBusqueda(valor) {
            // Crear objeto XMLHttpRequest
            var xhr = new XMLHttpRequest();

            // Obtener los valores de $empresa y $faena desde PHP
            var empresa = '<?php echo $empresa; ?>';
            var faena = '<?php echo $faena; ?>';

            // Configurar la solicitud
            xhr.open('POST', 'buscar_servicios.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Manejar el evento de carga
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('resultado').innerHTML = xhr.responseText;
                }
            };

            // Enviar la solicitud con los datos de búsqueda y las variables PHP
            xhr.send('buscar=' + encodeURIComponent(valor) +
                '&empresa=' + encodeURIComponent(empresa) +
                '&faena=' + encodeURIComponent(faena));
        }

        // Realizar búsqueda inicial al cargar la página
        realizarBusqueda('');

        // Asociar la función al evento input del input de búsqueda
        document.getElementById('buscar').addEventListener('input', function () {
            var valorBusqueda = this.value.trim();
            realizarBusqueda(valorBusqueda);
        });
    </script>
</body>
</html>