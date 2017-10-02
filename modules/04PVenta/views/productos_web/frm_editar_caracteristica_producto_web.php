<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 26/04/2017
 * Time: 05:10 PM
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

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

include "../../src/productos_web/ClassController.php";
$sqlconnect = new ClassController();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
$sqlconnect->get_caracteristicas($_POST['idcaracteristica']);
$IDCategoria = $sqlconnect->getIDCategoria();
?>
<script language="JavaScript">
    $('#mdl_editar_caracteristica').modal('show');
    $("#mdl_editar_caracteristica").draggable({
        handle: ".modal-header"
    });

    $("#tabs").tabs();
    $("input").focus(function(){
        this.select();
    });
</script>
<div class="modal fade bs-example-modal-sm" id="mdl_editar_caracteristica" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Editar caracteristica: <?=$sqlconnect->getNombreCaracteristica()?></h4>
            </div>
            <div class="modal-body no-padding">

                <!-- Panel para el Detalles y caracteristicas del producto -->
                <div id="tabs" style="font-size: 12px;padding: 0px;border: 0px solid #ccc;">
                    <ul style="border:none">
                        <li><a href="#inf">Informaci&oacute;n General</a></li>
                        <li><a href="#his">Historial</a></li>
                    </ul>
                    <div id="inf" >
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    Categoría
                                    <select class="formInput" id="NoCategoria">
                                        <option value="<?=$sqlconnect->getIDCategoria()?>"><?=$sqlconnect->getNombreCategoria()?></option>
                                        <?php
                                        $sqlconnect->_sqlQuery =
                                            "SELECT NoCategoria,NombreCategoria 
                                      FROM BDSPSAYT.dbo.BPFCatalogoCategoriasPrestamo 
                                      WHERE NoCategoria <> '$IDCategoria' 
                                      ORDER BY NombreCategoria ASC";
                                        $sqlconnect->get_result_query();
                                        $lista_categorias = $sqlconnect->_sqlRows;
                                        for($i=0;$i < count($lista_categorias);$i++){
                                            echo "<option value='".$lista_categorias[$i][0]."'>".$lista_categorias[$i][1]."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    Nombre
                                    <input class="formInput" value="<?=$sqlconnect->getNombreCaracteristica()?>" id="NombreCategoria" placeholder="Nombre caracteristica">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    Orden
                                    <select class="formInput" id="Orden">
                                        <option value="<?=$sqlconnect->getIDOrden()?>"><?=$sqlconnect->getIDOrden()?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    Estado
                                    <select class="formInput" id="Estatus">
                                        <?php
                                        if($sqlconnect->getIDEstatus() == 1){
                                            echo '<option value="1">Activado</option><option value="0">Desactivado</option>';
                                        }else{
                                            echo '<option value="0">Desactivado</option><option value="1">Activado</option>
                                ';
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div id="his">

                    </div>
                </div>

                <div id="result_modal"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" onclick="fn_caracteristicas_producto_web(4,<?=$_POST['idcaracteristica']?>)" ><i class="fa fa-save"></i> Guardar</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>