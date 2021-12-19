<?php

session_start();

require 'Db.php';

if (isset($_SESSION['login'])) {
    if ($_SESSION['tipo_usuario'] == "ALUMNO") {
        header("Location: index.php");
    } else if ($_SESSION['tipo_usuario'] == 'ADMIN') {
        header("Location: administracion.php");
    }
}

$db = new Db();
$mensaje = null;
$data = array(
    'login' => '',
    'password' => ''
);


if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $data = array(
        'login' => $login,
        'password' => $password
    );



    $usuario = $db->row("SELECT * from usuarios where correo = '$login' and contrasenia = '$password'");

    if (is_array($usuario)) {

        $tipo_usuario = $usuario['tipo_usuario'];
        $id_usuario = $usuario['id_usuario'];

        if ($tipo_usuario == 'ADMIN') {
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['login'] = $login;
            $_SESSION['tipo_usuario'] = $tipo_usuario;
            header("Location: administracion.php");
        } else {
            $mensaje = array('type' => 'danger', 'msg' => 'Usuario no encontrado');
        }
    } else {
        $mensaje = array('type' => 'danger', 'msg' => 'Usuario no encontrado');
    }
}

?>


<?php include('header.php'); ?>
<div style="min-height: 100vh; width: 100vw;" class="container">
    <h3>Inicio de sesión ADMIN</h3>
    <div class="row">
        <form class="col s6" action="admin_login.php" method="POST">
            <div class="row">
                <div class="input-field col s12">
                    <input placeholder="Ingresa nombre de usuario" value="<?= $data['login'] ?>" id="login" name="login" type="text" class="validate">
                    <label for="boleta">Nombre de usuario</label>
                </div>
                <div class="input-field col s12">
                    <input id="password" value="<?= $data['password'] ?>" placeholder="Ingresa tu contraseña" name="password" type="password" class="validate">
                    <label for="password">Contraseña</label>
                </div>
            </div>
            <button type="submit" class="btn">Iniciar sesión</button>
        </form>
    </div>
    <?php
    if (!is_null($mensaje)) { ?>
        <br />
        <div>

            <strong>
                <?= $mensaje['msg'] ?>
            </strong>
        </div>
    <?php } ?>
</div>
<?php include('footer.php'); ?>