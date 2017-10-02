<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 02/03/2017
 * Time: 10:41 AM
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
include "../../../../core/model_usuarios.php";

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

$usuarios = new \core\model_usuarios($_SESSION['data_login']['BDDatos']);

$usuarios->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsCalendario.js"></script>
<div class="panel panel-info">
    <div class="panel-heading" style="padding: 3px;"><i class="fa fa-user-secret"></i> Catalogo de usuarios</div>
    <div class="toolbars">
        <button class="btn-primary" onclick="fnsdMenu(16,null)"><i class="fa fa-arrow-circle-left"></i> Regresar</button>
        <button id="btnHome" class="btn-primary" onclick="fnCatSeleccionarCatalogo(2,'?ok')"><i class="fa fa-refresh"></i>  </button>
        <button id="btnList" class="btn-primary" onclick="fnCatSeleccionarCatalogo(2,'?ok')"><i class="fa fa-list"></i> Lista </button>
        <button class="btn-primary" onclick="fnCatNuevoUsuario(1)"><i class="fa fa-user-plus"></i> Nuevo</button>
        <button class="btn-success active" onclick="fnCatSaveNuevoUsuario(1)"><i class="fa fa-save"></i> Guardar</button>
        <button class="btn-success" onclick="fnCatSaveNuevoUsuario(2)" id="btnSaveChange"><i class="fa fa-save"></i> Guardar </button>

        <div class="btn-group">
            <button class="btn-primary dropdown-toggle"  data-toggle="dropdown"><i class="fa fa-filter"></i> Filtrar <i class="caret"></i></button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:void(0)" onclick="fnCatListarUsuarios(1)" >  Todos</a></li>
                <li><a href="javascript:void(0)" onclick="fnCatListarUsuarios(2)">  Solo activos</a></li>
                <li><a href="javascript:void(0)" onclick="fnCatListarUsuarios(3)">  Solo inactivos</a></li>
                <li><a href="javascript:void(0)" onclick="fnCatListarUsuarios(10)">  Conectados</a></li>
                <li class="divider"></li>
                <li><a href="javascript:void(0)" onclick="fnCatListarUsuarios(4)">  Ultimos 50 registros</a></li>
                <li><a href="javascript:void(0)" onclick="fnCatListarUsuarios(5)">  Ultimos 100 registros</a></li>
                <li><a href="javascript:void(0)" onclick="fnCatListarUsuarios(6)">  Registrados actualmente</a></li>
                <li><a href="javascript:void(0)" onclick="fnCatListarUsuarios(7)">  Actualizados actualmente</a></li>
            </ul>
        </div>
        <button class="btn-default" onclick="$('#myModal').modal('toggle')"><i class="fa fa-search"></i> Busqueda</button>
        <button class="btn-primary hidden" onclick="fnsdMenu(16,null)"><i class="fa fa-print"></i> Exportar</button>
<!--        <button id="btn_lock_sesion" onclick="fngnDesconectar_sesion()" class="btn-warning active" ><i class="fa fa-sign-out"></i> Desconectar </button>-->
        <button id="btn_off_sesion" onclick="fngnDesconectar_sesion()" class="btn-warning active" ><i class="fa fa-lock"></i> Desbloquear</button>
    </div>
    <div class="panel-body no-padding">
        <div id="lListTable">
            <div id="myGrid" style="height: 75vh;"></div>
        </div>
    </div>
</div>
<div class="modal fade " id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-search"></i> Busqueda de usuario </h4>
            </div>
            <div class="modal-body" >
                <form name="searchUsers" action="#" onsubmit="fnCatBuscarUsuario(1); return false;" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input id="txtNombre" class="form-control input-sm" placeholder="Buscar por: No Usuario, Departamento, Nombre">
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
                    <button type="submit" class="hidden" onclick="$('#btnSearch').click();" >Buscar</button>
                </form>
                <div id="Divtest"></div>
            </div>
            <div class="modal-footer">
                <button name="search" type="submit" id="btnSearch" class="btn btn-primary btn-sm" onclick="fnCatBuscarUsuario(1)" ><i class="fa fa-search"></i> Buscar</button>
                <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('button').addClass("btn btn-xs waves-effect");
    $('.modal-footer button').removeClass("btn-xs");
    $('.btn-success').hide();
    $('#btn_off_sesion').addClass('hidden');
    $('#btn_lock_sesion').addClass('hidden');


    $("#myModal").draggable({
        handle: ".modal-header"
    });
    $("#btnList").hide();
    $("#txtNombre").focus();
    $("input[type=text]").focus(function(){
        this.select();
    });
    fnCatListarUsuarios(2);
</script>
