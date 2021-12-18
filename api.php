
<?php

require 'db.php';
session_start();

function responder($res)
{
    echo json_encode($res);
}


$db = new Db();


$metodo = $_SERVER["REQUEST_METHOD"];
$ruta = implode("/", array_slice(explode("/", $_SERVER["REQUEST_URI"]), 3));
$datos = json_decode(file_get_contents("php://input"));
switch ($metodo) {
    case 'GET':
        switch ($ruta) {
            case 'preguntas':
                $res = $db->array("SELECT * from preguntas");
                responder($res);
                break;
            case 'unidades':
                $res = $db->array("SELECT * from unidades_aprendizaje");
                responder($res);
                break;
        }
        break;
    case 'POST':
        switch ($ruta) {
            case 'usuario':
                $nombreUsuario = $datos->nombre;
                break;
        }
        break;
    case 'PUT':
        switch ($ruta) {
        }
        break;
    case 'DELETE':
        switch ($ruta) {
        }
        break;
}
