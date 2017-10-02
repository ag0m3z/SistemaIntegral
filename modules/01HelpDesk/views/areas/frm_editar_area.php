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
$area->valida_session_id();

if(array_key_exists('NoArea',$_POST) || array_key_exists('NoDepartamento',$_POST)){
    $area->get($_POST['NoArea'],$_POST['NoDepartamento']);
    $NoDepartamento = $area->getNoDepartamento();
    $area->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE AsignarReportes = 'SI' AND NoDepartamento != '$NoDepartamento' ORDER BY Descripcion ASC";
    $area->get_result_query();
    $lista_depto = $area->_rows;
}

if($_SESSION['menu_opciones'][1][1][1][0]['OpcionC'] == 1){
    $NoArea = $_POST['NoArea'];
    $btnGuardar = '<button class="btn btn-primary btn-sm" id="btnSave" onclick="fn_cat_editar_area(2,'.$NoArea.',0)" ><i class="fa fa-save"></i> Guardar</button>';
}


/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
?>
<div class="modal fade" id="mdl_editar_area" data-backdrop="static"  role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-folder-open"></i> Editar área: <?=$area->getDescripcion()?> </h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    Nombre
                    <input id="editar_nombre_area" value="<?=$area->getDescripcion()?>" placeholder="Nombre del área" class="form-control input-sm">
                </div>
                <div class="form-group">
                    Departamento
                    <select class="formInput" id="editar_NoDepartamento">
                        <option value="<?=$area->getNoDepartamento()?>"><?=$area->getNombreDepartamento()?></option>
                        <?php

                        if(count($lista_depto) >= 1){
                            for($i =0 ;$i < count($lista_depto);$i++){
                                echo '<option value="'.$lista_depto[$i][0].'">'.$lista_depto[$i][1].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    Estado
                    <select class="formInput" id="editar_NoEstado">
                        <?php
                        if($area->getNoEstatus() == 1)
                        {
                            echo "<option value='1'>Activado</option><option value='0'>Desactivado</option>";
                        }else{
                            echo "<option value='0'>Desactivado</option><option value='1'>Activado</option>";
                        }

                        ?>
                    </select>

                </div>
                <div id="imgLoad"></div>
            </div>
            <div class="modal-footer">
                <?=$btnGuardar?>
                <button class="btn btn-danger btn-sm" id="modalbtnclose" onclick="$('#mdl_editar_area').modal('toggle')"><i class="fa fa-close"></i> Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript">
    $('#mdl_editar_area').modal('toggle');
    $("#mdl_editar_area").draggable({
        handle: ".modal-header"
    });
    $("input").focus(function(){
        this.select();
    });
    $("#editar_nombre_area").focus();

</script>
