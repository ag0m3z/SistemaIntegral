<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 15/09/2017
 * Time: 03:46 PM
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

$NoUsuario = $_SESSION['data_login']['NoUsuario'];

switch ($_POST['opc']){
    case 1:
        if($_SESSION['data_login']['NoPuesto'] == 1){
            //Puesto Supervisor
            $Where2 = " WHERE  date(a.FechaRegistro) = '".date("Y-m-d")."'" ;
        }else{
            $Where2 = " WHERE a.NoUsuarioRegistro =  $NoUsuario AND date(a.FechaRegistro) = '".date("Y-m-d")."'" ;
        }

        break;
    case 2:
        if($_SESSION['data_login']['NoPuesto'] == 1){
            //Puesto Supervisor
            $Where2 = "  ";
        }else{
            $Where2 = " WHERE a.NoUsuarioRegistro =  $NoUsuario ";
        }

        break;
}

$connect->_query = "
SELECT 
a.FolioCotizacion,lpad(a.FolioCotizacion,6,'0'),a.NombreCliente,
a.MedioContacto,b.Descripcion,a.TipoCotizacion,c.Descripcion,a.NoCategoria,d.Descripcion,a.NoTipo,a.MontoSolicitado,
a.MontoAutorizado,a.CotizacionEmpeno,a.CotizacionCompra,a.Descripcion,a.NoEstatus,f.Descripcion,f.Texto1,
a.NoUsuarioSolicitante,g.NombreDePila,a.NoUsuarioRegistro,h.NombreDePila,a.FechaInicial,a.FechaVigencia,a.FechaRegistro,a.FechaUM,ifnull(a.MontoPrestamo,0),a.BoletaPrestamo ,a.Serie 
FROM BGECotizador as a 
LEFT JOIN BGECatalogoGeneral as b 
ON a.MedioContacto = b.OpcCatalogo AND b.CodCatalogo = 30 
LEFT JOIN BGECatalogoGeneral as c 
ON a.TipoCotizacion = c.OpcCatalogo AND c.CodCatalogo = 31 
LEFT JOIN BGECatalogoGeneral as d 
ON a.NoCategoria = d.OpcCatalogo AND d.CodCatalogo = 9 
LEFT JOIN BGECatalogoGeneral as e 
ON a.NoTipo = e.OpcCatalogo AND e.CodCatalogo = 5 AND e.Numero2 = a.NoCategoria 
LEFT JOIN BGECatalogoGeneral as f 
ON a.NoEstatus = f.OpcCatalogo AND f.CodCatalogo = 29 
LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as g 
ON a.NoUsuarioSolicitante = g.NoUsuario 
LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as h 
ON a.NoUsuarioRegistro = h.NoUsuario 
$Where2
ORDER BY a.FechaRegistro DESC
";
$connect->get_result_query();

$Total = count($connect->_rows);

if($Total > 0){
    for($i=0;$i<count($connect->_rows);$i++){
        $data[] = array(
            "id"=>"<a href='#' onclick='fn04EditarCotizacion(1,\"".$connect->_rows[$i][0]."\",\"".$connect->_rows[$i][28]."\")'><span class='text text-primary'>".$connect->_rows[$i][1]."</span></a>",
            "cliente"=>$connect->_rows[$i][2],
            "descripcion"=>$connect->_rows[$i][14],
            "montoautorizado"=>$connect->_rows[$i][11],
            "montoprestamo"=>$connect->_rows[$i][26],
            "boletaprestamo"=>$connect->_rows[$i][27],
            "estatus"=>$connect->_rows[$i][17],
            "usuarioa"=>$connect->_rows[$i][21],
            "fechainicial"=>$connect->getFormatFecha($connect->_rows[$i][22],'dd/mm/yyyy'),
            "fechavigencia"=>$connect->getFormatFecha($connect->_rows[$i][23],'dd/mm/yyyy'),
            "fecharegistro"=>$connect->getFormatFecha($connect->_rows[$i][24],'dd/mm/yyyy'),
            "TotalRow"=>$Total
        );
    }
}else{
    $data[] = array( "TotalRow"=>$Total);
}
echo json_encode($data);