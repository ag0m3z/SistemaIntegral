<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 04:29 PM
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
include "../../../../core/seguridad.php";

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

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
?>
<script language="JavaScript">
    $('#myModal').modal('show');
    $("#myModal").draggable({
        handle: ".modal-header"
    });
    $(".btn-primary").focus();


</script>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px">Importar Archivo</h4>
            </div>
            <div class="modal-body" style="margin-bottom: -5px">
                <div class="row">
                    <div class="col-md-12">
                        <form action="#" method="POST" enctype="multipart/form-data">
                            <table class="tablaDetailticket">
                                <tr>
                                    <td><input id="btn_file" type="file" class="btn btn-default btn-sm" name="archivo"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" /></td>
                                    <td><input type="submit" value="Enviar" class="hidden" id="btn_submit" /></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
                <div id="divresult"></div>
                <div id="imgLoad"></div>
            </div>
            <div class="modal-footer" style="text-align: left;margin-top: -8px;">
                <button class="btn btn-primary btn-sm" onclick="fnmt_ImportarExcel()" >Guardar</button> &nbsp;<button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
