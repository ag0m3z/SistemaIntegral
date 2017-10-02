<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/01/2017
 * Time: 11:04 AM
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

//conectarse a la base de datos general
$sqguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);



if( isset($_POST['NoUsuario']) ) {

    $jsondata = array();
    $NoUsuario = $_POST['NoUsuario'] ;
    $NoUsuarioUM = $_SESSION['data_login']['NoUsuario'] ;
    $FechaDesconexion =  date("Y-m-d H:i:s");
    $FechaActual = date("Ymd");

    $sqguridad->_query = "UPDATE SINTEGRALGNL.BGEConexiones SET Estatus = 'D', NoUsuarioUM = $NoUsuarioUM, FechaDesconexion = '$FechaDesconexion' WHERE NoUsuario = $NoUsuario ";
    $sqguridad->execute_query();

    $sqguridad->_query = "UPDATE SINTEGRALGNL.BGECatalogoUsuarios SET Intentos = 0, NoUsuarioUM = $NoUsuarioUM, FechaUM = '$FechaActual' WHERE NoUsuario = $NoUsuario";
    $sqguridad->execute_query();


    if($_POST['opc'] == 1){
        // se eliminara la session activa
        $sqguridad->_query = "UPDATE SINTEGRALGNL.BGEConexiones SET Estatus = 'D', NoUsuarioUM = $NoUsuarioUM, FechaDesconexion = '$FechaDesconexion' WHERE NoUsuario = $NoUsuario ";
        $sqguridad->execute_query();
        session_unset ();
        session_destroy ();
        session_start();
        session_regenerate_id(true);

    }else if($_POST['opc'] == 2) {
        $jsondata['dexists'] = 'ok';

        //Aunque el content-type no sea un problema en la mayoría de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();
    }
}