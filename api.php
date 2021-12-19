
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
$datos = json_decode(file_get_contents("php://input"), true);
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
            case 'respuesta':
                $idAlumnno = $datos['idAlumno'];
                $comentario = $datos['comentario'];
                $respuestas = $datos['respuestas'];

                
                if (isset($comentario)) {
                    $db->insert("INSERT into comentarios(comentario, id_alumno) values('$comentario','$idAlumnno')");
                }
           
                foreach ($respuestas as $respuesta) {
                    $idPregunta = $respuesta['idPregunta'];
                    $idUnidad = $respuesta['idUnidad'];
                    $puntaje = $respuesta['puntaje'];
                    $query = "INSERT into respuestas(id_pregunta, id_alumno, id_unidad_aprendizaje, puntaje) values($idPregunta,$idAlumnno,$idUnidad, $puntaje)";
                    $db->insert($query);
                }
                responder(array("res" => 'ok'));
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
