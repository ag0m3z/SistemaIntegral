<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 12/09/2017
 * Time: 09:24 AM
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

$HoraInicial = date("H:i:s");

if($_SESSION['MenuOpciones'][5][2][1][0]['OpcionA'] == 1){

}

?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsFormatoMoneda.js" language="JavaScript" ></script>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsPVenta.js"></script>
<div class="panel panel-info">
    <script>

        if(<?=$_SESSION['menu_opciones'][5][2][1][0]['OpcionA']?> == 1){
            $("[type-alta]").removeClass('hidden');
        }

        $('.currency').numeric({prefix:'$ ', cents: true});
        $(".currency").focus(function () {
            $(this).select();
        })
        showReloj('<?=date("Y-m-d")?>','<?=date("H:i:s")?>');
        $("#txtPrecio").on('keyup',function(e){

            if (e.keyCode == 13) {
                // Do something
                fn04CalculaCotizador($('#txtDemanda').val())
            }
        });

        $("#txtNombreCliente").focus();

        $("#txtMontoSolicitado").on('keyup',function(e){

            var calc = $("#calc_import");

            calc.val($(this).val());

        });

        $("#txtPrecio").focus(function () {
            $("#FrmCalculadora").Frmreset();
        });



    </script>
    <div class="panel-heading padding-x3">
        <i class="fa fa-file"></i> Nueva Cotización
    </div>
    <div class="toolbars">
        <button class="btn btn-xs btn-primary" onclick="fnsdMenu(41,41)"><i class="fa fa-arrow-left"></i> Regresar</button>
        <button id="NuevaCotizacion" type-alta class="btn btn-xs hidden btn-default" onclick="fn04MenuCotizaciones(1)"><i class="fa fa-file"></i> Nueva Cotización</button>
        <button id="GuardarNuevaCotizacion" type-alta class="btn hidden btn-xs btn-success" onclick="fn04GuardarCotizacion('<?=$HoraInicial?>')"><i class="fa fa-save"></i> Guardar</button>
    </div>
    <div class="panel-body">

        <div class="row row-sm">

            <div class="col-md-7 table-responsive">
                <table class="classtable">
                    <thead>
                    <tr>
                        <th colspan="3" style="border-bottom: 1px solid #121212">
                            <i class="fa fa-credit-card"></i> Datos del Cliente
                            <span id="liveclock" title="Hora Actual" data-toggle="tooltip" data-placement="top" class="pull-right bg-red badge"><?=$HoraInicial?></span>
                            <span class="pull-right bg-green badge" title="Hora Inicial" data-toggle="tooltip" data-placement="top"><?=$HoraInicial?></span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr><td><br></td></tr>
                    <tr>
                        <td width="130">Cliente: </td>
                        <td colspan="2"><input id="txtNombreCliente"  title="Nombre del Cliente" placeholder="Nombre del Cliente" class="form-control input-sm"> </td>
                    </tr>
                    <tr class="hidden">
                        <td width="152">Celular: </td>
                        <td colspan="2"><input id="txtCelular"  title="Celular" placeholder="Numero de Celular" class="form-control input-sm"> </td>
                    </tr>
                    <tr>
                        <td>Medio contacto: </td>
                        <td>
                            <select id="idmediocontacto" title="Medio de Contacto"  class="form-control input-sm">
                                <option value="0">- Selecciona un medio -</option>
                                <?php
                                $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 30 AND NoEstatus = 1 ORDER BY Descripcion ASC";
                                $connect->get_result_query();

                                for($i=0; $i < count($connect->_rows); $i++ ){
                                    echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                }
                                ?>

                            </select>
                        </td>
                        <td>
                            <select id="idtipocotizacion" title="Tipo de Cotización"  class="form-control input-sm">
                                <option value="0">- Tipo de cotización -</option>
                                <?php
                                $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 31 AND NoEstatus = 1 ORDER BY Descripcion ASC";
                                $connect->get_result_query();

                                for($i=0; $i < count($connect->_rows); $i++ ){
                                    echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                }
                                ?>

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Categoría: </td>
                        <td>
                            <select id="idcategoria" title="Categoría" onchange="fnpv_load_categorias_clasificacion(11,this.value)"  class="form-control input-sm">
                                <option value="0">- Selecciona una categoria -</option>
                                <?php
                                $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 9 AND NoEstatus = 1 ORDER BY Descripcion ASC";
                                $connect->get_result_query();

                                for($i=0; $i < count($connect->_rows); $i++ ){
                                    echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select id="id_tpoproducto" title="Tipo de Aparato" class="form-control input-sm">
                                <option value="0">- Seleccione un tipo -</option>

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Solicitado: </td>
                        <td><input id="txtMontoSolicitado" class="form-control text-right currency input-sm" title="Monto Solicitado" data-placement="left" data-toggle="tooltip" placeholder="Monto Solicitado"> </td>
                        <td class="row-sm">
                            <div class="col-md-3">
                                Autorizado
                            </div>

                            <div class="col-md-9">

                                <input id="txtMontoAutorizado" class="form-control text-right currency input-sm" title="Monto Autorizado" data-placement="bottom" data-toggle="tooltip"  placeholder="Monto Autorizado">

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" >Descripción</td>
                        <td colspan="2">
                            <textarea id="txtDescripcion"  rows="05" class="form-control" placeholder="Descripción detallada"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" >Observaciones</td>
                        <td colspan="2">
                            <textarea sentences rows="02" autocomplete id="txtObservaciones" class="form-control" placeholder="Observaciones"></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-5">
                <table class=" classtable ">
                    <thead>
                    <tr>
                        <th colspan="3" style="border-bottom: 1px solid #121212"><i class="fa fa-calculator"></i> Calculadora</th>
                    </tr>
                    <tr><td><br></td></tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <form id="FrmCalculadora">
                <div class="row row-sm bg-r">

                    <div class="col-md-12">
                        <label style="width: 100%;" class="callout padding-x3 text-center callout-vino">Cotizador</label>
                    </div>

                    <div class="col-md-3 text-right">
                        <label class="text-bold text-justify text-right">
                            Precio
                        </label>
                    </div>
                    <div class="col-md-4">
                        <input id="txtPrecio" onblur="fn04CalculaCotizador($('#txtDemanda').val())" class="form-control input-sm text-bold currency text-right">
                    </div>
                    <div class="col-md-3">
                        <p class="small">Precio actual en el mercado</p>

                    </div>

                    <div class="col-md-3 text-right">
                        <label class="text-bold text-justify text-right">
                            Demanda
                        </label>
                    </div>
                    <div class="col-md-4">
                        <select id="txtDemanda" onchange="fn04CalculaCotizador(this.value)" class="form-control input-sm text-center text-bold">
                            <option value="0.66">Alta</option>
                            <option value="0.46">Media</option>
                            <option selected value="0.36">Baja</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <p class="small">Define si el artículo es comercializable</p>

                    </div>

                    <div class="col-md-3 text-right">
                        <label class="text-bold text-justify text-right">
                            Precio PEX
                        </label>
                    </div>
                    <div class="col-md-4">
                        <input id="txtPrecioPEX" readonly class="form-control input-sm currency text-bold text-right">
                    </div>
                    <div class="col-md-3">
                        <p class="small">Precio al que se pondra en venta</p>

                    </div>

                    <div class="col-md-3 text-right">
                        <label class="text-bold text-justify text-bold text-right">
                            &nbsp;
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label style="width: 100%;" class="callout padding-x3 text-center callout-vino">Empeño</label>
                    </div>
                    <div class="col-md-4">
                        <label style="width: 100%;" class="callout padding-x3 text-center callout-vino">Compra</label>
                    </div>

                    <div class="col-md-3 text-right">
                        <label class="text-bold text-justify text-right">
                            Clasificacion A
                        </label>
                    </div>
                    <div class="col-md-4">
                        <input id="txtClaseAE" readonly class="form-control input-sm text-bold currency text-right">
                    </div>
                    <div class="col-md-4 margin-bottom">
                        <input id="txtClaseAC" readonly class="form-control input-sm text-bold currency text-right">
                    </div>

                    <div class="col-md-3 text-right">
                        <label class="text-bold text-justify text-right">
                            Clasificacion B
                        </label>
                    </div>
                    <div class="col-md-4">
                        <input id="txtClaseBE" readonly class="form-control input-sm text-bold currency text-right">
                    </div>
                    <div class="col-md-4 margin-bottom">
                        <input id="txtClaseBC" readonly class="form-control input-sm text-bold currency text-right">
                    </div>


                    <div class="col-md-3 text-right">
                        <label class="text-bold text-justify text-right">
                            Clasificacion C
                        </label>
                    </div>
                    <div class="col-md-4">
                        <input id="txtClaseCE" readonly class="form-control input-sm text-bold currency text-right">
                    </div>
                    <div class="col-md-4 margin-bottom">
                        <input id="txtClaseCC" readonly class="form-control input-sm text-bold currency text-right">
                    </div>

                    <div class="col-md-3 text-right">
                        <label class="text-bold text-justify text-right">
                            Clasificacion D
                        </label>
                    </div>
                    <div class="col-md-4">
                        <input readonly value="0" class="form-control input-sm text-bold currency text-right">
                    </div>
                    <div class="col-md-4 margin-bottom">
                        <input readonly value="0" class="form-control input-sm text-bold currency text-right">
                    </div>

                </div>
                </form>


                <div class="row row-sm">

                    <div class="col-md-12">
                        <div id="result_calc"></div>
                    </div>
                </div>
            </div>


        </div>


    </div>
</div>