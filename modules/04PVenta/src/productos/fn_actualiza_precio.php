<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 11:12 AM
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

$msql = new \core\sqlconnect();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$jsondata = array();

if( isset($_POST['eCodigo']) ) {

    $CodigoProducto = $_POST['eCodigo'];
    $Precio = $_POST['ePrecio'];
    $PrecioNvo = $_POST['ePrecioNvo'];
    $Clasificacion = $_POST['eClasificacion'];
    $ClasificacionNvo = $_POST['eClasificacionNvo'];

    $NoUsuario = $_SESSION['data_login']['NoUsuario'];
    $FechaActual = date("Ymd");

    if($Precio != $PrecioNvo){
        // si hay un cambio Actualizar Precio

        $HoraActual = date("H:i:s");
        $connect->_query = "SELECT Clasificacion01 FROM BOPCatalogoProductos WHERE CodigoProducto = '$CodigoProducto' LIMIT 0,1 ";
        $connect->get_result_query();
        $idCategoria = $connect->_rows[0][0];

        //Si el Importe Si Se Actualizo Se Prepara el Sql Para el Update.
        $sql ="UPDATE BOPCatalogoProductos
                SET ImporteVenta = '$PrecioNvo'
              WHERE CodigoProducto = '".$CodigoProducto."' ";

        $sql_um = "UPDATE BOPCatalogoProductos 
                    SET NoUsuarioUM = ".$_SESSION['data_login']['NoUsuario']." ,
                        FechaUM = '".$FechaActual."', 
                        HoraUM = '".$HoraActual."' 
                    WHERe CodigoProducto = '".$CodigoProducto."'";


        //Registrar Movimiento en la Bitacora
        $call_sp = "CALL sp_BGE_RegistraBitacora('1','0','1','C','FrmEditLista','$CodigoProducto','ImporteVenta','Precio de Venta','$Precio','$PrecioNvo','Cambio','$NoUsuario','$FechaActual','$HoraActual','$idCategoria')";

        // Actualizar en SAYT Microsft SQL Server
        $connect->_query = $sql ;
        $connect->execute_query();

        $msql->_sqlQuery = "UPDATE BAPCatalogoAparatos SET PrecioVenta = '".$PrecioNvo."',FechaUM = '".$FechaActual."' WHERE NoArticulo= $CodigoProducto ";
        $msql->execute_query();

        $connect->_query = $sql_um ;
        $connect->execute_query();

        $connect->_query = $call_sp ;
        $connect->execute_query();
    }

    if($Clasificacion != $ClasificacionNvo){
        $HoraActual = date("H:i:s");
        //Si el Importe Si Se Actualizo Se Prepara el Sql Para el Update.
        $sql ="UPDATE BOPCatalogoProductos
                SET Clasificacion04 = '".$ClasificacionNvo."'
        WHERE CodigoProducto = '".$CodigoProducto."' ";

        $sql_um = "UPDATE BOPCatalogoProductos 
                    SET NoUsuarioUM = ".$_SESSION['data_login']['NoUsuario']." ,
                        FechaUM = '".$FechaActual."', 
                        HoraUM = '".$HoraActual."' 
                    WHERe CodigoProducto = '".$CodigoProducto."' ";

        $call_sp = "CALL sp_BGE_RegistraBitacora('1','0','1','C','FrmEditLista','$CodigoProducto','Clasificacion04','Clasificación','$Clasificacion','$ClasificacionNvo','Cambio','$NoUsuario','$FechaActual','$HoraActual','$idCategoria')";

        $connect->_query = $sql;
        $connect->execute_query();

        $msql->_sqlQuery = "UPDATE BAPCatalogoAparatos SET FechaUM = '".$FechaActual."',ClaveCondicion = '".$ClasificacionNvo."' WHERE NoArticulo= $CodigoProducto ";
        $msql->execute_query();

        $connect->_query = $sql_um ;
        $connect->execute_query();

        $connect->_query = $call_sp ;
        $connect->execute_query();

    }



    $jsondata['result'] = 'ok';
    //Aunque el content-type no sea un problema en la mayoría de casos, es recomendable especificarlo
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata);
    exit();
}
