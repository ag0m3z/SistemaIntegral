<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 14/02/2017
 * Time: 03:32 PM
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

$Seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$Seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);



//Revisar si el numero de Segmento pertenece a una sucursal.

$Seguridad->_query = "SELECT Diagrama FROM BGECatalogoDepartamentos WHERE NoDepartamento = '".$_POST['suc']."' LIMIT 1";
$Seguridad->get_result_query();


?>
    <div style="margin:20px;margin-left:25%;padding: 0px;width: 50%;">
        <h4>No Se Encontro el Diagrama para Esta Sucursal</h4>
        <a href="#" class="btn btn-danger active" data-dismiss="modal">Cerrar</a>
    </div>
<?php