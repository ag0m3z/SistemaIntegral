<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 29/06/2017
 * Time: 12:29 PM
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
include "../../../../core/model_tickets.php";

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

$connect = new \core\model_tickets($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

header("Content-type:application/json chartset-utf8");


if(
    array_key_exists('nombre_firma',$_POST) &&
    array_key_exists('anio',$_POST) &&
    array_key_exists('folio',$_POST) &&
    array_key_exists('NoDepartamento',$_POST) &&
    array_key_exists('imagen',$_POST) &&
    !empty($_POST['nombre_firma'])
){
    $FechaActual = date("Y-m-d H:i:s");
    $NoUsuario = $_SESSION['data_login']['NoUsuario'];

    //Sanatizar Datos
    $Folio = base64_decode($_POST['folio']);
    $Anio = base64_decode($_POST['anio']);
    $NoDepartamento = base64_decode($_POST['NoDepartamento']);
    $NombreFirma = $connect->get_sanatiza($_POST['nombre_firma']);
    $Imagen = $_POST['imagen'];


    $connect->setAgregarFirma(array(
       'nombre_firma'=>$NombreFirma,
        'imagen'=>$Imagen,
        'folio'=>$Folio,
        'anio'=>$Anio,
        'NoDepartamento'=>$NoDepartamento,
        'NoUsuarioFirma'=>$NoUsuario,
        'FechaFirma'=>$FechaActual

    ));

    if($connect->_confirm){
        echo json_encode(array("result"=>"ok","mensaje"=>"Firma guardada correctamente","data"=>$connect->_query));

    }else{
        echo json_encode(array("result"=>"error","mensaje"=>$connect->_message,"data"=>$connect->_query));

    }


}else{
        echo json_encode(array("result"=>"error","mensaje"=>"Error no se encontraron los datos enviados","data"=>$connect->_query));
}


