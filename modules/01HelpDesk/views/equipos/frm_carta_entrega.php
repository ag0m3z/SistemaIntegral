<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/02/2017
 * Time: 11:35 AM
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
include "../../../../core/model_equipos.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */



?>
<script language="JavaScript">
    $('#myModal').modal('toggle');
    $("#myModal").draggable({
        handle: ".modal-header"
    });
</script>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"> Datos de Entrega </h4>
            </div>
            <div class="modal-body" style="margin-bottom: -5px;">
                <table class="tablaDetailticket" style="margin-top: -2px;width: 100%;">
                    <tr>
                        <td width="150">Condiciones de Entrega</td>
                        <td colspan="2"><input style="width:100%;" placeholder="Condiciones de entrega"  id="condicionEntrega2" class="formInput" type="text" name="condicionEntrega"/></td>
                    </tr>
                    <tr>
                        <td>Motivo de Entrega</td>
                        <td colspan="2"><input style="width:100%;" placeholder="Motivo de Entrega" id="motivoentrega2" class="formInput" type="text" name="motivoentrega" /></td>
                    </tr>
                    <tr>
                        <td>Usuario: </td>
                        <td colspan="2"><input style="width:100%;" placeholder="Usuario del Equipo" id="usuarioequipo" class="formInput" type="text" name="usuairoequipo" /></td>
                    </tr>
                    <tr>
                        <td>Contrase&ntilde;a:</td>
                        <td colspan="2"><input style="width:100%;" placeholder="Contraseña del Equipo" id="contrasenaequipo" class="formInput" type="text" name="contrasenaequipo" /></td>
                    </tr>
                </table>
                <div id="imgLoad"></div>
                <div id="uploadfile"></div>
            </div>
            <div class="modal-footer" style="text-align: left;">
                <button class="btn btn-primary btn-sm" onclick="ActualizaEstatusEquipo(4,<?=$_REQUEST['fl']?>,<?=$_REQUEST['es']?>,'<?=$_REQUEST['ur']?>')" >Guardar</button>
                <button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
