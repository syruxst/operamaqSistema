<?php session_start(); error_reporting(0);
    // Verificar si la variable de sesión para el usuario existe
    if (isset($_SESSION['usuario'])) {
        // Obtener el usuario de la variable de sesión
        $usuario = $_SESSION['usuario'];
    } else {
        // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
        header("Location: ../index.php");
        exit();
    }
    // Conectarse a la base de datos
    require_once('../admin/conex.php');

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
    <title>Document</title>
    <style>
        :root {
            --color: #04C9FA;
        }

        body {
            font-family: 'Roboto', sans-serif;
            padding: 50px;
        }
        h1 {
            color: var(--color);
        }
        .item {
            width: 200px;
            height: 80px;
            float: left;
            cursor: pointer;
            border: 1px solid #e5e5e5;
            transition: box-shadow 0.3s;
            border-radius: 10px;
            display: flex;
            justify-content: center; 
            align-items: center; 
            flex: 0 0 calc(25% - 20px); /* Esto hará que haya 4 elementos en una fila */
            margin: 10px;
            text-align: center;
        }

        .item:hover {
            border: 1px solid var(--color);
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.1);
        }     
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            justify-content: center;
        }  
        a {
            text-decoration: none;
            color:rgba(0, 0, 0, 0.8);
        }
        a:hover{
            text-decoration: none;
            color: var(--color);
        }
        ul.image-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: nowrap;
        }

        li.image-item {
            margin-right: 10px;
            cursor: pointer;
            transition: transform 0.3s; 
            border: 3px solid #ACEBFB;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3); 
        }
        li.image-item:hover {
            transform: scale(1.1); 
            border: 3px solid var(--color);
        }
        .container-img {
            display: flex;
            height: 250px;
            border: 1px solid #e5e5e5;
        }
        .container-obs {
            height: 250px;
            /*border: 1px solid #e5e5e5;*/
            padding: 20px;
        }

        .div-50 {
            flex: 1;
            width: 50%;
            padding: 20px;
        }
        .buttons{
            display: flex;
            flex-direction: column;
            align-items: center; /* Centra horizontalmente los elementos */
            height: 100vh;
        }
        .div-50 button {
            margin: 5px 0; /* Agrega un margen entre los botones (5px arriba y abajo, 0 en los lados) */
        }
        @media (max-width: 666px) {
            .item {
                flex: 0 0 calc(50% - 20px);
            }
        } 
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
        .textarea {
            border: none;
            resize: none;
            width: 100%;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        textarea {
            border: none;
            resize: none;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }
        .tex {
            border: 1px solid #dddddd;
        }
    </style>
</head>
<body>
    <center><h1>Seleccionar Archivo</h1></center>
    <?php 
    $data = $_GET['dataInforme'];
    $buscar = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE id='$data'");
    $row = mysqli_fetch_array($buscar);
    $Id_informe = $row['id'];
    $licencia = $row['licencia'];
    $cv = $row['cv'];
    $img_1 = $row['img_1'];
    $img_2 = $row['img_2'];
    $img_3 = $row['img_3'];
    $E = $row['equipo'];

    $porNota = $row['porNota'];
    $porcentaje = $row['porcentaje'];
    $porcentajeRedondeado = round($porcentaje);

    $resultado_1 = $porNota * 20;
    $resultado_2 = $porcentajeRedondeado * 80;
    $resultado_3 = ($resultado_1 + $resultado_2)/100;

    $resolucion = ($resultado_3 >= 80) ? "APROBADO" : "RECHAZADO";

    ?>


    <div class="container">
        <input type="hidden" name="resolucion" id="resolucion" value="<?php echo $resolucion; ?>">
        <div class="item" title="VER CURRICULUM"> <a href="https://acreditasys.tech/uploads_op/<?php echo $cv;?>" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> &nbsp; &nbsp; CURRILUM  </a> </div>
        <div class="item" title="VER LICENCIA DE CONDUCIR"><a href="https://acreditasys.tech/licencias/<?php echo $licencia;?>" target="_blank"><i class="fa fa-address-card-o" aria-hidden="true"></i> &nbsp; &nbsp; LICENCIA CONDUCIR </a> </div>
        <div class="item" title="VER PRUEBA TEORICA"><a href="https://acreditasys.tech/miSitio/ver_examen.php?data=<?php echo $row['rut'];?>&E=<?php echo $E;?>&D=<?php echo $row['date_out'];?>&P=<?php echo $row['porNota'];?>&N=<?php echo $row['punNota'];?>" target="_blank" title="PRUEBA TEORICA"><i class="fa fa-file-text" aria-hidden="true"></i> &nbsp; &nbsp; PRUEBA TEORICA </a></div>
    </div>
    <hr>
        <div class="container-img">
            <div class="div-50">
                <ul class="image-list">
                    <li class="image-item"><a href="https://acreditasys.tech/SitioEI/evidencia/<?php echo $img_1; ?>" target="_blank"><img src="https://acreditasys.tech/SitioEI/evidencia/<?php echo $img_1; ?>" alt="Imagen 1" width="200px" height="200px"></a></li>
                    <li class="image-item"><a href="https://acreditasys.tech/SitioEI/evidencia/<?php echo $img_2; ?>" target="_blank"><img src="https://acreditasys.tech/SitioEI/evidencia/<?php echo $img_2; ?>" alt="Imagen 2" width="200px" height="200px"></a></li>
                    <li class="image-item"><a href="https://acreditasys.tech/SitioEI/evidencia/<?php echo $img_3; ?>" target="_blank"><img src="https://acreditasys.tech/SitioEI/evidencia/<?php echo $img_3; ?>" alt="Imagen 3" width="200px" height="200px"></a></li>
                </ul>
            </div>
            <div class="div-50 buttons">
                <input type="hidden" name="dataInforme" id="dataInforme" value="<?php echo $data;?>">
                <button type="button" class="btn btn-success" title="APROBAR ORDEN DE TRABAJO" onclick="enviarAccion('aprobar')">
                    <i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp; &nbsp; APROBAR
                </button>
                <button type="button" class="btn btn-danger" title="RECHAZAR ORDEN DE TRABAJO" onclick="enviarAccion('rechazar')">
                    <i class="fa fa-times-circle-o" aria-hidden="true"></i>&nbsp; &nbsp; RECHAZAR
                </button>
            </div>
        </div>
        <div class="container-obs">
            <center><H2>BRECHAS</H2></center>
            <?php
                $buscar = mysqli_query($conn, "SELECT * FROM `informes` WHERE IdOper='$Id_informe'");
                
                // Imprimir la tabla con observaciones en una sola fila y saltos de línea
                echo '<table width="100%" border="0">
                        <tr>
                            <th>Brechas Evaluador (PRUEBA PRACTICA)</th>';
                    
                echo '<th>Clasificación</th>';
                echo '
                        </tr>';

                while ($rows = mysqli_fetch_array($buscar)) {
                    // Agregar saltos de línea antes de cada "n.-"
                    $observaciones = preg_replace('/(\d+\.-)/', "\n$1", $rows['observaciones']);
                    
                    echo '<tr>
                            <td style="white-space: pre-wrap;">' . $observaciones . '</td>';
                    echo '
                            <td style="white-space: pre-wrap;">
                                <textarea class="tex" name="obs" id="obs" cols="30" rows="5"></textarea>
                            </td>';
                    echo '
                    </tr>';
                }
                echo '<tr>
                        <th>Brechas Sistema (PRUEBA TEORICA)</th>';
                
                echo '<th>Clasificación</th>';
                echo '
                </tr>';
                echo '<tr>
                        <td>';
                $query = "SELECT * FROM examenes WHERE id_oper = ? AND equipo = ? AND date_realizada = ?";
                $stmt = mysqli_prepare($conn, $query);
                
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sss", $row['rut'], $row['equipo'], $row['date_out']);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $p_values = array();
                    $r_values = array();
                
                    while ($row = mysqli_fetch_assoc($result)) {
                        $resultado = $row['resultado'];
                
                        // Almacenar las preguntas y respuestas en arrays
                        for ($i = 1; $i <= 20; $i++) {
                            $p_values[] = $row['p' . $i];
                            $r_values[] = $row['r' . $i];
                        }
                    }
                
                    for ($i = 0; $i < 20; $i++) {
                        $num_pregunta = $i + 1;
                        $num_respuesta = $i + 1;
                
                        // Utilizar una consulta parametrizada para evitar inyecciones SQL
                        $prueba_stmt = mysqli_query($conn, "SELECT * FROM `$E` WHERE `id` = '{$p_values[$i]}' ");
                        $prueba = mysqli_fetch_array($prueba_stmt);
                        $pregunta = $prueba['PREGUNTA'];
                        $dato = "R" . $r_values[$i];
                        $correcta = $prueba['id_respuesta_correcta'];
                        $respuesta = $prueba[$dato];
                
                        if ($r_values[$i] != $correcta) {
                            $color = "red";
                            $estado = "INCORRECTA";
                
                            echo "<section class='pregunta'>";
                            echo "{$num_pregunta}.- " . $pregunta . "<br><br>";
                            echo '</section>';
                        }
                    }
                }

                echo '</td>                           
                        <td style="white-space: pre-wrap;">
                            <textarea name="brechas" id="brechas" cols="30" rows="5"></textarea>
                        </td>';

                echo '</tr>';
                echo '<tr>';
                echo '<th colspan="2">Oportunidad de Mejora (Cada cierre de linea debe terminar en punto.)</th>';
                echo '</tr>';
                echo '<tr>';
                echo '<td colspan="2">';
                echo '<textarea name="oport" id="oport" cols="30" rows="8">PARA LOGRAR LA EXCELENCIA DEBE MEJORAR SU CONOCIMIENTO DE:' . "\n" . 'EN LO REFERENTE A:' . "\n" . 'DE ACUERDO CON: ' . "\n\n" . '
                </textarea>';
                echo '</td>';
                echo '</tr>';
                echo '<tr><td colspan="2"><button id="agregarTexto">Agregar Texto</button></td></tr>';
                echo '</table>';
            ?>

        </div>
        <div class="loading-overlay" id="loading-overlay">
            <div class="loader"></div>
        </div>
<script>
document.getElementById('agregarTexto').addEventListener('click', function() {
    var textarea = document.getElementById('oport');
    textarea.value += 'PARA LOGRAR LA EXCELENCIA DEBE MEJORAR SU CONOCIMIENTO DE:' + "\n" +
                     'EN LO REFERENTE A:' + "\n" +
                     'DE ACUERDO CON:' + "\n\n";
});

function enviarAccion(accion) {
    var dataInforme = document.getElementById("dataInforme").value;
    var obsValue = document.getElementById("obs").value;
    var brechasValue = document.getElementById("brechas").value;
    var oportValue = document.getElementById("oport").value;
    var resolucionValue = document.getElementById("resolucion").value;
    
    if (accion === 'rechazar') {
        // Preguntar al usuario si realmente desea rechazar el servicio
        swal({
            title: "¿Estás seguro?",
            text: "Una vez rechazado, no podrás recuperar este documento",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willRechazar) => {
            if (willRechazar) {
                // El usuario ha confirmado el rechazo, ahora puedes enviar el valor al archivo cierre_ot.php
                enviarValor(dataInforme, accion, obsValue, brechasValue, oportValue);
            } else {
                swal("Tu documento está seguro.");
            }
        });
    } else {
        // Si la acción es aprobar, simplemente envía el valor al archivo cierre_ot.php
        enviarValor(dataInforme, accion, obsValue, brechasValue, oportValue, resolucionValue);
    }
}

function enviarValor(dataInforme, accion, obsValue, brechasValue, oportValue, resolucionValue) {
    var xhr = new XMLHttpRequest();
    var url = 'cierre_ot.php';

    // Muestra el indicador de carga
    var loadingOverlay = document.getElementById('loading-overlay');
    loadingOverlay.style.display = 'block';

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            // Oculta el indicador de carga cuando la respuesta se recibe
            loadingOverlay.style.display = 'none';

            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        swal({
                            title: "Bien hecho!",
                            text: "Operación exitosa: " + response.message,
                            icon: "success",
                            button: "Aceptar!",
                        });
                    } else {
                        swal({
                            title: "Algo sali mal",
                            text: "Fallo: " + response.message + "!",
                            icon: "error",
                            button: "Aceptar!",
                        });
                    }
                } catch (e) {
                    console.error('Error al analizar la respuesta JSON: ' + e);
                }
            } else {
                console.error('Error en la solicitud. Estado: ' + xhr.status);
            }
        }
    };

    var data = 'dataInforme=' + encodeURIComponent(dataInforme) + '&accion=' + encodeURIComponent(accion) + '&obs=' + encodeURIComponent(obsValue) + '&brechas=' + encodeURIComponent(brechasValue) + '&oport=' + encodeURIComponent(oportValue) + '&resolucion=' + encodeURIComponent(resolucionValue);
    
    xhr.send(data);
}


</script>
</body>
</html>