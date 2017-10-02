<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 09/02/2017
 * Time: 10:06 AM
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

// Validar Sesion iniciada y activa
$Tickets->valida_session_id($_SESSION['data_login']['NoUsuario']);

sleep(1);

$FechaActual = date("Ymd");

//Sanatizar Datos
$Reporte = $Tickets->get_sanatiza($_POST['reporte']);
$DescripcionReporte = $Tickets->get_sanatiza($_POST['desc']);

//$Reporte = substr(strip_tags($Reporte), 0, 65);

if($_POST['param'] == 2){
    //Registro de Ticket a Nivel Solicitante

    $Tickets->set_registra_ticket(
        array(
            'solicitante'=>$_POST['request'],
            'prioridad'=>1,
            'NoSucursal'=>$_POST['suc'],
            'NoEstado'=>1,
            'medio_contacto'=>3,
            'NoArea'=>1,
            'NoCategoria'=>4,
            'NoUsuarioAsignado'=>0,
            'tipo_servicio'=>1,
            'descripcion_reporte'=>$DescripcionReporte,
            'reporte'=>$Reporte,
            'param_registra'=>$_POST['dptoasignen'],
            'FechaAlta'=>$FechaActual,
            'tipo_registro'=>$_POST['param']
        )
    );

    /*$obj->RegistraTicket($_POST['request'],1,$_POST['suc'],1,3,1,4,
        0,1,$_POST['desc'],$_POST['reporte'],$_POST['dptoasignen'],$FechaActual,$_POST['param']);*/

}else{
    //Registro de Ticket a Nivel de Tecnico

    $Tickets->set_registra_ticket(
        array(
            'solicitante'=>$_POST['request'],
            'prioridad'=>$_POST['priority'],
            'NoSucursal'=>$_POST['suc'],
            'NoEstado'=>$_POST['status'],
            'medio_contacto'=>$_POST['contac'],
            'NoArea'=>$_POST['sarea'],
            'NoCategoria'=>$_POST['scatego'],
            'NoUsuarioAsignado'=>$_POST['uasig'],
            'tipo_servicio'=>$_POST['tposerv'],
            'descripcion_reporte'=>$DescripcionReporte,
            'reporte'=>$Reporte,
            'param_registra'=>0,
            'FechaAlta'=>$_POST['fcha'],
            'tipo_registro'=>1
        )
    );

    /*$obj->RegistraTicket(
        $_POST['request'],
        $_POST['priority'],
        $_POST['suc'],
        $_POST['status'],
        $_POST['contac'],
        $_POST['sarea'],
        $_POST['scatego'],
        $_POST['uasig'],
        $_POST['tposerv'],
        $_POST['desc'],
        $_POST['reporte'],
        0,
        $_POST['fcha'],
        1
    );*/
}