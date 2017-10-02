<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 14/02/2017
 * Time: 11:48 AM
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

//include "RUTA/core.php";
//include "RUTA/sesiones.php";
//include "RUTA/seguridad.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

?>
<script language="JavaScript">
    $(document).ready(function(){
        $('#myModal').modal('toggle');
        $("#myModal").draggable({
            handle: ".modal-header"
        });


    });
</script>
<div class="modal fade animated" id="myModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"><span class="fa fa-envelope-o"></span> Correo Seguimiento Ticket </h4>
            </div>
            <div class="modal-body" style="margin-bottom: -5px;">
                <table width="100%">
                    <tr>
                        <td width="40">Asunto: </td>
                        <td>
                            <input type="text" id="subject" class="formInput" required="true">
                        </td>
                    </tr>
                    <tr>
                        <td>Mensaje</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <textarea class="formInput" id="mensaje" style="height: 160px" name="solucion" rows="6" ></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td id="result" colspan="2">
                            <div id="imgLoad"></div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer" style="text-align: left;margin-top: -2px;">
                <button class="btn btn-primary btn-sm" id="btnSenMailer" onclick="fnsdSendRecordatorio(1,<?=$_POST['fl']?>,<?=$_POST['an']?>,'<?=$_POST['nodpto']?>')"><i class="fa fa-paper-plane"></i> Enviar</button>
                <button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
