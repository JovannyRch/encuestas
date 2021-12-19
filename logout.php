<?php

session_start();



$nex_page = "login.php";

if ($_SESSION['tipo_usuario'] == "ALUMNO") {
    $nex_page = "login.php";
} else if ($_SESSION['tipo_usuario'] == 'ADMIN') {
    $nex_page = "admin_login.php";
}

unset($_SESSION['login']);
unset($_SESSION['tipo_usuario']);
unset($_SESSION['id_usuario']);
header("Location: $nex_page");
?>
