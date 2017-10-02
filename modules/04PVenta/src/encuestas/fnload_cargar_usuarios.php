<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/03/2017
 * Time: 11:04 AM
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

$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);


/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$connect->_query = "SELECT NoUsuario,NombreDePila FROM SINTEGRALGNL.BGECatalogoUsuarios WHERE NoDepartamento = ".$_POST['nosuc']." ORDER BY NombreDePila ASC";
$connect->get_result_query();
$ListUsers = $connect->_rows;
?>
<select id="filUser" class="formInput">
    <option value="0">Todos</option>
    <?php
    if($_POST['nosuc'] != 0){
        for($i=0;$i < count($ListUsers);$i++){
            echo "<option value='".$ListUsers[$i][0]."'>".$ListUsers[$i][1]."</option>";
        }
    }
    ?>
</select>