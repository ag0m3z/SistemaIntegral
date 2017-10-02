<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/02/2017
 * Time: 04:55 PM
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

//Datos del Servidor
$FechaActual = date("Ymd");
$HoraActual = date("H:i:s");
$NoUsuario = $_SESSION['data_login']['NoUsuario'];
$PerfilUsuario = $_SESSION['data_login']['NoPerfil'];

//recibiendo parametros de Ajax
$Folio = $_POST['fl'];
$Anio = $_POST['an'];
$NoDepartamento = $_POST['nodpto'];
$NoSucursal = $_POST['sucursal'];
$Solicitante = $_POST['solicita'];
$MedioContacto = $_POST['mcontacto'];
$NoArea = $_POST['area'];
$NoCategoria = $_POST['categoria'];
$DescripcionCorta = $Tickets->get_sanatiza($_POST['desc01']);
$DescripcionDetallada = $Tickets->get_sanatiza($_POST['desc02']);
$FechaAlta = $Tickets->getFormatFecha($_POST['falta'],1);
$NoPrioridad = $_POST['prioridad'];
$Estatus = $_POST['estado'];
$FechaPromesa = $Tickets->getFormatFecha($_POST['fchpromesa'],1);
$TipoAtencion = $_POST['tpatencion'];
$NoUsuarioAsignado = $_POST['usuario'];
$SeguimientoTicket = 'Edición de Ticket';

$update = "CALL sp_editar_ticket('$Anio','$Anio','$Folio','$NoDepartamento','$FechaActual','$HoraActual',
    '$Solicitante',
    '$DescripcionCorta',
    '$NoSucursal',
    '$NoArea',
    '$NoCategoria',
    '$NoPrioridad',
    '$TipoAtencion',
    '$DescripcionDetallada',
    '$Estatus',
    '$NoUsuario',
    '3',
    '$FechaPromesa',
    '$SeguimientoTicket',
    '$FechaAlta',
    '$MedioContacto',
    '$PerfilUsuario',
    '$NoUsuarioAsignado'
)";

$Tickets->_query = $update;

$Tickets->get_result_query();

//\core\core::MyAlert("Datos guardados correctamente","ok");
echo "<script language='JavaScript'>CloseModalAndReload();</script>";