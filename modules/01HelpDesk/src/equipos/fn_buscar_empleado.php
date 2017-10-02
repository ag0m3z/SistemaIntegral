<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 21/06/2017
 * Time: 10:25 AM
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
include "../../../../core/seguridad.php";

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

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$TextSearch  =$_GET['term'];

$connect->_query = "SELECT idEmpleado,concat(Nombre,' ',ApPaterno,' ',ApMaterno)as NombreEmpleado,Nombre FROM SINTEGRALGNL.BGEEmpleados WHERE concat(Nombre,' ',ApPaterno,' ',ApMaterno) like '%$TextSearch%' AND NoEstado = 1 ORDER BY Nombre ASC";
$connect->get_result_query();

$lista = $connect->_rows;

if(count($lista) > 0 ){
    for($i=0;$i < count($connect->_rows);$i++){

        $data[] = array(
            "tag_value"=>$lista[$i][1],"tag_id"=>$lista[$i][0]
        );

    }
}else{
    $data = array(

    );
}

echo json_encode($data);