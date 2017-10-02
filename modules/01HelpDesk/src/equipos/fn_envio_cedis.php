<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/02/2017
 * Time: 11:57 AM
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

//Enviar Equipo a Cedos
$FechaActual = date("Ymd");
$HoraActual = date("H:i:s");
$UsuarioRecibe = $_SESSION['data_login']['NoUsuario'];
$PerfilUser = $_SESSION['data_login']['NoPerfil'];

$MotivoEntrega = $Equipos->get_sanatiza($_POST['motivoentrega2']);
$CondicionesEntrega = $Equipos->get_sanatiza($_POST['condicionEntrega2']);

$Folio = $_POST['folio'];


$Equipos->_query = "UPDATE BSHInventarioEquipos 
                      SET  
                        MotivoEntrega = '".$MotivoEntrega."' ,
                        CondicionesEntrega = '".$CondicionesEntrega."', 
                        FechaEnvio = '".$FechaActual."', 
                        HoraEnvio = '".$HoraActual."', 
                        UsuarioEnvia = $UsuarioRecibe, 
                        Estatus= 3 
                  WHERE Folio = $Folio";
$Equipos->execute_query();

//Registrar Seguimiento
$Equipos->_query = "CALL sp_seguimiento_equipos('$Folio','Envío de equipo a cedis','C','3','$UsuarioRecibe','$FechaActual','$HoraActual')";
$Equipos->execute_query();


header("Content-type:application/json");

echo json_encode(array("result"=>"ok"));
