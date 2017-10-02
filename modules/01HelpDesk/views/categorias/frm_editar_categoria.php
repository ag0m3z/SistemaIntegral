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
include "../../../../core/model_categorias_sd.php";

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

$categorias = new \core\model_categorias_sd($_SESSION['data_login']['BDDatos']);
$categorias->valida_session_id();

if(array_key_exists('nocategoria',$_POST) || array_key_exists('NoArea',$_POST) || array_key_exists('NoDepartamento',$_POST)){

    $NoCategoria = $_POST['nocategoria'];
    $NoArea = $_POST['NoArea'];
    $NoDepartamento = $_POST['NoDepartamento'];

    $categorias->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE AsignarReportes = 'SI' AND NoDepartamento != '$NoDepartamento'  ORDER BY Descripcion ASC";
    $categorias->get_result_query();
    $lista_departamentos = $categorias->_rows;

    $categorias->get($NoCategoria,$NoArea,$NoDepartamento);
}

if($_SESSION['menu_opciones'][1][1][1][0]['OpcionC'] == 1){
    $btnGuardar = "<button class='btn btn-primary btn-sm' id='btnSave' onclick='fn_cat_editar_categoria(2,".$NoCategoria.",0,0)' ><i class='fa fa-save'></i> Guardar</button>";
}



/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
?>
<div class="modal fade" id="mdl_editar_categoria" data-backdrop="static"  role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-folder-open"></i> Editar categoría: <?=$categorias->getNombreCategoria()?> </h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    Nombre
                    <input id="editar_nombre_categoria" value="<?=$categorias->getNombreCategoria()?>" placeholder="Nombre del área" class="form-control input-sm">
                </div>
                <div class="form-group">
                    Departamento
                    <select class="formInput" onchange="fn_select_areas(this.value)" id="editar_NoDepartamento">
                        <option value="<?=$categorias->getNoDepartamento()?>"><?=$categorias->getNombreDepartamento()?></option>
                        <?php
                        if(count($lista_departamentos) >= 1){
                            for($i =0 ;$i < count($lista_departamentos);$i++){
                                echo '<option value="'.$lista_departamentos[$i][0].'">'.$lista_departamentos[$i][1].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    áreas
                    <select class="formInput" id="id_noarea">
                        <option value="<?=$categorias->getNoArea()?>"><?=$categorias->getNombreArea()?></option>
                        <?php
                        $categorias->_query = "SELECT NoArea,Descripcion FROM BSHCatalogoAreas where NoDepartamento = '$NoDepartamento' AND NoArea <> '$NoArea' AND NoEstatus = 1 ORDER BY Descripcion ASC";
                        $categorias->get_result_query();

                        for($i=0;$i<count($categorias->_rows);$i++){
                            echo "<option value='".$categorias->_rows[$i][0]."'>".$categorias->_rows[$i][1]."</option>";
                        }

                        ?>
                    </select>
                </div>
                <div class="form-group">
                    Estado
                    <select class="formInput" id="editar_NoEstado">
                        <?php
                        if($categorias->getNoEstatus() == 1)
                        {
                            echo "<option value='1'>Activado</option>";
                            echo "<option value='0'>Desactivado</option>";
                        }else{
                            echo "<option value='0'>Desactivado</option>";
                            echo "<option value='1'>Activado</option>";
                        }

                        ?>
                    </select>

                </div>
                <div id="imgLoad"></div>
            </div>
            <div class="modal-footer">
                <?=$btnGuardar?>
                <button class="btn btn-danger btn-sm" id="modalbtnclose" onclick="$('#mdl_editar_categoria').modal('toggle');"><i class="fa fa-close"></i> Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript">
    $('#mdl_editar_categoria').modal('toggle');
    $("#mdl_editar_categoria").draggable({
        handle: ".modal-header"
    });
    $("input").focus(function(){
        this.select();
    });
    $("#editar_nombre_categoria").focus();

</script>
