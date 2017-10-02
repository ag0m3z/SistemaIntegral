<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 26/05/2017
 * Time: 03:43 PM
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

<script>

    setOpenModal("mdl_nuevo_proveedor");

</script>

<div class="modal fade" id="mdl_nuevo_proveedor" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-users"></i> Nuevo Proveedor</h4>
            </div>
            <div class="modal-body no-padding">

                <div class="row">
                    <div class="col-md-12">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab">Datos generales</a></li>
                                <li><a href="#tab_2" data-toggle="tab">Historico</a></li>
                                <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">

                                    <div class="row">

                                        <div class="col-md-7">
                                            <div class="form-group">
                                                Nombre:
                                                <input id="nombre" class="form-control input-sm" placeholder="Nombre Empresa "/>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="form-group">
                                                Contacto:
                                                <input id="contacto" class="form-control input-sm" placeholder="Nombre del contacto "/>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            Descripción:
                                            <textarea id="descripcion" class="form-control input-sm" placeholder="Descripcion"></textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                Telefono:
                                                <input id="telefono01" class="form-control input-sm" placeholder="Telefono "/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                Telefono 2:
                                                <input id="telefono02" class="form-control input-sm" placeholder="Telefono 2 "/>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                Celular:
                                                <input id="celular" class="form-control input-sm" placeholder="Celular "/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                Ext.:
                                                <input id="ext" class="form-control input-sm" placeholder="Extencion "/>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                Correo:
                                                <input id="correo" class="form-control input-sm" placeholder="Correo "/>
                                            </div>
                                        </div>

                                        <div class="col-md-7">
                                            <div class="form-group">
                                                Calle y Numero:
                                                <input id="callenumero" class="form-control input-sm" placeholder="Calle y numero "/>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                Colonia:
                                                <input id="colonia" class="form-control input-sm" placeholder="Colonia "/>
                                            </div>
                                        </div>

                                    </div>



                                </div><!-- /.tab-pane -->

                                <div class="tab-pane" id="tab_2">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                Fecha Alta:
                                                <input class="form-control input-sm"  value="<?=date("d/m/Y")?>" disabled / >
                                            </div>
                                            <div class="form-group">
                                                Usuario Alta:
                                                <input class="form-control input-sm"  value="<?=$_SESSION['data_login']['NombreDePila']?>" disabled / >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                Fecha UM:
                                                <input class="form-control input-sm"  value="<?=date("d/m/Y")?>" disabled / >
                                            </div>
                                            <div class="form-group">
                                                Usuario UM:
                                                <input class="form-control input-sm"  value="<?=$_SESSION['data_login']['NombreDePila']?>" disabled / >
                                            </div>
                                        </div>
                                    </div>

                                </div><!-- /.tab-pane -->

                            </div><!-- /.tab-content -->
                        </div><!-- nav-tabs-custom -->
                    </div>
                </div>

                <div id="result_modal"></div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" onclick="fn07NuevoProveedor(2)" id="btnGuardarNuevoProveedor"><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger btn-sm" id="btnCloseModalNuevoProveedor" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>

</div>