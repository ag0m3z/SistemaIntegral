<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/02/2017
 * Time: 05:18 PM
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

$Tickets->_query = "SELECT NoUsuario,NombreDePila FROM SINTEGRALGNL.BGECatalogoUsuarios where NoDepartamento = '".$_SESSION['data_departamento']['NoDepartamento']."'  AND NoEstado = 1 ORDER BY NombreDePila ASC";
$Tickets->get_result_query();

?>
<script language="JavaScript">
    $(document).ready(function(){
        $('#myModal').modal('toggle');
        $("#myModal").draggable({
            handle: ".modal-header"
        });
    });
</script>
<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" ><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"> Reasignar Ticket </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        Asignar:
                        <select id="asignen" name="asignen" class="formInput">
                            <option value="0"> Seleccione un Agente </option>
                            <?php
                            for($i=0;$i < count($Tickets->_rows); $i++ ){
                                echo "<option value='".$Tickets->_rows[$i][0]."'>".$Tickets->_rows[$i][1]."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div id="imgLoad"></div>
            </div>
            <div class="modal-footer" style="text-align: left;">
                <button class="btn btn-primary btn-sm" id="btnSave" onclick="fn_reasignar_ticket(<?=$_POST['fl']?>,<?=$_POST['an']?>,'<?=$_POST['nodpto']?>')"><i class="fa fa-save"></i> Guardar</button>
                <button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
