<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 14/02/2017
 * Time: 10:57 AM
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

require_once '../../../../plugins/html2pdf/html2pdf.class.php';
include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/model_tickets.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$Tickets = new \core\model_tickets($_SESSION['data_login']['BDDatos']);

$Tickets->valida_session_id($_SESSION['data_login']['NoUsuario']);

//Variables requeridas
$FechaActual = $Tickets->getFormatFecha(date("Ymd"),2);
$Anio = date("Y");

//Parametros recibidos
$Reporte = base64_decode($_REQUEST['fl']);
$NoDepartamento = base64_decode($_REQUEST['nodpto']);
$AnioTicket = base64_decode($_REQUEST['an']);

$query_tickets = "SELECT 
  R.Folio, 
  R.NoDepartamento, 
  D.Descripcion, 
  R.Fecha, 
  R.HoraInicioReporte, 
  R.DescripcionReporte, 
  R.Reporte, 
  R.MedioContacto, 
  R.PrioridadTicket, 
  R.TipoMantenimiento, 
  CU.NombreDePila , 
  R.Estatus, 
  E.Descripcion ,
  A.Descripcion , 
  S.Descripcion ,
  R.idEmpleado, 
  C.descripcion ,
  R.FechaPromesa,
  R.FechaCierre,
  CC.Descripcion, 
  CCC.Descripcion, 
  CCCC.Descripcion
FROM BSHReportes AS R
JOIN BGECatalogoDepartamentos AS D
  ON R.NoDepartamento = D.NoDepartamento
LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios AS CU
  ON R.NoUsuarioAsignado = CU.NoUsuario AND R.NoDepartamento = CU.NoDepartamento
JOIN BSHCatalogoEstatus AS E
  ON R.Estatus = E.NoEstatus
JOIN BGECatalogoDepartamentos AS S
  ON R.NoSucursal = S.NoDepartamento
LEFT JOIN BSHCatalogoAreas AS A
  ON R.NoArea = A.NoArea AND R.NoDepartamento = A.NoDepartamento
LEFT JOIN BSHCatalogoCategoria AS C
  ON R.Categoria = C.nocategoria AND R.NoDepartamento = '".$NoDepartamento."'
JOIN BSHCatalogoCatalogos AS CC
	ON R.MedioContacto = CC.idDescripcion AND CC.idCatalogo = 2
JOIN BSHCatalogoCatalogos AS CCC
	ON R.PrioridadTicket = CCC.idDescripcion AND CCC.idCatalogo = 1
JOIN BSHCatalogoCatalogos AS CCCC
	ON R.TipoMantenimiento = CCCC.idDescripcion AND CCCC.idCatalogo = 5 WHERE Folio = '".$Reporte."' AND Anio='".$AnioTicket."' AND R.NoDepartamento= '".$NoDepartamento."' ORDER BY Folio DESC LIMIT 1";


$query_seguimiento_ticket = "SELECT SR.FolioSeguimiento, SR.FechaSeguimiento, SR.HoraInicioSeguimiento, U.NombreDePila,SR.TipoAtencion,E.Descripcion, SR.Seguimiento,C.Descripcion
    FROM BSHSeguimientoReportes AS SR
    LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON SR.NoUsuarioSeguimiento = U.NoUsuario AND SR.NoDepartamento = U.NoDepartamento
    JOIN BSHCatalogoEstatus AS E
        ON SR.NoEstatus = E.NoEstatus
    JOIN BSHCatalogoCatalogos AS C
        ON SR.TipoAtencion = C.idDescripcion AND C.idCatalogo = 2
    WHERE SR.Folio = '".$Reporte."' AND SR.Anio='".$AnioTicket."' AND SR.NoDepartamento= '".$NoDepartamento."' ORDER BY SR.FolioSeguimiento DESC";


ob_start();


$Tickets->_query = $query_tickets ;
$Tickets->get_result_query();
$rowa = $Tickets->_rows[0];

$Tickets->_query = $query_seguimiento_ticket ;
$Tickets->get_result_query();
$seguimiento = $Tickets->_rows;

$NoTicket = $Tickets->getFormatFolio($rowa[0],4);

?>
<page backtop="35mm" backbottom="2mm">
    <style>
        .tablaDetailticket{
            width: 100% !important;
            border-collapse:collapse;
            font-weight: normal;
            font-size:12px;
            table-layout: fixed;
            cellppading:0px;
            cellspacing:0px;
        }
        .tablaDetailticket th{
            height:20px;
            padding:3px;
            background: #f4f4f4;
            font-weight: normal;
            font-size:13px;
        }
        .tablaDetailticket tr{
            cellppading:0px;
            cellspacing:0px;
        }
        .tablaDetailticket td{
            cellppading:0px;
            cellspacing:0px;
            padding:2px;
        }
    </style>

    <page_header footer='page'>
        <table>
            <tr>
                <td  width="150" rowspan="2"><img src="../../../../site_design/img/logos/pexpress_01.jpg"></td>
                <td align="center"><span style="font-weight:bold;font-size: 25px; text-align: center;vertical-align: text-bottom">Mesa de Ayuda</span></td>
                <td rowspan="2"><span  style="font-size:12px;">Folio: </span> <span style="font-size:17px; font-weight: bold; color:red;"><?=$NoTicket?></span></td>
            </tr>
            <tr>
                <td width="500" align="center" style="vertical-align:top;">Departamento de <?=$rowa[2]?></td>
            </tr>
        </table>
        <p style="font-size:13px;">
            Fecha: <?=$FechaActual?><br>
            Ticket: <?=$NoTicket?><br>
            Técnico Asignado: <?=$rowa[10]?>
        </p>
    </page_header>

    <div style="margin-top: 10px;;text-align:center;padding:3px;background:#d4d4d4;border:1px solid #ccc"><strong>Informaci&oacute;n de Reporte</strong></div>
    <div>

        <table  class="tablaDetailticket" border="1">
            <tr>
                <td width="100"><strong>Solicitante: </strong></td>
                <td width="355" ><?=$rowa[15]?> </td>
                <td width="100" ><strong>Fecha Alta: </strong></td>
                <td width="150" ><?=$rowa[3]?></td>
            </tr>
            <tr>
                <td><strong>Localidad: </strong></td>
                <td><?=$rowa[14]?></td>
                <td><strong>Fecha Promesa: </strong></td>
                <td><?=$rowa['FechaPromesa']?></td>
            </tr>
            <tr>
                <td><strong>&Aacute;rea:</strong></td>
                <td><?=$rowa[13]?></td>
                <td><strong>Fecha Cierre:</strong></td>
                <td><?=$rowa['FechaCierre']?></td>
            </tr>
            <tr>
                <td><strong>Categoria:</strong></td>
                <td><?=$rowa[16]?></td>
                <td><strong>Prioridad:</strong></td>
                <td><?=$rowa[20]?></td>

            </tr>
            <tr>
                <td><strong>Servicio:</strong></td>
                <td><?=$rowa[21]?></td>
                <td><strong>Estatus: </strong></td>
                <td><?=$rowa[12]?></td>
            </tr>
            <tr>
                <td><strong>Descripci&oacute;n:</strong></td>
                <td><?=$rowa[5]?></td>
                <td><strong>Medio de Contacto:</strong></td>
                <td><?=$rowa[19]?></td>
            </tr>
        </table>
    </div>
    <br/>
    <table class="tablaDetailticket">
        <tr><td align="center" style="font-size:13.5px;text-align:center;padding:3px;background:#d4d4d4; width: 743px;"><strong> Detalles de Servicio</strong></td></tr>
        <tr><td style="vertical-align: text-top;height:85px;"><?=$rowa[6]?></td></tr>

    </table>
    <br/>

    <table class="tablaDetailticket">
        <tr><td colspan="6" align="center" style="font-size:13.5px;text-align:center;padding:3px;background:#d4d4d4;"><strong>Seguimiento del Reporte</strong></td></tr>
        <tr style="font-weight:bold;border:none;background:#f4f4f4;"><td width="20">No</td><td width="120">Fecha</td><td width="120">Hora</td><td width="305">Nombre de Usuario</td><td width="70" align="center">Tipo</td><td width="70" align="center">Estatus</td></tr>
        <?php
        for($i = 0; $i < count($seguimiento);$i++){
            $fondo = 0;
            echo "<tr><td>".$seguimiento[$i]['FolioSeguimiento']."</td><td>".$Tickets->getFormatFecha($seguimiento[$i]['FechaSeguimiento'],2)."</td><td>".$seguimiento[$i]['HoraInicioSeguimiento']."</td><td>".$seguimiento[$i]['NombreDePila']."</td><td align='center'>".$seguimiento[$i][7]."</td><td align='center'>".$seguimiento[$i][5]."</td></tr>";
            $fondo++;
            if($fondo >= 1){
                $colors = "#f4f4f4";
            }
            echo "<tr><td></td><td colspan='5' width='700' style='background:".$colors.";text-align:justify;'>".$seguimiento[$i]["Seguimiento"]."</td></tr><tr><td colspan='5'>&nbsp;</td></tr>";
        }
        ?>
    </table>


    <page_footer>
        <table>
            <tr><td><div style="float:left; width:300px; text-align:center; margin-left:50px;border-top:1px solid;">Firma De Entrega</div></td><td><div style="float:left; width:300px; text-align:center; margin-left:50px;border-top:1px solid;">Firma De Recibido</div></td></tr>
        </table>
        <div style="font-size:9px;color:#b4b4b4; text-align:center;"><p>© 2014 Sistema Integral - Prestamo Express</p></div>
        <div style="font-size:9px;color:#b4b4b4; text-align:center;">[[page_cu]]/[[page_nb]]</div>
        <br/>
        <br/>
        <br/>
    </page_footer>

</page>
<?php
$content = ob_get_clean();
$pdf = new HTML2PDF('P','A4','fr','UTF-8');
$pdf->writeHTML($content);
$pdf->pdf->IncludeJS('print(TRUE)');
$pdf->output('Reporte'.$FechaActual.'.pdf');

?>
