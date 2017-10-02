<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 01:47 PM
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
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsFormatoMoneda.js"></script>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsRemate.js"></script>
<script>
    $(".info-box-number").numeric({prefix:'$',cents:true});
    fnmt_ListarCotizaciones(99);
    $( "#tabs" ).tabs();

    $("#list").click(function () {
        fnmt_ListarCotizaciones(99);
    });



</script>
<div class="panel panel-info">
    <div class="panel-heading" style="padding: 3px;">
        <span class="fa fa-diamond"></span> Catalogo Cotizaciones
        <span class="pull-right badge bg-green" id="num">0</span>
    </div>
    <div id="list_botons" class="toolbars">
        <?php
        if($_SESSION['menu_opciones'][5][1][2][0]['OpcionC'] == 1){
            $btn00 = '
        <div class="btn-group">
            <button id="btnExport" type="button" class="btn btn-success btn-xs dropdown-toggle"
                    data-toggle="dropdown">
                <i class="fa fa-file-excel-o"></i> Importar/Exportar <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:void(0)" onclick="fnmt_ImportarArchivo(1)">Importar</a></li>
                <li><a href="javascript:void(0)" onclick="fnmt_ExportarResultado(2)">Exportar</a></li>
            </ul>
        </div>';

        }
        if($_SESSION['menu_opciones'][5][1][2][0]['OpcionV'] == 1){
            $btn01 = '<div class="btn-group">
            <button id="btnExport" type="button" class="btn btn-primary btn-xs dropdown-toggle"
                    data-toggle="dropdown">
                <i class="fa fa-filter"></i> Filtrar <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:void(0)" onclick="fnmt_ListarCotizaciones(99)">Todas</a></li>
                <li><a href="javascript:void(0)" onclick="fnmt_ListarCotizaciones(1)">Categoria Oro</a></li>
                <li><a href="javascript:void(0)" onclick="fnmt_ListarCotizaciones(7)">Categoria Plata</a></li>
            </ul>
        </div>';
            $btn02 = '<button id="btnRefreshCotizaciones"  onclick="fnsdMenu(14,14)" class="btn btn-primary btn-xs" ><i class="fa fa-refresh"></i> </button>';

        }

        echo $btn02. " ".$btn01. " ".$btn00;
        ?>
    </div>
    <div class="panel-body no-padding">

        <div id="tabs">
            <ul>
                <li><a href="#list" onclick="fnmt_ListarCotizaciones(99);">Lista de Cotizaciones</a></li>
            </ul>
            <div id="list" style="padding: 0px;">

                <div class="row">
                    <div class="col-md-12">
                        <div id="lListarTabla">
                            <div id="myGrid" style="height: 80vh;width: 100%;font-size: 12.55px;"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
</div>
<div id="showModal"></div>

