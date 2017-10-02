<?php


include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/model_tickets.php";

$Tickets = new \core\model_tickets($_SESSION['data_login']['BDDatos']);
$Tickets->valida_session_id($_SESSION['data_login']['NoUsuario']);


$FechaActual = date("Ymd");
$Final_Query = $_SESSION['EXPORT_QUERY'];

$Tickets->_query = $Final_Query;
$Tickets->get_result_query();

/** Include PHPExcel */
require_once '../../../../plugins/PHPExcel.php';

$objPHPExcel = new PHPExcel(); // Create new PHPExcel object

$objPHPExcel->getProperties()->setCreator("Sigit prasetya n")
    ->setCreator("Alejandro Gomez")
    ->setLastModifiedBy("AGB")
    ->setTitle("Reporte de HelpDesk")
    ->setSubject("Reporte de HelpDesk ")
    ->setDescription("Reporte de HelpDesk")
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
    ->setCellValue('A2', 'Reporte Mesa de Ayuda') // El contenido de una cadena

    ->setCellValue('A4','Nombre de Usuario')
    ->setCellValue('B4','Folio')
    ->setCellValue('C4','Suc / Departamento')

    ->setCellValue('D4','Categoria')
    ->setCellValue('E4','Area')

    ->setCellValue('F4','Descripción')
    ->setCellValue('G4','Tipo de Atención')
    ->setCellValue('H4','Fecha Inicial')
    ->setCellValue('I4','Hora Inicial')
    ->setCellValue('J4','Fecha Promesa')
    ->setCellValue('K4','Solución')
    ->setCellValue('L4','Fecha Final')
    ->setCellValue('M4','Hora Final')
    ->setCellValue('N4','Tiempo Real');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:N2');
$objPHPExcel->getDefaultStyle()
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray( $style_title ); // give style to header
$objPHPExcel->getActiveSheet()->getStyle('A4:N4')->applyFromArray( $style_header ); // give style to header
$objPHPExcel->getActiveSheet()
    ->getStyle('A2:N2')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Create Data
$dataku=array(

    array('C001','Iphone 6'),
);

$firststyle='A4';


$cel = 5;
for($i=0;$i<count($Tickets->_rows);$i++)
{


    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A".$cel, $Tickets->_rows[$i][0])
        ->setCellValue("B".$cel, $Tickets->getFormatFolio($Tickets->_rows[$i][1],5))
        ->setCellValue("C".$cel, $Tickets->_rows[$i][3])
        ->setCellValue("D".$cel, $Tickets->_rows[$i][16])
        ->setCellValue("E".$cel, $Tickets->_rows[$i][18])
        ->setCellValue("F".$cel, $Tickets->_rows[$i][4])
        ->setCellValue("G".$cel, $Tickets->_rows[$i][19])
        ->setCellValue("H".$cel, $Tickets->getFormatFecha($Tickets->_rows[$i][6],2))
        ->setCellValue("I".$cel, $Tickets->_rows[$i][7])
        ->setCellValue("J".$cel, $Tickets->getFormatFecha($Tickets->_rows[$i][10],2))
        ->setCellValue("K".$cel, $Tickets->_rows[$i][11])
        ->setCellValue("L".$cel, $Tickets->getFormatFecha($Tickets->_rows[$i][8],2))
        ->setCellValue("M".$cel, $Tickets->_rows[$i][9])
        ->setCellValue("N".$cel, "00:00:00")

    ;

    $cel = $cel+1;

}




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
$objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(true);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Reporte Mesa de Ayuda');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=" reporte_tickets'.$FechaActual.'.xls"'); // file name of excel
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