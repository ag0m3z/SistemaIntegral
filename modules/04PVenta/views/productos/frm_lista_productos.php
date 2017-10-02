<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 21/02/2017
 * Time: 10:36 AM
 */
/**
 * Incluir las Librerias Principales del Sistema
 * En el Siguiente Orden
 * ruta de libreias: @@/SistemaIntegral/core/
 *
 * 1.- core.php;
 * 2.- sesiones.php
 * 3.- seguridad.php
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

//vaciar variable donde se almacenan los reportes
unset($_SESSION["EXPORT"]);



?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsPVenta.js" language="JavaScript"></script>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsCalendario.js" language="JavaScript" ></script>
<script language="JavaScript">
    fnpv_listar_productos(1,null);

    $('.currency').numeric({prefix:'$ ', cents: true});
    $("input").focus(function(){
        this.select();
    });

    $("#calc_import").on('keyup', function (e) {
        if (e.keyCode == 13) {
            // Do something
            fnpv_calcula_precio(2);
        }
    });


</script>

<div class="panel panel-info">
    <div class="panel-heading" style="padding: 3px;">
        <i class="fa fa-table"></i> Cotización de Productos
    </div>
    <div class="toolbars">
        <button id="btnHome" class="btn btn-primary btn-xs" onclick="fnsdMenu(15,null)" ><i class="fa fa-refresh"></i> </button>
        <button id="btnList" class="btn btn-primary btn-xs" onclick="fnsdMenu(15,null)" ><i class="fa fa-list"></i> Lista</button>
        <?php
        // Boton de Encuesta
        $BtnEncuesta = '<button data-opcion="alta" class="btn btn-default btn-xs" onclick="fnpv_show_modal_encuestas()" ><i class="fa fa-clipboard"></i> Encuesta</button>';

        if($_SESSION['menu_opciones'][5][1][1][0]['OpcionA'] == 1){
            // Boton de nuevo Producto
            $btnNuevo = '<button class="btn btn-primary btn-xs" onclick="fnpv_nuevo_producto()"><i class="fa fa-file"></i> Nuevo</button>';
            //Boton Guardar Nuevo Producto
            $btnGuardar = '<button class="btn btn-success btn-xs hidden" onclick="fnpv_registra_articulo()" id="btn3"><i class="fa fa-save"></i> Guardar</button>';
        }
        if($_SESSION['menu_opciones'][5][1][1][0]['OpcionB'] == 1){
        }
        if($_SESSION['menu_opciones'][5][1][1][0]['OpcionC'] == 1){
            $BtnGuardarC ='<button class="btn btn-success btn-xs" onclick="fnpv_guardar_cambios()" id="btn5" ><i class="fa fa-floppy-o"></i> Guardar</button>';
        }
        if($_SESSION['menu_opciones'][5][1][1][0]['OpcionV'] == 1){
            $Btn = '<button data-opcion="vista" class="btn btn-default btn-xs" onclick="$(\'#myModal2\').modal(\'show\');"><i class="fa fa-search"></i> Buscar</button>';
            $Btn2 = '        <button class="btn btn-primary btn-xs" onclick="setOpenModal(\'mdl_calcula_precios\');" ><i class="fa fa-calculator"></i> Calcular Precio</button>';
        }
        if($_SESSION['menu_opciones'][5][1][1][0]['OpcionR'] == 1){
            $BtnReporte ='<button data-opcion="reporte" class="btn btn-default btn-xs" onclick="fnpv_exportar_resultado(1,\'agomez\')"><i class="fa fa-file-excel-o"></i> Exportar</button>';
        }
        echo $btnNuevo.' '.$btnGuardar.' '.$BtnGuardarC.' '.$Btn.' '.$Btn2.' '.$BtnEncuesta.' '.$BtnReporte;
        ?>
        <span class="small pull-right">Se encontraron <span id="badgeTtPtoductos" class="badge bg-green">25</span> Productos</span>
    </div>
    <div class="panel-body no-padding">
        <div id="lListarTabla">
            <div id="myGrid" style="height: 80vh;font-size: 12px;">

            </div>
        </div>
    </div>
</div>

<div class="ModalAjax"></div>

<!-- Modal para Realizar el Calculo de Cotizacion -->
<div class="modal fade bs-example-modal-sm" id="mdl_calcula_precios" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Calculo de Empeño y Venta</h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-sm-4">
                        <select id="idcategoria" class="form-control input-sm">
                            <option value="0">- Selecciona una categoria -</option>
                            <?php
                            $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 9 AND NoEstatus = 1 ORDER BY Descripcion ASC";
                            $connect->get_result_query();

                            for($i=0; $i < count($connect->_rows); $i++ ){
                                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                            }
                            ?>

                        </select>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control input-sm right currency text-right" placeholder="$ 0.00" style="width: 100%;" id="calc_import">
                    </div>

                    <div class="col-sm-2">
                        <a href="#" onclick="fnpv_calcula_precio()" class="btn btn-primary btn-xs btn-block pull-left ">Calcular</a>

                    </div>

                </div>
                <div id="result_calc">
                    <table class="tablesorter table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="text center" rowspan='2' valign="middle">Clasificaci&oacute;n</th>
                            <th rowspan='2' valign='middle' >Empe&ntilde;o</th>
                            <th style='text-align: center;' colspan='3'>Compra</th>
                        </tr>
                        <tr>
                            <th>Excelente Compra</th>
                            <th>Buena Compra</th>
                            <th>Maxima Compra</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class='text-center'>A</td>
                            <td class='text-right'><span class='right currency'>0</span></td>
                            <td class='text-right'><span class='right currency'> 0</span></td>
                            <td class='text-right'><span class='right currency'> 0</span></td>
                            <td class='text-right'><span class='right currency'> 0</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="border:none;">
                <button type="button" class="btn btn-danger active btn-sm pull-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!--
    ## Inicio de Modal para realizar la Busqueda de los Producots
    -->
<div class="modal fade dropModal" id="myModal2" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-search"></i> Catalogo Productos</h4>
            </div>
            <div class="modal-body">
                <form id="frmBuscaProducta">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="tablaDetailticket">
                                <tr>
                                    <td>Producto: </td>
                                    <td><input type="text"  id="id_producto2" class="formInput" /></td>
                                </tr>
                                <tr>
                                    <td>Categoria: </td>
                                    <td width="250">
                                        <select class="formInput" onchange="fnpv_load_categorias_clasificacion(1,this.value)" id="id_categoria_producto">
                                            <option value="0">-- --</option>
                                            <?php
                                            $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 9 AND NoEstatus = 1 ORDER BY Descripcion ASC";
                                            $connect->get_result_query();

                                            for($i=0; $i < count($connect->_rows); $i++ ){
                                                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tipo Producto: </td>
                                    <td id="load_categorias_productos">
                                        <select class="formInput" id="id_tpoproducto" onchange="fnpv_loadMarcas(this.value,$('#id_categoria_producto').val())">
                                            <option value="0">-- --</option>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Marca: </td>
                                    <td>
                                        <div id="loadMarca2">
                                            <select class="formInput" id="id_marca">
                                                <option value="0">-- --</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Clasificación: </td>
                                    <td>
                                        <select class="formInput" id="clasificacion">
                                            <option value="0">-- --</option>

                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="tablaDetailticket">
                                <tr>
                                    <td>Fecha Alta: </td>
                                    <td style="width: 90px;"><input id="fchalta_ini" onfocus='$("#fchalta_fin").val(this.value);'  type="text" placeholder="Fecha Inicial" class="formInput datepicker" /> </td>
                                    <td style="width: 90px;"><input id="fchalta_fin" type="text" placeholder="Fecha Final" class="formInput datepicker" /></td>
                                </tr>
                                <tr>
                                    <td>Fecha UM: </td>
                                    <td style="width: 90px;"><input id="fchum_ini" onfocus='$("#fchum_fin").val(this.value);'  type="text" placeholder="Fecha Inicial" class="formInput datepicker" /></td>
                                    <td style="width: 90px;"><input id="fchum_fin" type="text" placeholder="Fecha Final" class="formInput datepicker" /></td>
                                </tr>
                                <tr>
                                    <td>Usuario Alta: </td>
                                    <td colspan="2">
                                        <select id="nousuario" class="formInput">
                                            <option value="0">-- --</option>
                                            <?php
                                            $connect->_query =
                                                "SELECT b.NoUsuario,b.NombreDePila FROM BSIModuloOpciones as a
                                                                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b
                                                                ON a.NoUsuario = b.NoUsuario AND b.NoEstado = 1
                                                                WHERE a.CodCatalogo = 10 AND a.CodModulo = 1 AND a.Opcion1 = 1
                                                                AND a.Opcion2 = 1 AND a.Marca = 'SI' AND b.NoPerfil <> 2 ORDER BY b.NombreDePila ASC";
                                            $connect->get_result_query();

                                            for($i=0; $i < count($connect->_rows); $i++ ){
                                                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Usuario UM: </td>
                                    <td colspan="2">
                                        <select id="nousuarioum" class="formInput">
                                            <option value="0">-- --</option>
                                            <?php
                                            $connect->_query =
                                                "SELECT b.NoUsuario,b.NombreDePila FROM BSIModuloOpciones as a
                                                                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b
                                                                ON a.NoUsuario = b.NoUsuario AND b.NoEstado = 1
                                                                WHERE a.CodCatalogo = 10 AND a.CodModulo = 1 AND a.Opcion1 = 1
                                                                AND a.Opcion2 = 1 AND a.Marca = 'SI' AND b.NoPerfil <> 2 ORDER BY b.NombreDePila ASC";

                                            $connect->get_result_query();

                                            for($i=0; $i < count($connect->_rows); $i++ ){
                                                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div id="row" class="row">
                        <div class="col-md-12">
                            <table class="tablaDetailticket" style="width: 99%">
                                <tr>
                                    <td style="width: 89px;">Descripci&oacute;n: </td>
                                    <td><input type="text"  id="txtDescripcionProd" class="formInput" /> </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="text-align: left;">
                <button id="btn-search-product" onclick="fnpv_buscar_producto()" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Buscar</button>
                <button id="closemodal" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
                <button onclick="$('#frmBuscaProducta').Frmreset()" class="btn btn-default btn-sm"><i class="fa fa-trash"></i> Limpiar</button>
            </div>
        </div>
    </div>
</div>
