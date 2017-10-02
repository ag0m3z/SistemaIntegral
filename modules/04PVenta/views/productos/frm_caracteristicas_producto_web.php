<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 26/04/2017
 * Time: 01:14 PM
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

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsPVenta.js"></script>
<script>
    fn_caracteristicas_productos_web_listar(1);
</script>
<div class="panel panel-info">
    <div class="panel-heading"><i class="fa fa-list-ul"></i> Caracteristicas </div>
    <div class="toolbars">
        <button class="btn btn-primary btn-xs" onclick="fnsdMenu(27,27)" ><i class="fa fa-refresh"></i> Lista</button>
        <button class="btn btn-primary btn-xs" onclick="fn_caracteristicas_producto_web(1,0)" ><i class="fa fa-file"></i> Nueva</button>
        <button class="btn hidden btn-primary btn-xs" > Filtrar</button>
        <button class="btn hidden btn-default btn-xs"><i class="fa fa-search"></i> Buscar</button>
        <button class="btn hidden btn-default btn-xs"> Exportar</button>
    </div>
    <div class="panel-body no-padding">
        <div id="lListarTabla">
            <div id="myGrid" style="height: 80vh;font-size: 12px;">

            </div>
        </div>
    </div>
</div>
