<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 06/06/2017
 * Time: 10:52 AM
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
include "../../../../core/model_equipos.php";

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

$connect = new \core\model_equipos($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

$Folio = $_POST['folio'];

$connect->_query = "SELECT MotivoEntrega,CondicionesEntrega FROM BSHInventarioEquipos WHERE Folio = '$Folio' ";
$connect->get_result_query();
$DataEquipo = $connect->_rows[0];

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
?>
<script>
    setOpenModal("mdl_envio_equipo");
</script>
<div class="modal fade" id="mdl_envio_equipo" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-list-alt"></i> Envío de Equipo</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <table class="tablaDetailticket" style="margin-top: -2px;width: 100%;">
                            <tr>
                                <td width="150">Condiciones de Envío</td>
                                <td colspan="2"><input style="width:100%;" value="<?=$DataEquipo[1]?>" placeholder="Condiciones de entrega"  id="condicionEntrega2" class="formInput" type="text" name="condicionEntrega"/></td>
                            </tr>
                            <tr>
                                <td>Motivo de Envío</td>
                                <td colspan="2"><input style="width:100%;" placeholder="Motivo de Entrega" value="<?=$DataEquipo[0]?>" id="motivoentrega2" class="formInput" type="text" name="motivoentrega" /></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="modal_result"></div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" onclick="ActualizaEstatusEquipo(3,<?=$_POST['folio']?>,<?=$_POST['estado']?>,'<?=$_POST['urlReporte']?>')"><i class="fa fa-list-alt"></i> Guardar</button>
                <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>
