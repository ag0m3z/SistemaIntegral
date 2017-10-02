<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/02/2017
 * Time: 11:43 AM
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


$FechaActual = date("Ymd");
$HoraActual = date("H:i:s");
$UsuarioRecibe = $_SESSION['data_login']['NoUsuario'];
$PerfilUser = $_SESSION['data_login']['NoPerfil'];

//Sanatizar Datos
$MotivoEntrega = $Equipos->get_sanatiza($_POST['motivoentrega2']);
$CondicionesEntrega = $Equipos->get_sanatiza($_POST['condicionEntrega2']);
$Folio = $_POST['folio'];
$usuario = $Equipos->get_sanatiza($_POST['usuarioequipo']);
$password = $Equipos->get_sanatiza($_POST['contrasenaequipo']);


$Equipos->_query =
    "
    UPDATE 
      BSHInventarioEquipos 
        SET 
          MotivoEntrega = '".$MotivoEntrega."' ,
          CondicionesEntrega = '".$CondicionesEntrega."',
          FechaEntrega = '".$FechaActual."',
          HoraEntrega = '".$HoraActual."',
          UsuarioRecibeEntrega = $UsuarioRecibe, 
          UsuarioEquipo = '".$usuario."', 
          ContrasenaEquipo= '".$password."' ,
          Estatus = 2 
      WHERE 
          Folio = '".$Folio."'
    ";

$Equipos->execute_query();
$Equipos->_query = "CALL sp_seguimiento_equipos('$Folio','Entrega de Equipo','C','2','$UsuarioRecibe','$FechaActual','$HoraActual')";
$Equipos->execute_query();

header("Content-type:application/json");

echo json_encode(array("result"=>"ok"));



