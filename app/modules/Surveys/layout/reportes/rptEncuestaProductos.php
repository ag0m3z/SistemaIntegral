<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 1/03/16
 * Time: 12:02 PM
 */
include "../../../../controller/genContenido.class.php";

$connect = new \controller\Contenido();
//Validar que el usuario este logueado
if(!$connect->ValidaAcceso()){$connect->returnHomePage();}

//validar tiempo de actividad
$connect->ValidaSession_id();

$FechaActual = date("Ymd");

$condicion = $_REQUEST['cond'];

if(empty($condicion)){
    $where = "";
}else{
    $where = "WHERE ".$condicion;
}


$sql = "SELECT a.idEncuesta,e.Descripcion,a.CodProducto,ifnull(b.Descripcion,'Servicios'),c.Descripcion,d.Descripcion,a.Descripcion,a.PorcentajeCompra,a.PorcentajeEmpeno,a.ImporteVenta,
a.Clasificacion,g.Descripcion,h.Descripcion,a.IncMontoSolicita,i.Descripcion,a.IncMontoCompetidor,a.Observacion, a.NombreCorto,a.FechaAlta,f.Descripcion,a.HoraAlta
FROM BGEEncuestaProducto as a
LEFT JOIN BGECatalogoGeneral as b
ON a.NoCategoria = b.OpcCatalogo AND CodCatalogo = 9
LEFT JOIN BGECatalogoGeneral as c
ON a.TipoProducto = c.OpcCatalogo AND c.CodCatalogo = 5
LEFT JOIN BGECatalogoGeneral as d
ON a.NoMarca = d.OpcCatalogo AND d.CodCatalogo = 6
LEFT JOIN BGECatalogoGeneral as e
ON a.IncidenciaTipoServicio = e.OpcCatalogo AND e.CodCatalogo = 14
LEFT JOIN BGECatalogoDepartamentos as f
ON a.NoSucursal = f.NoDepartamento
LEFT JOIN BGECatalogoGeneral as g
ON a.NoAtendidos = g.OpcCatalogo AND g.CodCatalogo = 17
LEFT JOIN BGECatalogoGeneral as h
ON a.IncCondiciones = h.OpcCatalogo AND h.CodCatalogo = 15
LEFT JOIN BGECatalogoGeneral as i
ON a.IncNoCompetidor = i.OpcCatalogo AND i.CodCatalogo = 16
 ".$where."
ORDER BY a.idEncuesta DESC";
$sql;

$consulta_q = $connect->Consulta($sql);

/** Incluir la libreria PHPExcel */
require_once '../../../../lib/PHPExcel.php';

// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Establecer propiedades
$objPHPExcel->getProperties()
    ->setCreator("Alejandro Gomez")
    ->setLastModifiedBy("AGB")
    ->setTitle("Reporte de HelpDesk")
    ->setSubject("Reporte de HelpDesk ")
    ->setDescription("Reporte de HelpDesk")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Report");

$objActSheet = $objPHPExcel->getActiveSheet();
$objActSheet->setTitle('Encuestas');
$objActSheet->setCellValue('A2', 'Reporte Encuestas de Productos'); // El contenido de una cadena
$objActSheet->setCellValue('A4','Folio Encuesta');
$objActSheet->setCellValue('B4','Servicio');
$objActSheet->setCellValue('C4','Codigo Producto');
$objActSheet->setCellValue('D4','Categoria');
$objActSheet->setCellValue('E4','Tipo Producto');
$objActSheet->setCellValue('F4','Marca');
$objActSheet->setCellValue('G4','Descripcion');
$objActSheet->setCellValue('H4','Importe Venta');
$objActSheet->setCellValue('I4','Clasificacion');
$objActSheet->setCellValue('J4','No Atendido');
$objActSheet->setCellValue('K4','Condiciones');

$objActSheet->setCellValue('L4','Monto Solicita');
$objActSheet->setCellValue('M4','Monto Competidor');
$objActSheet->setCellValue('N4','Competidor');
$objActSheet->setCellValue('O4','Observaciones');
$objActSheet->setCellValue('P4','Sucursal');
$objActSheet->setCellValue('Q4','Usuario');

$objActSheet->setCellValue('R4','Fecha Registro');
$objActSheet->setCellValue('S4','Hora Registro');


$cel= 5;
while ($registro = mysqli_fetch_array($consulta_q)) {
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$cel, $connect->getFormatFolio($registro[0],5))
        ->setCellValueExplicit('A'.$cel, $connect->getFormatFolio($registro[0],5), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValue('B'.$cel,utf8_encode($registro[1]))
        ->setCellValue('C'.$cel, $connect->getFormatFolio($registro[2],5))
        ->setCellValueExplicit('C'.$cel, $connect->getFormatFolio($registro[2],5), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValue('D'.$cel,utf8_encode($registro[3]))
        ->setCellValue('E'.$cel,utf8_encode($registro[4]))
        ->setCellValue('F'.$cel,utf8_encode($registro[5]))
        ->setCellValue('G'.$cel,utf8_encode($registro[6]))
        ->setCellValue('H'.$cel,$registro[9])
        ->setCellValue('I'.$cel,$registro[10])
        ->setCellValue('J'.$cel,utf8_encode($registro[11]))
        ->setCellValue('K'.$cel,utf8_encode($registro[12]))
        ->setCellValue('L'.$cel,$registro[13])
        ->setCellValue('M'.$cel,$registro[15])
        ->setCellValue('N'.$cel,utf8_encode($registro[14]))
        ->setCellValue('O'.$cel,$registro[16])
        ->setCellValue('P'.$cel,utf8_encode($registro[19]))
        ->setCellValue('Q'.$cel,utf8_encode($registro[17]))
        ->setCellValue('R'.$cel,$connect->getFormatFecha($registro[18],2))
        ->setCellValue('S'.$cel,$registro[20]);


    $cel++;
}
$objActSheet->mergeCells('A2:M2');

//Establecer la anchura
$objActSheet->getColumnDimension('A4')->setAutoSize(true);
$objActSheet->getColumnDimension('A')->setWidth(10);
$objActSheet->getColumnDimension('B')->setWidth(30);
$objActSheet->getColumnDimension('C')->setWidth(10);
$objActSheet->getColumnDimension('D')->setWidth(10);
$objActSheet->getColumnDimension('E')->setWidth(30);
$objActSheet->getColumnDimension('F')->setWidth(13);
$objActSheet->getColumnDimension('G')->setWidth(13);
$objActSheet->getColumnDimension('H')->setWidth(30);
$objActSheet->getColumnDimension('I')->setWidth(10);
$objActSheet->getColumnDimension('J')->setWidth(13);
$objActSheet->getColumnDimension('K')->setWidth(15);
$objActSheet->getColumnDimension('L')->setWidth(10);
$objActSheet->getColumnDimension('M')->setWidth(10);
$objActSheet->getColumnDimension('N')->setWidth(10);
$objActSheet->getColumnDimension('O')->setWidth(10);
$objActSheet->getColumnDimension('P')->setWidth(10);
$objActSheet->getColumnDimension('Q')->setWidth(10);
$objActSheet->getColumnDimension('R')->setWidth(10);
$objActSheet->getColumnDimension('S')->setWidth(10);

$objStyleA5 = $objActSheet ->getStyle('A2:M2');
$objPHPExcel->getActiveSheet()->getStyle('A2:M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//Configuración de tipos de letra
$objFontA5 = $objStyleA5->getFont();
//$objFontA5->setName('Courier New');
$objFontA5->setSize(18);
$objFontA5->setBold(true);
//$objFontA5->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objFontA5 ->getColor()->setRGB('760025') ;
//Preferencias de color de relleno de celda - A5321123
$objStyleA5 = $objActSheet ->getStyle('A4:X4');
$objFontA5 ->getColor()->setRGB('ffffff') ;
$objFillA5 = $objStyleA5->getFill();
$objFillA5->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objFillA5->getStartColor()->setRGB('760025');

$objBorder = $objStyleA5->getBorders();
$objPHPExcel->getActiveSheet()->getStyle('A4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('B4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('C4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('D4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('E4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('F4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('G4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('H4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('I4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('J4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('K4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('L4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('M4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('N4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('O4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('P4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('Q4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('R4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('S4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('ffffff');



// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="EncuestaProductos_'.$FechaActual.'.xls"');
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
