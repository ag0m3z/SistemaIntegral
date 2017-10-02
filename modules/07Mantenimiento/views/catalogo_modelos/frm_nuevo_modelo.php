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

    setOpenModal("mdl_nuevo_modelo");

</script>

<div class="modal fade" id="mdl_nuevo_modelo" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-home"></i> Nuevo Modelo</h4>
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
                                                Marca:
                                                <select id="idmarca" class="form-control input-sm">
                                                    <option value="0">-- --</option>
                                                    <?php
                                                    $connect->_query = "SELECT idValor,Nombre FROM 07MTOCatalogoGeneral WHERE idTabla = 4 AND idestatus= 1 ORDER BY Nombre ";
                                                    $connect->get_result_query();
                                                    for($i=0;$i < count($connect->_rows); $i++){
                                                        echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                Nombre:
                                                <input id="nombre" class="form-control input-sm" placeholder="Nombre modelo "/>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            Descripción:
                                            <textarea id="descripcion" class="form-control input-sm" placeholder="Descripción"></textarea>
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
                <button class="btn btn-primary btn-sm" onclick="fn07NuevoModelo(2)" id="btnGuardarNuevoProveedor"><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger btn-sm" id="btnCloseModalNuevaModelo" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>

</div>