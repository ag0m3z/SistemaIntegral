<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 02/05/2017
 * Time: 12:22 PM
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

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();
$SqlConnect = new \core\sqlconnect();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

if(!array_key_exists('idcodigo',$_POST)){
    \core\core::MyAlert("No se encontro el codigo del producto","error");
    exit();
}

//informacion de producto por codigo
$SqlConnect->_sqlQuery =
    "SELECT 
        a.idCodigo,
        a.Descripcion,
        a.idCategoria,
        b.NombreCategoria,
        a.idTipo,
        c.Descripcion,
        a.idMarca,
        d.Descripcion,
        a.Estatus 
    FROM SAyT.dbo.INVProductos as a 
        LEFT JOIN BDSPSAYT.dbo.BPFCatalogoCategoriasPrestamo as b
            ON a.idCategoria = b.NoCategoria 
        LEFT JOIN BDSPAPARATOS.dbo.BAPCatalogoTiposAparatos as c 
            ON a.idTipo = c.NoTipoAparato AND a.idCategoria = c.NoCategoria 
        LEFT JOIN BDSPAPARATOS.dbo.BAPCatalogoMarcas as d 
            ON a.idMarca = d.NoMarca AND a.idCategoria = d.NoCategoria
  WHERE a.idCodigo = '$_POST[idcodigo]' ";

$SqlConnect->get_result_query();

$DataCodigo = $SqlConnect->_sqlRows[0];
$SqlConnect->_sqlQuery = "
                         SELECT 
                            idCaracteristica,
                            Descripcion
                        FROM 
                            SAyT.dbo.INVProdCaracteristica
                        WHERE Estatus = '1' AND idCategoria = '".$_POST['idcategoria']."' ORDER BY Orden ASC ";

$SqlConnect->get_result_query();
$car = $SqlConnect->_sqlRows;

?>

<script type="text/javascript" language="JavaScript" src="<?=\core\core::ROOT_APP?>site_design/js/jsFormatoMoneda.js"></script>

<script language="JavaScript">
    $('.currency').numeric({prefix:'$ ', cents: true});
    $("#tabs").tabs();
    $("#codigo_producto").focus();
    $(".select2").select2();
    $("#btncambiar").hide();
    $("#btn_add_caracteristica").show();
    $("#btn_add_imagen").show();
    fn_registrar_caracteristica(9,'<?=$_POST['idcategoria']?>','<?=$_POST['idcodigo']?>','0');

</script>
<div id="modal_result"></div>
<!--- Panel para la Informacion del Producto -->
<div class="panel panel-info">
    <div class="panel-heading" style="padding: 5px;" id="title"><span class="fa fa-edit"></span>Alta de Producto </div>
    <div class="panel-body table-responsive">

        <table class="tablaDetailticket" >
            <tr>
                <td>Codigo producto: </td>
                <td style="width: 100px;"><input class="formInput" value="<?=$DataCodigo[0]?>" disabled id="codigo_producto" placeholder="Codigo producto" name="codigo_producto" type="text" /></td>
                <td>Nombre Producto: </td>
                <td><input type="text" id="nombre_producto" value="<?=$DataCodigo[1]?>" disabled placeholder="Nombre del Producto" class="formInput" size="62%" /> </td>
                <td>Categoría: </td>
                <td>
                    <select id="nocategoria" disabled onchange="fnpv_load_categorias_clasificacion(11,this.value)" class="formInput">
                        <option value="<?=$DataCodigo[2]?>"><?=$DataCodigo[3]?></option>
                        <?php
                        $SqlConnect->_sqlQuery= "SELECT NoCategoria,NombreCategoria FROM BDSPSAYT.dbo.BPFCatalogoCategoriasPrestamo";
                        $SqlConnect->get_result_query();
                        for($i=0;$i < count($SqlConnect->_sqlRows);$i++){
                            echo "<option value='".$SqlConnect->_sqlRows[$i][0]."'>".$SqlConnect->_sqlRows[$i][1]."</option>";
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
        <li class="hidden"><a href="#lcaract_codigo">Caracteristicas</a></li>
        <li class="hidden"><a href="#img_codigo">Imagenes</a></li>
        <li><a href="#exis">Existencias</a></li>
        <li><a href="#his">Historial</a></li>
    </ul>

    <div id="lcaract_codigo" class="table-responsive">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <br>
                <table  class="table table-striped table-condensed table-hover" style="font-size: 11.55px;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Caracteristica</th>
                        <th>Valor</th>
                    </tr>
                    </thead>
                    <tbody id="carac_codigo">
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div id="img_codigo" class="table-responsive"></div>

    <div id="exis" class="table-responsive">
        <table class="table table-striped table-condensed table-hover" style="font-size: 11.5px;">
            <thead>
            <tr>
                <th>Sucursal</th>
                <th>Serie</th>
                <th>Precio Venta</th>
                <!-- <th>Tipo Atenci&oacute;n</th> -->
                <th width="90" >Existencia</th>
                <th width="90">Estado</th>
                <th width="290" class="text-right">Funciones</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $SqlConnect->_sqlQuery =
                "
                SELECT 
                    a.idCodigo,
                    a.Descripcion,
                    b.idSucursal,
                    c.Descripcion,
                    b.idSerie,
                    b.PrecioVenta,
                    b.Existencia,
                    b.Estatus,
                    a.idCategoria
                FROM SAyT.dbo.INVProductos as a 
                    LEFT JOIN SAyT.dbo.INVExistencia as b
                        ON a.idCodigo = b.idCodigo 
                    LEFT JOIN BDSPSAYT.dbo.BSAYTCatalogoSucursales as c 
                        ON b.idSucursal = c.NoSucursal 
              WHERE a.idCodigo = '$DataCodigo[0]'
                ";
            $SqlConnect->get_result_query();
            for($i=0;$i < count($SqlConnect->_sqlRows);$i++){

                $Estado = $connect->getFormatoEstatus($SqlConnect->_sqlRows[$i][7]);

                echo "<tr>
                    <td>".$SqlConnect->_sqlRows[$i][3]."</td>
                    <td>".$SqlConnect->_sqlRows[$i][4]."</td>
                    <td class='currency' >".$SqlConnect->_sqlRows[$i][5]."</td>
                    <td><span class=''>".$connect->getFormatFolio($SqlConnect->_sqlRows[$i][6],2)."</span></td>
                    <td>".$Estado."</td>
                    <td class='text-right'> 
                        <span class='btn btn-link btn-xs' onclick='fn_registrar_caracteristica(1,\"".$SqlConnect->_sqlRows[$i][8]."\",\"".$DataCodigo[0]."\",\"".$SqlConnect->_sqlRows[$i][4]."\")' ><i class='fa fa-plus text-success'></i> Caracteristicas  </span> 
                        <span class='btn btn-link btn-xs' onclick='fn_agregar_imagenes(2,\"".$SqlConnect->_sqlRows[$i][8]."\",\"".$DataCodigo[0]."\",\"".$SqlConnect->_sqlRows[$i][4]."\")' ><i class='fa fa-plus text-success'></i> Imagenes</span>
                    </td>
                    </tr>";
            }
            ?>
            </tbody>
        </table>

    </div>

    <div id="inf" class="table-responsive">

        <table class="tablaDetailticket table-hover " style="font-family: 'Source Sans Pro', sans-serif;">
            <tr>
                <td >Tipo Producto: </td>
                <td colspan="2" >
                    <select style="width: 200px" id="id_tpoproducto" disabled class="formInput">
                        <option value="<?=$DataCodigo[4]?>"><?=$DataCodigo[5]?></option>
                    </select>
                </td>
                <td >Marca: </td>
                <td colspan="2" >
                    <select style="width: 200px !important;" id="id_marca" disabled class="formInput">
                        <option value="<?=$DataCodigo[6]?>"><?=$DataCodigo[7]?></option>

                    </select>
                </td>
                <td>Fecha Alta: </td>
                <td><input type="text" id="fch_alta"  size="25%" disabled value="<?=date('d/m/Y')?>" class="formInput datepicker " />  </td>
            </tr>
            <tr>
                <td>Serie: </td>
                <td colspan="2">
                    <select  disabled class="formInput">
                        <option value="1">SI</option><option value="0">NO</option>
                    </select></td>
                <td>Lote: </td>
                <td colspan="2">
                    <select  disabled class="formInput">
                        <option value="1">SI</option><option value="0">NO</option>
                    </select>
                </td>
                <td>Estatus: </td>
                <td >
                    <select  disabled class="formInput">
                        <?php
                        if($DataCodigo[8] == 1){
                            echo '<option value="1">Activado</option><option value="0">Desactivado</option>';
                        }else{
                            echo '<option value="0">Desactivado</option><option value="1">Activado</option>';
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


