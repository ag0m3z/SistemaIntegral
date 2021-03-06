<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 26/05/2017
 * Time: 04:36 PM
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
include "../../controller/ControllerCatalogoUbicaciones.php";

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

$connect = new ControllerCatalogoUbicaciones($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);




//Validar Nombre,contacto y telefono
if(
    array_key_exists('idubicacion',$_POST) &&
    array_key_exists('nombre',$_POST)


){
    //Sanatizar Datos
    $_POST = $connect->get_sanatiza($_POST);

    $connect->edit_ubicacion(array(
        'idtabla'=>2,
        'idubicacion'=>$_POST['idubicacion'],
        "nombre"=>$_POST['nombre'],
        "descripcion"=>$_POST['descripcion'],
        "idestatus"=>$_POST['idestatus'],
        "NoUsuarioAlta"=>$_SESSION['data_login']['NoUsuario'],
        "FechaAlta"=>date("Y-m-d H:i:s")
    ));


    if($connect->_confirm){

        //Todo correcto

        echo "<script>getMessageNotify('','Ubicación Editada correctamente','success',3000);fn07ListarUbicaciones(5,1);$('#btnCloseModalEditarUbicacion').click();</script>";


    }else{
        \core\core::MyAlert($connect->_message,"error");
    }

}else{
    \core\core::MyAlert("error en los datos para el registro","error");
}