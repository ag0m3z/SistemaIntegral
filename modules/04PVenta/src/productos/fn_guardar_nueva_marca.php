<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 19/05/2017
 * Time: 11:57 AM
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
include "../../../../core/model_aparatos.php";

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

$connect = new \core\model_aparatos($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

//Sanatizar Datos

$Descripcion = $connect->get_sanatiza($_POST['descripcion']);
$NoCategoria = $_POST['nocategoria'];
$Abreviacion = $connect->get_sanatiza($_POST['abreviacion']);

$NoUsuarioAlta = $_SESSION['data_login']['NoUsuario'];
$FechaAlta = date("Ymd");
$HoraAlta = date("H:i:s");

$idProducto = $connect->set_nueva_marca(
  array(
      "nocategoria"=>$NoCategoria,
      'descripcion'=>$Descripcion,
      'abreviacion'=>$Abreviacion,
      'NoUsuarioAlta'=>$NoUsuarioAlta,
      'FechaAlta'=>$FechaAlta,
      'HoraAlta'=>$HoraAlta
  )
);

if($connect->_confirm){

    //Registrar en SQLServer[SAyT][BDAPARATOS]
    include "../../../../core/sqlconnect.php";
    $SQlServer = new \core\sqlconnect();

    $SQlServer->_sqlQuery = "EXECUTE BDSPAPARATOS.dbo.sp_RegistraNuevaMarca '$NoCategoria','$Descripcion','$FechaAlta','$FechaAlta'";
    $SQlServer->execute_query();

    echo "<script>AddOptionSelect('#id_marca','$idProducto','$Descripcion');$('#btnCloseModal').click();</script>";




}else{
    \core\core::MyAlert($connect->_message,"error");
}