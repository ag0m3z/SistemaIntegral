<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 21/07/16
 * Time: 02:01 PM
 */

include "../../../../core/core.php";
include "../../../../core/contenido.php";

$connect = new \core\contenido('SINTEGRALPRD');


echo "<script language='JavaScript'>$('#btnsave').attr('disabled','true');</script>";

$FechaActual = date("Ymd");
$HoraActual = date("H:i:s");


if($_POST['ani'] == "" ||$_POST['dpt'] == "" ||$_POST['tic'] == "" ){
    echo "No se encontraron los Parametros del Ticket";
}

$connect->_query = "SELECT MAX(idFolioEncuesta) FROM BGEEncuestaServicios";
$connect->get_result_query();
$qUltimoFolio = $connect->_rows[0][0];

$IdEncuesta = $_POST['idEnc'];
if( $qUltimoFolio >= 1 ){
    //Se Encontraron Registros
    $FolioEncuesta = $qUltimoFolio + 1;
}else{
    //No Hay Registros
    $FolioEncuesta = 1 ;
}
$NoComent = "";

$FolioEncuesta;

$urlRedirect = \core\core::ROOT_APP."modules/Surveys/views/";
//$urlRedirect = "http://localhost/SisHelpDesk/surveys/";

$connect->_query = "SELECT idPregunta FROM BGECatalogoPreguntas where NoEncuesta = ".$IdEncuesta." ";
$connect->get_result_query();

$rTtotalPreguntas = $connect->_rows;
$cantidadPreguntas = count($connect->_rows);

$i=0;
for($c = 0 ; $c < count($rTtotalPreguntas);$c++){

    $i++;

    $idPregunta = $rTtotalPreguntas[$c][0];

    switch($i){
        case 1:
            $connect->_query = "INSERT INTO BGEEncuestaServicios VALUES (".$_POST['ani'].",'".$_POST['dpt']."',".$_POST['tic'].",".$IdEncuesta.",$FolioEncuesta,".$idPregunta.",".$_POST['qest1'].",
'".$_POST['otr']."','$FechaActual','$HoraActual')";
            $connect->execute_query();
            break;
        case 2:
            $connect->_query = "INSERT INTO BGEEncuestaServicios VALUES (".$_POST['ani'].",'".$_POST['dpt']."',".$_POST['tic'].",".$IdEncuesta.",$FolioEncuesta,".$idPregunta.",".$_POST['qest2'].",
'".$_POST['otr']."','$FechaActual','$HoraActual')";
            $connect->execute_query();
            break;

        case 3:
            $connect->_query = "INSERT INTO BGEEncuestaServicios VALUES (".$_POST['ani'].",'".$_POST['dpt']."',".$_POST['tic'].",".$IdEncuesta.",$FolioEncuesta,".$idPregunta.",".$_POST['qest3'].",
'".$NoComent."','$FechaActual','$HoraActual')";
            $connect->execute_query();
            break;

        case 4:
            $connect->_query = "INSERT INTO BGEEncuestaServicios VALUES (".$_POST['ani'].",'".$_POST['dpt']."',".$_POST['tic'].",".$IdEncuesta.",$FolioEncuesta,".$idPregunta.",".$_POST['qest4'].",
'".$NoComent."','$FechaActual','$HoraActual')" ;
            $connect->execute_query();
            break;

        case 5:
            $connect->_query = "INSERT INTO BGEEncuestaServicios VALUES (".$_POST['ani'].",'".$_POST['dpt']."',".$_POST['tic'].",".$IdEncuesta.",$FolioEncuesta,".$idPregunta.",".$_POST['qest5'].",
'".$NoComent."','$FechaActual','$HoraActual') ";
            $connect->execute_query();
            break;
    }
}

$connect->_query = "INSERT INTO BGEEncuestaServicios VALUES (".$_POST['ani'].",'".$_POST['dpt']."',".$_POST['tic'].",".$IdEncuesta.",$FolioEncuesta,6,99,
'".$_POST['coment']."','$FechaActual','$HoraActual')" ;
$connect->execute_query();

echo "<script language='JavaScript'>location.href = '".$urlRedirect."?ref=".md5('savesuccesfull')."';</script>";












