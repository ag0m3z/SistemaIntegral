<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 04/04/2017
 * Time: 12:43 PM
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
include "../../../../core/model_puestos.php";

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

$connect = new \core\model_puestos($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

// Sanatizar Datos para la Consulta SQL
$idPuesto = $_POST['idPuesto'];
$Nombre = $connect->get_sanatiza($_POST['nomnbre_puesto']);
$Descripcion = $connect->get_sanatiza($_POST['descripcion_puesto']);


// registrar el Puesto
$connect->edit(
    array(
        "idpuesto"=>$idPuesto,
        "nombre_puesto"=>$Nombre,
        "descripcion_puesto"=>$Descripcion
    )
);


if($connect->_confirm){
    // puesto registrado correctamente;
    echo "<script> fnCatListarPuestos(1);$('#modalbtnclose').click();</script>";

}