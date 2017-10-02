<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 04:31 PM
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
include '../../../../core/sqlconnect.php';

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

$FechaACtual = date("Ymd");
$HoraActual = date("H:i:s");
$idEmpleado = substr($_SESSION['data_login']['NoEmpleado'], -5);

$FDateTime = date('Y-m-d H:i:s');
$NoUsuario = $_SESSION['data_login']['NoUsuario'];

//Validar que Contegan un Archivo
if(isset($_FILES['archivo'])){

    //Extraer Nombre del Documento Adjunto
    $nombre = $_FILES['archivo']['name'];
    $nombre_tmp = $_FILES['archivo']['tmp_name'];
    $tipo = $_FILES['archivo']['type'];
    $tamano = $_FILES['archivo']['size'];

    //Ruta de Carpeta
    $Carpeta = "../../Adjuntos/temp/";
    $Archivo = $_FILES['archivo'];

    //Extension del Archivo.
    $extension1 = pathinfo($Archivo['name'], PATHINFO_EXTENSION);
    $ext_permitidas = array('xlsx','XLSX');
    $partes_nombre = explode('.', $nombre);
    $extension = end( $partes_nombre );
    $ext_correcta = in_array($extension, $ext_permitidas);
    $arrayMoneda = array("$",",");

    //expresion para sacar el filtro.
    $tipo_correcto = preg_match('/^application\/(msword|vnd.ms-excel|nd.ms-powerpoint|pdf)$/', $tipo);
    if(in_array($extension, $ext_permitidas)){
        //Validar que no venga dañado
        if($_FILES['archivo']['error']>0){

        }else{

            $HoraActual =  date("H:i:s");
            $NombreFinal = "Cotizaciones".$FechaACtual.$HoraActual.".".$extension1;

            if(!move_uploaded_file($Archivo['tmp_name'], $Carpeta.$NombreFinal)){

                echo json_encode(array('result'=>false,'message'=>'Error al Subir el documento','data'=>array()));
            }else{

                error_reporting(E_ALL);
                ini_set("display_errors", 1);


                /** Include path **/
                set_include_path(get_include_path() . PATH_SEPARATOR . '../../../../plugins/');
                /** PHPExcel_IOFactory */
                include 'PHPExcel/IOFactory.php';

                $inputFileType = 'Excel2007';
                $inputFileName = $Carpeta.$NombreFinal;

                /**  Create a new Reader of the type defined in $inputFileType  **/
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);

                /**  Load $inputFileName to a PHPExcel Object  **/
                $objPHPExcel = $objReader->load($inputFileName);

                try {
                    //This line seen error, but cannot echo in catch.
                    $objPHPExcel->setActiveSheetIndexByName('Tabla Cotizaciones Metal');// $cell contain a formula, example: `=A1+A6-A8`
                    // with A1 is constant, A6 is formula `=A2*A5`
                    // and A8 is another `=A1/(A4*100)-A7`
                } catch (Exception $e) {
                    //echo $e->getTraceAsTring();
                    echo json_encode(array('result'=>false,'message'=>'La Hoja de Cotizacion No existe','data'=>array()));
                    exit();
                }

                //$objPHPExcel->setActiveSheetIndex(0);

                $Titulo = $objPHPExcel->getActiveSheet()->getCell('A4')->getCalculatedValue();
                $TipoDeCambio = $objPHPExcel->getActiveSheet()->getCell('B4')->getCalculatedValue();
                $OnzaTroyUSD = $objPHPExcel->getActiveSheet()->getCell('B5')->getCalculatedValue();;
                $OnzaPlataUSD = $objPHPExcel->getActiveSheet()->getCell('B6')->getCalculatedValue();;
                $TipoMoneda = "USD";

                $BaseComercializacionOro = $objPHPExcel->getActiveSheet()->getCell('AA3')->getCalculatedValue();;
                $BaseComercializacionPlata = $objPHPExcel->getActiveSheet()->getCell('AA19')->getCalculatedValue();


                if($Titulo == "Tipo de Cambio (MXN/USD)" && $BaseComercializacionOro == "COMERCIALIZACIÓN ORO" && $BaseComercializacionPlata == "COMERCIALIZACIÓN PLATA"){

                    $mmsql = new \core\sqlconnect();

                    //Recorrer Campos de Metal de Plata
                    for($i=20;$i <= 25;$i++){

                        //Bloque Compra
                        $MetalPlata[$i]['CompraCteNuevo'] = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue();
                        $MetalPlata[$i]['CompraBuena'] = $objPHPExcel->getActiveSheet()->getCell('H'.$i)->getCalculatedValue();
                        $MetalPlata[$i]['CompraMaxima'] = $objPHPExcel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue();

                        //Bloque de Empeño
                        $MetalPlata[$i]['EmpenoCteNuevo'] = $objPHPExcel->getActiveSheet()->getCell('J'.$i)->getCalculatedValue();
                        $MetalPlata[$i]['EmpenoCteExcelente'] = $objPHPExcel->getActiveSheet()->getCell('K'.$i)->getCalculatedValue();

                        //Bloque de Agresivo
                        $MetalPlata[$i]['UltimaOpcion'] = $objPHPExcel->getActiveSheet()->getCell('L'.$i)->getCalculatedValue();
                        $MetalPlata[$i]['HistorialImpecable'] = $objPHPExcel->getActiveSheet()->getCell('M'.$i)->getCalculatedValue();

                        //Precio Base Comision
                        $MetalPlata[$i]['precio_comision'] = $objPHPExcel->getActiveSheet()->getCell('S'.$i)->getCalculatedValue();

                        //Precio Comercializacion
                        $MetalPlata[$i]['precio_comercializacion'] = $objPHPExcel->getActiveSheet()->getCell('AB'.$i)->getCalculatedValue();

                    }

                    foreach ($MetalPlata as $key=>$valor){

                        //Bloque de Compra
                        $CompraBuenaCteNuevo = str_replace($arrayMoneda, "", $valor['CompraCteNuevo']);
                        $CompraBuenaBuena = str_replace($arrayMoneda, "", $valor['CompraBuena']);
                        $CompraBuenaMaxima = str_replace($arrayMoneda, "", $valor['CompraMaxima']);

                        //Bloque de Empeño
                        $EmpenoCteNuevo = str_replace($arrayMoneda, "", $valor['EmpenoCteNuevo']);
                        $EmpenoCteExcelente = str_replace($arrayMoneda, "", $valor['EmpenoCteExcelente']);

                        //Bloque de Agresivo
                        $UltimaOpcion = str_replace($arrayMoneda, "", $valor['UltimaOpcion']);
                        $HistorialImpecable = str_replace($arrayMoneda, "", $valor['HistorialImpecable']);

                        //Precio Base Comision
                        $PrecioComision = str_replace($arrayMoneda, "", $valor['precio_comision']);

                        //Precio Comercializacion
                        $PrecioComercializacionPlata = str_replace($arrayMoneda, "", $valor['precio_comercializacion']);

                        switch ($key){
                            case 20:
                                $idCotizacion = 18;
                                $where = " where idCotizacion = 18";

                                $mmsql->_sqlQuery ="UPDATE SAyT.dbo.BPFCotizacionPlata SET "
                                    . "Ley999=".$EmpenoCteNuevo.","
                                    . "Ley9992= ".$EmpenoCteExcelente.","
                                    . "Ley9993= ".$CompraBuenaCteNuevo.","
                                    . "Ley9994= ".$CompraBuenaBuena.","
                                    . "Ley9995= ".$CompraBuenaMaxima.","
                                    . "Ley9996= ".$UltimaOpcion.","
                                    . "Ley9997= ".$HistorialImpecable.","
                                    . "NoUsuario= ".$NoUsuario.","
                                    . "FechaUM='".$FechaACtual."'  WHERE SysKey = 1";

                                $mmsql->execute_query();

                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionPlata',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 21 AND Elemento = 5 ";
                                $mmsql->execute_query();


                                break;
                            case 21:
                                $idCotizacion = 17;
                                $where = " where idCotizacion = 17";
                                $connect->_query = "UPDATE BGECotizaciones SET "
                                    . "Cotizacion01 = ".$EmpenoCteNuevo.", "
                                    . "Cotizacion02 = ".$EmpenoCteExcelente.", "
                                    . "Cotizacion03 = ".$CompraBuenaCteNuevo.","
                                    . "Cotizacion04 = ".$CompraBuenaBuena.","
                                    . "Cotizacion05 = ".$CompraBuenaMaxima.","
                                    . "Cotizacion06 = ".$UltimaOpcion.","
                                    . "Cotizacion07 = ".$HistorialImpecable.","
                                    . "FechaUM = '".$FechaACtual."', HoraUM = '".$HoraActual."',NoUsuarioUM = ".$NoUsuario." where idCotizacion = 19";

                                $connect->execute_query();
                                $connect->_query = "
                                CALL sp_bge_HitorialCotizaciones(
                                    '19',
                                    '$EmpenoCteNuevo',
                                    '$EmpenoCteExcelente',
                                    '$CompraBuenaCteNuevo',
                                    '$CompraBuenaBuena',
                                    '$CompraBuenaMaxima',
                                    '$UltimaOpcion',
                                    '$HistorialImpecable',
                                    '$PrecioComision',
                                    '$PrecioComercializacionPlata',
                                    '$TipoDeCambio',
                                    '$TipoMoneda',
                                    '$OnzaTroyUSD',
                                    '$OnzaPlataUSD',
                                    '$NoUsuario',
                                    '$FechaACtual',
                                    '$HoraActual'
                                )";
                                $connect->execute_query();

                                //Insertar Actualizacion en SQL Server Tabla de Cotizacion Plata-excelente_venta
                                $mmsql->_sqlQuery = "
                   UPDATE SAyT.dbo.BPFCotizacionPlata
                   SET Joyeria925 = ".$EmpenoCteNuevo.",
                        Joyeria9252 = ".$EmpenoCteExcelente.",
                        Joyeria9253=".$CompraBuenaCteNuevo.",
                        Joyeria9254 = ".$CompraBuenaBuena.",
                         Joyeria9255=".$CompraBuenaMaxima.",
                        Joyeria9256 = ".$UltimaOpcion.",
                        Joyeria9257 = ".$HistorialImpecable.",
                        Ley925 = ".$EmpenoCteNuevo.",
                        Ley9252 = ".$EmpenoCteExcelente.",
                        Ley9253 = ".$CompraBuenaCteNuevo.",
                        Ley9254 = ".$CompraBuenaBuena.",
                        Ley9255 = ".$CompraBuenaMaxima.",
                        Ley9256 = ".$UltimaOpcion.",
                        Ley9257 = ".$HistorialImpecable.",
                        FechaUM ='".$FechaACtual."' WHERE SysKey = 1";

                                $mmsql->execute_query();

                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionPlata',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 21 AND Elemento = 4 ";
                                $mmsql->execute_query();

                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionPlata',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 21 AND Elemento = 6 ";
                                $mmsql->execute_query();


                                break;
                            case 22:
                                $idCotizacion = 16;
                                $where = " where idCotizacion = 16";
                                $mmsql->_sqlQuery = "UPDATE SAyT.dbo.BPFCotizacionPlata SET "
                                    . "Ley900=".$EmpenoCteNuevo.","
                                    . "Ley9002= ".$EmpenoCteExcelente.","
                                    . "Ley9003= ".$CompraBuenaCteNuevo.","
                                    . "Ley9004= ".$CompraBuenaBuena.","
                                    . "Ley9005= ".$CompraBuenaMaxima.","
                                    . "Ley9006= ".$UltimaOpcion.","
                                    . "Ley9007= ".$HistorialImpecable.","
                                    . "FechaUM='".$FechaACtual."'  WHERE SysKey = 1";
                                $mmsql->execute_query();

                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionPlata',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 21 AND Elemento = 3 ";
                                $mmsql->execute_query();

                                break;
                            case 23:
                                $idCotizacion = 15;
                                $where = " where idCotizacion = 15";
                                $mmsql->_sqlQuery = "UPDATE SAyT.dbo.BPFCotizacionPlata SET "
                                    . "Ley720=".$EmpenoCteNuevo.","
                                    . "Ley7202= ".$EmpenoCteExcelente.","
                                    . "Ley7203= ".$CompraBuenaCteNuevo.","
                                    . "Ley7204= ".$CompraBuenaBuena.","
                                    . "Ley7205= ".$CompraBuenaMaxima.","
                                    . "Ley7206= ".$UltimaOpcion.","
                                    . "Ley7207= ".$HistorialImpecable.","
                                    . "FechaUM='".$FechaACtual."'  WHERE SysKey = 1";
                                $mmsql->execute_query();

                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionPlata',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 21 AND Elemento = 2 ";
                                $mmsql->execute_query();

                                break;
                            case 24:
                                $idCotizacion = 14;
                                $where = " where idCotizacion = 14";

                                $mmsql->_sqlQuery = "UPDATE SAyT.dbo.BPFCotizacionPlata SET "
                                    . "Ley500=".$EmpenoCteNuevo.","
                                    . "Ley5002= ".$EmpenoCteExcelente.","
                                    . "Ley5003= ".$CompraBuenaCteNuevo.","
                                    . "Ley5004= ".$CompraBuenaBuena.","
                                    . "Ley5005= ".$CompraBuenaMaxima.","
                                    . "Ley5006= ".$UltimaOpcion.","
                                    . "Ley5007= ".$HistorialImpecable.","
                                    . "FechaUM='".$FechaACtual."'  WHERE SysKey = 1";
                                $mmsql->execute_query();

                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionPlata',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 21 AND Elemento = 1 ";
                                $mmsql->execute_query();

                                break;
                            case 25:
                                $idCotizacion = 20;
                                $where = " where idCotizacion = 20";

                                $mmsql->_sqlQuery = "UPDATE SAyT.dbo.BPFCotizacionPlata SET "
                                    . "SinLey=".$EmpenoCteNuevo.","
                                    . "SinLey2= ".$EmpenoCteExcelente.","
                                    . "SinLey3= ".$CompraBuenaCteNuevo.","
                                    . "SinLey4= ".$CompraBuenaBuena.","
                                    . "SinLey5= ".$CompraBuenaMaxima.","
                                    . "SinLey6= ".$UltimaOpcion.","
                                    . "SinLey7= ".$HistorialImpecable.","
                                    . "FechaUM='".$FechaACtual."'  WHERE SysKey = 1";
                                $mmsql->execute_query();

                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionPlata',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 21 AND Elemento = 7 ";
                                $mmsql->execute_query();


                                break;
                            default:
                                $idCotizacion = "NO";
                                break;
                        }

                        $connect->_query = "UPDATE BGECotizaciones SET "
                            . "Cotizacion01 = ".$EmpenoCteNuevo.", "
                            . "Cotizacion02 = ".$EmpenoCteExcelente.", "
                            . "Cotizacion03 = ".$CompraBuenaCteNuevo.","
                            . "Cotizacion04 = ".$CompraBuenaBuena.","
                            . "Cotizacion05 = ".$CompraBuenaMaxima.","
                            . "Cotizacion06 = ".$UltimaOpcion.","
                            . "Cotizacion07 = ".$HistorialImpecable.","
                            . "FechaUM = '".$FechaACtual."', HoraUM = '".$HoraActual."',NoUsuarioUM =".$NoUsuario.$where;

                        $connect->execute_query();

                        if($idCotizacion != 'NO'){
                            $connect->_query = "
                                CALL sp_bge_HitorialCotizaciones(
                                    '$idCotizacion',
                                    '$EmpenoCteNuevo',
                                    '$EmpenoCteExcelente',
                                    '$CompraBuenaCteNuevo',
                                    '$CompraBuenaBuena',
                                    '$CompraBuenaMaxima',
                                    '$UltimaOpcion',
                                    '$HistorialImpecable',
                                    '$PrecioComision',
                                    '$PrecioComercializacionPlata',
                                    '$TipoDeCambio',
                                    '$TipoMoneda',
                                    '$OnzaTroyUSD',
                                    '$OnzaPlataUSD',
                                    '$NoUsuario',
                                    '$FechaACtual',
                                    '$HoraActual'
                                )";
                            $connect->execute_query();

                        }

                    }


                    // ### Metal de Oro
                    for($i=4;$i <= 16;$i++){

                        /*
                         * Cambio solicitado por enrique
                         * VAlor del campo (G) sera el mismo del campo (H)
                         * VAlor del campo (J) sera el mismo del campo (K)
                        */

                        /*$MetalOro[$i]['compra'] = $objPHPExcel->getActiveSheet()->getCell('H'.$i)->getCalculatedValue();
                        $MetalOro[$i]['bcompra'] = $objPHPExcel->getActiveSheet()->getCell('H'.$i)->getCalculatedValue();
                        $MetalOro[$i]['mcompra'] = $objPHPExcel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue();
                        $MetalOro[$i]['nuevo'] = $objPHPExcel->getActiveSheet()->getCell('K'.$i)->getCalculatedValue();
                        $MetalOro[$i]['bueno'] = $objPHPExcel->getActiveSheet()->getCell('K'.$i)->getCalculatedValue();
                        $MetalOro[$i]['excelente'] = $objPHPExcel->getActiveSheet()->getCell('L'.$i)->getCalculatedValue();
                        */
                        //Bloque de Compra
                        $MetalOro[$i]['CompraCteNuevo'] = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue();
                        $MetalOro[$i]['CompraBuena'] = $objPHPExcel->getActiveSheet()->getCell('H'.$i)->getCalculatedValue();
                        $MetalOro[$i]['CompraMaxima'] = $objPHPExcel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue();

                        //Bloque de Empeño
                        $MetalOro[$i]['EmpenoCteNuevo'] = $objPHPExcel->getActiveSheet()->getCell('J'.$i)->getCalculatedValue();
                        $MetalOro[$i]['EmpenoCteExcelente'] = $objPHPExcel->getActiveSheet()->getCell('K'.$i)->getCalculatedValue();

                        //Bloque de Agresivo
                        $MetalOro[$i]['UltimaOpcion'] = $objPHPExcel->getActiveSheet()->getCell('L'.$i)->getCalculatedValue();
                        $MetalOro[$i]['HistorialImpecable'] = $objPHPExcel->getActiveSheet()->getCell('M'.$i)->getCalculatedValue();

                        //Precio Base Comision
                        $MetalOro[$i]['precio_comision'] = $objPHPExcel->getActiveSheet()->getCell('S'.$i)->getCalculatedValue();

                        //Precio Comercializacion
                        $MetalOro[$i]['precio_comercializacion'] = $objPHPExcel->getActiveSheet()->getCell('AB'.$i)->getCalculatedValue();
                    }

                    foreach ($MetalOro as $key=>$valor){

                        //Bloque de Compra
                        $CompraBuenaCteNuevo = str_replace($arrayMoneda, "", $valor['CompraCteNuevo']);
                        $CompraBuenaBuena = str_replace($arrayMoneda, "", $valor['CompraBuena']);
                        $CompraBuenaMaxima = str_replace($arrayMoneda, "", $valor['CompraMaxima']);

                        //Bloque de Empeño
                        $EmpenoCteNuevo = str_replace($arrayMoneda, "", $valor['EmpenoCteNuevo']);
                        $EmpenoCteExcelente = str_replace($arrayMoneda, "", $valor['EmpenoCteExcelente']);

                        //Bloque de Agresivo
                        $UltimaOpcion = str_replace($arrayMoneda, "", $valor['UltimaOpcion']);
                        $HistorialImpecable = str_replace($arrayMoneda, "", $valor['HistorialImpecable']);

                        //Precio Base Comision
                        $PrecioComision = str_replace($arrayMoneda, "", $valor['precio_comision']);

                        //Precio Comercializacion
                        $PrecioComercializacionOro = str_replace($arrayMoneda, "", $valor['precio_comercializacion']);

                        switch ($key){
                            case 4:
                                $idCotizacion = 7;
                                $where = " where idCotizacion = 7";

                                $mmsql->_sqlQuery = "
                                UPDATE SAyT.dbo.BPFCotizacionOro
                                SET CGMedalla1=".$EmpenoCteNuevo.",
                                    CGMedalla2=".$EmpenoCteExcelente.",
                                    CGMedalla3=".$CompraBuenaCteNuevo.",
                                    CGMedalla4=".$CompraBuenaBuena.",
                                    CGMedalla5=".$CompraBuenaMaxima.",
                                    CGMedalla6=".$UltimaOpcion.",
                                    CGMedalla7=".$HistorialImpecable.",
                                    NoUsuario= ".$NoUsuario.",
                                    FechaUM ='".$FechaACtual."'
                                    WHERE SysKey = 1";
                                $mmsql->execute_query();

                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionOro',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 20 AND Elemento = 7 ";
                                $mmsql->execute_query();

                                break;
                            case 5:
                                $idCotizacion = 1;
                                $where = " where idCotizacion = 1";

                                $mmsql->_sqlQuery = "
                                UPDATE SAyT.dbo.BPFCotizacionOro
                                SET CG8K1=".$EmpenoCteNuevo.",
                                    CG8K2=".$EmpenoCteExcelente.",
                                    CG8K3=".$CompraBuenaCteNuevo.",
                                    CG8K4=".$CompraBuenaBuena.",
                                    CG8K5=".$CompraBuenaMaxima.",
                                    CG8K6=".$UltimaOpcion.",
                                    CG8K7=".$HistorialImpecable.",
                                    FechaUM ='".$FechaACtual."'
                                    WHERE SysKey = 1";

                                $mmsql->execute_query();

                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionOro',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 20 AND Elemento = 1 ";
                                $mmsql->execute_query();

                                break;
                            case 6:

                                $idCotizacion = 2;
                                $where = " where idCotizacion = 2";
                                $mmsql->_sqlQuery = "
                                UPDATE SAyT.dbo.BPFCotizacionOro
                                SET CG10K1=".$EmpenoCteNuevo.",
                                CG10K2=".$EmpenoCteExcelente.",
                                CG10K3=".$CompraBuenaCteNuevo.",
                                CG10K4=".$CompraBuenaBuena.",
                                CG10K5=".$CompraBuenaMaxima.",
                                CG10K6=".$UltimaOpcion.",
                                CG10K7=".$HistorialImpecable.",
                                FechaUM ='".$FechaACtual."'
                                WHERE SysKey = 1";

                                $mmsql->execute_query();

                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionOro',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 20 AND Elemento = 2 ";
                                $mmsql->execute_query();


                                break;
                            case 7:

                                $idCotizacion = 3;
                                $where = " where idCotizacion = 3";

                                $mmsql->_sqlQuery = "
                                UPDATE SAyT.dbo.BPFCotizacionOro
                                SET CG14K1=".$EmpenoCteNuevo.",
                                    CG14K2=".$EmpenoCteExcelente.",
                                    CG14K3=".$CompraBuenaCteNuevo.",
                                    CG14K4=".$CompraBuenaBuena.",
                                    CG14K5=".$CompraBuenaMaxima.",
                                    CG14K6=".$UltimaOpcion.",
                                    CG14K7=".$HistorialImpecable.",
                                    FechaUM ='".$FechaACtual."'
                                    WHERE SysKey = 1" ;

                                $mmsql->execute_query();
                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionOro',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 20 AND Elemento = 3 ";
                                $mmsql->execute_query();


                                break;
                            case 8:

                                $idCotizacion = 4;
                                $where = " where idCotizacion = 4";
                                $mmsql->_sqlQuery = "
                        UPDATE SAyT.dbo.BPFCotizacionOro
                        SET CG18K1=".$EmpenoCteNuevo.",
                            CG18K2=".$EmpenoCteExcelente.",
                            CG18K3=".$CompraBuenaCteNuevo.",
                            CG18K4=".$CompraBuenaBuena.",
                            CG18K5=".$CompraBuenaMaxima.",
                            CG18K6=".$UltimaOpcion.",
                            CG18K7=".$HistorialImpecable.",
                            FechaUM ='".$FechaACtual."'
                            WHERE SysKey = 1";

                                $mmsql->execute_query();
                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionOro',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 20 AND Elemento = 4 ";
                                $mmsql->execute_query();



                                break;
                            case 9:
                                $idCotizacion = 5;
                                $where = " where idCotizacion = 5";
                                $mmsql->_sqlQuery = "
                        UPDATE SAyT.dbo.BPFCotizacionOro
                        SET CG21K1=".$EmpenoCteNuevo.",
                            CG21K2=".$EmpenoCteExcelente.",
                            CG21K3=".$CompraBuenaCteNuevo.",
                            CG21K4=".$CompraBuenaBuena.",
                            CG21K5=".$CompraBuenaMaxima.",
                            CG21K6=".$UltimaOpcion.",
                            CG21K7=".$HistorialImpecable.",
                            FechaUM ='".$FechaACtual."'
                            WHERE SysKey = 1";

                                $mmsql->execute_query();
                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionOro',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 20 AND Elemento = 5 ";
                                $mmsql->execute_query();


                                break;
                            case 10:
                                $idCotizacion = 6;
                                $where = " where idCotizacion = 6";
                                $mmsql->_sqlQuery = "
                        UPDATE SAyT.dbo.BPFCotizacionOro
                        SET CGOroFino1=".$EmpenoCteNuevo.",
                            CGOroFino2=".$EmpenoCteExcelente.",
                            CGOroFino3=".$CompraBuenaCteNuevo.",
                            CGOroFino4=".$CompraBuenaBuena.",
                            CGOroFino5=".$CompraBuenaMaxima.",
                            CGOroFino6=".$UltimaOpcion.",
                            CGOroFino7=".$HistorialImpecable.",
                            FechaUM ='".$FechaACtual."'
                            WHERE SysKey = 1";

                                $mmsql->execute_query();
                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionOro',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 20 AND Elemento = 6 ";
                                $mmsql->execute_query();


                                break;
                            case 11:
                                $idCotizacion = 8;
                                $where = " where idCotizacion = 8";
                                $HistorialImpecable = 0;

                                $mmsql->_sqlQuery = "
                        UPDATE SAyT.dbo.BPFCotizacionOro
                        SET CM21=".$EmpenoCteNuevo.",
                            CM22=".$EmpenoCteExcelente.",
                            CM23=".$CompraBuenaCteNuevo.",
                            CM24=".$CompraBuenaBuena.",
                            CM25=".$CompraBuenaMaxima.",
                            CM26=".$UltimaOpcion.",
                            CM27=0,
                            FechaUM ='".$FechaACtual."'
                            WHERE SysKey = 1" ;

                                $mmsql->execute_query();
                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionOro',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 20 AND Elemento = 8 ";
                                $mmsql->execute_query();

                                break;
                            case 12:
                                $idCotizacion = 9;
                                $where = " where idCotizacion = 9";
                                $HistorialImpecable = 0;
                                $mmsql->_sqlQuery = "
                        UPDATE SAyT.dbo.BPFCotizacionOro
                        SET CM251=".$EmpenoCteNuevo.",
                            CM252=".$EmpenoCteExcelente.",
                            CM253=".$CompraBuenaCteNuevo.",
                            CM254=".$CompraBuenaBuena.",
                            CM255=".$CompraBuenaMaxima.",
                            CM256=".$UltimaOpcion.",
                            CM257=0,
                            FechaUM ='".$FechaACtual."'
                            WHERE SysKey = 1";

                                $mmsql->execute_query();
                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionOro',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 20 AND Elemento = 9 ";
                                $mmsql->execute_query();

                                break;
                            case 13:
                                $idCotizacion = 10;
                                $where = " where idCotizacion = 10";
                                $HistorialImpecable = 0;
                                $mmsql->_sqlQuery = "
                        UPDATE SAyT.dbo.BPFCotizacionOro
                        SET CM51=".$EmpenoCteNuevo.",
                            CM52=".$EmpenoCteExcelente.",
                            CM53=".$CompraBuenaCteNuevo.",
                            CM54=".$CompraBuenaBuena.",
                            CM55=".$CompraBuenaMaxima.",
                            CM56=".$UltimaOpcion.",
                            CM57=0,
                            FechaUM ='".$FechaACtual."'
                            WHERE SysKey = 1";
                                $mmsql->execute_query();
                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionOro',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 20 AND Elemento = 10 ";
                                $mmsql->execute_query();
                                break;
                            case 14:

                                $idCotizacion = 11;
                                $where = " where idCotizacion = 11";
                                $HistorialImpecable = 0;
                                $mmsql->_sqlQuery = "
                        UPDATE SAyT.dbo.BPFCotizacionOro
                        SET CM101=".$EmpenoCteNuevo.",
                            CM102=".$EmpenoCteExcelente.",
                            CM103=".$CompraBuenaCteNuevo.",
                            CM104=".$CompraBuenaBuena.",
                            CM105=".$CompraBuenaMaxima.",
                            CM106=".$UltimaOpcion.",
                            CM107=0,
                            FechaUM ='".$FechaACtual."'
                            WHERE SysKey = 1";

                                $mmsql->execute_query();
                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionOro',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 20 AND Elemento = 11 ";
                                $mmsql->execute_query();

                                break;
                            case 15:

                                $idCotizacion = 12;
                                $where = " where idCotizacion = 12";
                                $HistorialImpecable = 0;
                                $mmsql->_sqlQuery = "
                        UPDATE SAyT.dbo.BPFCotizacionOro
                        SET CM201=".$EmpenoCteNuevo.",
                            CM202=".$EmpenoCteExcelente.",
                            CM203=".$CompraBuenaCteNuevo.",
                            CM204=".$CompraBuenaBuena.",
                            CM205=".$CompraBuenaMaxima.",
                            CM206=".$UltimaOpcion.",
                            CM207=0,
                            FechaUM ='".$FechaACtual."'
                            WHERE SysKey = 1";
                                $mmsql->execute_query();
                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionOro',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 20 AND Elemento = 12 ";
                                $mmsql->execute_query();
                                break;
                            case 16:

                                $idCotizacion = 13;
                                $where = " where idCotizacion = 13";
                                $HistorialImpecable = 0;
                                $mmsql->_sqlQuery = "
                        UPDATE SAyT.dbo.BPFCotizacionOro
                        SET CM501=".$EmpenoCteNuevo.",
                            CM502=".$EmpenoCteExcelente.",
                            CM503=".$CompraBuenaCteNuevo.",
                            CM504=".$CompraBuenaBuena.",
                            CM505=".$CompraBuenaMaxima.",
                            CM506=".$UltimaOpcion.",
                             CM507= 0,
                            FechaUM ='".$FechaACtual."'
                            WHERE SysKey = 1";

                                $mmsql->execute_query();
                                $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTCatalogos SET Valor1N = '$PrecioComision',Valor2N = '$PrecioComercializacionOro',NoEmpleado = '$idEmpleado',FechaUltimaActualizacion = convert(datetime,'$FDateTime',120) WHERE CatalogoID = 20 AND Elemento = 13 ";
                                $mmsql->execute_query();

                                break;
                            default:
                                $idCotizacion = "NO";
                                break;
                        }

                        $connect->_query = "UPDATE BGECotizaciones SET "
                            . "Cotizacion01 = ".$EmpenoCteNuevo.", "
                            . "Cotizacion02 = ".$EmpenoCteExcelente.", "
                            . "Cotizacion03 = ".$CompraBuenaCteNuevo.","
                            . "Cotizacion04 = ".$CompraBuenaBuena.","
                            . "Cotizacion05 = ".$CompraBuenaMaxima.","
                            . "Cotizacion06 = ".$UltimaOpcion.","
                            . "Cotizacion07 = ".$HistorialImpecable.","
                            . " FechaUM = '".$FechaACtual."', HoraUM = '".$HoraActual."',NoUsuarioUM =".$NoUsuario.$where;
                        $connect->execute_query();

                        if($idCotizacion != 'NO'){

                            $connect->_query = "
                                CALL sp_bge_HitorialCotizaciones(
                                    '$idCotizacion',
                                    '$EmpenoCteNuevo',
                                    '$EmpenoCteExcelente',
                                    '$CompraBuenaCteNuevo',
                                    '$CompraBuenaBuena',
                                    '$CompraBuenaMaxima',
                                    '$UltimaOpcion',
                                    '$HistorialImpecable',
                                    '$PrecioComision',
                                    '$PrecioComercializacionOro',
                                    '$TipoDeCambio',
                                    '$TipoMoneda',
                                    '$OnzaTroyUSD',
                                    '$OnzaPlataUSD',
                                    '$NoUsuario',
                                    '$FechaACtual',
                                    '$HoraActual'
                                )";
                            $connect->execute_query();
                        }
                    }

                    $mmsql->_sqlQuery = "SELECT * FROM BDSPSAYT.dbo.BSAYTTipodeCambio WHERE convert(date,Fecha,110) =  convert(date,'$FDateTime',120)";
                    $mmsql->get_result_query();

                    if(count($mmsql->_sqlRows) > 0 ){

                        $id = $mmsql->_sqlRows[0][0];

                        $mmsql->_sqlQuery = "UPDATE BDSPSAYT.dbo.BSAYTTipodeCambio SET Moneda = '$TipoMoneda', Fecha = convert(datetime,'$FDateTime',120), TipodeCambio = '$TipoDeCambio' WHERE TipoCambioID =$id";
                        $mmsql->execute_query();

                    }else{
                        $mmsql->_sqlQuery = "insert into BDSPSAYT.dbo.BSAYTTipodeCambio (Moneda,Fecha,TipodeCambio) values ('$TipoMoneda',convert(datetime,'$FDateTime',120),'$TipoDeCambio')";
                        $mmsql->execute_query();
                    }



                    echo json_encode(array('result'=>true,'message'=>'Documento cargado correctamente','data'=>array('Titulo'=>$Titulo,'metales'=>'')));





                }else{

                    echo json_encode(array('result'=>false,'message'=>'Tipo de Documento Invalido','data'=>array()));

                }
            }
        }
    }else{

        //Extencion no Valida
        echo json_encode(array('result'=>false,'message'=>'Extencion no valida','data'=>array()));
    }
}else{
    echo json_encode(array('result'=>false,'message'=>'Error en el documento','data'=>array()));

}
