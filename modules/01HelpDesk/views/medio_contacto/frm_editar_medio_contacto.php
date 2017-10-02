<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 08/04/2017
 * Time: 11:58 AM
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
include "../../../../core/model_medio_contacto.php";

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

$medios = new \core\model_medio_contacto($_SESSION['data_login']['BDDatos']);
$medios->valida_session_id();

$medios->get($_POST['idMedioContacto']);

if($_SESSION['menu_opciones'][1][1][1][0]['OpcionC'] == 1){
    $idMedioContacto = $_POST['idMedioContacto'];
    $btnGuardar = '<button class="btn btn-primary btn-sm" id="btnSave" onclick="fn_cat_editar_medio_contacto(2,'.$idMedioContacto.',0)" ><i class="fa fa-save"></i> Guardar</button>';
}


/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
?>
<div class="modal fade" id="mdl_editar_medio_contacto" data-backdrop="static"  role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-folder-open"></i> Editar medio de contacto: <?=$medios->getNombreMedio()?> </h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    Nombre
                    <input id="nombre_medio_contacto" value="<?=$medios->getNombreMedio()?>" placeholder="Nombre del medio de contacto" class="form-control input-sm">
                </div>
                <div class="form-group">
                    Estado
                    <select class="formInput" id="editar_medio_contacto">
                        <?php
                        if($medios->getNoEstatus() == 1)
                        {
                            echo "<option value='1'>Activado</option>";
                            echo "<option value='0'>Desactivado</option>";
                        }else{
                            echo "<option value='1'>Activado</option>";
                            echo "<option value='0'>Desactivado</option>";
                        }

                        ?>
                    </select>

                </div>
                <div id="imgLoad"></div>
            </div>
            <div class="modal-footer">
                <?=$btnGuardar?>
                <button class="btn btn-danger btn-sm" id="modalbtnclose" onclick="$('#mdl_editar_medio_contacto').modal('toggle')"><i class="fa fa-close"></i> Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript">
    $('#mdl_editar_medio_contacto').modal('toggle');
    $("#mdl_editar_medio_contacto").draggable({
        handle: ".modal-header"
    });
    $("input").focus(function(){
        this.select();
    });
    $("#nombre_medio_contacto").focus();

</script>
