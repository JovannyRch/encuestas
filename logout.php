<?php

session_start();


unset($_SESSION['login']);
unset($_SESSION['tipo_usuario']);
unset($_SESSION['id_usuario']);
header("Location: login.php");
?>
