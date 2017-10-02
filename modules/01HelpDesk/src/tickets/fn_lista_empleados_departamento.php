<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 08/02/2017
 * Time: 05:23 PM
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
include "../../../../core/seguridad.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);

//validar si existe una sesion
$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**
 * Funcion para cargar un select con la lista de los Empleados
 */
$AnioActual = date("Y");

//$Encargado = $connect->getNombreEncargada($_POST['nosuc'],true);

$seguridad->_query = "SELECT idEmpleado,CONCAT_WS(' ',Nombre,ApPaterno,ApMaterno)as NombreEmpleado FROM SINTEGRALGNL.BGEEmpleados WHERE NoDepartamento = '".$_POST['nosuc']."' AND NoEstado = 1 ORDER BY Nombre ASC ";

$seguridad->get_result_query();

$data_empleados = $seguridad->_rows;



?>

    <option value="0">-- --</option>
    <?php
    for($i = 0 ;$i < count($data_empleados) ; $i++){

        echo  "<option value='".$data_empleados[$i]['idEmpleado']."' > ".$data_empleados[$i]['NombreEmpleado']."</option>";
    }
    ?>


