<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 09:56 AM
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
$sqlConnect = new \core\sqlconnect();


/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

//Recibiendo Datos Enviados desde Jaquery Con Sus Valores Nuevos.
$NoArticulo = $_POST['noart'];
$NoCategoria =     $_POST['nocate'];

$NombreArticulo =  $_POST['namrart'];
$TipoArticulo   =  $_POST['tpoart'];
$NoMarca        =  $_POST['nomarc'];

//Formatear Fecha de 29/09/2015 a: 20150929
$FechaAlta      =  $connect->getFormatFecha($_POST['fchalt'],1);

//Formatear Importe para Quitar el $ y la ',' Si cuenta con ella.
$array = array("$",",");
$NoImporte      =  str_replace($array,"",$_POST['importe']);
$precio_empeno      =  str_replace($array,"",$_POST['precio_empeno']);
$precio_compra      =  str_replace($array,"",$_POST['precio_compra']);

$Clasificacion  =  $_POST['clasif'];

//Formatear Fecha de 29/09/2015 a: 20150929
$FechaUm        =  $connect->getFormatFecha($_POST['fchu'],1);

$NombreFoto     =  $_POST['namefoto'];
$NoEstatus      =  $_POST['esta'];

//Datos del Usuario
$NoUsuario = $_SESSION['data_login']['NoUsuario'];
$FechaActual = date("Ymd");
$HoraAlta = date('H:i:s');
$array1 = array($NoArticulo,$NoCategoria,$NombreArticulo,$TipoArticulo,$NoMarca,$FechaAlta,$NoImporte,$Clasificacion,$FechaUm,$NombreFoto,$NoEstatus,$precio_empeno,$precio_compra);

$connect->_query  = "SELECT a.CodigoProducto,a.Clasificacion01,a.Descripcion,a.Clasificacion02,a.Clasificacion03,a.FechaAlta,a.ImporteVenta,a.Clasificacion04,
a.FechaUM,a.NombreFotografia,a.NoEstatus,a.Importe01,a.Importe02
FROM BOPCatalogoProductos as a
LEFT JOIN BGECatalogoGeneral as b
ON a.Clasificacion02 = b.OpcCatalogo AND b.CodCatalogo = 5
LEFT JOIN BGECatalogoGeneral as c
ON a.Clasificacion03 = c.OpcCatalogo AND c.CodCatalogo = 6
LEFT JOIN BGECatalogoGeneral as d
ON a.NoEstatus = d.OpcCatalogo AND d.CodCatalogo = 8
WHERE a.Clasificacion01 = ".$NoCategoria." AND a.CodigoProducto = ".$NoArticulo." ORDER BY convert(a.CodigoProducto,signed) ASC";

$connect->get_result_query();

$infoProducto = $connect->_rows;



if( count($infoProducto) > 0 ){
    $rows = $infoProducto[0];
    //var_dump($rows);
    //echo "<br>";
    //var_dump($array1);
    $total_size = count($rows);

    for( $i=0; $i <= $total_size; $i++ ){

        if($array1[$i] != $rows[$i]){
            //Campos a Actualizar
            switch($i){
                case 0:
                    $nombre_campo = "CodigoProducto";
                    $descripcion_campo = " No de Productos";
                    $campoSql = "NoArticulo";
                    break;
                case 1:
                    $nombre_campo = "Clasificacion01";
                    $descripcion_campo  = "No Categoria";
                    break;
                case 2:
                    $nombre_campo = "Descripcion";
                    $descripcion_campo  = "Nombre Producto";
                    $campoSql = "Descripcion";
                    break;
                case 3:
                    $nombre_campo = "Clasificacion02";
                    $descripcion_campo  = "Tipo de Articulo";
                    $campoSql = "NoTipoAparato";
                    break;
                case 4:
                    $nombre_campo = "Clasificacion03";
                    $descripcion_campo  = "Marca de Articulo";
                    $campoSql = "NoMarca";
                    break;
                case 5:
                    $nombre_campo = "FechaAlta";
                    $descripcion_campo  = "Fecha Alta";
                    $campoSql = "FechaAlta";
                    break;
                case 6:
                    $nombre_campo = "ImporteVenta";
                    $descripcion_campo = "Precio de Venta";
                    $campoSql = "PrecioVenta";
                    break;
                case 7:
                    $nombre_campo = "Clasificacion04";
                    $campoSql = 'ClaveCondicion';
                    $descripcion_campo = "Clasificación";
                    break;
                case 8:
                    $nombre_campo = "FechaUM";
                    $descripcion_campo = "Fecha UM";
                    $campoSql = "FechaUM";
                    break;
                case 9:
                    $nombre_campo = "NombreFotografia";
                    $descripcion_campo = "Nombre Fotografia";
                    $campoSql ="Fotografia";
                    break;
                case 10:
                    $nombre_campo = "NoEstatus";
                    $descripcion_campo = "Estado";
                    break;
                case 11:
                    $nombre_campo = "Importe01";
                    $descripcion_campo = "Empeño";
                    $campoSql = "EmpenoMinimo";
                    break;
                case 12:
                    $nombre_campo = "Importe02";
                    $descripcion_campo = "Compra";
                    $campoSql = "EmpenoMaximo";
                    break;
            }

            // "id: ".$i." - campo Nuevo: ".$array1[$i] ." - Campo Anterior: ".$rows[$i]."<br>";

            $connect->_query = "UPDATE BOPCatalogoProductos SET ".$nombre_campo. "= '".$array1[$i]."' WHERE CodigoProducto = ".$NoArticulo." AND Clasificacion01 = ".$NoCategoria." ";
            $connect->execute_query();

            if($i <> 1 && $i <> 10 ){

                $sqlConnect->_sqlQuery = "UPDATE BAPCatalogoAparatos SET ".$campoSql." = '".$array1[$i]."',FechaUM = '".$FechaActual."' WHERE NoArticulo = $NoArticulo ";
                $sqlConnect->execute_query();

                $connect->_query = "UPDATE BOPCatalogoProductos SET NoUsuarioUM = ".$_SESSION['data_login']['NoUsuario']." ,FechaUM = '".$FechaActual."', HoraUM = '".$HoraAlta."' WHERe CodigoProducto = '.$NoArticulo.' AND Clasificacion01 =  '.$NoCategoria.' ";
                $connect->execute_query();
            }

            $connect->_query = "CALL sp_BGE_RegistraBitacora('1','0','1','C','FrmEditar','$NoArticulo','$nombre_campo','$descripcion_campo','$rows[$i]','$array1[$i]','Cambio','$NoUsuario','$FechaActual','$HoraAlta','$NoCategoria')";
            $connect->execute_query();

        }
    }

    echo "<script language='JavaScript'>MyAlert('Datos Guardados Correctamente','ok');fnpv_ver_producto('".$NoArticulo."','".$NoCategoria."')</script>";
}