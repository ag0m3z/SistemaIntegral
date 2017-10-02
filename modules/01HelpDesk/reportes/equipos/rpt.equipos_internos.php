<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/02/2017
 * Time: 05:46 PM
 */
/**
 * Incluir las Librerias Principales del Sistema
 * En el Siguiente Orden
 * ruta de libreias: @@/SistemaIntegral/core/
 *
 * 1.- core.php;
 * 2.- sesiones.php
 * 3.- seguridad.php
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/model_equipos.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$Equipos = new \core\model_equipos($_SESSION['data_login']['BDDatos']);
$Equipos->valida_session_id($_SESSION['data_login']['NoUsuario']);


$SqlSelect = "SELECT I.Folio,I.NombreCompleto,D.Descripcion,I.Puesto,C.Descripcion,I.Marca,I.Modelo,
                        I.Procesador,I.Memoria,I.Disco,I.Caracteristicas,I.CodigoCedis,I.SerieCedis,I.SerieEquipo,
                        I.MotivoAsignacion,I.MotivoEntrega,I.CondicionesEntrega,I.UsuarioEquipo,I.ContrasenaEquipo,
                        CC.Descripcion,us.NombreDePila,I.FechaRegistro,I.HoraRegistro,I.FechaAsignacion,
                        I.FechaEntrega,I.HoraEntrega,I.FechaEnvio,I.HoraEnvio
                  FROM BSHInventarioEquipos AS I
                  JOIN BGECatalogoDepartamentos AS D
                  ON I.NoDepartamento = D.NoDepartamento
                  JOIN BSHCatalogoCatalogos AS C
                  ON I.Equipo = C.idDescripcion AND C.idCatalogo = 7
                  JOIN BSHCatalogoCatalogos AS CC
                  ON I.Estatus = CC.idDescripcion AND CC.idCatalogo = 8
                  JOIN SINTEGRALGNL.BGECatalogoUsuarios as us
                  ON I.UsuarioRecibe = us.NoUsuario ";

$SqlOrderBy = "ORDER BY I.Folio DESC";


if( $_SESSION['EXPORT'] ){

    $SqlWhere = " WHERE ".$_SESSION['EXPORT'];

    $Equipos->_query = $SqlSelect.$SqlWhere.$SqlOrderBy;
    $Equipos->get_result_query();
    $data = $Equipos->_rows;

    if(count($data) > 0 ){

        // incluir la libreria PHPEXCEL
        include "../../../../plugins/PHPExcel.php" ;

        // crear el objecto phpexcel
        $objPHPExcel = new PHPExcel();

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

        //propiedades de excel
        $objPHPExcel->getProperties()
            ->setCreator("Alejandro Gomez")
            ->setLastModifiedBy("AGB")
            ->setTitle("Reporte Equipos de Uso Interno")
            ->setSubject("Equipos de uso interno ")
            ->setDescription("Equipos de uso interno")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Report");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'Reporte Equipos De Uso Interno') // El contenido de una cadena
            ->setCellValue('A4','Folio')
            ->setCellValue('B4','Nombre ')
            ->setCellValue('C4','Departamento')
            ->setCellValue('D4','Puesto')
            ->setCellValue('E4','Tipo de Equipo')
            ->setCellValue('F4','Marca')
            ->setCellValue('G4','Modelo')

            ->setCellValue('H4','Procesador')
            ->setCellValue('I4','Memoria')
            ->setCellValue('J4','Disco')
            ->setCellValue('K4','Caracteristicas')
            ->setCellValue('L4','Codigo Cedis')

            ->setCellValue('M4','Serie Cedis')
            ->setCellValue('N4','Serie Equipo')
            ->setCellValue('O4','Motivo de Asignación')
            ->setCellValue('P4','Motivo de Entrega')
            ->setCellValue('Q4','Condiciones de Entrega')
            ->setCellValue('R4','Usuario Windows')
            ->setCellValue('S4','Contraseña Windows')
            ->setCellValue('T4','Estatus')
            ->setCellValue('U4','Usuario Registra')
            ->setCellValue('V4','Fecha de Registro')
            ->setCellValue('W4','Hora de Registro')
            ->setCellValue('X4','Fecha de Asignación')
            ->setCellValue('Y4','Fecha de Entrega')
            ->setCellValue('Z4','Hora de Entrega')
            ->setCellValue('AA4','Fecha de Envio')
            ->setCellValue('AB4','Hora de Envio');

        //Aplicar Estilos
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:AB2');

        $objPHPExcel->getDefaultStyle()
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray( $style_title ); // give style to header
        $objPHPExcel->getActiveSheet()->getStyle('A4:AB4')->applyFromArray( $style_header ); // give style to header

        $objPHPExcel->getActiveSheet()
            ->getStyle('A2:O2')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $cel= 5;
        for ($i=0; $i < count($data); $i++) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$cel, $Equipos->getFormatFolio($data[$i][0],5))
                ->setCellValue('B'.$cel, $data[$i][1])
                ->setCellValue('C'.$cel, $data[$i][2])
                ->setCellValue('D'.$cel, $data[$i][3])
                ->setCellValue('E'.$cel, $data[$i][4])
                ->setCellValue('F'.$cel, $data[$i][5])
                ->setCellValue('G'.$cel, $data[$i][6])

                ->setCellValue('H'.$cel, $data[$i][7])
                ->setCellValue('I'.$cel, $data[$i][8])
                ->setCellValue('J'.$cel, $data[$i][9])
                ->setCellValue('K'.$cel, $data[$i][10])
                ->setCellValueExplicit('L'.$cel, $data[$i][11],PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('M'.$cel, $data[$i][12],PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('N'.$cel, $data[$i][13],PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('O'.$cel, $data[$i][14])
                ->setCellValue('P'.$cel, $data[$i][15])
                ->setCellValue('Q'.$cel, $data[$i][16])
                ->setCellValue('R'.$cel, $data[$i][17])
                ->setCellValue('S'.$cel, $data[$i][18])
                ->setCellValue('T'.$cel, $data[$i][19])
                ->setCellValue('U'.$cel, $data[$i][20])
                ->setCellValue('V'.$cel, $data[$i][21])
                ->setCellValue('W'.$cel, $data[$i][22])
                ->setCellValue('X'.$cel, $data[$i][23])
                ->setCellValue('Y'.$cel, $data[$i][24])
                ->setCellValue('Z'.$cel, $data[$i][25])
                ->setCellValue('AA'.$cel, $data[$i][26])
                ->setCellValue('AB'.$cel, $data[$i][27]);


            $i++;
            $cel++;

        }
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Equipos de uso Interno');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=" equipos_uso_interno'.$FechaActual.'.xls"'); // file name of excel
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


    }else{
        echo "<h2>No se encontraron resultados !</h2>";

    }

}else{
    echo "<h2> No se encontro la consulta, requerida </h2><br>";
    var_dump($_SESSION['EXPORT']);

}