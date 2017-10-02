<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 05/05/2017
 * Time: 05:27 PM
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
include "../../../../core/model_encuestas.php";

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

$connect = new \core\model_encuestas($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();


/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/

$connect->_query = "select a.idEncuesta,a.Anio,a.CodProducto,c.Descripcion,d.Descripcion,a.Descripcion,a.Clasificacion,a.ImporteVenta,a.PorcentajeEmpeno,a.PorcentajeCompra,
a.NoAtendidos,f.Descripcion,CONCAT(g.Descripcion,' - ',g.Texto1),IncMontoSolicita,h.Descripcion,a.IncMontoCompetidor,a.Descripcion,a.Observacion,i.Descripcion,a.IncidenciaTipoServicio,a.CategoriaServicio,a.NoCategoria,a.CalidadMetal,j.Descripcion
FROM BGEEncuestaProducto as a
LEFT JOIN BGECatalogoGeneral as c
ON a.TipoProducto = c.OpcCatalogo AND c.CodCatalogo = 5
LEFT JOIN BGECatalogoGeneral as d
ON a.NoMarca = d.OpcCatalogo AND d.CodCatalogo = 6
LEFT JOIN BGECatalogoGeneral as e
ON a.NoAtendidos = e.OpcCatalogo AND e.CodCatalogo = 17
LEFT JOIN BGECatalogoGeneral as f
ON a.IncidenciaTipoServicio = f.OpcCatalogo AND f.CodCatalogo = 14
LEFT JOIN BGECatalogoGeneral as g
ON a.IncCondiciones = g.OpcCatalogo AND g.CodCatalogo = 15
LEFT JOIN BGECatalogoGeneral as h
ON a.IncNoCompetidor = h.OpcCatalogo AND h.CodCatalogo = 16
LEFT JOIN BGECatalogoGeneral as i
ON a.CategoriaServicio = i.OpcCatalogo AND i.CodCatalogo = 20
LEFT JOIN BGECatalogoGeneral as j
ON a.CalidadMetal = j.OpcCatalogo AND j.CodCatalogo = 21
 WHERE a.idEncuesta = '".$_POST['idEnc']."'";

$connect->get_result_query();
$info_encuesta = $connect->_rows[0];

$dataInfo = $info_encuesta;
?>
<script>
    $('#myModal2').modal('show');
    $("#myModal2").draggable({
        handle: ".modal-header"
    });

//    $("input").attr("disabled","disabled");
//    $("select").attr("disabled","disabled");
</script>
<div class="modal fade" data-backdrop="static"  id="myModal2" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"><img src="<?=\core\core::ROOT_APP?>site_design/img/icons/tarea2.png">  Encuesta</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="tablaDetailticket">
                                    <tr>
                                        <td>Cte. No Atendido: </td>
                                        <td style="width: 250px;">
                                            <select disabled class="formInput">
                                                <?php
                                                $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE OpcCatalogo = $dataInfo[10] AND CodCatalogo = 17";
                                                $connect->get_result_query();
                                                $result = $connect->_rows[0];
                                                ?>
                                                <option value="1"><?=$result[1]?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Servicio: </td>
                                        <td>
                                            <select disabled id="frmServicioEncuesta"  class="formInput">
                                                <option><?=$dataInfo[11]?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            if($dataInfo[19]==3){
                                                $TpoServicio = "Tipo Servicio";
                                            }else{
                                                $TpoServicio = "No Categoria";
                                            }
                                            ?>
                                            <?=$TpoServicio?></td>
                                        <td>
                                            <select disabled class="formInput">
                                                <?php
                                                if($dataInfo[19] == 3){
                                                    $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 20 AND OpcCatalogo = $dataInfo[20]";
                                                }else{
                                                    $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 9 AND OpcCatalogo = $dataInfo[20]";
                                                }
                                                $connect->get_result_query();
                                                $result = $connect->_rows;
                                                for($i=0;$i <count($result);$i++){
                                                    echo "<option value='".$result[$i][0]."'>".$result[$i][1]."</option>";
                                                }
                                                ?>

                                            </select>
                                        </td>
                                    </tr>
                                    <tr style="height: 38px;">
                                        <td>
                                            <?php
                                            if($dataInfo[21]==1 || $dataInfo[21]==7 ){
                                                echo "Calidad Metal: ";

                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if($dataInfo[21]==1 || $dataInfo[21]==7 ){
                                                echo "<select disabled class='formInput' id='dcalidadMetal'>";
                                                echo "<option value='".$result[22]."' >".$dataInfo[23]."</option>";
                                                echo "</select>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tipo Producto: </td>
                                        <td>
                                            <form class="formDataProducto">
                                                <select disabled id="tpo_articulo" class="formInput prod">
                                                    <option value="0"><?=$dataInfo[3]?></option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Marca: </td>
                                        <td style="width: 14em;">
                                            <form class="formDataProducto">
                                                <div id="loadMarca">
                                                    <select disabled id="id_marca" class="formInput prod">
                                                        <option value="0"><?=$dataInfo[4]?></option>
                                                    </select>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nombre Producto: </td>
                                        <td>
                                            <select disabled id="nombreprod" class="formInput prod">
                                                <?php
                                                if($dataInfo[2] > 0){
                                                    echo " <option>".$dataInfo[5]."</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Condiciones: </td>
                                        <td>
                                            <select disabled id="frmCondicionesEncuesta" class="formInput">
                                                <option><?=$dataInfo[12]?></option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6" style="border-left:1px solid #ccc; ">
                                <table class="tablaDetailticket" style="width: 100%">
                                    <tr>
                                        <td>Importe cliente solicita: </td>
                                        <td>
                                            <input disabled id="frmMontoSolicitaEncuesta" readonly value="<?=$dataInfo[13]?>" type="text" class="formInput text-right currency">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 10em ">Competidor: </td>
                                        <td>
                                            <select disabled id="frmCompetidorEncuesta" class="formInput">
                                                <option><?=$dataInfo[14]?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Importe competidor: </td>
                                        <td>
                                            <input disabled id="frmMontoCompetidorEncuesta" readonly value="<?=$dataInfo[15]?>" type="text" class="formInput text-right currency">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height:3px" colspan="2">
                                            Descripción Producto &nbsp;<span class="small">(Si no esta dentro del catalogo)</span>
                                            <textarea disabled id="DescripcionProducto" style="height: 5em;" class="form-control"disabled ><?=$dataInfo[16]?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height:3px"  colspan="2">
                                            Observaciones
                                            <textarea disabled id="observaciones" style="height: 5em;" class="form-control" disabled ><?=$dataInfo[17]?></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div id="divresult"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: left;margin-top: -18px;">
                <button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>