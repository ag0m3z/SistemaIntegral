<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 26/02/16
 * Time: 05:02 PM
 */

$cons_query = $connect->_query = "SELECT a.Folio,a.Anio,a.NoDepartamento,b.Descripcion,a.NoSucursal,b.Correo,b.Descripcion,a.idEmpleado,a.NoUsuarioCierre,c.NombreDePila,a.SolucionCierre,a.DescripcionReporte,a.HoraCierre,a.FechaCierre,a.Fecha,a.HoraInicioReporte
FROM BSHReportes as a
LEFT JOIN BGECatalogoDepartamentos as b
ON a.NoSucursal = b.NoDepartamento
LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c
ON a.NoUsuarioCierre = c.NoUsuario
where a.Anio = '".base64_decode($AnioTicket)."' And a.NoDepartamento = '".base64_decode($NoDepartamento)."' AND a.Folio = '".base64_decode($NoTicket)."' ";

$connect->get_result_query();
$dataRow = $connect->_rows[0];

//Consulta para Saber que Encuesta esta Activa por Departamento

$connect->_query = "SELECT OpcCatalogo,Texto2 FROM BGECatalogoGeneral WHERE NoEstatus = 1 AND Texto1 = '".base64_decode($NoDepartamento)."'  ORDER BY OpcCatalogo ASC";
$connect->get_result_query();

if(count( $connect->_rows)> 0 ){
    $idEncuestaActiva =$connect->_rows[0];
}


?>
<script language="JavaScript">
    $(document).ready(
        function(){
            $('input:radio').click(
                function(){
                    if($("input:radio[name='resp1']:checked").val() == 5){
                        $("#otras5").css("display","block");$("#otras5").focus();

                    }else{
                        $("#otras5").css("display","none");
                    }

                }
            );
        }
    );
</script>
<style type="text/css">
    .addbold{
        font-weight: bold;
        color: #2C33B2 !important;
    }
    .rmbold{
        margin-left: 25px;
    }
    label{
        font-weight: normal !important;
        color:#474747 !important;
    }
</style>

<div class="panel panel-info">
    <div class="panel-heading" style="padding: 5px;">
        <img src="../../../site_design/img/icons/tarea2.png"> Encuesta Ticket de Servicio: <?=base64_decode($NoTicket)?>
        <span class="pull-right"><button class="btn btn-default btn-xs" data-toggle="modal" data-target="#myModal"><i class="fa fa-eye"></i> ver reporte</button></span>
    </div>
    <div class="panel-body">
        <div class="col-md-12">
            <div class="row">
                <p class="addbold"><?=$idEncuestaActiva[1]?></p>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <form name="dencuesta" id="dencuesta">
                        <table class="tablesorter" style="width: 100%">

                            <?php
                            $id=0;
                            $connect->_query = "SELECT idPregunta,Descripcion FROM BGECatalogoPreguntas where NoEncuesta = ".$idEncuestaActiva[0]." AND NoDepartamento = '".base64_decode($NoDepartamento)."' AND NoEstatus = 1";
                            $connect->get_result_query();

                            if(count($connect->_rows) > 0 ){

                                $preg = $connect->_rows;
                                for($i=0;$i < count($preg);$i++){
                                    $id++;
                                    echo "<tr>
                                <td class='addbold'>".$id.".- ".$preg[$i][1]."</td>";
                                    echo "</tr>";

                                    $idPregunta =  $preg[$i][0];

                                    $connect->_query = "SELECT idPregunta,idRespuesta,Descripcion,Texto FROM BGECatalogoRespuestas WHERE NoEstatus = 1 AND idPregunta= $idPregunta" ;
                                    $connect->get_result_query();
                                    $resp = $connect->_rows;
                                    echo "<tr><td>";

                                    for($i2=0;$i2 < count($resp);$i2++){
                                        echo "<label class='rmbold'><input class='rsp01' type='radio' value='".$resp[$i2][1]."' name='resp".$id."' id='resp".$preg[$i][0]."'> ".$resp[$i2][2]." <input class='pull-right' style='display:none; width:35em;' id='otras".$resp[$i2][1]."' type='text'></label>";
                                    }
                                    echo "</td></tr>";

                                }
                            }
                            ?>
                            <tr>
                                <td>
                                    <textarea id="comentarios" class="formInput" style="height: 105px; "></textarea>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
            <div id="result22"></div>
            <div id="imgLoad"></div>
            <br/>
            <div class="row">
                <button id="btnsave" class="btn btn-primary btn-sm" onclick="GuardarEncuesta(5,'<?=base64_decode($AnioTicket)?>','<?=base64_decode($NoDepartamento)?>','<?=base64_decode($NoTicket)?>',<?=$idEncuestaActiva[0]?>)"><i class="fa fa-floppy-o"></i> Guardar</button>
                <a href="javascript:void(0)" onclick="$('#dencuesta').Frmreset();" class="btn btn-default btn-sm"><i class="fa fa-trash"></i> Limpiar</a>
                <a href="javascript:void(0)" onclick="window.close();" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Cerrar</a>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px">Informaci&oacute;n de Reporte - <?=$dataRow[3]?></h4>
            </div>
            <div class="modal-body" style="margin-bottom: -5px;">
                <table class="tablaDetailticket2">
                    <tr>
                        <td style="width: 25%">Departamento/Sucursal: </td>
                        <td colspan="2"><input disabled type="text" value="<?=$dataRow[6]?>" class="formInput"> </td>
                    </tr>
                    <tr>
                        <td>Solicitante: </td>
                        <td colspan="2"><input disabled type="text" value="<?=$dataRow[7]?>" class="formInput"> </td>
                    </tr>
                    <tr>
                        <td>Fecha/Hora: </td>
                        <td><input disabled type="text" value="<?=$connect->getFormatFecha($dataRow[14],2)?>" class="formInput"> </td>
                        <td><input disabled type="text" value="<?=$dataRow[15]?>" class="formInput"> </td>
                    </tr>
                    <tr>
                        <td>Reporte: </td>
                        <td colspan="2"><textarea disabled type="text"  style="height: 75px" class="formInput"><?=$dataRow[11]?></textarea> </td>
                    </tr>
                    <tr>
                        <td>Agente Cierre: </td>
                        <td colspan="2"><input disabled type="text" value="<?=$dataRow[9]?>" class="formInput"> </td>
                    </tr>
                    <tr>
                        <td>Fecha/Hora: </td>
                        <td><input disabled type="text" value="<?=$connect->getFormatFecha($dataRow[13],2)?>" class="formInput"> </td>
                        <td><input disabled type="text" value="<?=$dataRow[12]?>" class="formInput"> </td>
                    </tr>
                    <tr>
                        <td>Soluci&oacute;n: </td>
                        <td colspan="2"><textarea disabled type="text" style="height: 125px" class="formInput"><?=$dataRow[10]?></textarea> </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer" style="text-align: left;margin-top: -18px;">
                <button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
