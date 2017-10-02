<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 04/04/2017
 * Time: 12:08 PM
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
include "../../../../core/model_puestos.php";

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

$connect = new \core\model_puestos($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/

$connect->get($_POST['idpuesto']);

unset($_SESSION['EXPORT']);

?>
<div class="modal fade" id="myModal" data-backdrop="static"  role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-folder-open"></i> Editar Puesto <?=$connect->getNombrePuesto()?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    Nombre
                    <input id="nombre_puesto" type="text" value="<?=$connect->getNombrePuesto()?>" placeholder="Nombre del puesto" class="form-control input-sm">
                </div>
                <div class="form-group">
                    Descripción
                    <textarea id="descripcion_puesto" placeholder="Descripción del puesto" class="form-control"><?=$connect->getDescripcionPuesto()?></textarea>
                </div>
                <div id="imgLoad"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="btnSave" onclick="fnCatEditarPuesto(2,<?=$_POST['idpuesto']?>)" ><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger btn-sm" id="modalbtnclose" onclick="$('#myModal').modal('toggle')"><i class="fa fa-close"></i> Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript">
    $('#myModal').modal('toggle');
    $("#myModal").draggable({
        handle: ".modal-header"
    });
    $("input[type=text]").focus(function(){
        this.select();
    });
    $("textarea").focus(function(){
        this.select();
    });

</script>
