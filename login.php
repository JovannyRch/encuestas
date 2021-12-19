<?php

session_start();

require 'Db.php';

function validarBoleta($login)
{

    if (strlen("$login") != 10) return false;

    $digitos = "1234567890";
    for ($i = 0; $i < strlen($login); $i++) {
        if (!str_contains($digitos, $login[$i])) {
            return false;
        }
    }
    return true;
}

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

    if (!validarBoleta($login)) {
        $mensaje = array('type' => 'alert', 'msg' => 'La boleta debe de ser de 10 dígitos');
    } else {

        $usuario = $db->row("SELECT * from usuarios where correo = '$login' and contrasenia = '$password'");

        if (is_array($usuario)) {

            $tipo_usuario = $usuario['tipo_usuario'];
            $id_usuario = $usuario['id_usuario'];

            if ($tipo_usuario == 'ALUMNO') {
                $_SESSION['id_usuario'] = $id_usuario;
                $_SESSION['login'] = $login;
                $_SESSION['tipo_usuario'] = $tipo_usuario;
                header("Location: index.php");
            } else {
                $mensaje = array('type' => 'danger', 'msg' => 'Usuario no encontrado');
            }
        } else {
            $mensaje = array('type' => 'danger', 'msg' => 'Usuario no encontrado');
        }
    }
}

?>


<?php include('header.php'); ?>
<div style="min-height: 100vh; width: 100vw;" class="container">
    <h3>Inicio de sesión</h3>
    <div class="row">
        <form class="col s6" action="login.php" method="POST">
            <div class="row">
                <div class="input-field col s12">
                    <input required placeholder="Ingresa tu boleta" value="<?= $data['login'] ?>" id="boleta" name="login" type="text" class="validate">
                    <label for="boleta">Boleta</label>
                </div>
                <div class="input-field col s12">
                    <input required id="password" value="<?= $data['password'] ?>" placeholder="Ingresa tu contraseña" name="password" type="password" class="validate">
                    <label for="password">Contraseña</label>
                </div>
            </div>
            <button type="submit" class="btn">Iniciar sesión</button>
            <br><br>
            <a href="registro.php">
                Registrarme
            </a>
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