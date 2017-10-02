<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 11/02/2017
 * Time: 12:29 PM
 */
/**
 * Incluir las Librerias Principales del Sistema
 * En el Siguiente Orden
 * ruta de libreias: @@/SistemaIntegral/core/
 *
 * 1.- core.php;
 * 2.- sesiones.php
 * 3.- seguridad.php
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/model_tickets.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$Tickets = new \core\model_tickets($_SESSION['data_login']['BDDatos']);

$Tickets->valida_session_id($_SESSION['data_login']['NoUsuario']);

//Catalogos Tipo de Atencion
$Tickets->_query = "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos WHERE idCatalogo=2";
$Tickets->get_result_query();
$TipoAtencion = $Tickets->_rows;

//Catalogos Tipo Cierre
$Tickets->_query = "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos where idCatalogo=4";
$Tickets->get_result_query();
$TipoCierre = $Tickets->_rows;




?>
<script language="JavaScript">
    $(document).ready(function(){
        $('#myModal').modal('toggle');
        $("#myModal").draggable({
            handle: ".modal-header"
        });
    });
</script>
<div class="modal fade animated " id="myModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" ><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"> Cerrar Ticket </h4>
            </div>
            <div class="modal-body" style="margin-bottom: -5px;">
                <table width="100%">
                    <tr>
                        <td width="160">Tipo Atenci&oacute;n: </td>
                        <td height="40px">
                            <select id="t_atencion" name="t_atencion" class="formInput">
                                <option value="0">-- --</option>
                                <?php
                                for( $i=0; $i < count($TipoAtencion); $i++ ){
                                    echo '<option value="'.$TipoAtencion[$i]['idDescripcion'].'">'.$TipoAtencion[$i]['Descripcion'].'</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="160">Tipo de Cierre: </td>
                        <td height="40px">
                            <select id="tipo_cierre" name="tipo_cierre" class="formInput">
                                <?php
                                for( $i=0; $i < count($TipoCierre); $i++ ){
                                    echo '<option value="'.$TipoCierre[$i]['idDescripcion'].'">'.$TipoCierre[$i]['Descripcion'].'</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Soluci&oacute;n</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <textarea id="solucion" class="formInput" style="height: 160px" name="solucion" rows="6" required="true"></textarea>
                        </td>
                    </tr>
                </table>
                <div id="imgLoad"></div>
            </div>
            <div class="modal-footer" style="text-align: left;">
                <button class="btn btn-primary btn-sm" id="btnSave" onclick="fn_cerrar_ticket(<?=$_POST['fl']?>,<?=$_POST['an']?>,'<?=$_POST['nodpto']?>')"><i class="fa fa-save"></i> Guardar</button>
                <button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
