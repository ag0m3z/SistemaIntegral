<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 21/02/2017
 * Time: 04:34 PM
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

$connect = new \core\model_aparatos($_SESSION['data_login']['BDDatos']);

$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);



/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$SqlConnect = new \core\sqlconnect();

$NoCategoria =     $_POST['nocate'];
$NombreArticulo =  $_POST['namrart'];

$TipoArticulo   =  $_POST['tpoart'];
$NoMarca        =  $_POST['nomarc'];

//Formatear Fecha de 29/09/2015 a: 20150929
$FechaAlta      =  $connect->getFormatFecha($_POST['fchalt'],1);

//Formatear Importe para Quitar el $ y la ',' Si cuenta con ella.
$array = array("$",",");
$NoImporte      =  str_replace($array,"",$_POST['importe']);
$precio_empeno = str_replace($array,"",$_POST['precio_empeno']);
$precio_compra = str_replace($array,"",$_POST['precio_compra']);

$Clasificacion  =  $_POST['clasif'];

//Formatear Fecha de 29/09/2015 a: 20150929
$FechaUm        =  $connect->getFormatFecha($_POST['fchu'],1);
$NombreFoto     =  $_POST['namefoto'];
$NoEstatus      =  $_POST['esta'];

//Datos del Usuario
$NoUsuario = $_SESSION['data_login']['NoUsuario'];
$HoraAlta = date('H:i:s');

$CodigoProducto = $_POST['noprod'];
$Opcion = 0;
if(!empty($CodigoProducto )){
    $Opcion = 1;
    $CodigoProducto= 0;
}

$connect->_query = "SELECT CodigoProducto FROM BOPCatalogoProductos WHERE Clasificacion01 = '".$NoCategoria."'  AND Descripcion = '".$NombreArticulo."' AND  Clasificacion02 = '".$TipoArticulo."' AND Clasificacion03 = '".$NoMarca."' ";
$connect->get_result_query();

if(count($connect->_rows) > 0){

    echo '<script language="JavaScript">MyAlert("El producto ya se encuetra registrado, Nombre: '.$_POST['namrart'].'","error");</script>';
}else{


    $connect->RegistraArticulo
    (
        $Opcion,$CodigoProducto,
        $NombreArticulo,$NoCategoria,
        $TipoArticulo,$NoMarca,
        $Clasificacion,"","","","","","",
        $NoImporte,$precio_empeno,$precio_compra,0,0,0,$NombreFoto,$NoEstatus,$NoUsuario,$NoUsuario,$FechaAlta,$HoraAlta,$FechaUm,$HoraAlta
    );

    // Registrar en Sql Server

    $connect->_query = "SELECT fn_BOP_UltimoProducto(1)";
    $connect->get_result_query();

    $folio = $connect->_rows[0];

    if( count($connect->_rows) > 0){

        $connect->_query = "SELECT CodigoProducto,Clasificacion02,Clasificacion03,Descripcion,ImporteVenta,NombreFotografia,FechaAlta,FechaUM,Clasificacion04,Clasificacion01,Importe01,Importe02 FROM BOPCatalogoProductos WHERe CodigoProducto = '".$folio[0]."' LIMIT 1";
        $connect->get_result_query();



        if( count($connect->_rows) > 0){

            $infoProducto = $connect->_rows[0];
            $importeVenta = $infoProducto[4];
            $precio_empeno2 = $infoProducto[10];
            $precio_compra2 = $infoProducto[11];


            $SqlConnect->_sqlQuery = "INSERT INTO BAPCatalogoAparatos VALUES (".$infoProducto[9].",".$infoProducto[1].",".$infoProducto[2].",'".$infoProducto[0]."','".$infoProducto[3]."','".$infoProducto[4]."','".$precio_empeno2."','".$precio_compra2."','".$infoProducto[0].".jpg','".$infoProducto[6]."','".$infoProducto[7]."',0,'".$infoProducto[8]."')";
            $SqlConnect->execute_query();
            //$SqlConnect->_sqlMensaje;

        }

        $connect->_query = "UPDATE BOPCatalogoProductos SET NombreFotografia = '".$folio[0].".jpg' WHERE CodigoProducto = '".$folio[0]."' AND Clasificacion01 = '$NoCategoria' ";
        $connect->execute_query();
    }
    echo "<script language='JavaScript'>MyAlert('Se Registro el Articulo: ".$connect->getFormatFolio($folio[0],5)."','ok');fnsdMenu(15,null)</script>";
}