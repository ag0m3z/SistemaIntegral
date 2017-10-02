<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 05/04/2017
 * Time: 12:28 PM
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

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);


?>
<div class="modal fade" id="mdl_alta_medio_contacto" data-backdrop="static"  role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content ">
            <form name="form_nuevo_medio_contacto" action="#?" onsubmit="fn_cat_nuevo_medio_contacto(2); return false;"   method="post" >
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-folder-open"></i> Nuevo medio de contacto</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        Medio de Contacto
                        <input id="nombre_medio_contacto" placeholder="Nombre medio de contacto" class="form-control input-sm">
                    </div>

                    <div id="imgLoad"></div>

                </div>
            </form>

            <div class="modal-footer">
                    <button class="btn btn-primary btn-sm" onclick="fn_cat_nuevo_medio_contacto(2)" id="btnSave" ><i class="fa fa-save"></i> Guardar</button>
                    <button class="btn btn-danger btn-sm" id="modalbtnclose" onclick="$('#mdl_alta_medio_contacto').modal('toggle')"><i class="fa fa-close"></i> Cancelar</button>
                </div>
        </div>
    </div>
</div>

<script language="JavaScript">
    $('#mdl_alta_medio_contacto').modal('toggle');
    $("#mdl_alta_medio_contacto").draggable({
        handle: ".modal-header"
    });
    $("#nombre_medio_contacto").focus();

</script>



