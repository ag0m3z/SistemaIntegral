<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/02/2017
 * Time: 05:28 PM
 */
/**
 * Incluir las Librerias Principales del Sistema
 * En el Siguiente Orden
 * ruta de libreias: @@/SistemaIntegral/core/
 *
 * 1.- core.php;
 * 2.- sesiones.php
 * 3.- seguridad.php
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/model_tickets.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$Tickets = new \core\model_tickets($_SESSION['data_login']['BDDatos']);

$Tickets->valida_session_id($_SESSION['data_login']['NoUsuario']);


//Reasignacion de Ticket

if($_POST['UsuarioAsignado']){


$Tickets->reasignar_ticket($_POST['UsuarioAsignado'],$_POST['folio'],$_POST['anio'],$_POST['dpto']);

if($Tickets->_confirm){
    echo "<script language='JavaScript'>CloseModalAndReload();</script>";

}


}