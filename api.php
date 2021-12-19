
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
            case 'reporte':{

                $totalAlumnos = $db->row('SELECT count(*) total from alumnos')['total'];
                $totalEncuestas = $db->row('SELECT count(*) total from encuestas')['total'];

                $preguntas = $db->array("SELECT * from preguntas");
                $preguntas_reporte = array();
                foreach ($preguntas as $pregunta) {
                    $idPregunta = $pregunta['id_pregunta'];
                    $promedio = floatval($db->row("SELECT avg(puntaje) promedio from preguntas natural join respuestas where id_pregunta = $idPregunta")['promedio']);
                    $preguntas_reporte[] = array('pregunta' => $pregunta, 'promedio' => $promedio);
                }

                $comentarios = $db->array("SELECT * from comentarios natural join alumnos");
                $reporte = array(
                    'totalAlumnos' => intval($totalAlumnos), 
                    'totalEncuestas' => intval($totalEncuestas),
                    'preguntas' => $preguntas_reporte,
                    'comentarios' => $comentarios
                );
                responder($reporte);
                break;
            }
        }
        break;
    case 'POST':
        switch ($ruta) {
            case 'encuesta':
                $idAlumnno = $datos['idAlumno'];
                $comentario = $datos['comentario'];
                $respuestas = $datos['respuestas'];
                
                $id_encuesta = $db->insert("INSERT INTO encuestas(id_alumno) values($idAlumnno)");

                if (isset($comentario) && $comentario != "") {
                    echo "INSERT INTO comentarios(comentario, id_encuesta) values('$comentario','$id_encuesta')";
                    $db->insert("INSERT INTO comentarios(comentario, id_encuesta) values('$comentario','$id_encuesta')");
                }

                foreach ($respuestas as $respuesta) {
                    $idPregunta = $respuesta['idPregunta'];
                    $idUnidad = $respuesta['idUnidad'];
                    $puntaje = $respuesta['puntaje'];

                    $query = "INSERT into respuestas(id_pregunta, id_alumno, id_unidad_aprendizaje, puntaje, id_encuesta) values($idPregunta,$idAlumnno,$idUnidad, $puntaje, $id_encuesta)";
                    $db->insert($query);
                }
                responder(array("res" => 'ok'));
                break;
            case 'disponibilidad_encuesta': {
                    $idAlumnno = $datos['idAlumno'];
                    $respuestas = $db->array("SELECT * from encuestas where id_alumno = $idAlumnno");
                    responder(array("res" => sizeof($respuestas) == 0));
                    break;
                }
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
