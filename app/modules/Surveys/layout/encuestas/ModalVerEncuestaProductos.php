<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 15/10/15
 * Time: 05:28 PM
 */

//$_POST['producto']
include "../../../../controller/Aparatos.class.php";
$connect = new \controller\Aparatos();
//Validar que el usuario este logueado
if(!$connect->ValidaAcceso()){$connect->returnHomePage();}

//validar tiempo de actividad
$connect->ValidaSession_id();

$QueryInfo = $connect->Consulta("select a.idEncuesta,a.Anio,a.CodProducto,c.Descripcion,d.Descripcion,a.Descripcion,a.Clasificacion,a.ImporteVenta,a.PorcentajeEmpeno,a.PorcentajeCompra,
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
 WHERE a.idEncuesta = '".$_POST['idEnc']."'");

$dataInfo = mysqli_fetch_array($QueryInfo);
?>
<script>
        $('#myModal2').modal('show');
        $("#myModal2").draggable({
            handle: ".modal-header"
        });
        $("input[type=text]").focus(function(){
            this.select();
        });

        $(".btn-primary").focus();

        $('.currency').numeric({prefix:'$ ', cents: true});
</script>
<div class="modal fade" data-backdrop="static"  id="myModal2" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"><img src="../../images/icons/tarea2.png"> Encuesta</h4>
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
                                            <select class="formInput">
                                                <?php
                                                $Query2 = $connect->Consulta("SELECT OpcCatalogo,Descripcion FROM BSISHELPDESK.BGECatalogoGeneral WHERE OpcCatalogo = $dataInfo[10] AND CodCatalogo = 17");
                                                $result = mysqli_fetch_array($Query2);
                                                ?>
                                                <option value="1"><?=  utf8_encode($result[1])?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Servicio: </td>
                                        <td>
                                            <select id="frmServicioEncuesta"  class="formInput">
                                                <option><?=utf8_encode($dataInfo[11])?></option>
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
                                            <select class="formInput">
                                                <?php
                                                if($dataInfo[19] == 3){
                                                    $qry = $connect->Consulta("SELECT OpcCatalogo,Descripcion FROM BSISHELPDESK.BGECatalogoGeneral WHERE CodCatalogo = 20 AND OpcCatalogo = $dataInfo[20]");
                                                }else{
                                                    $qry = $connect->Consulta("SELECT OpcCatalogo,Descripcion FROM BSISHELPDESK.BGECatalogoGeneral WHERE CodCatalogo = 9 AND OpcCatalogo = $dataInfo[20]");
                                                }
                                                while($result = mysqli_fetch_array($qry)){
                                                    echo "<option value='".$result[0]."'>".$result[1]."</option>";
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
                                                echo "<select class='formInput' id='dcalidadMetal'>";
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
                                                <select id="tpo_articulo" class="formInput prod">
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
                                                    <select id="id_marca" class="formInput prod">
                                                        <option value="0"><?=$dataInfo[4]?></option>
                                                    </select>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nombre Producto: </td>
                                        <td>
                                            <select id="nombreprod" class="formInput prod">
                                                <?php
                                                if($dataInfo[2] > 0){
                                                    echo " <option>".utf8_encode($dataInfo[5])."</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Condiciones: </td>
                                        <td>
                                            <select id="frmCondicionesEncuesta" class="formInput">
                                                <option><?=utf8_encode($dataInfo[12])?></option>
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
                                            <input id="frmMontoSolicitaEncuesta" readonly value="<?=$dataInfo[13]?>" type="text" class="formInput text-right currency">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 10em ">Competidor: </td>
                                        <td>
                                            <select id="frmCompetidorEncuesta" class="formInput">
                                                <option><?=utf8_encode($dataInfo[14])?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Importe competidor: </td>
                                        <td>
                                            <input id="frmMontoCompetidorEncuesta" readonly value="<?=$dataInfo[15]?>" type="text" class="formInput text-right currency">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height:3px" colspan="2">
                                            Descripción Producto &nbsp;<span class="small">(Si no esta dentro del catalogo)</span>
                                            <textarea id="DescripcionProducto" style="height: 5em;" class="form-control"disabled ><?=$dataInfo[16]?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height:3px"  colspan="2">
                                            Observaciones
                                            <textarea id="observaciones" style="height: 5em;" class="form-control" disabled ><?=$dataInfo[17]?></textarea>
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