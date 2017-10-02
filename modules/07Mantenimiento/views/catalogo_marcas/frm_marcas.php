<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 26/05/2017
 * Time: 03:29 PM
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
include "../../controller/ControllerCatalogoProveedores.php";

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

$connect = new ControllerCatalogoProveedores($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
?>

<script src="<?=\core\core::ROOT_APP?>site_design/js/jsMantenimiento.js"></script>

<script>

    fn07ListarMarca(1,1);
    $("input").focus(function(e){
        $(this).select();
    });

</script>
<div class="panel panel-info">
    <div class="panel-heading">
        <i class="fa fa-home"></i> Catalogo Marcas
    </div>
    <div class="toolbars">

        <button class="btn animated fadeIn btn-xs btn-primary" onclick="fnsdMenu(33,33)"><i class="fa fa-refresh"></i></button>
        <button class="btn animated fadeIn btn-xs btn-primary" onclick="fn07NuevaMarca(1)"><i class="fa fa-file"></i> Nuevo</button>

        <div class="btn-group">
            <button type="button" class="btn animated fadeIn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Filtrar <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#" onclick="fn07ListarMarca(3,0)">Todos</a></li>
                <li><a href="#" onclick="fn07ListarMarca(2,0)">Solo Desactivados</a></li>
                <li><a href="#" onclick="fn07ListarMarca(1,1)">Solo Activados</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" onclick="fn07ListarMarca(4,0)" >Registrados Actualmente</a></li>
                <li><a href="#" onclick="fn07ListarMarca(5,0)">Actualizados Actualmente</a></li>
            </ul>
        </div>
        <button class="btn animated fadeIn btn-xs btn-default" data-toggle="modal" data-target="#mdl_buscar_marcas" ><i class="fa fa-search" ></i> Buscar</button>
        <button class="btn animated fadeIn btn-xs btn-default" disabled onclick="alert('isTest')" ><i class="fa fa-file-excel-o"></i> Exportar</button>
        <span class="pull-right badge bg-green" id="totalrows">0</span>

    </div>
    <div id="listaMarcas" class="panel-body no-padding">
        <div class="animated fadeIn" id="myGrid"><br></div>
    </div>
</div>

<div class="modal fade" id="mdl_buscar_marcas">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-search"></i> Buscar Marca</h4>
            </div>
            <div class="modal-body">
                <form action="#" method="post" onsubmit="fn07ListarMarca(6,0);$('#btnCloseModal').click(); return false;">
                    <div class="form-group">
                        Busqueda por: nombre, descripción
                        <input id="txtSearch" class="form-control input-sm" placeholder="Busqueda de Marcas">
                    </div>
                </form>
            </div>
            <div class="modal-footer">

                <button class="btn btn-primary btn-sm" onclick="fn07ListarMarca(6,0);$('#btnCloseModal').click()" ><i class="fa fa-search"></i> Buscar</button>
                <button id="btnCloseModal" class="btn btn-danger btn-sm" data-dismiss="modal" ><i class="fa fa-close"></i> Cerrar</button>

            </div>
        </div>
    </div>
</div>
