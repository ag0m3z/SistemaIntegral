<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 11/04/2017
 * Time: 11:31 AM
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
include "../../../../core/model_medio_contacto.php";

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

$medios = new \core\model_medio_contacto($_SESSION['data_login']['BDDatos']);
$medios->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

if(array_key_exists('idMedioContacto',$_POST) || array_key_exists('nombre_medio_contacto',$_POST)){

    $idMedioContacto = $medios->get_sanatiza($_POST['idMedioContacto']);
    $NombreMedio = $medios->get_sanatiza($_POST['nombre_medio_contacto']);
    $NoEstatus = $medios->get_sanatiza($_POST['NoEstado']);


    $medios->edit(array(
        'idMedioContacto'=>$idMedioContacto,
        'nombre_medio_contacto'=>$NombreMedio,
        'NoEstatus'=>$NoEstatus
    ));

    if($medios->_confirm){

        echo "<script>fn_cat_medio_contacto_lista(5);$('#modalbtnclose').click();</script>";

    }else{
        \core\core::MyAlert("Error al actualizar el medio de contacto","error");
    }


}else{
    \core\core::MyAlert("No existe el medio de contacto solicitada","error");
}