<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 28/03/2017
 * Time: 10:28 AM
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
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsPVenta.js" language="JavaScript"></script>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsCalendario.js" language="JavaScript" ></script>
<script language="JavaScript">

    fn_productos_web_listar(1);
    $("#btn_add_caracteristica").hide();
    $("#btn_add_imagen").hide();

    //    $('.currency').numeric({prefix:'$ ', cents: true});
    $("input[type=text]").focus(function(){
        this.select();
    });

</script>

<!-- Panel Principal para la lista de productos web -->
<div class="panel panel-info">
    <div class="panel-heading padding-x3">
        <i class="fa fa-list"></i> Catalogo de Productos
    </div>
    <div class="toolbars">
        <button id="btnHome" class="btn btn-primary btn-xs" onclick="fnsdMenu(23,null)" ><i class="fa fa-refresh"></i> </button>
        <button id="btnList" class="btn btn-primary btn-xs" onclick="fnsdMenu(23,null)" ><i class="fa fa-list"></i> Lista</button>
        <button id="btn_add_caracteristica" class="btn btn-success btn-xs" onclick="fn_registrar_caracteristica(12,$('#nocategoria').val(),$('#codigo_producto').val(),' ',' ')" ><i class="fa fa-plus"></i> Caracteristica</button>
        <button id="btn_add_imagen" class="btn btn-success btn-xs" onclick="fn_agregar_imagenes(1,$('#nocategoria').val(),$('#codigo_producto').val(),'','')" ><i class="fa fa-image"></i> Imagenes</button>
<!--        <button id="btnList" class="btn btn-primary btn-xs" onclick="fn_productos_web_nuevo(1)" ><i class="fa fa-file"></i> Nuevo</button>-->
        <button id="btnList" class="btn btn-default btn-xs" onclick="$('#mdl_buscar_producto_web').modal('toggle');" ><i class="fa fa-search"></i> Buscar</button>
        <button id="btnList" class="btn btn-default btn-xs" onclick="fnsdMenu(23,null)" ><i class="fa fa-file-excel-o"></i> Exportar</button>
    </div>
    <div class="panel-body no-padding">
        <div id="lListarTabla">
            <div id="myGrid" style="height: 80vh;font-size: 12px;">

            </div>
        </div>
    </div>
</div>
<!-- FIn del Panel principal para la lista de productos web -->

<!-- Div para la carga de divs mediante ajax -->
<div class="ModalAjax"></div>
<!-- FIN del div para la carga de divs mediante ajax -->

<!-- modal para la busqueda de productos -->
<div class="modal fade dropModal" id="mdl_buscar_producto_web" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-search"></i> Catalogo Productos</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button id="btn-search-product" onclick="fnpv_buscar_producto()" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Buscar</button>
                <button id="closemodal" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
                <button onclick="$('#frmBuscaProducta').Frmreset()" class="btn btn-default btn-sm"><i class="fa fa-trash"></i> Limpiar</button>
            </div>
        </div>
    </div>
</div>
<!-- FIN modal para la busqueda de productos -->