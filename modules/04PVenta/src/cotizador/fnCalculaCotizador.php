<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 20/09/2017
 * Time: 06:02 PM
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

header("ContentType:application/json");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $_POST['precioPEX'] = round($_POST['precioPEX'] ,-1);

    $_POST['claseae'] = round($_POST['precioPEX'] * $_POST['claseae'],-1);
    $_POST['clasebe'] = round($_POST['precioPEX'] * $_POST['clasebe'],-1);
    $_POST['clasece'] = round($_POST['precioPEX'] * $_POST['clasece'],-1);

    $_POST['claseac'] = round($_POST['precioPEX'] * $_POST['claseac'],-1);
    $_POST['clasebc'] = round($_POST['precioPEX'] * $_POST['clasebc'],-1);
    $_POST['clasecc'] = round($_POST['precioPEX'] * $_POST['clasecc'],-1);

    echo json_encode(array("result"=>true,"message"=>"exitoso","data"=>array(
        "precioPex"=>$_POST['precioPEX'],
        "claseae"=>$_POST['claseae'],
        "clasebe"=>$_POST['clasebe'],
        "clasece"=>$_POST['clasece'],
        "claseac"=>$_POST['claseac'],
        "clasebc"=>$_POST['clasebc'],
        "clasecc"=>$_POST['clasecc']
    )));



}else{
    echo json_encode(array("result"=>false,"message"=>"Error, metodo no soportado","data"=>array()));

}