<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 01:18 PM
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
if($_SESSION["EXPORT"]){
    $where = $_SESSION['EXPORT'];
}else{
    $where = "";
}

$rowData = $connect->listar_aparatos(0,'ASC',0,90000,$where);

$FechaActual = date("Ymd");
/** Incluir la libreria PHPExcel */
require_once '../../../../plugins/PHPExcel.php';

// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()
    ->setCreator("Alejandro Gomez")
    ->setLastModifiedBy("AGB")
    ->setTitle("Catalogo de Productos")
    ->setSubject("Catalogo de Productos")
    ->setDescription("Catalogo de Productos")
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
->setCellValue('A2', 'Reporte Catalogo de Productos') // El contenido de una cadena
->setCellValue('A4','Producto')
->setCellValue('B4','Descripción')
->setCellValue('C4','Categoria')
->setCellValue('D4','Tipo Producto')
->setCellValue('E4','Marca')
->setCellValue('F4','Precio Venta')
->setCellValue('G4','Clasificación')
->setCellValue('H4','C Empeño')
->setCellValue('I4','C Excelecte Compra')
->setCellValue('J4','C Buena Compra')

->setCellValue('K4','C Maxima Compra')
->setCellValue('L4','B Empeño')
->setCellValue('M4','B Exelente Compra')
->setCellValue('N4','B Buena Compra ')
->setCellValue('O4','B Maxima Compra')
->setCellValue('P4','A Empeño')

->setCellValue('Q4','A Buena Compra')
->setCellValue('R4','A Maxima Compra')
->setCellValue('S4','A Excelente Compra')
->setCellValue('T4','Fecha Alta')
->setCellValue('U4','Fecha UM');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:U2');


$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray( $style_title ); // give style to header
$objPHPExcel->getActiveSheet()->getStyle('A4:U4')->applyFromArray( $style_header ); // give style to header

$objPHPExcel->getActiveSheet()
    ->getStyle('A2:O2')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


$cel= 5;
for ($i = 0 ; $i < count($rowData) ;$i++){

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$cel, $connect->getFormatFolio($rowData[$i]['hCodigoProducto'],5))
        ->setCellValueExplicit('A'.$cel, $connect->getFormatFolio($rowData[$i]['hCodigoProducto'],5), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValue('B'.$cel, utf8_encode($rowData[$i]['hDescripcion']))
        ->setCellValue('C'.$cel, utf8_encode($rowData[$i]['hCategoria']))
        ->setCellValue('D'.$cel, utf8_encode($rowData[$i]['hTProducto']))
        ->setCellValue('E'.$cel, $rowData[$i]['hMarca'])
        ->setCellValue('F'.$cel, $rowData[$i]['hPrecioNvo'])
        ->setCellValue('G'.$cel, $rowData[$i]['hClasificaNvo'])
        ->setCellValue('H'.$cel, $rowData[$i]['hcEmpeno'])
        ->setCellValue('I'.$cel, $rowData[$i]['hcexcompra'])
        ->setCellValue('J'.$cel, $rowData[$i]['hcbuecompra'])
        ->setCellValue('K'.$cel, $rowData[$i]['hcmaxcompra'])
        ->setCellValue('L'.$cel, $rowData[$i]['hbEmpeno'])
        ->setCellValue('M'.$cel, $rowData[$i]['hbexcompra'])
        ->setCellValue('N'.$cel, $rowData[$i]['hbbuecompra'])
        ->setCellValue('O'.$cel, $rowData[$i]['hbmaxcompra'])
        ->setCellValue('P'.$cel, $rowData[$i]['haEmpeno'])
        ->setCellValue('Q'.$cel, $rowData[$i]['haexcompra'])
        ->setCellValue('R'.$cel, $rowData[$i]['habuecompra'])
        ->setCellValue('S'.$cel, $rowData[$i]['hamaxcompra'])
        ->setCellValue('T'.$cel, $rowData[$i]['hFechaA'])
        ->setCellValue('U'.$cel, $rowData[$i]['hFechaU']);
    $cel++;

}


//Establecer la anchura

$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("L")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("M")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("O")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("P")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("Q")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("R")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("S")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("T")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("U")->setAutoSize(true);


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Catalogo de Productos');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=" catalogo_productos'.$FechaActual.'.xls"'); // file name of excel
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
