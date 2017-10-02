<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/03/2017
 * Time: 11:29 AM
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
include "../../../../core/model_encuestas.php";

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

$connect = new \core\model_encuestas($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/

foreach($_SESSION['EXPORT'] as $key=> $val){
    if($val != 0){
        if($key == 'Fecha2'){$key = 'a.FechaAlta <';}
        if($key == 'a.Clasificacion'){
            $DesClass = $connect->Consulta("SELECT Descripcion FROM BGECatalogoGeneral WHERE OpcCatalogo = ".$val." AND CodCatalogo = 7");
            if($connect->num_rows($DesClass)>0){
                $idClass = mysqli_fetch_array($DesClass);

            }$val = "'$idClass[0]'";

        }
        $matriz[] = array($key,$val);
    }
}
if(count($matriz)>0){

    for($i=0;$i < count($matriz);$i++){
        if(count($matriz) > $i){
            $and = " and ";
        }else{
            $and="";
        }

        $where[] = $matriz[$i][0]."=".$matriz[$i][1].$and." ";
    }

    $Condicion = " ".substr($where[0].$where[1].$where[2].$where[3].$where[4].$where[5].$where[6].$where[7].$where[8].$where[9],0,-5);

}else{
    $Condicion = "";
}

$registro = $connect->buscar_encuesta_producto(1,$Condicion,false);


/** Incluir la libreria PHPExcel */
require_once '../../../../plugins/PHPExcel.php';

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()
    ->setCreator("Alejandro Gomez")
    ->setLastModifiedBy("AGB")
    ->setTitle("Encuesta de Productos")
    ->setSubject("Encuesta de Productos")
    ->setDescription("Encuesta de Productos")
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
    ->setCellValue('A2','Reporte Encuestas de Productos')
    ->setCellValue('A4','Folio Encuesta')
    ->setCellValue('B4','Servicio')
    ->setCellValue('C4','Codigo Producto')
    ->setCellValue('D4','Categoria')
    ->setCellValue('E4','Tipo Producto')
    ->setCellValue('F4','Marca')
    ->setCellValue('G4','Descripcion')
    ->setCellValue('H4','Importe Venta')
    ->setCellValue('I4','Clasificacion')
    ->setCellValue('J4','No Atendido')
    ->setCellValue('K4','Condiciones')
    ->setCellValue('L4','Monto Solicita')
    ->setCellValue('M4','Monto Competidor')
    ->setCellValue('N4','Competidor')
    ->setCellValue('O4','Observaciones')
    ->setCellValue('P4','Sucursal')
    ->setCellValue('Q4','Usuario')
    ->setCellValue('R4','Fecha Registro')
    ->setCellValue('S4','Hora Registro');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:S2');


$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray( $style_title ); // give style to header
$objPHPExcel->getActiveSheet()->getStyle('A4:S4')->applyFromArray( $style_header ); // give style to header



$objPHPExcel->getActiveSheet()
    ->getStyle('A2:S2')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$cel= 5;
for ($i = 0 ; $i < count($registro) ;$i++){

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$cel, $connect->getFormatFolio($registro[$i][0],5))
        ->setCellValueExplicit('A'.$cel, $connect->getFormatFolio($registro[$i][0],5), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValue('B'.$cel,$registro[$i][9])
        ->setCellValue('C'.$cel, $connect->getFormatFolio($registro[$i][1],5))
        ->setCellValueExplicit('C'.$cel, $connect->getFormatFolio($registro[$i][1],5), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValue('D'.$cel,$registro[$i][2])
        ->setCellValue('E'.$cel,$registro[$i][3])
        ->setCellValue('F'.$cel,$registro[$i][4])
        ->setCellValue('G'.$cel,utf8_encode($registro[$i][5]))
        ->setCellValue('H'.$cel,$registro[$i][11])
        ->setCellValue('I'.$cel,$registro[$i][6])
        ->setCellValue('J'.$cel,$registro[$i][13])
        ->setCellValue('K'.$cel,$registro[$i][15])
        ->setCellValue('L'.$cel,$registro[$i][16])
        ->setCellValue('M'.$cel,$registro[$i][17])
        ->setCellValue('N'.$cel,$registro[$i][19])
        ->setCellValue('O'.$cel,$registro[$i][20])
        ->setCellValue('P'.$cel,utf8_encode($registro[$i][10]))
        ->setCellValue('Q'.$cel,utf8_encode($registro[$i][7]))
        ->setCellValue('R'.$cel,$connect->getFormatFecha($registro[$i][21],2))
        ->setCellValue('S'.$cel,$registro[$i][22]);
    $cel++;

}


//Establecer la anchura
$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(10);;
$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(10);;
$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(30);;
$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(13);;
$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension("K")->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension("L")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("M")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("N")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("O")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("P")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("Q")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("R")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("S")->setWidth(10);




// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Encuestas de Productos');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=" reporte_encuesta_productos'.$FechaActual.'.xls"'); // file name of excel
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