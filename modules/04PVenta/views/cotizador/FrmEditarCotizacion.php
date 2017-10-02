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


$FolioCotizacion = $_POST['FolioCotizacion'];
$Serie = $_POST['Serie'];

$connect->_query = "
SELECT 
a.FolioCotizacion,lpad(a.FolioCotizacion,6,'0'),a.NombreCliente,
a.MedioContacto,b.Descripcion,a.TipoCotizacion,c.Descripcion,a.NoCategoria,d.Descripcion,a.NoTipo,e.Descripcion,a.MontoSolicitado,
a.MontoAutorizado,a.CotizacionEmpeno,a.CotizacionCompra,a.Descripcion,a.Observaciones,a.NoEstatus,f.Descripcion,f.Texto1,
a.NoUsuarioSolicitante,g.NombreDePila,a.NoUsuarioRegistro,h.NombreDePila,date(a.FechaInicial),time(a.FechaInicial),
a.FechaVigencia,date(a.FechaRegistro),time(a.FechaRegistro),a.FechaRegistro,a.FechaUM,a.MontoPrestamo,a.SucursalPrestamo,a.BoletaPrestamo,a.FechaBoleta  
FROM BGECotizador as a 
LEFT JOIN BGECatalogoGeneral as b 
ON a.MedioContacto = b.OpcCatalogo AND b.CodCatalogo = 30 
LEFT JOIN BGECatalogoGeneral as c 
ON a.TipoCotizacion = c.OpcCatalogo AND c.CodCatalogo = 31 
LEFT JOIN BGECatalogoGeneral as d 
ON a.NoCategoria = d.OpcCatalogo AND d.CodCatalogo = 9 
LEFT JOIN BGECatalogoGeneral as e 
ON a.NoTipo = e.OpcCatalogo AND e.CodCatalogo = 5 AND e.Numero2 = a.NoCategoria 
LEFT JOIN BGECatalogoGeneral as f 
ON a.NoEstatus = f.OpcCatalogo AND f.CodCatalogo = 29 
LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as g 
ON a.NoUsuarioSolicitante = g.NoUsuario 
LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as h 
ON a.NoUsuarioRegistro = h.NoUsuario 
WHERE a.FolioCotizacion = $FolioCotizacion AND a.Serie = '$Serie' 
";

$connect->get_result_query();
$DataCotizacion = $connect->_rows[0];
$HoraInicial = $DataCotizacion[25];
$HoraFinal = $DataCotizacion[28];

?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsFormatoMoneda.js" language="JavaScript" ></script>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsPVenta.js"></script>
<script>

    if(<?=$_SESSION['menu_opciones'][5][2][1][0]['OpcionA']?> == 1){
        $("[type-alta]").removeClass('hidden');
    }
    if(<?=$_SESSION['menu_opciones'][5][2][1][0]['OpcionC']?> == 1){
        $("[type-cambio]").removeClass('hidden');
    }
    $(".tabs").tabs();

    $('.currency').numeric({prefix:'$ ', cents: true});

    $(".currency").focus(function () {
        $(this).select();
    });

    //showReloj('<?=date("Y-m-d")?>','<?=date("H:i:s")?>');

    $("#txtPrecio").on('keyup',function(e){

        if (e.keyCode == 13) {
            // Do something
            fn04CalculaCotizador($('#txtDemanda').val())
        }
    });

    $("#txtNombreCliente").focus();

    $("#txtPrecio").focus(function () {
        $("#FrmCalculadora").Frmreset();
    });

    $("#detalle input").attr('readonly',true);

</script>
<div class="panel panel-info">
    <div class="panel-heading padding-x3">
        <i class="fa fa-file"></i> Nueva Cotización
    </div>
    <div class="toolbars">
        <button class="btn btn-xs btn-primary" onclick="fnsdMenu(41,41)"><i class="fa fa-arrow-left"></i> Regresar</button>
        <button class="btn btn-xs hidden btn-default" type-alta onclick="fn04MenuCotizaciones(1)"><i class="fa fa-file"></i> Nueva Cotización</button>
        <button class="btn btn-xs hidden btn-success" type-cambio onclick="fn04EditarCotizacion(2,'<?=$HoraInicial?>',0)"><i class="fa fa-save"></i> Guardar</button>
    </div>
    <div class="panel-body">

        <div class="row row-sm">
            <div class="col-md-7 table-responsive">
                <table class="classtable">
                    <thead>
                    <tr>
                        <th colspan="3" style="border-bottom: 1px solid #121212">
                            <i class="fa fa-credit-card"></i> Datos del Cliente
                            <span id="liveclock" title="Hora Actual" data-toggle="tooltip" data-placement="top" class="pull-right bg-red badge"><?=$HoraFinal?></span>
                            <span class="pull-right bg-green badge" title="Hora Inicial" data-toggle="tooltip" data-placement="top"><?=$HoraInicial?></span>
                        </th>
                    </tr>
                    </thead>
                </table>

               <div class="tabs">
                   <ul>
                       <li><a href="#dgeneral"> Datos Generales</a> </li>
                       <li><a href="#detalle"> Detalle</a> </li>
                   </ul>

                   <div id="dgeneral">
                       <table class="classtable">
                           <tbody>
                           <tr>
                               <td>Folio: </td>
                               <td colspan="2">
                                   <input id="txtSerie" disabled value="<?=$Serie?>"  class="form-control hidden input-sm">
                                   <input id="txtFolio" disabled value="<?=$DataCotizacion[1]?>"  title="Folio Cotización" placeholder="Folio Cotización" class="form-control input-sm">
                               </td>

                           </tr>
                           <tr>
                               <td>Estatus: </td>
                               <td colspan="2"><input id="txtEstatus" readonly  value="<?=$DataCotizacion[19]?>"  title="Folio Cotización" placeholder="Folio Cotización" class="form-control input-sm"> </td>

                           </tr>

                           <tr>
                               <td >Cliente: </td>
                               <td colspan="2"><input id="txtNombreCliente"  value="<?=$DataCotizacion[2]?>" title="Nombre del Cliente" placeholder="Nombre del Cliente" class="form-control input-sm"> </td>

                           </tr>
                           <tr class="hidden">
                               <td width="130">Celular: </td>
                               <td colspan="2"><input id="txtCelular"  title="Celular" placeholder="Numero de Celular" class="form-control input-sm"> </td>
                           </tr>
                           <tr>
                               <td>Medio contacto: </td>
                               <td>
                                   <select id="idmediocontacto" title="Medio de Contacto"  class="form-control input-sm">
                                       <option value="<?=$DataCotizacion[3]?>"><?=$DataCotizacion[4]?></option>
                                       <?php
                                       $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 30 AND OpcCatalogo <> '$DataCotizacion[3]' AND NoEstatus = 1 ORDER BY Descripcion ASC";
                                       $connect->get_result_query();

                                       for($i=0; $i < count($connect->_rows); $i++ ){
                                           echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                       }
                                       ?>

                                   </select>
                               </td>
                               <td>
                                   <select id="idtipocotizacion" title="Tipo de Cotización"  class="form-control input-sm">
                                       <option value="<?=$DataCotizacion[5]?>"><?=$DataCotizacion[6]?></option>
                                       <?php
                                       $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 31 AND OpcCatalogo <> '$DataCotizacion[5]' AND NoEstatus = 1 ORDER BY Descripcion ASC";
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
                                       <option value="<?=$DataCotizacion[7]?>"><?=$DataCotizacion[8]?></option>
                                       <?php
                                       $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 9 AND OpcCatalogo <> '$DataCotizacion[7]' AND NoEstatus = 1 ORDER BY Descripcion ASC";
                                       $connect->get_result_query();

                                       for($i=0; $i < count($connect->_rows); $i++ ){
                                           echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                       }
                                       ?>
                                   </select>
                               </td>
                               <td>
                                   <select id="id_tpoproducto" title="Tipo de Aparato" class="form-control input-sm">
                                       <option value="<?=$DataCotizacion[9]?>"><?=$DataCotizacion[10]?></option>
                                       <?php
                                       $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 5 AND OpcCatalogo <> '$DataCotizacion[9]' AND NoEstatus = 1 ORDER BY Descripcion ASC";
                                       $connect->get_result_query();

                                       for($i=0; $i < count($connect->_rows); $i++ ){
                                           echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                       }
                                       ?>

                                   </select>
                               </td>
                           </tr>

                           <tr>
                               <td>Solicitado: </td>
                               <td><input id="txtMontoSolicitado" value="<?=$DataCotizacion[11]?>" class="form-control text-right currency input-sm" title="Monto Solicitado" data-placement="left" data-toggle="tooltip" placeholder="Monto Solicitado"> </td>
                               <td class="row-sm">
                                   <div class="col-md-3">
                                       Autorizado
                                   </div>

                                   <div class="col-md-9">

                                       <input id="txtMontoAutorizado" value="<?=$DataCotizacion[12]?>" class="form-control text-right currency input-sm" title="Monto Autorizado" data-placement="bottom" data-toggle="tooltip"  placeholder="Monto Autorizado">

                                   </div>
                               </td>
                           </tr>

                           <tr>
                               <td valign="top" >Descripción</td>
                               <td colspan="2">
                                   <textarea id="txtDescripcion" class="form-control" placeholder="Descripción detallada"><?=$DataCotizacion[15]?></textarea>
                               </td>
                           </tr>
                           <tr>
                               <td valign="top" >Observaciones</td>
                               <td colspan="2">
                                   <textarea sentences rows="01" autocomplete id="txtObservaciones" class="form-control" placeholder="Observaciones"><?=$DataCotizacion[16]?></textarea>
                               </td>
                           </tr>
                           </tbody>
                       </table>
                   </div>

                   <div id="detalle">

                       <div class="row row-sm">

                           <div class="col-md-4">
                               <div class="form-group">
                                   Sucursal Prestamo
                                   <input class="form-control input-sm" value="<?=$DataCotizacion[32]?>" />
                               </div>
                           </div>

                           <div class="col-md-2">
                               <div class="form-group">
                                   Boleta Prestamo
                                   <input class="form-control input-sm" value="<?=$DataCotizacion[33]?>" />
                               </div>
                           </div>

                           <div class="col-md-3">
                               <div class="form-group">
                                   Prestamo
                                   <input class="form-control text-right currency input-sm" value="<?=$DataCotizacion[31]?>" />
                               </div>
                           </div>

                           <div class="col-md-3">
                               <div class="form-group">
                                   Fecha Boleta
                                   <input class="form-control input-sm" value="<?=$DataCotizacion[34]?>" />
                               </div>
                           </div>

                       </div>

                       <br>
                       <div class="row row-sm">

                           <div class="col-md-4">
                               <div class="form-group">
                                   Usuario Solicita
                                   <input value="<?=$DataCotizacion[21]?>" class="form-control input-sm" />
                               </div>
                           </div>

                           <div class="col-md-4">
                               <div class="form-group">
                                   Usuario Registro
                                   <input value="<?=$DataCotizacion[23]?>" class="form-control input-sm" />
                               </div>
                           </div>

                           <div class="col-md-4">
                               <div class="form-group">
                                   Usuario UM
                                   <input value="<?=$DataCotizacion[23]?>" class="form-control input-sm" />
                               </div>
                           </div>
                       </div>

                       <div class="row row-sm">

                           <div class="col-md-4">
                               <div class="form-group">
                                   Fecha Inicial
                                   <input class="form-control input-sm" value="<?=$DataCotizacion[24]." " .$DataCotizacion[25]?>" />
                               </div>
                           </div>

                           <div class="col-md-4">
                               <div class="form-group">
                                   Fecha Vigencia
                                   <input class="form-control input-sm" value="<?=$DataCotizacion[26]?>" />
                               </div>
                           </div>

                           <div class="col-md-4">
                               <div class="form-group">
                                   Fecha Registro
                                   <input class="form-control input-sm" value="<?=$DataCotizacion[29]?>" />
                               </div>
                           </div>
                       </div>



                   </div>

               </div>

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