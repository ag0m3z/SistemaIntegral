<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 05/04/2017
 * Time: 12:28 PM
 */
include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/model_areas_sd.php";

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

$area = new \core\model_areas_sd($_SESSION['data_login']['BDDatos']);
$area->valida_session_id($_SESSION['data_login']['NoUsuario']);
$area->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE AsignarReportes = 'SI' ORDER BY Descripcion ASC";
$area->get_result_query();

?>
<div class="modal fade" id="mdl_alta_area" data-backdrop="static"  role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-folder-open"></i> Nueva área</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    Nombre
                    <input id="nombre_area" placeholder="Nombre del área" class="form-control input-sm">
                </div>
                <div class="form-group">
                    Departamento
                    <select class="formInput" id="NoDepartamento">
                        <option value="0">-- --</option>
                        <?php
                        if(count($area->_rows) >= 1){
                            for($i =0 ;$i < count($area->_rows);$i++){
                                echo '<option value="'.$area->_rows[$i][0].'">'.$area->_rows[$i][1].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div id="imgLoad"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="btnSave" onclick="fn_cat_nueva_area(2)" ><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger btn-sm" id="modalbtnclose" onclick="$('#mdl_alta_area').modal('toggle')"><i class="fa fa-close"></i> Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript">
    $('#mdl_alta_area').modal('toggle');
    $("#mdl_alta_area").draggable({
        handle: ".modal-header"
    });
    $("#nombre_area").focus();

</script>



