<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 19/05/2017
 * Time: 11:20 AM
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

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$Disabled = ($_POST['pantalla'] == 1) ? 'Disabled' : '';
$Categoria = ($_POST['pantalla'] == 1) ? $_POST['idcategoria'] : 0 ;

?>
<script language="JavaScript">

    setOpenModal('mdl_nuevo_tipo_producto');
    $('#nocategoria option[value="<?=$Categoria?>" ]').prop('selected', true).change();

</script>
<!-- Modal nuevo tipo de producto -->
<div class="modal fade" id="mdl_nuevo_tipo_producto" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Nuevo marca</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    Categoría:
                    <select class="formInput" <?=$Disabled?> id="nocategoria">
                        <option value="0">-- --</option>
                        <?php
                        $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral where CodCatalogo = 9 AND NoEstatus = 1 ORDER BY Descripcion ASC";
                        $connect->get_result_query();
                        if(count($connect->_rows) > 0 ){
                            for($i=0;$i < count($connect->_rows);$i++){
                                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    Nombre:
                    <input id="descripcion" class="formInput" placeholder="Descripcion" />
                </div>

                <div class="form-group">
                    Abreviatura:
                    <input id="abreviacion" class="formInput" placeholder="Descripcion" />
                </div>

                <div id="modal_result"></div>


            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" onclick="fn_nueva_marca(2,1,0)" ><i class="fa fa-save"></i> Guardar</button>
                <button id="btnCloseModal" class="btn btn-danger btn-sm" data-dismiss="modal" ><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>

</div>
