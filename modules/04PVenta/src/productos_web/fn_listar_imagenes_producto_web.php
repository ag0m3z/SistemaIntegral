<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 12/05/2017
 * Time: 02:19 PM
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

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
$idCodigo = $_POST['idcodigo'];
$idSerie = $_POST['idserie'];
$SqlServer = new \core\sqlconnect();

if($_POST['opc'] == 1){
    //Nivel codigo
    $idSerie = ' ';

    $SqlServer->_sqlQuery = "SELECT idSerie,idCodigo,idImagen,NombreImagen,TamanoImagen FROM SAyT.dbo.INVProdImagen WHERE idCodigo = '$idCodigo' AND idSerie = '$idSerie' ";
    $SqlServer->get_result_query();

    for($i=0;$i < count($SqlServer->_sqlRows[$i]);$i++){

        $idImagen = $SqlServer->_sqlRows[$i][2];

        echo "<tr><td><img width='35' src='modules/04PVenta/src/productos_web/fn_mostrar_image_producto.php?tpo=1&id=$idImagen' /></td><td>".$SqlServer->_sqlRows[$i][3]."</td><td>".round($SqlServer->_sqlRows[$i][4] * 0.0009765625,2)."KB</td>
         <td width='65'>
          <span class='btn-link btn btn-xs ' onclick='fn_eliminar_imagen_producto_web(1,\"".$idCodigo."\",\"".$idSerie."\",\"".$idImagen."\")' ><i class='fa fa-trash text-danger'></i></span>
          <span class='btn-link btn btn-xs' onclick='fn_ve_imagen_producto_web(1,\"".$idCodigo."\",\"".$idSerie."\",\"".$idImagen."\")' ><i class='fa fa-eye text-primary'></i></span>
         </td>
         </tr>";

    }
}else if($_POST['opc'] == 2){
    //Nivel Serie
    $SqlServer->_sqlQuery = "SELECT idSerie,idCodigo,idImagen,NombreImagen,TamanoImagen FROM SAyT.dbo.INVProdImagen WHERE idCodigo = '$idCodigo' AND idSerie = '$idSerie' ";
    $SqlServer->get_result_query();

    for($i=0;$i < count($SqlServer->_sqlRows[$i]);$i++){

        $idImagen = $SqlServer->_sqlRows[$i][2];

        echo "<tr><td><img width='35' src='modules/04PVenta/src/productos_web/fn_mostrar_image_producto.php?tpo=1&id=$idImagen' /></td><td>".$SqlServer->_sqlRows[$i][3]." se</td><td>".round($SqlServer->_sqlRows[$i][4] * 0.0009765625,2)."KB</td>
         <td width='65'>
          <span class='btn-link btn btn-xs ' onclick='fn_eliminar_imagen_producto_web(2,\"".$idCodigo."\",\"".$idSerie."\",\"".$idImagen."\")' ><i class='fa fa-trash text-danger'></i></span>
          <span class='btn-link btn btn-xs' onclick='fn_ve_imagen_producto_web(1,\"".$idCodigo."\",\"".$idSerie."\",\"".$idImagen."\")' ><i class='fa fa-eye text-primary'></i></span>
         </td>
         </tr>";

    }
}