<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 21/02/2017
 * Time: 11:57 AM
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
include "../../../../core/model_aparatos.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$connect = new \core\model_aparatos($_SESSION['data_login']['BDDatos']);

$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);


$NoArticulo = $_POST['nprod'];
$NoCategoria = $_POST['categoria'];

if($NoArticulo || $NoCategoria){

    $connect->_query = "SELECT a.Descripcion,a.CodigoProducto,a.Clasificacion02,b.Descripcion,a.Clasificacion03,c.Descripcion,a.Clasificacion04,a.ImporteVenta,a.Importe01,a.Importe02,a.Importe03,
                        a.FechaAlta,a.FechaUM,a.NombreFotografia,a.NoEstatus,a.Clasificacion01,d.Descripcion,d.Texto1,e.Descripcion
                        FROM BOPCatalogoProductos as a
                        LEFT JOIN BGECatalogoGeneral as b
                        ON a.Clasificacion02 = b.OpcCatalogo AND b.CodCatalogo = 5 AND b.Numero2 = '$NoCategoria'
                        LEFT JOIN BGECatalogoGeneral as c
                        ON a.Clasificacion03 = c.OpcCatalogo AND c.CodCatalogo = 6 AND c.Numero2 = '$NoCategoria' 
                        LEFT JOIN BGECatalogoGeneral as d
                        ON a.NoEstatus = d.OpcCatalogo AND d.CodCatalogo = 8 
                        LEFT JOIN BGECatalogoGeneral as e 
                        ON a.Clasificacion01 = e.OpcCatalogo AND e.CodCatalogo = 9
                        WHERE a.CodigoProducto = $NoArticulo ";

    $connect->get_result_query();

    $rows = $connect->_rows[0];

}else{

    // no se vienen datos en las variables
    \core\core::MyAlert("Error no se encotraron las llaves del producto",'error');

}

?>
<script language="JavaScript" type="text/javascript" src="<?=\core\core::ROOT_APP?>site_design/js/jsFormatoMoneda.js"></script>
<script language="JavaScript">

    $('.currency').numeric({prefix:'$ ', cents: true});

    if(<?=$rows[15]?> == 3){
        $("#precios_capturados").toggleClass('hidden');
    }


    $("#btn3").hide();
    $("#btn2").show();
    $("#btn4").hide();
    $("#idencuesta").hide();

    $("#btnExport").attr('disabled','disabled');
    $( "#tabs" ).tabs();
    $("input[type=text]").focus(function(){
        this.select();
    });
    $("#btn5").show();
    $("#btnList").show();
    $("#btnHome").hide();

    $("#precio_empeno").popover(
        {
            title: 'Mensaje de Notificación',
            trigger: 'focus',
            content: "Si deja el campo en 2, este valor es para solicitar autorizacion"
        }
    );

    confirm_close = true;

</script>

<!-- Panel de Informacion del Producto -->

<div class="panel panel-info">
    <div class="panel-heading" style="padding: 5px;"><span class="fa fa-edit"></span>Editar Producto <?=$NoArticulo?> </div>
    <div class="panel-body">

        <table class="tablaDetailticket">
            <tr>
                <td>No Producto: </td>
                <td style="width: 60px;"><input disabled class="formInput" id="no_producto" value="<?=$connect->getFormatFolio($rows[1],5)?>" name="name_articulo" type="text" /></td>
                <td>Nombre Producto: </td>
                <td><input type="text" <?=$CtrlDisabled?> id="name_producto" value='<?=$rows[0]?>' class="formInput" size="62%" /> </td>
                <td>Categoria: </td>
                <td>
                    <select id="nocategoria" <?=$CtrlDisabled?> class="formInput">
                        <option value="<?=$rows[15]?>"><?=$rows[18]?></option>
                    </select>
                </td>
            </tr>
        </table>

    </div>
</div>

<!-- Panel del detalle del producto -->

<div id="tabs" class="scroll-auto" style="padding: 0px;height: 50vh;border: none;">
    <ul style="border:none">
        <li><a href="#inf">Informaci&oacute;n de Venta</a></li>
        <li><a href="#his">Historial</a></li>
    </ul>
    <div id="inf">
        <table class="tablaDetailticket" style="font-family: 'Source Sans Pro', sans-serif;">
            <tr>
                <td>Tipo Producto: </td>
                <td>
                    <select id="tpo_articulo" <?=$CtrlDisabled?> onchange="loadMarcas(this.value)"  class="formInput">
                        <option value="<?=$rows[2]?>"><?=$rows[3]?></option>
                        <?php
                        $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERe  OpcCatalogo <> ".$rows[2]." AND Numero2 = ".$rows[15]." AND CodCatalogo = 5 AND NoEstatus = 1 ORDER BY Descripcion ASC";
                        $connect->get_result_query();

                        for($i=0; $i < count($connect->_rows) ; $i++){
                            echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>Marca: </td>
                <td style="width: 14em;">
                    <div id="loadMarca">
                        <select id="id_marca" <?=$CtrlDisabled?> class="formInput">
                            <option value="<?=$rows[4]?>"><?=$rows[5]?></option>
                            <?php
                            $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERe  OpcCatalogo <> ".$rows[4]." AND Numero2 = ".$rows[15]." AND CodCatalogo = 6 AND NoEstatus = 1 ORDER BY Descripcion ASC";
                            $connect->get_result_query();

                            for($i=0; $i < count($connect->_rows) ; $i++){
                                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </td>
                <td>Fecha Alta: </td>
                <td><input type="text" <?=$CtrlDisabled?> id="fch_alta"  size="25%" value="<?=$connect->getFormatFecha($rows[11],2)?>" class="formInput datepicker" />  </td>
            </tr>
            <tr>
                <td>Precio de Venta: </td>
                <td><input type="text" style="text-align: right;" <?=$CtrlDisabled2?> id="importe_venta" value="<?=$rows[7]?>" size="25%" class="formInput right currency" /> </td>
                <td>Clasificaci&oacute;n: </td>
                <td>
                    <select class="formInput" <?=$CtrlDisabled2?> id="clasificacion">
                        <option><?=$rows[6]?></option>
                        <?php
                        $connect->_query = "SELECT * FROM BGECatalogoGeneral where Numero2 = ".$rows[15]." AND  Descripcion <> '".$rows[6]."' AND CodCatalogo = 7";
                        $connect->get_result_query();

                        for($i=0; $i < count($connect->_rows) ; $i++){
                            echo "<option value='".$connect->_rows[$i][2]."'>".$connect->_rows[$i][2]."</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>Fecha UM: </td>
                <td><input type="text" <?=$CtrlDisabled?> id="fch_um"  size="25%" value="<?=$connect->getFormatFecha($rows[12],2)?>" class="formInput datepicker" />  </td>
            </tr>
            <tr id="precios_capturados" class="hidden">
                <td>Empeño: </td>
                <td><input type="text" id="precio_empeno" value="<?=$rows[8]?>" style="text-align: right;" size="25%" class="formInput right currency" /> </td>
                <td>Compra</td>
                <td><input type="text" id="precio_compra" value="<?=$rows[9]?>" style="text-align: right;" size="25%" class="formInput right currency" /> </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Nombre Fotografia: </td>
                <td colspan="3"><input <?=$CtrlDisabled?> type="text" id="rta_fga" value="<?=$rows[13]?>" class="formInput" /> </td>
                <td>Estatus: </td>
                <td  style="width: 150px;">
                    <select class="formInput" <?=$CtrlDisabled?> id="estatus">
                        <option value="<?=$rows[14]?>"><?=$rows[16]?></option>
                        <?php
                        $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERe  CodCatalogo = 8 AND NoEstatus = 1 AND OpcCatalogo <> ".$rows[14]." ORDER BY Descripcion ASC";
                        $connect->get_result_query();

                        for($i=0; $i < count($connect->_rows) ; $i++){
                            echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>

    <div id="his" style="padding: 0px;">
        <table class="table table-striped table-hover" style="font-size: 0.89em;">
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
            <?php
            $result = $connect->mostrar_bitacora($rows[1]);
            if(count($result) > 0 ){

                for($i=0; $i < count($result); $i++ ){

                    if($result[$i][5] == 1){$result[$i][5] = 'Activado';}elseif( $result[$i][5] == 2){$result[$i][5] = 'Desactivado';}
                    if($result[$i][6] == 1){$result[$i][6] = 'Activado';}elseif($result[$i][6] == 2){$result[$i][6] = 'Desactivado';}
                    if($result[$i][4] == 'Precio de Venta'){$row_class = 'right currency';}else{$row_class = '';}

                    echo '<tr>
                            <td>'.$result[$i][1].'</td>
                            <td>'.$result[$i][3].'</td>
                            <td>'.$result[$i][4].'</td>
                            <td class="'.$row_class.'">'.$result[$i][5].'</td>
                            <td class="'.$row_class.'">'.$result[$i][6].'</td>
                            <td>'.$result[$i][7].'</td>
                            <td>'.$result[$i][8].'</td>
                            <td style="width:27px;">'.$connect->getFormatFecha($result[$i][9],2).'</td>
                            <td style="width:27px;">'.$result[$i][10].'</td>
                         </tr>';
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
