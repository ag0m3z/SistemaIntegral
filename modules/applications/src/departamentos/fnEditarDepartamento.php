<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/03/2017
 * Time: 05:12 PM
 */
/**
 * Incluir las Librerias Principales del Sistema
 * En el Siguiente Orden ruta de libreias: @@/SistemaIntegral/core/
 *
 * 1.- core.php;
 * 2.- sesiones.php
 * 3.- seguridad.php o modelo ( ej: model_aparatos.php)
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/model_departamentos.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 *
 * Ejemplo:
 * Si se requiere cambiar de servidor de base de datos
 * $data_server = array(
 *   'bdHost'=>'192.168.2.5',
 *   'bdUser'=>'sa',
 *   'bdPass'=>'pasword',
 *   'port'=>'3306',
 *   'bdData'=>'dataBase'
 *);
 *
 * Si no es requerdio se puede dejar en null
 *
 * con @data_server
 * @@$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos'],$data_server);
 *
 * Sin @data_server
 * @@$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 *
 * @@$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$connect = new \core\model_departamentos($_SESSION['data_login']['BDDatos']);

$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);


/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/

$FechaActual = date("Ymd");
$HoraActual = date("H:i:s");
$NoUsuarioActual = $_SESSION['data_login']['NoUsuario'] ;

$NoDepartamento = $_POST['nodepartamento'];
$idEmpresa = $_POST['idempresa'];
$NoTipo = $_POST['notipo'];
$AsignarReportes = $_POST['asignareportes'];
$NoSucursal = $_POST['nosucursal'];
$NombreDepartamento = $_POST['nombre'];
$Domicilio = $_POST['domicilio'];
$idEstado = $_POST['idestado'];
$idMunicipio = $_POST['idmunicipio'] ;
$Telefono01 = $_POST['telefono01'];
$Telefono02 = $_POST['telefono02'];
$Telefono03 = $_POST['telefono03'];
$Telefono04 = $_POST['telefono04'];
$Correo = $_POST['correo'] ;
$NoZona = $_POST['nozona'] ;
$NoSupervisor = $_POST['nosupervisor'] ;
$idEncargado = $_POST['idencargado'];
$Diagrama = "";
$NoEstatus = $_POST['estatus'];

sleep(1) ;
if($idMunicipio == ""){
    $idMunicipio = 0;
}

$call_sp = "CALL sp00_abc_Departamentos(
                '$NoDepartamento','$idEmpresa',
                '$NoTipo','$AsignarReportes',
                '$NoSucursal','$NombreDepartamento',
                '$Domicilio','$idEstado',
                '$idMunicipio','$Telefono01',
                '$Telefono02','$Telefono03',
                '$Telefono04','$Correo',
                '$NoZona','$NoSupervisor',
                '$idEncargado','$Diagrama',
                '$NoEstatus','$NoUsuarioActual',
                '$FechaActual','$HoraActual',
                2
            )";

$connect->_query = $call_sp;
$connect->get_result_query();

if($_POST['listar'] == 1){
    echo "<script> fnCatListarDepartamentos(7);$('#BtnCloseModalDepartamento').click();</script>";

}else{
    echo "<script>$('#BtnCloseModalDepartamento').click();</script>";

}

