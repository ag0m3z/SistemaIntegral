<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 05/04/2017
 * Time: 12:44 PM
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

$medios->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


//Validar que los datos no vengan vacios

if($_POST['nombre_medio_contacto'] == "" ){

    \core\core::MyAlert("Ingrese el nombre del medio de contacto","alert");
}else{

    //Sanatizar Datos
    $NombreMedioContacto = $medios->get_sanatiza($_POST['nombre_medio_contacto']);

    $medios->set(
        array(
            "nombre_medio_contacto"=>$NombreMedioContacto,
        )
    );

    if($medios->_confirm){
        echo "<script>$('#modalbtnclose').click();fn_cat_medio_contacto_lista(4);</script>";
    }else{
        \core\core::MyAlert($medios->_message,"alert");
    }


}
