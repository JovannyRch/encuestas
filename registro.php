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
    'password' => '',
    'password2' => '',
    'nombre' => '',
    'apellido_materno' => '',
    'apellido_paterno' => '',
    'id_semestre' => '',
    'id_grupo' => '',
    'id_programa_academico' => ''
);

$metodo = $_SERVER["REQUEST_METHOD"];

if ($metodo == "POST") {


    if (
        isset($_POST['login'])
        && isset($_POST['password'])
        && isset($_POST['password2'])
        && isset($_POST['nombre'])
        && isset($_POST['apellido_materno'])
        && isset($_POST['apellido_paterno'])
        && isset($_POST['id_semestre'])
        && isset($_POST['id_grupo'])
        && isset($_POST['id_programa_academico'])
    ) {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $nombre = $_POST['nombre'];
        $apellido_materno = $_POST['apellido_materno'];
        $apellido_paterno = $_POST['apellido_paterno'];
        $id_grupo = $_POST['id_grupo'];
        $id_programa_academico = $_POST['id_programa_academico'];
        $id_semestre = $_POST['id_semestre'];

        $data = array(
            'login' => $login,
            'password' => $password,
            'password2' => $password2,
            'nombre' => $nombre,
            'apellido_materno' => $apellido_materno,
            'apellido_paterno' => $apellido_paterno,
            'id_semestre' => $id_semestre,
            'id_grupo' => $id_grupo,
            'id_programa_academico' => $id_programa_academico
        );


        if ($password != $password2) {
            $mensaje = array('type' => 'alert', 'msg' => 'Las contraseñas no coiciden');
        } else {

            $busqueda = $db->array("SELECT * from usuarios where correo = $login");

            if (sizeof($busqueda) != 0) {
                $mensaje = array('type' => 'alert', 'msg' => 'La boleta ya esta registrada, intente iniciar sesión o intente con otra boleta.');
            } else {
                $id_usuario = $db->insert("INSERT into usuarios(correo, contrasenia) values('$login','$password')");
                $db->insert("INSERT INTO alumnos(nombre, apellido_paterno, apellido_materno, id_semestre, id_programa_academico, id_grupo, id_usuario) values('$nombre', '$apellido_paterno', '$apellido_materno',$id_semestre, $id_programa_academico, $id_grupo, $id_usuario)");
                $mensaje = array('type' => 'success', 'msg' => 'Usuario registrado exitosamente, inicie sesión para continuar.');
                $data = array(
                    'login' => '',
                    'password' => '',
                    'password2' => '',
                    'nombre' => '',
                    'apellido_materno' => '',
                    'apellido_paterno' => '',
                    'id_semestre' => '',
                    'id_grupo' => '',
                    'id_programa_academico' => ''
                );
            }
        }
    }else{
        $mensaje = array('type' => 'alert', 'msg' => 'Datos incompletos');
    }
}

$semestres = $db->array("SELECT * from semestres");
$programas = $db->array("SELECT * from programas_academicos");
$grupos = $db->array("SELECT * from grupos");

?>


<?php include('header.php'); ?>
<div style="min-height: 100vh; width: 100vw;" class="container">

    <?php
    if (!is_null($mensaje)) { ?>
        <br />
        <div>
            <strong>
                <?= $mensaje['msg'] ?>
            </strong>
        </div>
    <?php } ?>

    <h3>Registro de alumno</h3>
    <div class="row">
        <form class="col s6" action="registro.php" method="POST">
            <div class="row">

                <div class="input-field col s12">
                    <input required placeholder="Ingresa apellido paterno" value="<?= $data['apellido_paterno'] ?>" id="boleta" name="apellido_paterno" type="text" class="validate">
                    <label for="boleta">Apellido paterno</label>
                </div>

                <div class="input-field col s12">
                    <input required placeholder="Ingresa apellido materno" value="<?= $data['apellido_materno'] ?>" id="boleta" name="apellido_materno" type="text" class="validate">
                    <label for="boleta">Apellido materno</label>
                </div>

                <div class="input-field col s12">
                    <input required placeholder="Ingresa nombre" value="<?= $data['nombre'] ?>" id="boleta" name="nombre" type="text" class="validate">
                    <label for="boleta">Nombre</label>
                </div>

                <div class="input-field col s12 m12">
                    <select name="id_semestre">
                        <option value="-1" disabled>Seleccione un semestre</option>
                        <?php foreach ($semestres as $semestre) { ?>
                            <option value="<?= $semestre['id_semestre'] ?>"><?= $semestre['nombre'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <label for="boleta">Semestre</label>
                </div>

                <div class="input-field col s12 m12">
                    <select name="id_programa_academico">
                        <option value="-1" disabled>Seleccione un programa académico</option>
                        <?php foreach ($programas as $programa) { ?>
                            <option value="<?= $programa['id_programa_academico'] ?>"><?= $programa['nombre'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <label for="boleta">Programas académicos</label>
                </div>

                <div class="input-field col s12 m12">
                    <select name="id_grupo">
                        <option value="-1" disabled>Seleccione un grupo</option>
                        <?php foreach ($grupos as $grupo) { ?>
                            <option value="<?= $grupo['id_grupo'] ?>"><?= $grupo['nombre'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <label for="boleta">Grupos</label>
                </div>




                <div class="input-field col s12">
                    <input required placeholder="Ingresa boleta" value="<?= $data['login'] ?>" id="boleta" name="login" type="text" class="validate">
                    <label for="boleta">Boleta</label>
                </div>
                <div class="input-field col s12">
                    <input id="password" required value="<?= $data['password'] ?>" placeholder="Ingresa contraseña" name="password" type="password" class="validate">
                    <label for="password">Contraseña</label>
                </div>

                <div class="input-field col s12">
                    <input id="password2" required value="<?= $data['password2'] ?>" placeholder="Ingresa contraseña nuevamente" name="password2" type="password" class="validate">
                    <label for="password2">Confirmación de contraseña</label>
                </div>

            </div>
            <button type="submit" class="btn">Registrarme</button>
            <br><br>
            <a href="login.php">
                Iniciar sesión
            </a>
        </form>
    </div>

</div>
<?php include('footer.php'); ?>