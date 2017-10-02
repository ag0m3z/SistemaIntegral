<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/02/2017
 * Time: 10:50 AM
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

require_once '../../../../plugins/html2pdf/html2pdf.class.php';


/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$Equipos = new \core\model_equipos($_SESSION['data_login']['BDDatos']);

$Equipos->valida_session_id($_SESSION['data_login']['NoUsuario']);

$FechaActual = date("Ymd");
$Anio = date("Y");
$Folio =  $_REQUEST['fl'];
$TpoArchivo =  $_REQUEST['rf'];

$Titulo = "";
$Imagen = "";
$FechaLarga = "";
$pageFooter = '';
$Body2="";

$Equipos->_query = "SELECT I.NombreCompleto,I.Puesto,I.NoDepartamento,I.FechaAsignacion,I.Equipo,C.Descripcion,I.Procesador,I.Modelo,
I.Marca,I.Caracteristicas,I.CodigoCedis,I.SerieCedis,I.SerieEquipo,I.Memoria,I.Disco,U.NombreDePila,I.UsuarioEquipo,I.ContrasenaEquipo,
D.Descripcion,I.FechaEntrega,I.FechaEnvio, I.MotivoAsignacion,I.MotivoEntrega,I.CondicionesEntrega
    FROM BSHInventarioEquipos AS I
    	JOIN BSHCatalogoCatalogos AS C
    		ON I.Equipo = C.idDescripcion AND idCatalogo = 7
    	JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
    		ON I.UsuarioRecibe = U.NoUsuario AND U.NoDepartamento = '0109'
    	JOIN BGECatalogoDepartamentos AS D
    		ON I.NoDepartamento = D.NoDepartamento
    WHERE Folio = $Folio LIMIT 1";

$Equipos->get_result_query();

$data = $Equipos->_rows[0];

ob_start();

switch($TpoArchivo){
    case 1:
        // CARTA DE ASIGNACION - PARA EQUIPOS DE USO INTERNO
        $nombre_doc = "Carta Responsiva";
        $Titulo = "<br>CARTA RESPONSIVA";
        $head = "<p class='txt1'>
                            Por medio del presente escrito me permito manifestar. Recib&iacute; de parte de la persona moral denominada
                            ORGANIZACI&Oacute;N TREVI&Ntilde;O, S.A. DE C.V. el siguiente aparato descrito a continuaci&oacute;n, para uso
                            exclusivo del desempe&ntilde;o de mis actividades laborales asignadas.
                        </p>";
        $Body = "<p class='txt1'>
                        Equipo: <strong>".$data[5]."&nbsp;&nbsp;</strong>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        Marca: <strong>".$data[8]."&nbsp;&nbsp;&nbsp;</strong>
                        Modelo: <strong>".$data[7]."&nbsp;</strong>
                    </p>
                    <p class='txt1'>
                        Procesador: <strong>".$data[6]."&nbsp;&nbsp;</strong>
                        Memoria: <strong>".$data[13]."&nbsp;&nbsp;&nbsp;</strong>
                        DD: <strong>".$data[14]."&nbsp;</strong>
                    </p>
                    <p class='txt1'>
                        C&oacute;digo: <strong>".$Equipos->getFormatFolio($data[10],5)."&nbsp;&nbsp;&nbsp;</strong>
                        Serie: <strong>".$data[11]."&nbsp;</strong>
                    </p>
                    <p class='txt1'>
                        Serie Equipo: <strong>".$data[12]."</strong>
                    </p>";
        $footer = '<p class="txt1">
                            Por tal motivo y de mi libre voluntad, manifiesto que a partir de la fecha antes mencionada,
                            el suscrito soy el &Uacute;nico responsable del equipo ya referido, y mediante el presente me
                            comprometo a responder por cualquier da&ntilde;o o negligencia, que se contraigan por el mal
                            uso de dicho equipo.
                        </p>
                        <p class="txt1">
                            Lo anterior, en el entendido de que al finalizar la relaci&oacute;n laboral que me une con la
                            persona moral antes citada, deber&eacute; devolver el mencionado equipo en buenas condiciones,
                            salvo el deterioro normal que por su uso se ocasion&eacute;.
                        </p>
                        <p class="txt1">
                            As&iacute;, y enterada la persona moral Organizaci&oacute;n Trevi&ntilde;o S.A. de C.V., a
                            trav&eacute;s de su representante legal, recibe de conformidad, la constancia firmada por el
                            responsable, manifestando que este de acuerdo con el contenido de la misma.
                        </p>
                        <p class="txt1" style="text-align: center;margin-top:50px;">
                            Monterrey, Nuevo Le&oacute;n; '.\core\core::mostrarFecha('small').'
                        </p>
                        <table style="margin-top:50px;margin-bottom:120px;" align="center">
                            <tr>
                                <td width="450" height="30"></td>
                            </tr>
                            <tr>
                                <td style="border-top:1px solid #000;text-align:center;">
                                    '.$data[0].'<br>
                                    <strong>'.$data[1]." ".$data[18].'</strong>
                                    <br><span style="font-size:12px">
                                            Recibe
                                        </span>
                                </td>
                            </tr>
                        </table>';
        $Body2 = '<br><br><br><br>
                        <table style="margin-top:20px;border:1px solid #ccc;CELLSPACING=0px;" align="center">'."
                            <tr>
                                <td colspan='2' style='background:#c1c1c1;font-weight:bold;text-align:center'>
                                    DATOS DEL EQUIPO
                                </td>
                            </tr>
                            <tr>
                                <td width='270'>
                                    <br>FECHA DE ASIGNACI&Oacute;N
                                </td>
                                <td width='270'>
                                    <br><strong>".$Equipos->getFormatFecha($data[3],2)."</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>PUESTO</td>
                                <td><strong>".$data[1]."</strong></td>
                            </tr>
                            <tr>
                                <td>EQUIPO</td>
                                <td><strong>".$data[5]."</strong></td>
                            </tr>
                            <tr>
                                <td>MODELO</td>
                                <td><strong>".$data[7]."</strong></td>
                            </tr>
                            <tr>
                                <td>MARCA</td>
                                <td><strong>".$data[8]."</strong></td>
                            </tr>
                            <tr>
                                <td>CARACTERISTICAS</td>
                                <td><strong>".$data[6]."</strong></td>
                            </tr>
                            <tr>
                                <td>CODIGO</td>
                                <td><strong>".$Equipos->getFormatFolio($data[10],5)."</strong></td>
                            </tr>
                            <tr>
                                <td>SERIE</td>
                                <td><strong>".$data[11]."</strong></td>
                            </tr>
                            <tr>
                                <td>SERIE EQUIPO</td>
                                <td><strong>".$data[12]."</strong></td>
                            </tr>
                            <tr>
                                <td colspan='2'>&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2' style='background:#c1c1c1;font-weight:bold;text-align:center'>&nbsp;
                                </td>
                            </tr>
			    <tr>
				<td><br>MOTIVO ASIGNACI&Oacute;N</td>
				<td><br><strong>".$data[21]."</strong></td>
			    </tr>
			    <tr>
				<td>ACCESORIOS</td>
				<td><strong>".$data[9]."</strong></td>
			    </tr>
                            </table>
                            <table align='center'>
                                <tr>
                                    <td width='600' height='100'></td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;border-top:1px solid #000;'>
                                        ".$data[0]."<br><strong>".$data[1]." ".$data[18]."</strong>
                                        <br><span style='font-size:12px;'>Recibe</span>
                                    </td>
                                </tr>
                            </table>
                            <table align='center' >
                                <tr>
                                    <td width='600' height='100'></td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;border-top:1px solid #000;'>
                                        Entrega<br>
                                        <span style='font-size:12px;'>Control Interno</span>
                                    </td>
                                </tr>
                            </table>";
        break;
    case 2:
        $Titulo = "<br>Entrega de Equipo de C&oacute;mputo";
        $Imagen = "<img src='../../../images/logos/img1.jpg' width='130' height='60'/>
                        <hr>
                            <div style='text-align:right;'>".\core\core::mostrarFecha("small")."</div>
                        </hr>";

        $head = "<p>A QUIEN CORRESPONDA:</p>
                     <p>Por medio de la presente hago entrega del equipo de
                        c&oacute;mputo que me fue asignado por motivo laboral en la empresa
                        ORGANIZACI&Oacute;N TREVI&Ntilde;O S.A. DE C.V.
                     </p>";

        $Body = '<table style="margin-top:20px;font-size:14px;border:1px solid #ccc;CELLSPACING=0px;" align="center">'."
                         <tr>
                            <td colspan='2' style='background:#c1c1c1;font-weight:bold;text-align:center'>
                                DATOS DEL USUARIO
                            </td>
                        </tr>
                        <tr><td>NOMBRE</td><td width='400'><strong>".$data[0]."</strong></td></tr>
                        <tr><td>DEPARTAMENTO</td><td width='400'><strong>".$data[18]."</strong></td></tr>
                        <tr><td>PUESTO</td><td width='400'><strong>".$data[1]."</strong></td></tr>
                        <tr><td colspan='2'>&nbsp;</td></tr>
                        <tr>
                            <td colspan='2' style='background:#c1c1c1;font-weight:bold;text-align:center'>
                                DATOS DEL EQUIPO
                            </td>
                        </tr>
                        <tr>
                            <td width='270'>
                                <br>FECHA DE ASIGNACI&Oacute;N
                            </td>
                            <td width='270'>
                                <br><strong>".$Equipos->getFormatFecha($data[3],2)."</strong>
                            </td>
                        </tr>
                        <tr>
                            <td width='270'>
                                FECHA DE ENTREGA
                            </td>
                            <td width='270'>
                                <strong>".$Equipos->getFormatFecha($data[19],2)."</strong>
                            </td>
                        </tr>
                        <tr><td>EQUIPO</td><td><strong>".$data[5]."</strong></td></tr>
                        <tr><td>MODELO</td><td><strong>".$data[7]."</strong></td></tr>
                        <tr><td>MARCA</td><td><strong>".$data[8]."</strong></td></tr>
                        <tr><td>CARACTER&Iacute;STICAS</td><td><strong>".$data[6]."</strong></td></tr>
                        <tr><td>C&Oacute;DIGO</td><td><strong>".$Equipos->getFormatFolio($data[10],5)."</strong></td></tr>
                        <tr><td>SERIE</td><td><strong>".$data[11]."</strong></td></tr>
                        <tr><td>SERIE EQUIPO</td><td><strong>".$data[12]."</strong></td></tr>
		
                        <tr><td>USUARIO</td><td><strong>".$data[16]."</strong></td></tr>
                        <tr><td>CONTRASE&Ntilde;A</td><td><strong>".$data[17]."</strong></td></tr>
                            <tr>
                                <td colspan='2'>&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2' style='background:#c1c1c1;font-weight:bold;text-align:center'>&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2'>MOTIVO DE ENTREGA:</td>
                            </tr>
                            <tr>
                            <td colspan='2'>
                                    <strong>".$data[22]."</strong>
                                </td>
</tr>
                            <tr><td colspan='2'>&nbsp;</td></tr><tr><td colspan='2'>CONDICIONES ENTREGA:</td></tr><tr><td colspan='2'><strong>".$data[23]."</strong></td></tr></table>";

        $footer = "<p style='margin-top:30px;'>
                            Tambi&eacute;n hago entrega de contrase&ntilde;a para acceso a la misma y que la
                            informaci&oacute;n que se encuentra en ella ya es responsabilidad de la empresa antes mencionada.
                        </p>";

        $pageFooter = '<table align="center" style="text-align:center;margin-top:10px;">
            <tr><td style="width:210px;height:70px;text-align:center;"></td><td style="width:90px;"></td><td style="width:210px;"></td></tr>
            <tr><td style="width:210px;border-top:1px solid #000;">Firma y Nombre<br><strong>Entrega</strong></td><td style="width:90px;"></td><td style="width:210px;border-top:1px solid #000;">Firma y Nombre<br><strong>Recibe</strong></td></tr>
	    </table>';

        break;
    case 3:
        $Titulo = "<br>Envió Equipo de C&oacute;mputo a CEDIS";
        $Imagen = "<img src='../../../images/logos/img1.jpg' width='130' height='60'/><hr><div style='text-align:right;'>".\core\core::mostrarFecha("small")."</div>";
        $head  = "<p>A QUIEN CORRESPONDA:</p><p>Por medio de la presenta el Departamento de Sistemas hace entrega de los siguientes equipos, son equipos obsoletos y da&ntilde;ados, que fueron de uso interno:</p>";
        $Body = '<table style="margin-top:30px;CELLSPACING=0px;font-size:14px;border:1px solid #ccc;" align="center">'."
            
            <tr>
            <td colspan='2' style='background:#c1c1c1;font-weight:bold;text-align:center'>
            DATOS DEL USUARIO
            </td>
            </tr>
            <tr><td>NOMBRE</td><td width='400'><strong>".$data[0]."</strong></td></tr>
            <tr><td>DEPARTAMENTO</td><td width='400'><strong>".$data[18]."</strong></td></tr>
            <tr><td>PUESTO</td><td width='400'><strong>".$data[1]."</strong></td></tr>
            <tr><td colspan='2'>&nbsp;</td></tr>
            
            <tr><td colspan='2' style='background:#c1c1c1;font-weight:bold;text-align:center'>DATOS DEL EQUIPO</td></tr>
            <tr><td width='270'><br>FECHA DE ASIGNACI&Oacute;N</td><td width='270'><br><strong>".$Equipos->getFormatFecha($data[3],2)."</strong></td></tr>
	        <tr>
                <td width='270'>
                    FECHA DE ENTREGA
                </td>
                <td width='270'>
                <strong>".$Equipos->getFormatFecha($data[19],2)."</strong>
                </td>
             </tr>
            <tr><td>EQUIPO</td><td><strong>".$data[5]."</strong></td></tr>
            <tr><td>MODELO</td><td><strong>".$data[7]."</strong></td></tr>
            <tr><td>MARCA</td><td><strong>".$data[8]."</strong></td></tr>
            <tr><td>CARACTERISTICAS</td><td><strong>".$data[6]."</strong></td></tr>
            <tr><td>CODIGO</td><td><strong>".$Equipos->getFormatFolio($data[10],5)."</strong></td></tr>
            <tr><td>SERIE</td><td><strong>".$data[11]."</strong></td></tr>
            <tr><td>SERIE EQUIPO</td><td><strong>".$data[12]."</strong></td></tr>
            <tr><td>USUARIO</td><td><strong>".$data[16]."</strong></td></tr>
            <tr><td>CONTRASE&Ntilde;A</td><td><strong>".$data[17]."</strong></td></tr>
<tr>
                                <td colspan='2'>&nbsp;
                                </td>
                            </tr>

		<tr>
                                <td colspan='2' style='background:#c1c1c1;font-weight:bold;text-align:center'>&nbsp;
                                </td>
                            </tr>
<tr>
				<td><br>MOTIVO ASIGNACI&Oacute;N</td>
				<td><br><strong>".$data[21]."</strong></td>
			    </tr>
			    <tr>
				<td>ACCESORIOS</td>
				<td><strong>".$data[9]."</strong></td>
			    </tr><tr><td>MOTIVO DE ENVIO:</td><td><strong>".$data[22]."</strong></td></tr><tr><td colspan='2'>&nbsp;</td></tr><tr><td colspan='2'>CONDICIONES DE ENVIO:</td></tr><tr><td colspan='2'>&nbsp;</td></tr><tr><td colspan='2'><strong>".$data[23]."</strong></td></tr></table>";
        $footer = "<p style='margin-top:20px'>Para que sean regresados a CEDIS para su venta.</p>";
        $pageFooter = '<table align="center" style="text-align:center;margin-top:5px;">
            <tr><td style="width:210px;height:70px;text-align:center;"></td><td style="width:90px;"></td><td style="width:210px;"></td></tr>
            <tr><td style="width:210px;border-top:1px solid #000;">Firma y Nombre<br><strong>Entrega</strong></td><td style="width:90px;"></td><td style="width:210px;border-top:1px solid #000;">Firma y Nombre<br><strong>Recibe</strong></td></tr>
	    </table>';
        break;

}
?>
    <style type="text/css">
        <!--
        table.page_header {width: 100%; border: none; border-bottom: solid 2px #000; }
        table.page_footer {width: 100%; border: none; border-top: solid 2px #000; color:#3d3d3d;font-size:13px;}
        h1 {color: #000033}
        h2 {color: #000055}
        h3 {color: #000077}
        .fch_long{
            text-align: right;
            font-size: 12px;
            font-family: Arial;
            padding: 5px;
            color:#3d3d3d;
        }
        div.standard
        {
            padding-left: 5mm;
        }
        .titulo1{
            text-align: center;
            font-weight: bold;
        }

        p{
            text-align: justify;
        }
        .text1{
            text-align: justify;
            font-size: 14px;
        }
        -->
    </style>
    <page backtop="14mm" backbottom="10mm" backleft="10mm" backright="10mm" style="font-size: 12pt">
        <page_header>
            <table class="page_header">
                <tr>
                    <td style="width: 100%; text-align: left">
                        <img src="../../../../site_design/img/logos/pexpress_01.jpg" width="100" />
                    </td>
                </tr>
            </table>
            <div class="fch_long">
                <?php
                if($TpoArchivo == 1){
                    echo \core\core::mostrarFecha('small');
                }else{
                    echo \core\core::mostrarFecha('small');
                }
                ?>
            </div>
        </page_header>
        <h2 class="titulo1"><?=$Titulo?></h2>
        <?php
        echo $head;
        echo $Body;
        echo $footer;
        echo $pageFooter;
        ?>
        <page_footer>
            <table class="page_footer">
                <tr>
                    <td style="width: 50%;">Organizaci&oacute;n Trevi&ntilde;o S.A. de C.V.</td>
                    <td style="width: 50%; text-align: right">
                        Pagina [[page_cu]]/[[page_nb]]
                    </td>
                </tr>
            </table>
            <br />
        </page_footer>
        <bookmark title="Summary" level="0" ></bookmark>
    </page>
<?php
if($TpoArchivo == 1){
    ?>
    <page pageset="old">
        <?php
        echo $Body2;
        ?>
    </page>
    <?php
}
?>
<?php
$content = ob_get_clean();
$pdf = new HTML2PDF('P','Letter','fr','UTF-8');
$pdf->writeHTML($content);
$pdf->pdf->IncludeJS('print(TRUE)');
$pdf->output('Reporte'.$FechaActual.'.pdf');
?>