<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/02/2017
 * Time: 10:58 AM
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
include "../../../../core/model_equipos.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$Equipos = new \core\model_equipos($_SESSION['data_login']['BDDatos']);

$Equipos->valida_session_id($_SESSION['data_login']['NoUsuario']);

$NoUsuarioAlta = $_SESSION['data_login']['NoUsuario'];
$_POST = $Equipos->get_sanatiza($_POST);


$Folio =$_POST['folio'];
$MotivoAsignacion = $_POST['motivo_asignacion'];
$CaracteristicasEquipo = $_POST['caracteristica_equipo'];
$FechaActual = date("Ymd");
$HoraActual = date("H:i:s");

$Equipos->_query = "UPDATE BSHInventarioEquipos SET Estatus = 1,MotivoAsignacion = '$MotivoAsignacion', Caracteristicas ='$CaracteristicasEquipo',FechaAsignacion = '$FechaActual'  WHERE Folio = '".$Folio."' ";
$Equipos->execute_query();

$Equipos->_query = "call sp_seguimiento_equipos(
'$Folio',
'Asignación de equipo',
'S',
'1',
'$NoUsuarioAlta',
'$FechaActual',
'$HoraActual'
)";
$Equipos->execute_query();

header("Content-type:application/json");

echo json_encode(array("result"=>"ok"));