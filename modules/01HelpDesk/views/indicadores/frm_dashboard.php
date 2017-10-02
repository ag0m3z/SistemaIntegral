<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/04/2017
 * Time: 11:06 AM
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
?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsServiceDesk.js"></script>
<script language="JavaScript">
    $(".panel-heading").css("padding","3px");
    $(":button").addClass("btn btn-default btn-xs");
    fnsd_cargar_graficas(1,0);
</script>
<div class="box box-success"  id="ContentHomeSDManagerBox">
    <div class="box-header" style="padding: 0px;">
        <div class="toolbart" style="padding: 2px;background: #f4f4f5">
<!--            <button data-toggle="tooltip" onclick="window.open('frm_dashboard.php');" data-placement="bottom" title="En Otra Ventana" ><i class="fa fa-clone"></i></button>-->
            <button data-toggle="tooltip" onclick="requestFullScreen()" data-placement="right" title="Pantalla Completa"><i class="fa fa-arrows-alt"></i> </button>
            <button data-toggle="tooltip" onclick="fnsdMenu(1,1)"  data-placement="right" ><i class="fa fa-refresh"></i> </button>
        </div>
    </div>
    <div class="box-body" style="padding-bottom:0.5vh">
        <div id="ContentHomeSDManager">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="fa fa-area-chart"> Indicador por d&iacute;a</i>
                            <span class="pull-right">
                                <button><i class="fa fa-bar-chart"></i></button>
                                <button><i class="fa fa-list"></i></button>
                            </span>
                        </div>
                        <div class="panel-body" style="padding: 0px;">
                            <div id="tabs1" style="padding: 0px">
                                <p id="imgLoad1" class="text-center">cargando.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="fa fa-area-chart"> Indicador por mes</i>
                            <span class="pull-right">
                                <button><i class="fa fa-bar-chart"></i></button>
                                <button><i class="fa fa-list"></i></button>
                            </span>
                        </div>
                        <div class="panel-body" style="padding: 0px;">
                            <div id="tabs2" style="padding: 0px">
                                <p id="imgLoad2" class="text-center">cargando.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="fa fa-area-chart"> Indicador por a&ntilde;o</i>
                            <span class="pull-right">
                                <button><i class="fa fa-bar-chart"></i></button>
                                <button><i class="fa fa-list"></i></button>
                            </span>
                        </div>
                        <div class="panel-body no-padding">
                            <div id="tabs3" style="padding: 0px">
                                <p id="imgLoad3" class="text-center">cargando.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="fa fa-area-chart"> Indicador por Usuario</i>
                            <span class="pull-right">
                                <button><i class="fa fa-bar-chart"></i></button>
                                <button><i class="fa fa-list"></i></button>
                            </span>
                        </div>
                        <div class="panel-body no-padding">
                            <div id="tabs4" style="padding: 0px">
                                <p id="imgLoad4" class="text-center">cargando.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
