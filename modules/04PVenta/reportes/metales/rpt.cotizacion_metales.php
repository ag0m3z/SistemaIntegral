<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 04:01 PM
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
include "../../../../core/model_metales.php";
require_once '../../../../plugins/PHPExcel.php';



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


$connect = new \core\model_metales($_SESSION['data_login']['BDDatos']);

$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();


/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

// Datos para oro
$connect->_query = "SELECT Descripcion,Cotizacion01,Cotizacion02,Cotizacion03,Cotizacion04 FROM BGECotizaciones WHERE NoCategoria = 1";
$connect->get_result_query();
$cons_oro  = $connect->_rows;

// Datos de Plata
$connect->_query = "SELECT Descripcion,Cotizacion01,Cotizacion02,Cotizacion03,Cotizacion04 FROM BGECotizaciones WHERE NoCategoria = 7";
$connect->get_result_query();
$cons_plata = $connect->_rows;


$FechaActual = date("Ymd");
$HoraActual = date("H:i:s");



$objPHPExcel->getProperties()
    ->setCreator("Alejandro Gomez")
    ->setLastModifiedBy("AGB")
    ->setTitle("Cotizacion Metales")
    ->setSubject("Cotizacion Metales")
    ->setDescription("Cotizacion Metales")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Report");

// create style
$default_border = array(
    'style' => PHPExcel_Style_Border::BORDER_THIN,
    'color' => array('rgb'=>'FFFFFF')
);

$style_title = array(
    'font' => array(
        'bold' => true,
        'size' => 11,
        'color'=>array('rgb'=>'793240')
    )
);

$style_header = array(
    'borders' => array(
        'bottom' => $default_border,
        'left' => $default_border,
        'top' => $default_border,
        'right' => $default_border,
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb'=>'793240'),
    ),
    'font' => array(
        'bold' => false,
        'size' => 12,
        'color'=>array('rgb'=>'FFFFFF')
    )
);
$style_content = array(
    'borders' => array(
        'bottom' => $default_border,
        'left' => $default_border,
        'top' => $default_border,
        'right' => $default_border,
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb'=>'eeeeee'),
    ),
    'font' => array(
        'size' => 12,
    )
);


// Create Header
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A2','Tabla de Cotizaciones')
    ->setCellValue('A4','Cotización por Gramo')
    ->setCellValue('B4','Compra')
    ->setCellValue('C4','Cliente Nuevo')
    ->setCellValue('D4','Buen Cliente')
    ->setCellValue('E4','Excelente Cliente');




$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:E2');


$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray( $style_title ); // give style to header
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->applyFromArray( $style_header ); // give style to header



$objPHPExcel->getActiveSheet()
    ->getStyle('A2:E2')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


$cel1= 5;
for ($i = 0 ; $i < count($cons_oro) ;$i++){

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$cel1,$cons_oro[$i][0])
        ->setCellValue('B'.$cel1,$cons_oro[$i][1])
        ->setCellValue('C'.$cel1,$cons_oro[$i][2])
        ->setCellValue('D'.$cel1,$cons_oro[$i][3])
        ->setCellValue('E'.$cel1,$cons_oro[$i][4]);
    $i++;
    $cel1++;

}

$cel1 +=2;

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A'.$cel1,'Cotización por Gramo')
->setCellValue('B'.$cel1,'Compra')
->setCellValue('C'.$cel1,'Cliente Nuevo')
->setCellValue('D'.$cel1,'Buen Cliente')
->setCellValue('E'.$cel1,'Excelente Cliente');

$objPHPExcel->getActiveSheet()->getStyle('A'.$cel1.':E'.$cel1.'')->applyFromArray( $style_header ); // give style to header


$cel2 = $cel1 + 1;
for ($i = 0 ; $i < count($cons_plata) ;$i++){

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$cel2,$cons_plata[$i][0])
        ->setCellValue('B'.$cel2,$cons_plata[$i][1])
        ->setCellValue('C'.$cel2,$cons_plata[$i][2])
        ->setCellValue('D'.$cel2,$cons_plata[$i][3])
        ->setCellValue('E'.$cel2,$cons_plata[$i][4]);
    $cel2++;

}


//Establecer la anchura

$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Cotizacion Metales');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=" cotizacion_metales'.$FechaActual.'.xls"'); // file name of excel
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
