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
header("ContentType:application/json");


if($_SERVER['REQUEST_METHOD']=="POST"){


    if(array_key_exists('NoUsuarioRecive',$_POST)){

        $_POST = $connect->get_sanatiza($_POST);

        $NoUsuarioRecive = $_POST['NoUsuarioRecive'];
        $NoUsuario = $_SESSION['data_login']['NoUsuario'];
        $Mensaje = $_POST['Mensaje'];

        $TipoMensaje = $_POST['TipoMensaje'];
        $RutaImagen = $_POST['RutaImagen'];
        $NombreImagen = $_POST['NombreImagen'];
        $NombreUnico = $_POST['NombreUnico'];

        $connect->_query = "INSERT INTO SINTEGRALPRD.BGEMensajes VALUES (null,'$TipoMensaje','$NoUsuario','$NoUsuarioRecive','$Mensaje','$RutaImagen','$NombreImagen','$NombreUnico',1,now())";
        $connect->execute_query();

        $connect->_query = "SELECT MAX(Fecha) FROM SINTEGRALPRD.BGEMensajes WHERE NoUsuarioRecive = $NoUsuarioRecive";
        $connect->get_result_query();
        $FechaUltimoMensaje = $connect->_rows[0][0];

        echo json_encode(array("result"=>true,"message"=>"Todo correcto","data"=>array('nombre'=>$_SESSION['data_login']['NombreDePila'],"hora"=>date('H:i:s'),"mensaje"=>$Mensaje,"img"=>$_SESSION['data_login']['imagen_profile'],"fechaultimomensaje"=>$FechaUltimoMensaje)));

    }else{
        echo json_encode(array("result"=>false,"message"=>"No se encontraron las llaves para el registro"));
    }


}else{
    echo json_encode(array("result"=>false,"message"=>"Metodo no soportado"));
}

