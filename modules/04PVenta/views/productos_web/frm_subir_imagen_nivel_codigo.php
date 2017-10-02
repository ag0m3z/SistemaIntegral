<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 11/05/2017
 * Time: 10:58 AM
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

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
$idCodigo = $_POST['idcodigo'];
$idSerie = $_POST['idserie'];

$SqlServer = new \core\sqlconnect();


?>

<script src="<?=\core\core::ROOT_APP?>plugins/webcam/webcam.min.js"></script>
<script>
    setOpenModal("mdl_subir_imagen_codigo");
    fn_listar_imagenes_producto_web(1,'<?=$_POST['idcodigo']?>','<?=$_POST['idserie']?>');

</script>
<div class="modal fade" id="mdl_subir_imagen_codigo" data-backdrop="static" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-image"></i> Subir Imagenes - Nivel Codigo</h4>
            </div>
            <div class="modal-body">

                <button onclick="$('#btn_upload').click();" class="btn btn-sm btn-success"><i class="fa fa-upload"></i> Subir</button>
                <input id="btn_upload" class="hidden" type="file" onchange="fn_imagen_producto_web(1,'<?=$_POST['idcategoria']?>','<?=$_POST['idcodigo']?>','<?=$_POST['idserie']?>',0)" accept="file_extension| ,.gif, .jpg, .png," name="btn_upload" >

                <div class="row ">
                    <div class="col-md-12 scroll-auto" style="max-height: 45vh;" >
                        <table class="table table-condensed table-hover">
                            <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Tamaño</th>
                                <th>Eliminar</th>
                            </tr>
                            </thead>
                            <tbody id="listar_imagenes">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="result"></div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-danger btn-sm" onclick="$('#cam_stop').click();" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>
