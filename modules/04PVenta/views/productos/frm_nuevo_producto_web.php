<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 29/03/2017
 * Time: 12:18 PM
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

?>

<script language="JavaScript">
    $("#tabs").tabs();
    $("#codigo_producto").focus();

</script>

<!--- Panel para la Informacion del Producto -->
<div class="panel panel-info">
    <div class="panel-heading" style="padding: 5px;" id="title"><span class="fa fa-edit"></span>Alta de Producto </div>
    <div class="panel-body table-responsive">

        <table class="tablaDetailticket" >
            <tr>
                <td>Codigo producto: </td>
                <td style="width: 100px;"><input class="formInput" id="codigo_producto" placeholder="Codigo producto" name="codigo_producto" type="text" /></td>
                <td>Nombre Producto: </td>
                <td><input type="text" id="nombre_producto" placeholder="Nombre del Producto" class="formInput" size="62%" /> </td>
                <td>Categoría: </td>
                <td>
                    <select id="nocategoria" onchange="fnpv_load_categorias_clasificacion(11,this.value)" class="formInput">
                        <option value="0">-- --</option>
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
        <li><a href="#exis">Existencias</a></li>
        <li><a href="#his">Historial</a></li>
    </ul>
    <div id="exis" class="table-responsive">

    </div>
    <div id="inf" class="table-responsive">

        <table class="tablaDetailticket table-hover " style="font-family: 'Source Sans Pro', sans-serif;">
            <tr>
                <td >Tipo Producto: </td>
                <td colspan="2" >
                    <select style="width: 200px" id="id_tpoproducto" class="formInput">
                        <option value="0">-- --</option>
                    </select>
                </td>
                <td >Marca: </td>
                <td colspan="2" >
                    <select style="width: 200px !important;" id="id_marca" class="formInput">
                        <option value="0">-- --</option>

                    </select>
                </td>
                <td>Fecha Alta: </td>
                <td><input type="text" id="fch_alta"  size="25%" disabled value="<?=date('d/m/Y')?>" class="formInput datepicker " />  </td>
            </tr>
            <tr>
                <td>Serie: </td>
                <td colspan="2">
                    <select id="estatus" class="formInput">
                        <option value="1">SI</option><option value="0">NO</option>
                    </select></td>
                <td>Lote: </td>
                <td colspan="2">
                    <select id="estatus" class="formInput">
                        <option value="1">SI</option><option value="0">NO</option>
                    </select>
                </td>
                <td>Estatus: </td>
                <td >
                    <select id="estatus" class="formInput">
                        <option value="1">Activado</option><option value="0">Desactivado</option>
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

