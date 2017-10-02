<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/03/2017
 * Time: 12:21 PM
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
<script>
    $('button').addClass("btn btn-xs waves-effect");
    $('.modal-footer button').removeClass("btn-xs");
    $("#ModalSearch").draggable({
        handle: ".modal-header"
    });
    fnCatListarDepartamentos(1);
</script>
<div class="panel panel-info">
    <div class="panel-heading"  style="padding: 3px;"><i class="fa fa-home"></i> Catalogo de departamentos</div>
    <div class="toolbars">
        <button class="btn-primary" data-placement="top" data-toggle="tooltip" title="Regresar a Catalogos" onclick="fnsdMenu(16,null)"><i class="fa fa-arrow-circle-left"></i> Regresar</button>
        <button id="btnHome" data-placement="top" data-toggle="tooltip" title="Actualizar" class="btn-primary"  onclick="fnCatSeleccionarCatalogo(3,'?ok')"><i class="fa fa-refresh"></i></button>
        <button class="btn-primary" onclick="fnCatFrmNuevoDepartamento(1,0)"><i class="fa fa-file"></i> Nuevo </button>
        <div class="btn-group">
            <button class="btn-primary dropdown-toggle"  data-toggle="dropdown"><i class="fa fa-filter"></i> Filtrar <i class="caret"></i></button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:void(0)" onclick="fnCatListarDepartamentos(2)" >  Todos</a></li>
                <li><a href="javascript:void(0)" onclick="fnCatListarDepartamentos(1)">  Solo activos</a></li>
                <li><a href="javascript:void(0)" onclick="fnCatListarDepartamentos(3)">  Solo inactivos</a></li>
                <li class="divider"></li>
                <li><a href="javascript:void(0)" onclick="fnCatListarDepartamentos(4)">  Ultimos 50 registros</a></li>
                <li><a href="javascript:void(0)" onclick="fnCatListarDepartamentos(5)">  Ultimos 100 registros</a></li>
                <li><a href="javascript:void(0)" onclick="fnCatListarDepartamentos(6)">  Registrados actualmente</a></li>
                <li><a href="javascript:void(0)" onclick="fnCatListarDepartamentos(7)">  Actualizados actualmente</a></li>
            </ul>
        </div>
        <button class="btn-default" onclick="$('#ModalSearch').modal('toggle')"><i class="fa fa-search"></i> Busqueda</button>
        <button class="btn-warning hidden" onclick="fnsdMenu(16,null)"><i class="fa fa-print"></i> Exportar</button>
    </div>
    <div class="panel-body no-padding">
        <div id="lListTable">
            <div id="myGrid" style="height: 75vh;"></div>
        </div>
    </div>
</div>

<div id="ShowModal"></div>

<div class="modal fade " id="ModalSearch">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-search"></i> Catalogo de departamentos </h4>
            </div>
            <div class="modal-body" >
                <form id="mdl_search_departamento" action="#" onsubmit="fnCatBuscarDepartamento(8); return false;" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input id="txtNombre" class="form-control input-sm" placeholder="Buscar por: NoDepartamento, Nombre Departamento">
                            </div>
                        </div>
                        <div class="col-md-2 hidden">
                            <div class="form-group">
                                <select id="txtNoEstado" class="form-control input-sm">
                                    <option value="1"> Activos</option>
                                    <option value="0"> Desactivados</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Departamento:
                                <select id="txtNoDepartamento" class="form-control input-sm">
                                    <option value="0">-- --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                Usuario Alta:
                                <select id="txtNoUsuarioA" class="form-control input-sm">
                                    <option value="0">-- --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                Usuario UM:
                                <select id="txtNoUsuarioU" class="form-control input-sm">
                                    <option value="0">-- --</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="hidden" onclick="$('#btnSearch').click();" >Buscar</button>
                </form>
                <div id="resutlDiv"></div>
            </div>
            <div class="modal-footer">
                <button name="search" id="btnSearch" class="btn btn-primary btn-sm" onclick="fnCatBuscarDepartamento(8)" ><i class="fa fa-search"></i> Buscar</button>
                <button id="mdl_close" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
                <button onclick="$('#mdl_search_departamento').Frmreset();" class="btn btn-default btn-sm"><i class="fa fa-trash"></i> Limpiar</button>
            </div>
        </div>
    </div>
</div>




