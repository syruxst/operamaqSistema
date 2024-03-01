<?php
error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Conectar a la base de datos
include_once('../admin/conex.php');


$username = trim($_POST['usuario']);
$password = trim($_POST['pass']);

// Consulta para validar el usuario y la contraseña
$sql = "SELECT * FROM operadores WHERE rut = ? AND clave_web = PASSWORD(?) LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Si la consulta tiene más de 0 filas, significa que se encontró al menos un resultado en la base de datos.

    session_start();
    // Inicia una sesión en PHP.

    $_SESSION['loggedin'] = true;
    // Establece el valor de 'loggedin' en la sesión como verdadero, indicando que el usuario ha iniciado sesión.

    $_SESSION['cliente'] = $username;
    // Almacena el nombre de usuario en la sesión con la clave 'cliente'.

    $_SESSION['start'] = time();
    // Establece el tiempo de inicio de la sesión como el momento actual.

    $_SESSION['expire'] = $_SESSION['start'] + (15 * 60);
    // Establece el tiempo de expiración de la sesión 15 minutos después del inicio.

    header("Location: ../cliente/");
    // Redirige al usuario a la página '../cliente/'.

    exit();
    // Termina la ejecución del script para evitar que se procesen más líneas después de la redirección.
} else {
    include_once("login.php");

    mostrarError("El usuario o la contraseña son incorrectos.");
}

$stmt->close();

// Función para mostrar mensajes de error y redirigir
function mostrarError($mensaje) {
    echo '
    <script>
    swal({
        title: "Control de Usuario!",
        text: "'. $mensaje .'",
        icon: "info",
        button: "Aceptar!",
      }).then(function() {
        window.location.href = "login.php";
      });
    </script>
    ';
    exit();
}

// Cerrar la conexión a la base de datos
$conn->close();
?>