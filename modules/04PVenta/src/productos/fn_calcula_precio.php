<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 10:24 AM
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
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$idCategoria = $_POST['idcategoria'];

$connect->_query = "SELECT Descripcion,Numero1N,Numero2N,Numero3N,Numero4N FROM BGECatalogoGeneral WHERE CodCatalogo = 7 AND NoEstatus = 1 AND Numero2 = $idCategoria  ;";
$connect->get_result_query();

$array_importe = array("$",",");
$PrecioVenta = str_replace($array_importe,"",$_POST['importe']);
?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsFormatoMoneda.js" language="JavaScript" ></script>
<script language="JavaScript">
    $('.currency').numeric({prefix:'$ ', cents: true});
</script>
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
    <?php
    for($i=0; $i < count($connect->_rows); $i++){
        $Empeno = $connect->_rows[$i][1];
        $CompraMaxima = $connect->_rows[$i][2];
        $CompraOpc1 = $connect->_rows[$i][3];
        $CompraOpc2 = $connect->_rows[$i][4];
        if ($i%2==0){
            $bgColor = "#fff";
        }elsE{
            $bgColor = "#EFEFF7";
        }

        echo "<tr>
					<td style='background: ".$bgColor."' class='text-center'>".$connect->_rows[$i][0]."</td>
					<td style='background: ".$bgColor."' class='text-right'><span class='right currency'>".round(($PrecioVenta * $Empeno),-1)."</span></td>
        			<td style='background: ".$bgColor."' class='text-right'><span class='right currency'> ".round(($PrecioVenta * $CompraOpc2),-1)."</span></td>
        			<td style='background: ".$bgColor."' class='text-right'><span class='right currency'> ".round(($PrecioVenta * $CompraOpc1),-1)."</span></td>
        			<td style='background: ".$bgColor."' class='text-right'><span class='right currency'> ".round(($PrecioVenta * $CompraMaxima),-1)."</span></td>
				</tr>";
    }
    ?>
    </tbody>
</table>