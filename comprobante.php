<?php

require 'db.php';
session_start();


function filter_html($value){
    $value = mb_convert_encoding($value, 'ISO-8859-1', 'UTF-8');
    return $value;
}

if (isset($_SESSION['login'])) {
    if ($_SESSION['tipo_usuario'] != "ALUMNO") {
        header("Location: logout.php");
    }
}

$db = new Db();
$id_usuario = $_SESSION['id_usuario'];
$alumno = $db->row("SELECT * from alumnos where id_usuario = $id_usuario");
$id_alumno = $alumno['id_alumno'];
$id_semestre = $alumno['id_semestre'];
$id_programa_academico = $alumno['id_programa_academico'];
$id_grupo = $alumno['id_grupo'];

$semestre = $db->row("SELECT nombre from semestres where id_semestre = $id_semestre")['nombre'];
$grupo = $db->row("SELECT nombre from grupos where id_grupo = $id_grupo")['nombre'];
$programa_academico = filter_html($db->row("SELECT nombre from programas_academicos where id_programa_academico = $id_programa_academico")['nombre']);

$encuesta = $db->row("SELECT encuestas.* from encuestas natural join alumnos where id_alumno = $id_alumno");
$id_encuesta = $encuesta['id_encuesta'];


$preguntas = $db->array("SELECT * from preguntas");



ob_end_clean();
require('fpdf/fpdf.php');

$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 18);


$pdf->Cell(0, 20, 'Comprobante de encuesta realizada');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(10, 20, filter_html($alumno['nombre']." ".$alumno['apellido_paterno']." ".$alumno['apellido_materno']));
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(10, 20, "Semestre: $semestre");
$pdf->Ln(10);
$pdf->Cell(10, 20, filter_html("Grupo: $grupo"));
$pdf->Ln(10);
$pdf->Cell(10, 20, filter_html("Programa académico: $programa_academico"));
$pdf->Ln(15);
$pdf->Cell(10, 20, filter_html("Fecha realización: ".$encuesta['fecha']));

$pdf->Ln(20);
$pdf->Cell(10, 20, "Resumen respuestas");
$unidades = $db->array("SELECT * from unidades_aprendizaje");
foreach ($preguntas as $pregunta) {
    $id_pregunta = $pregunta['id_pregunta'];
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'b', 12);
    $pdf->Cell(10, 20, filter_html($pregunta['pregunta']));
    $pdf->Ln(10);
    foreach ($unidades as $unidad) {
        $id_unidad_aprendizaje = $unidad['id_unidad_aprendizaje'];
        $pdf->SetFont('Arial', '', 10);
        
        $respuesta = $db->row("SELECT * from respuestas where id_encuesta = $id_encuesta and id_pregunta = $id_pregunta and id_unidad_aprendizaje = $id_unidad_aprendizaje");
        $pdf->Cell(10, 20, filter_html($unidad['nombre']." - ".$respuesta['puntaje']));
        $pdf->Ln(10);
    }
    

   
    $pdf->Ln(10);
}

$pdf->Output();


