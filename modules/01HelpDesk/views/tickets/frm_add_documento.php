<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 11/02/2017
 * Time: 01:10 PM
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
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" ><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"> Adjuntar Documento Ticket <?=$_POST['fl']?></h4>
            </div>
            <div class="modal-body" style="margin-bottom: -5px;">
                <div class="row">
                    <div id="fotoperfil" class="col-sm-11 col-md-11">
                        <from enctype="multipart/form-data" id="formuploadajax" method="post">
                            <input type="file" id="selectedFile" name="selectedFile">
                            <input type="submit" id="btnsend" value="Subir archivos" style="display: none;" />
                        </from>
                        <br/>
                    </div>
                </div>
                <div id="imgLoad"></div>
                <div id="uploadfile"></div>
            </div>
            <div class="modal-footer" style="text-align: left;margin-top: -1px;">
                <button class="btn btn-primary btn-sm" id="btnSave" onclick="$('#btnsend').click(fnsd_uploadAjax(<?=$_POST['fl']?>,<?=$_POST['an']?>,'<?=$_POST['nodpto']?>',2));" ><i class="fa fa-save"></i> Guardar</button>
                <button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
