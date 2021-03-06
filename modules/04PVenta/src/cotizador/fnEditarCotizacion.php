<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 15/09/2017
 * Time: 12:53 PM
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

header("ContentType:application/json");

if($_SERVER["REQUEST_METHOD"] == "POST"){


    //Sanatizar Datos
    $_POST = $connect->get_sanatiza($_POST);

    if(
        array_key_exists('txtNombre',$_POST) &&
        array_key_exists('txtMedioContacto',$_POST) &&
        array_key_exists('txtTipoCotizacion',$_POST) &&
        array_key_exists('txtCategoria',$_POST) &&
        array_key_exists('txtTipoProducto',$_POST) &&
        array_key_exists('txtMontoSolicitado',$_POST) &&
        array_key_exists('txtMontoAutorizado',$_POST) &&
        array_key_exists('txtDescripcion',$_POST)
    ){
        $NoUsuario = $_SESSION['data_login']['NoUsuario'];

        /*
         * --------------------------------
         *
         * Fecha Vigencia
         *
         * --------------------------------
         */

        $FolioCotizacion = (int) $_POST['FolioCotizacion'];
        $Serie = (int) $_POST['Serie'];
        $FechaUM = date("Y-m-d H:i:s");
        $FechaInicial = $FechaUM;
        $FechaVigencia = $FechaUM;
        $connect->_query = "
            call sp_04CotizadorRegistraCotizacion(
            '2',
            '$Serie',
            '$FolioCotizacion',
            '$_POST[txtNombre]',
            '$_POST[txtMedioContacto]',
            '$_POST[txtTipoCotizacion]',
            '$_POST[txtCategoria]',
            '$_POST[txtTipoProducto]',
            '$_POST[txtMontoSolicitado]',
            '$_POST[txtMontoAutorizado]',
            '$_POST[txtMontoAutorizado]',
            '$_POST[txtMontoAutorizado]',
            '$_POST[txtDescripcion]',
            '$_POST[txtObservaciones]',
            '1',
            '0',
            '$NoUsuario',
            '$FechaInicial',
            '$FechaVigencia',
            '$FechaUM',
            '$FechaUM'
            )";
        $connect->execute_query();
        echo json_encode(array("result"=>true,"message"=>"datos correctos","data"=>array("Folio"=>$FolioCotizacion)));

    }else{

        echo json_encode(array("result"=>false,"message"=>"Error parametros incorrectos, o vacios","data"=>array()));

    }




}else{
    echo json_encode(array("result"=>false,"message"=>"Error, metodo no soportado","data"=>array()));
}