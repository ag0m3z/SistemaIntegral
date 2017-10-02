<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 27/09/2017
 * Time: 03:18 PM
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/mensajeria.php";

$connect = new \core\mensajeria($_SESSION['data_lagin']['BDDatos']);
$NoUsuario = $_SESSION['data_login']['NoUsuario'];

header("ContentType:application/json");


if($_SERVER['REQUEST_METHOD']=="GET"){
    $loopStart = time();
    $updateAt = 1;
    $loopSeconds = 8;

    if(isset($_POST["timestamp"]))
    {
        $timestamp = $_POST["timestamp"];
    }
    else
    {
        $connect->_query = "SELECT NOW() AS now";
        $connect->get_result_query();
        $timestamp = $connect->_rows[0][0];
    }

    $timestamp = date($timestamp, time() + $updateAt);
    $connect->_query = "SELECT count(NoEstatus) FROM SINTEGRALPRD.BGENotificacionMensaje WHERE NoEstatus = 1 AND NoUsuarioRecibe = '$NoUsuario' ORDER BY idNotificacion";

    $newMessages = false;
    $notificaciones = array();

    while(!$newMessages && (time() - $loopStart) < $loopSeconds)
    {
        $connect->get_result_query();
        $DataNotif = $connect->_rows;
        $notificaciones[] =$DataNotif[0][0];
        $newMessages = true;
        sleep($updateAt);

        /*for($i=0;$i<count($DataNotif);$i++){
            $notificaciones[] ='OK';
            $newMessages = true;
            sleep($updateAt);
        }*/
    }

    $connect->_query = "SELECT NOW() AS now";
    $connect->get_result_query();
    $timestamp = $connect->_rows[0][0];

    $data = array("notificaciones" => $notificaciones, "timestamp" => $timestamp);
    echo json_encode($data);
    exit;


}else{
    echo json_encode(array("result"=>false,"message"=>"Metodo no soportado"));
}

