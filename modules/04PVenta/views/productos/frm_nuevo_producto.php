<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 21/02/2017
 * Time: 12:41 PM
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
include "../../../../core/model_aparatos.php";

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

$connect = new \core\model_aparatos($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$FechaActual = date("Ymd");
?>
<script type="text/javascript" language="JavaScript" src="<?=\core\core::ROOT_APP?>site_design/js/jsFormatoMoneda.js"></script>
<script language="JavaScript">
    $('.currency').numeric({prefix:'$ ', cents: true});
    $("#tabs").tabs(
        //{disabled: [ 2 ]}
    );
    $("#btn3").show();
    $("#btn3").removeClass('hidden');
    $("#btn5").hide();

    $("#btn2").hide();
    $("#btn4").hide();
    $("#btnList").show();
    $("#btnHome").hide();
    $("#btnShowImportes").hide();
    $("#btnOcultarImportes").hide();
    $("#idencuesta").hide();
    $("input[type=text]").focus(function(){
        this.select();
    });

    $("#precio_empeno").popover(
        {
            title: 'Mensaje de Notificación',
            trigger: 'focus',
            content: "Si deja el campo en 2, este valor es para solicitar autorizacion"
        }
    );
</script>

<div id="showmodal"></div>

<!--- Panel para la Informacion del Producto -->
<div class="panel panel-info">
    <div class="panel-heading" style="padding: 5px;" id="title"><span class="fa fa-edit"></span>Alta de Producto </div>
    <div class="panel-body">

        <table class="tablaDetailticket" >
            <tr>
                <td>No Producto: </td>
                <td style="width: 60px;"><input disabled class="formInput" id="no_producto" name="no_producto" type="text" /></td>
                <td>Nombre Producto: </td>
                <td><input type="text" id="name_producto" placeholder="Nombre del Producto" class="formInput" size="62%" /> </td>
                <td>Categoria: </td>
                <td>
                    <select id="nocategoria" onchange="fnpv_load_categorias_clasificacion(11,this.value)" class="formInput">
                        <option value="0">-- --</option>
                        <?php
                        $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERe  CodCatalogo = 9 AND NoEstatus = 1 ORDER BY Descripcion ASC";
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

<!-- Panel para el Detalles y caracteristicas del producto -->
<div id="tabs" style="padding: 0px;height: 50vh;border: none;">
    <ul style="border:none">
        <li><a href="#inf">Informaci&oacute;n de Venta</a></li>
        <li><a href="#his">Historial</a></li>
    </ul>
    <div id="inf">
        <table class="tablaDetailticket"style="font-family: 'Source Sans Pro', sans-serif;">
            <tr>
                <td>Tipo Producto: </td>
                <td style="width: 14em;">
                    <div>
                        <select id="id_tpoproducto" class="formInput">
                            <option value="0">-- --</option>
                        </select>
                    </div>
                </td>
                <td>
                    <button data-toggle="tooltip" onclick="fn_nuevo_tipo_producto(1,1,$('#nocategoria').val())" data-placement="top" title="Alta de nuevo tipo producto" style="margin-left: -5px;" class="btn btn-default btn-xs">
                        <i class="fa fa-file"></i>
                    </button>
                </td>
                <td>Marca: </td>
                <td style="width: 14em;">
                    <div id="div_categoria_marca">
                        <select id="id_marca" class="formInput">
                            <option value="0">-- --</option>

                        </select>
                    </div>
                </td>
                <td>
                    <button data-toggle="tooltip"  onclick="fn_nueva_marca(1,1,$('#nocategoria').val())" data-placement="top" title="Alta de nueva marca" style="margin-left: -5px;" class="btn btn-default btn-xs">
                        <i class="fa fa-file"></i>
                    </button>
                </td>
                <td>Fecha Alta: </td>
                <td><input type="text" id="fch_alta"  size="25%" disabled value="<?=$connect->getFormatFecha($FechaActual,2)?>" class="formInput datepicker " />  </td>
            </tr>
            <tr>
                <td>Precio de Venta: </td>
                <td colspan="2"><input type="text" id="importe_venta" style="text-align: right;" size="25%" class="formInput right currency" /> </td>
                <td>Clasificaci&oacute;n: </td>
                <td colspan="2">
                    <select id="clasificacion" class="formInput">
                        <option value="0">-- --</option>
                    </select>
                </td>
                <td>Fecha UM: </td>
                <td><input type="text" id="fch_um"  disabled value="<?=$connect->getFormatFecha($FechaActual,2)?>" size="25%" class="formInput" />  </td>
            </tr>
            <tr id="precios_capturados" class="hidden">
                <td>Empeño: </td>
                <td colspan="2"><input id="precio_empeno" style="text-align: right;" size="25%" class="formInput right currency" /> </td>
                <td>Compra</td>
                <td colspan="2"><input type="text" id="precio_compra" style="text-align: right;" size="25%" class="formInput right currency" /> </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Nombre Fotografia: </td>
                <td colspan="5"><input disabled type="text" id="rta_fga" class="formInput" /> </td>
                <td>Estatus: </td>
                <td style="width: 150px;">
                    <select id="estatus" class="formInput">
                        <?php
                        $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERe  CodCatalogo = 8 AND NoEstatus = 1 ORDER BY Descripcion ASC";
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
    <div id="his">
        <table class="table table-striped table-hover" style="font-size: 11.5px;">
            <thead>
            <tr>
                <th>Rubro</th>
                <!-- <th>Tipo Atenci&oacute;n</th> -->
                <th>Pantalla</th>
                <th>Campo Actualiza</th>
                <th>Dato Anterior</th>
                <th>Dato Nuevo</th>
                <th>Operación</th>
                <th>Nombre Usuario</th>
                <th>Fecha</th>
                <th>Hora</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>