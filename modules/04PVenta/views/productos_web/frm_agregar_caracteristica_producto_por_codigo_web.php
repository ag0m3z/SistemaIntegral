<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 09/05/2017
 * Time: 04:24 PM
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
include "../../../../core/sqlconnect.php";
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

$SqlConnect = new \core\sqlconnect();

$SqlConnect->_sqlQuery = "
                         SELECT 
                            idCaracteristica,
                            Descripcion
                        FROM 
                            SAyT.dbo.INVProdCaracteristica
                        WHERE Estatus = '1' AND idCategoria = '$_POST[idcategoria]' ORDER BY Descripcion ASC ";

$SqlConnect->get_result_query();
$car = $SqlConnect->_sqlRows;

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);




?>
<script>

    setOpenModal("mdl_nueva_caracteristica_codigo");
    $(".select2").select2();
    $("#btncambiar").hide();
    fn_registrar_caracteristica(10,'<?=$_POST['idcategoria']?>','<?=$_POST['idcodigo']?>','0')

</script>
<div class="modal fade" id="mdl_nueva_caracteristica_codigo">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Caracteristica - Nivel Codigo</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table id="tabla-caracteristica" class="table table-condensed">
                            <thead>
                            <tr>
                                <td width="250">
                                    <select id="idcaracteristica" class="form-control select2" style="width: 100%;">
                                        <option value="0">-- --</option>
                                        <?php
                                        for($i=0;$i < count($car);$i++){
                                            echo "<option value='".$car[$i][0]."' >".$car[$i][1]."</option>";
                                        }

                                        ?>
                                    </select>
                                </td>
                                <td><input id="valor_caracteristica" class="form-control input-sm" placeholder="Descripcion" /></td>
                                <td width="100px">
                                    <button id="btnagregar" onclick="fn_registrar_caracteristica(3,'<?=$_POST['idcategoria']?>','<?=$_POST['idcodigo']?>','<?=$_POST['idserie']?>')" class="btn btn-success btn-xs  " ><i class="fa fa-plus"></i> Agregar</button>
                                    <button hidden id="btncambiar" onclick="fn_registrar_caracteristica(11,'<?=$_POST['idcategoria']?>','<?=$_POST['idcodigo']?>',' ')" class="btn btn-info btn-xs " ><i class="fa fa-save"></i> Guardar</button>
                                </td>
                            </tr>
                            </thead>
                            <tbody id="tblista">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="result_modal"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>
