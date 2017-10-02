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
include "../../controller/ControllerCatalogoTipoEquipos.php";

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

$connect = new ControllerCatalogoTipoEquipos($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


$connect->get_tipo_equipo($_POST['idtipo_equipo']);

?>

<script>

    setOpenModal("mdl_editar_tipo_equipo");
    $("input").focus(function () {
        $(this).select();
    })

</script>

<div class="modal fade" id="mdl_editar_tipo_equipo" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-home"></i> Editar Tipo de Equipo - <?=$connect->getNombre()?></h4>
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

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                Nombre:
                                                <input id="nombre" value="<?=$connect->getNombre()?>" class="form-control input-sm" placeholder="Nombre Tipo de Equipo "/>
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            Descripción:
                                            <textarea id="descripcion" class="form-control input-sm" placeholder="Descripcion"><?=$connect->getDescripcion()?></textarea>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                Estatus:
                                                <select id="idestatus" class="form-control input-sm">
                                                    <?php
                                                    if($connect->getIDEstatus() == 1){
                                                        //Activo
                                                        echo "<option value='1'>Activado</option><option value='0'>Desactivado</option>";
                                                    }else{
                                                        //Inactivo
                                                        echo "<option value='0'>Desactivado</option><option value='1'>Activado</option>";

                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>



                                </div><!-- /.tab-pane -->

                                <div class="tab-pane" id="tab_2">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                Fecha Alta:
                                                <input class="form-control input-sm" value="<?=$connect->getFechaAlta()?>" disabled / >
                                            </div>
                                            <div class="form-group">
                                                Usuario Alta:
                                                <input class="form-control input-sm"  value="<?=$connect->getUsuarioAlta()?>" disabled / >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                Fecha UM:
                                                <input class="form-control input-sm"  value="<?=$connect->getFechaUM()?>" disabled / >
                                            </div>
                                            <div class="form-group">
                                                Usuario UM:
                                                <input class="form-control input-sm"  value="<?=$connect->getUsuarioUM()?>" disabled / >
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
                <button class="btn btn-primary btn-sm" onclick="fn07EditarTipoEquipo(2,<?=$_POST['idtipo_equipo']?>)" id="btnGuardarNuevoProveedor"><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger btn-sm" id="btnCloseModalEditarTipoEquipo" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>

</div>