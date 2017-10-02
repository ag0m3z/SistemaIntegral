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
$idCategoria = $_POST['idcategoria'];
$idCodigo = $_POST['idcodigo'];
$idSerie = $_POST['idserie'];

//Imagenes a Nivel Serie
$SqlServer = new \core\sqlconnect();
$SqlServer->_sqlQuery = "SELECT idSerie,idCodigo,idImagen,NombreImagen,TamanoImagen FROM SAyT.dbo.INVProdImagen WHERE idCodigo = '$idCodigo' AND idSerie = '$idSerie' ";
$SqlServer->get_result_query();
$lista_img = $SqlServer->_sqlRows;


//Imagenes a Nivel Codigo
$SqlServer->_sqlQuery = "SELECT idSerie,idCodigo,idImagen,NombreImagen,TamanoImagen FROM SAyT.dbo.INVProdImagen WHERE idCodigo = '$idCodigo' AND idSerie = ' ' ";
$SqlServer->get_result_query();
$lista_img_codigo = $SqlServer->_sqlRows;

?>

<script src="<?=\core\core::ROOT_APP?>plugins/webcam/webcam.min.js"></script>
<script>
    setOpenModal("mdl_subir_imagen_codigo");
    fn_listar_imagenes_producto_web(2,'<?=$_POST['idcodigo']?>','<?=$_POST['idserie']?>');
    $('input[type="checkbox"]').click(function(){$(this).attr('disabled',true);});

    <?php
    if(count($lista_img)>0){echo "$('#img-codigo').hide()";}else{echo "$('#img-series').hide()";;}
    ?>
</script>
<div class="modal fade" id="mdl_subir_imagen_codigo" data-backdrop="static" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-image"></i> Subir Imagenes - Nivel Serie</h4>
            </div>
            <div class="modal-body">

               <div class="row" id="img-series">
                   <div class="col-md-12">
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
                   </div>
               </div>

                <div class="row" id="img-codigo">
                    <div class="col-md-12 scroll-auto" style="max-height: 45vh;">
                        <label>Seleccionar imagenes del codigo</label>

                        <table class="table table-condensed table-hover">
                            <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Tamaño</th>
                                <th>Seleccionar</th>
                            </tr>
                            </thead>
                            <tbody id="listar_imagenes">
                            <?php
                            for($i=0;$i < count($lista_img_codigo[$i]);$i++) {

                                $idImagen = $lista_img_codigo[$i][2];

                                echo "<tr><td><img width='35' src='modules/04PVenta/src/productos_web/fn_mostrar_image_producto.php?tpo=1&id=$idImagen' /></td><td>" . $lista_img_codigo[$i][3] . "</td><td>" . round($lista_img_codigo[$i][4] * 0.0009765625, 2) . "KB</td>
                                     <td width='65'>
                                      <span class='btn-link btn btn-xs' onclick='fn_ve_imagen_producto_web(1,\"" . $idCodigo . "\",\"" . $idSerie . "\",\"" . $idImagen . "\")' ><i class='fa fa-eye text-primary'></i></span>
                                      <input type='checkbox' onclick='fn_agregar_imagenes(3,\"" . $idCategoria . "\",\"" . $idCodigo . "\",\"" . $idSerie . "\",\"" . $idImagen . "\")' />
                                     </td>
                                     </tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                        <span class='btn btn-link' id="show_tables" onclick="$('#img-codigo').hide();$('#img-series').show();" >Terminar y Agregar Mas Caracteristicas</span>

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
