<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 16/03/2017
 * Time: 11:05 AM
 */

header('Content-Type: application/JSON; charset-UTF8');
header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];
$posData = file_get_contents('php://input');


$serverName = "192.168.2.8"; //serverName\instanceName

$connectionInfo = array(
    "Database" => "SAyT",
    "Uid" => "sa",
    "PWD" => "masterkey"
);

$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {

    switch ($method) {

        case 'POST':

            if(isset($posData)){

                $request = json_decode($posData);

                $claveAutoriza = $request->claveAutoriza;

                if($claveAutoriza != "" ){

                    $query = "select Anio,FechaSolicitud,NoSucursal,FolioBoleta from SAyT.dbo.SolicitudesAutorizacion where CodigoSolicitud = '".$claveAutoriza."'; ";

                    $stmt = sqlsrv_query( $conn, $query);
                    if( $stmt === false ) {
                        header('HTTP/1.0 401 Unauthorized');
                    }else{

                        $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);


                        if(count($row) > 0){

                            echo json_encode($row);


                        }else{
                            header('HTTP/1.0 401 Unauthorized');
                        }

                    }

                }else{

                    header('HTTP/1.0 401 Unauthorized');

                }

            }else{
                header('HTTP/1.0 401 Unauthorized');
            }

            break;

        default://metodo NO soportado
            echo 'METODO NO SOPORTADO';
            break;
    }



}else{
    die( print_r( sqlsrv_errors(), true));
}