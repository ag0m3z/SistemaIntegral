<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 18/04/2017
 * Time: 10:34 AM
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

$_SESSION['menu_opciones'][1][1][1][0]['OpcionA'];
$_SESSION['menu_opciones'][1][1][1][0]['OpcionB'];
$_SESSION['menu_opciones'][1][1][1][0]['OpcionC'];
$_SESSION['menu_opciones'][1][1][1][0]['OpcionV'];
$_SESSION['menu_opciones'][1][1][1][0]['OpcionR'];

if($_SESSION['menu_opciones'][1][1][1][0]['OpcionA'] == 1){

    $btnNuevo = '<button class="btn-primary btn-xs" onclick="fn_cat_nuevo_medio_contacto(1)" ><i class="fa fa-file"></i> Nuevo </button>';

}


?>
<script>
    $('button').addClass("btn waves-effect");

    fn_cat_medio_contacto_lista(1);

</script>
<div class="panel panel-info">
    <div class="panel-heading"  style="padding: 3px;"><i class="fa fa-phone"></i> Catálogo medios de contacto</div>
    <div class="toolbars">
        <button class="btn-primary btn-xs" data-placement="top" data-toggle="tooltip" title="Regresar a Catalogos" onclick="fnsdMenu(7,null)"><i class="fa fa-arrow-circle-left"></i> Regresar</button>
        <button id="btnHome" data-placement="top" data-toggle="tooltip" title="Actualizar" class="btn-primary btn-xs"  onclick="sdMenuCatalogos(3)"><i class="fa fa-refresh"></i></button>
        <?=$btnNuevo?>
        <div class="btn-group">
            <button class="btn-primary btn-xs dropdown-toggle"  data-toggle="dropdown"><i class="fa fa-filter"></i> Filtrar <i class="caret"></i></button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:void(0)" onclick="fn_cat_medio_contacto_lista(1)">  Solo activos</a></li>
                <li><a href="javascript:void(0)" onclick="fn_cat_medio_contacto_lista(2)">  Solo inactivos</a></li>
                <li><a href="javascript:void(0)" onclick="fn_cat_medio_contacto_lista(3)">  Todos</a></li>
                <li><a href="javascript:void(0)" onclick="fn_cat_medio_contacto_lista(4)">  Ultimos 10 Registrados</a></li>
                <li><a href="javascript:void(0)" onclick="fn_cat_medio_contacto_lista(5)">  Ultimos 10 Actualizados</a></li>
            </ul>
        </div>
        <button class="btn-default hidden btn-xs" onclick="$('#ModalSearch').modal('toggle')"><i class="fa fa-search"></i> Busqueda</button>
    </div>
    <div class="panel-body no-padding">
        <div id="lListTable">
            <div id="myGrid" style="height: 75vh;"></div>
        </div>
    </div>
</div>

<div id="ShowModal"></div>