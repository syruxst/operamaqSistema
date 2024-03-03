<?php session_start();
    error_reporting(1);
    if (isset($_POST['usuario']) && isset($_POST['pass'])) {
        $usuario = $_POST['usuario'];
        $contraseña = $_POST['pass'];

        require_once('../../admin/conex.php');

        $query = "SELECT * FROM insp_eva WHERE user = ? AND pass = PASSWORD(?)";
        $stmt = mysqli_prepare($conn, $query);

        mysqli_stmt_bind_param($stmt, "ss", $usuario, $contraseña);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $num_filas = mysqli_num_rows($result);

        if ($num_filas > 0) {
            $_SESSION['usuario'] = $usuario;
            header("Location: ../SitioEI/");
            exit();
        } else {
            $_SESSION['error'] = "El usuario no existe";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        }
    }
?>