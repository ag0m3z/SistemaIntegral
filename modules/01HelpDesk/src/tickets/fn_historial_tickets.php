<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 08/02/2017
 * Time: 11:21 AM
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
include "../../../../core/seguridad.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);

//validar session activa
$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);

$AnioActual = date("Y");

if($_POST['noarea'] != 0 || $_POST['noarea'] != "-- --" ){

    $AndArea = " AND R.NoArea = '".$_POST['noarea']."'" ;
}else{$AndArea = "";}

$n= 1;
$pie="";
$seguridad->_query = "SELECT R.Folio,R.DescripcionReporte,R.Estatus,C.Descripcion,R.Fecha,R.Anio,R.NoDepartamento  FROM BSHReportes AS R
                           LEFT JOIN BSHCatalogoEstatus AS C
                           ON R.Estatus = C.NoEstatus
                           WHERe R.NoSucursal = '".$_POST['nosuc']."' AND R.NoDepartamento = '".$_SESSION['data_departamento']['NoDepartamento']." '".$AndArea."
                           ORDER BY R.Estatus,R.Fecha DESC LIMIT 25";

$seguridad->get_result_query();
$Tickets = $seguridad->_rows ;


echo '<div class="panel panel-default scroll-auto" style="height:19em;margin-top: 7px;">
    <div class="panel-heading" style="padding: 3px;"><span class="fa fa-eye"></span> Historial de Reportes</div>
    <table class="table table-hover table-condensed" style="font-size: 10.7px;">
        <thead>
        <th style="text-align: center;">#</th><th>Fecha</th><th>Descripci&oacute;n</th><th style="text-align: right;">Estatus</th>
        </thead>
        <tbody>';
$pie = '</tbody>
    </table>
</div>
</div>';

for($i = 0;$i < count($Tickets);$i++ ){

    switch($Tickets[$i]['Estatus']){
        case 1:
            $Estatus = "<span class='label label-info' style='width: 120px;font-size:9.5px;'>".$Tickets[$i]['Descripcion']."&nbsp;&nbsp;</span>";
            break;
        case 2:
            $Estatus = "<span class='label label-success' style='width:120px;font-size:9px;'>Progreso</span>";
            break;
        case 4:
            $Estatus = "<span class='label label-danger' style='width:120px;font-size:9.5px;'>".$Tickets[$i]['Descripcion']."&nbsp;</span>";
            break;
    }

    echo "<tr>
            <td width='5px' style='vertical-align: middle;'>
                <strong>
                    <a href='#' onclick='fnsdMenu(11,\"fl=".$Tickets[$i]['Folio']."&dpto=".$Tickets[$i]['NoDepartamento']."&anio=".$Tickets[$i]['Anio']."  \")'>".$seguridad->getFormatFolio($Tickets[$i]['Folio'],4)."</a>
                </strong>
            </td>
            <td style='vertical-align: middle;'>".$seguridad->getFormatFecha($Tickets[$i]['Fecha'],2)."</td>
            <td width='79.5%;'>".$Tickets[$i]['DescripcionReporte']."</td><td style='vertical-align: middle;'><span class='pull-right'>".$Estatus."</span></td>
        </tr>";
}


echo $pie;