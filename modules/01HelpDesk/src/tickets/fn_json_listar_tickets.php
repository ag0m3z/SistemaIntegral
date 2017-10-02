<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 01/02/2017
 * Time: 04:22 PM
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
include "../../../../core/model_tickets.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$tickets = new \core\model_tickets($_SESSION['data_login']['BDDatos']);

// Declaracion de variables
$FechaActual = date("Ymd");
$uNoDepartamento = $_SESSION['data_departamento']['NoDepartamento'];
$uNoUsuario = $_SESSION['data_login']['NoUsuario'];

if($_REQUEST['opt'] == 5 ){ $_REQUEST['opt'] = 0 ;}

switch($_REQUEST['opt']){
    case 0:
        $Label = "Tickets Pendientes";
        if($_SESSION['data_departamento']['AsignarReportes'] == 'NO'){
            $ConsSql = "SELECT R.Folio,R.Fecha, S.Descripcion as NombreDepartamento,R.DescripcionReporte, U.NombreDePila as UsuarioAsignado,R.FechaPromesa, E.Descripcion,R.PrioridadTicket,R.Reporte,R.Anio,R.NoDepartamento,d.NombreDePila,x.Descripcion as NameDepto
        FROM BSHReportes AS R
        LEFT JOIN BGECatalogoDepartamentos AS S
        ON R.NoSucursal = S.NoDepartamento
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON R.NoUsuarioAsignado = U.NoUsuario
        JOIN BSHCatalogoEstatus AS E
        ON R.Estatus = E.NoEstatus
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS d
        ON R.NoUsuarioRecibe = d.NoUsuario 
         LEFT JOIN BGECatalogoDepartamentos AS x
        ON R.NoDepartamento = x.NoDepartamento
        WHERE R.NoSucursal = '".$_SESSION['data_departamento']['NoDepartamento']."' AND R.Estatus <= 3 ORDER BY R.Fecha DESC, R.Folio DESC";
        }else{
            $ConsSql = "SELECT R.Folio,R.Fecha, S.Descripcion as NombreDepartamento,R.DescripcionReporte, U.NombreDePila as UsuarioAsignado,R.FechaPromesa, E.Descripcion,R.PrioridadTicket,R.Reporte,R.Anio,R.NoDepartamento,d.NombreDePila,x.Descripcion as NameDepto
        FROM BSHReportes AS R
        LEFT JOIN BGECatalogoDepartamentos AS S
        ON R.NoSucursal = S.NoDepartamento
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON R.NoUsuarioAsignado = U.NoUsuario AND  U.NoDepartamento = R.NoDepartamento
        LEFT JOIN BSHCatalogoEstatus AS E
        ON R.Estatus = E.NoEstatus
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS d
        ON R.NoUsuarioRecibe = d.NoUsuario 
        LEFT JOIN BGECatalogoDepartamentos AS x
        ON R.NoDepartamento = x.NoDepartamento 
        WHERE R.NoDepartamento = '$uNoDepartamento' AND R.Estatus <= 3 ORDER BY R.Fecha DESC, R.Folio DESC";
        }
        break;
    case 1:
        $Label = "Mis Tickets Pendientes";
        $ConsSql = "SELECT R.Folio,R.Fecha, S.Descripcion as NombreDepartamento,R.DescripcionReporte, U.NombreDePila as UsuarioAsignado,R.FechaPromesa, E.Descripcion,R.PrioridadTicket,R.Reporte,R.Anio,R.NoDepartamento,d.NombreDePila,x.Descripcion as NameDepto
        FROM BSHReportes AS R
        JOIN BGECatalogoDepartamentos AS S
        ON R.NoSucursal = S.NoDepartamento
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON R.NoUsuarioAsignado = U.NoUsuario AND  U.NoDepartamento = R.NoDepartamento
        JOIN BSHCatalogoEstatus AS E
        ON R.Estatus = E.NoEstatus
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS d
        ON R.NoUsuarioRecibe = d.NoUsuario 
        LEFT JOIN BGECatalogoDepartamentos AS x
        ON R.NoDepartamento = x.NoDepartamento 
        WHERE R.NoDepartamento = $uNoDepartamento AND R.Estatus <= 3 AND R.NoUsuarioAsignado = $uNoUsuario ORDER BY R.Fecha DESC, R.Folio DESC";
        break;
    case 2:
        $Label = "Mis Tickets Cerrados";
        $ConsSql = "SELECT R.Folio,R.Fecha, S.Descripcion as NombreDepartamento,R.DescripcionReporte, U.NombreDePila as UsuarioAsignado,R.FechaPromesa, E.Descripcion,R.PrioridadTicket,R.Reporte,R.Anio,R.NoDepartamento,d.NombreDePila,x.Descripcion as NameDepto
        FROM BSHReportes AS R
        JOIN BGECatalogoDepartamentos AS S
        ON R.NoSucursal = S.NoDepartamento
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON R.NoUsuarioCierre = U.NoUsuario
        JOIN BSHCatalogoEstatus AS E
        ON R.Estatus = E.NoEstatus
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS d
        ON R.NoUsuarioRecibe = d.NoUsuario 
        LEFT JOIN BGECatalogoDepartamentos AS x
        ON R.NoDepartamento = x.NoDepartamento 
        WHERE R.NoDepartamento = $uNoDepartamento AND R.Estatus = 4 AND R.NoUsuarioAsignado = $uNoUsuario AND R.FechaCierre = $FechaActual ORDER BY R.Fecha DESC, R.Folio DESC";
        break;
    case 3:
        $Label = "Mis Tickets Cancelados";
        $ConsSql = "SELECT R.Folio,R.Fecha, S.Descripcion as NombreDepartamento,R.DescripcionReporte, U.NombreDePila as UsuarioAsignado,R.FechaPromesa, E.Descripcion,R.PrioridadTicket,R.Reporte,R.Anio,R.NoDepartamento,d.NombreDePila,x.Descripcion as NameDepto
        FROM BSHReportes AS R
        JOIN BGECatalogoDepartamentos AS S
        ON R.NoSucursal = S.NoDepartamento
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON R.NoUsuarioCierre = U.NoUsuario
        JOIN BSHCatalogoEstatus AS E
        ON R.Estatus = E.NoEstatus
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS d
        ON R.NoUsuarioRecibe = d.NoUsuario 
        LEFT JOIN BGECatalogoDepartamentos AS x
        ON R.NoDepartamento = x.NoDepartamento 
        WHERE R.NoDepartamento = '$uNoDepartamento' AND R.Estatus = 5 AND R.NoUsuarioAsignado = $uNoUsuario AND R.FechaCierre = $FechaActual ORDER BY R.Fecha DESC, R.Folio DESC";
        break;
    case 4:
        $Label = "Tickets Sin Asignar";
        $ConsSql = "SELECT R.Folio,R.Fecha, S.Descripcion as NombreDepartamento,R.DescripcionReporte, U.NombreDePila as UsuarioAsignado,R.FechaPromesa, E.Descripcion,R.PrioridadTicket,R.Reporte,R.Anio,R.NoDepartamento,d.NombreDePila,x.Descripcion as NameDepto
        FROM BSHReportes AS R
        JOIN BGECatalogoDepartamentos AS S
        ON R.NoSucursal = S.NoDepartamento
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON R.NoUsuarioAsignado = U.NoUsuario
        JOIN BSHCatalogoEstatus AS E
        ON R.Estatus = E.NoEstatus
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS d
        ON R.NoUsuarioRecibe = d.NoUsuario 
        LEFT JOIN BGECatalogoDepartamentos AS x
        ON R.NoDepartamento = x.NoDepartamento 
        WHERE R.NoDepartamento = '$uNoDepartamento' AND R.Estatus <=3 AND R.NoUsuarioAsignado = '0' ORDER BY R.Fecha DESC, R.Folio DESC";
        break;
    case 6:
        $Label = "Tickets Cerrados";
        $ConsSql = "SELECT R.Folio,R.Fecha, S.Descripcion as NombreDepartamento,R.DescripcionReporte, U.NombreDePila as UsuarioAsignado,R.FechaPromesa, E.Descripcion,R.PrioridadTicket,R.Reporte,R.Anio,R.NoDepartamento,d.NombreDePila,x.Descripcion as NameDepto
        FROM BSHReportes AS R
        JOIN BGECatalogoDepartamentos AS S
        ON R.NoSucursal = S.NoDepartamento
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON R.NoUsuarioCierre = U.NoUsuario
        JOIN BSHCatalogoEstatus AS E
        ON R.Estatus = E.NoEstatus
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS d
        ON R.NoUsuarioRecibe = d.NoUsuario 
        LEFT JOIN BGECatalogoDepartamentos AS x
        ON R.NoDepartamento = x.NoDepartamento 
        WHERE R.NoDepartamento = '$uNoDepartamento' AND R.Estatus = 4 AND R.FechaCierre = $FechaActual ORDER BY R.Fecha DESC, R.Folio DESC";
        break;
    case 7:
        $Label = "Tickets Cancelados";
        $ConsSql = "SELECT R.Folio,R.Fecha, S.Descripcion as NombreDepartamento,R.DescripcionReporte, U.NombreDePila as UsuarioAsignado,R.FechaPromesa, E.Descripcion,R.PrioridadTicket,R.Reporte,R.Anio,R.NoDepartamento,d.NombreDePila,x.Descripcion as NameDepto
        FROM BSHReportes AS R
        JOIN BGECatalogoDepartamentos AS S
        ON R.NoSucursal = S.NoDepartamento
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON R.NoUsuarioCierre = U.NoUsuario
        JOIN BSHCatalogoEstatus AS E
        ON R.Estatus = E.NoEstatus
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS d
        ON R.NoUsuarioRecibe = d.NoUsuario 
        LEFT JOIN BGECatalogoDepartamentos AS x
        ON R.NoDepartamento = x.NoDepartamento 
        WHERE R.NoDepartamento = '$uNoDepartamento' AND R.Estatus = 5 AND R.FechaCierre = $FechaActual ORDER BY R.Fecha DESC, R.Folio DESC";
        break;
    case 8:
        //Tickets con Prioridad Alta
        $Label = "Tickets Prioridad Alta";
        $ConsSql = "SELECT R.Folio,R.Fecha, S.Descripcion as NombreDepartamento,R.DescripcionReporte, U.NombreDePila as UsuarioAsignado,R.FechaPromesa, E.Descripcion,R.PrioridadTicket,R.Reporte,R.Anio,R.NoDepartamento,d.NombreDePila,x.Descripcion as NameDepto
        FROM BSHReportes AS R
        JOIN BGECatalogoDepartamentos AS S
        ON R.NoSucursal = S.NoDepartamento
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON R.NoUsuarioAsignado = U.NoUsuario
        JOIN BSHCatalogoEstatus AS E
        ON R.Estatus = E.NoEstatus
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS d
        ON R.NoUsuarioRecibe = d.NoUsuario 
        LEFT JOIN BGECatalogoDepartamentos AS x
        ON R.NoDepartamento = x.NoDepartamento 
        WHERE R.NoDepartamento = '$uNoDepartamento' AND R.Estatus <= 3 AND PrioridadTicket = 3 ORDER BY R.Fecha DESC, R.Folio DESC";
        break;
    case 9:
        //Tickets con Prioridad Media
        $Label = "Tickets Prioridad Media";
        $ConsSql = "SELECT R.Folio,R.Fecha, S.Descripcion as NombreDepartamento,R.DescripcionReporte, U.NombreDePila as UsuarioAsignado,R.FechaPromesa, E.Descripcion,R.PrioridadTicket,R.Reporte,R.Anio,R.NoDepartamento,d.NombreDePila,x.Descripcion as NameDepto
        FROM BSHReportes AS R
        JOIN BGECatalogoDepartamentos AS S
        ON R.NoSucursal = S.NoDepartamento
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON R.NoUsuarioAsignado = U.NoUsuario
        JOIN BSHCatalogoEstatus AS E
        ON R.Estatus = E.NoEstatus
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS d
        ON R.NoUsuarioRecibe = d.NoUsuario 
        LEFT JOIN BGECatalogoDepartamentos AS x
        ON R.NoDepartamento = x.NoDepartamento 
        WHERE R.NoDepartamento = '$uNoDepartamento' AND R.Estatus <= 3 AND PrioridadTicket = 2 ORDER BY R.Fecha DESC, R.Folio DESC";
        break;
    case 10:
        //Tickets con Prioridad Baja
        $Label = "Tickets Prioridad Baja";
        $ConsSql = "SELECT R.Folio,R.Fecha, S.Descripcion as NombreDepartamento,R.DescripcionReporte, U.NombreDePila as UsuarioAsignado,R.FechaPromesa, E.Descripcion,R.PrioridadTicket,R.Reporte,R.Anio,R.NoDepartamento,d.NombreDePila,x.Descripcion as NameDepto
        FROM BSHReportes AS R
        JOIN BGECatalogoDepartamentos AS S
        ON R.NoSucursal = S.NoDepartamento
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON R.NoUsuarioAsignado = U.NoUsuario
        JOIN BSHCatalogoEstatus AS E
        ON R.Estatus = E.NoEstatus
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS d
        ON R.NoUsuarioRecibe = d.NoUsuario 
        LEFT JOIN BGECatalogoDepartamentos AS x
        ON R.NoDepartamento = x.NoDepartamento 
        WHERE R.NoDepartamento = '$uNoDepartamento' AND R.Estatus <= 3 AND PrioridadTicket = 1 ORDER BY R.Fecha DESC, R.Folio DESC";
        break;
    case 11:
        $Label = "Tickets Asignados a Seguridad";
        $ConsSql = "SELECT R.Folio,R.Fecha, S.Descripcion as NombreDepartamento,R.DescripcionReporte, U.NombreDePila as UsuarioAsignado,R.FechaPromesa, E.Descripcion,R.PrioridadTicket,R.Reporte,R.Anio,R.NoDepartamento,d.NombreDePila,x.Descripcion as NameDepto
        FROM BSHReportes AS R
        JOIN BGECatalogoDepartamentos AS S
        ON R.NoSucursal = S.NoDepartamento
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON R.NoUsuarioAsignado = U.NoUsuario
        JOIN BSHCatalogoEstatus AS E
        ON R.Estatus = E.NoEstatus
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS d
        ON R.NoUsuarioRecibe = d.NoUsuario 
        LEFT JOIN BGECatalogoDepartamentos AS x
        ON R.NoDepartamento = x.NoDepartamento 
        WHERE R.NoSucursal = '".$_SESSION['NoDepartamento']."' AND R.NoDepartamento = '1903'  AND R.Estatus <= 3 ORDER BY R.Fecha DESC, R.Folio DESC";
        break;
    case 12:
        $Label = "Tickets Asignados a Sistemas";
        $ConsSql = "SELECT R.Folio,R.Fecha, S.Descripcion as NombreDepartamento,R.DescripcionReporte, U.NombreDePila as UsuarioAsignado,R.FechaPromesa, E.Descripcion,R.PrioridadTicket,R.Reporte,R.Anio,R.NoDepartamento,d.NombreDePila,x.Descripcion as NameDepto
        FROM BSHReportes AS R
        JOIN BGECatalogoDepartamentos AS S
        ON R.NoSucursal = S.NoDepartamento
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON R.NoUsuarioAsignado = U.NoUsuario
        JOIN BSHCatalogoEstatus AS E
        ON R.Estatus = E.NoEstatus
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS d
        ON R.NoUsuarioRecibe = d.NoUsuario
        LEFT JOIN BGECatalogoDepartamentos AS x
        ON R.NoDepartamento = x.NoDepartamento 
        WHERE R.NoSucursal = '".$_SESSION['data_departamento']['NoDepartamento']."' AND R.NoDepartamento = '0109'  AND R.Estatus <= 3 ORDER BY R.Fecha DESC, R.Folio DESC";
        break;
    case 13:
        $Label = "Tickets Asignados a ".$_REQUEST['nameDpto'];
        $ConsSql = "SELECT R.Folio,R.Fecha, S.Descripcion as NombreDepartamento,R.DescripcionReporte, U.NombreDePila as UsuarioAsignado,R.FechaPromesa, E.Descripcion,R.PrioridadTicket,R.Reporte,R.Anio,R.NoDepartamento,d.NombreDePila,x.Descripcion as NameDepto
        FROM BSHReportes AS R
        JOIN BGECatalogoDepartamentos AS S
        ON R.NoSucursal = S.NoDepartamento
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
        ON R.NoUsuarioAsignado = U.NoUsuario
        JOIN BSHCatalogoEstatus AS E
        ON R.Estatus = E.NoEstatus
        left JOIN SINTEGRALGNL.BGECatalogoUsuarios AS d
        ON R.NoUsuarioRecibe = d.NoUsuario 
        LEFT JOIN BGECatalogoDepartamentos AS x
        ON R.NoDepartamento = x.NoDepartamento 
        WHERE R.NoSucursal = '".$_SESSION['data_departamento']['NoDepartamento']."' AND R.NoDepartamento = ".$_POST['dpto']."  AND R.Estatus <= 3 ORDER BY R.Fecha DESC, R.Folio DESC";
        break;

}

//Todos los Reportes sin Resolver los
$tickets->_query = $ConsSql;

$tickets->get_result_query();

$TotalReportes = count($tickets->_rows);
$data_ticket = $tickets->_rows;
$NombreDepartamento = $_REQUEST['nameDpto'];
if(empty($NombreDepartamento)){$NombreDepartamento = $_SESSION['data_departamento']['NombreDepartamento'];}


if($TotalReportes > 0 ){

    for($i=0;$i < $TotalReportes; $i++){

        $alert = $tickets->getAlertFechaPromesa($FechaActual,$data_ticket[$i]['FechaPromesa']);

        if($alert < 1 or $alert == 0){

            $FechaPromesa = "<span style='color:red;'>".$tickets->getFormatFecha($data_ticket[$i]['FechaPromesa'],2)."</span>";
            $NoTickets =  "<span style='color:red;'>".$tickets->getFormatFolio($data_ticket[$i]['Folio'],4)."</span>";

        }elseif($alert >= 1 && $alert <= 2){

            $FechaPromesa = "<span style='color:#F0AD4E;'>".$tickets->getFormatFecha($data_ticket[$i]['FechaPromesa'],2)."</span>";
            $NoTickets =  "<span style='color:#F0AD4E;'>".$tickets->getFormatFolio($data_ticket[$i]['Folio'],4)."</span>";

        }else{

            $FechaPromesa =$tickets->getFormatFecha($data_ticket[$i]['FechaPromesa'],2);
            $NoTickets =  $tickets->getFormatFolio($data_ticket[$i]['Folio'],4);

        }

        $UrlTicket = "MenuSd(11,'ref=".$data_ticket[$i]['Folio']."')";

        $rowData[] = array(
            "hStats"=>$tickets->getStatAdjuntosTicket($data_ticket[$i]['Folio'],$data_ticket[$i]['NoDepartamento'],$data_ticket[$i]['Anio'],$data_ticket[$i]['Folio']),
            "hTicket"=>"<a href='#' onclick='fnsdMenu(11,\"fl=".$data_ticket[$i]['Folio']."&dpto=".$data_ticket[$i]['NoDepartamento']."&anio=".$data_ticket[$i]['Anio']."  \")'><span class='text text-primary'>".$NoTickets."</span></a>",
            "hFecha"=>$tickets->getFormatFecha($data_ticket[$i]['Fecha'],2),
            "hSucursal"=>$data_ticket[$i]['NombreDepartamento'],
            "hReporte"=>$data_ticket[$i]['DescripcionReporte'],
            "hRegistros"=>$data_ticket[$i]['NombreDePila'],
            "hAsignado"=>$data_ticket[$i]['UsuarioAsignado'],
            "hPromesa"=>$FechaPromesa,
            "hEstatus"=>$data_ticket[$i]['Descripcion'],
            "hNivel"=>$tickets->getPrioridadTicket(2,$data_ticket[$i]['PrioridadTicket'])
        );

    }
}

//json_encode($rowData);
?>
<script language="JavaScript">

    $('#txt_nombre_departamento').text("<?=$NombreDepartamento?>");
    $("#num").text("<?=$TotalReportes?>");
    $("#lblTipoFiltro").text("<?=$Label?>");

    //funcion para Formatear las Celdas
    function formatter(row, cell, value, columnDef, dataContext) {
        return value;
    }
    var grid;

    //Declaracion de Columnas
    var columns = [
        {id:"hStats",name:"",field:"hStats",width:40,cssClass: "cell-title", formatter: formatter},
        {id: "hTicket", name: "Ticket", field: "hTicket", width: 70,sortable:true ,cssClass: "text-center  btn-link", formatter: formatter},
        {id: "hFecha", name: "Fecha", field: "hFecha",width: 80,sortable:true},
        {id: "hSucursal", name: "Sucursal", field: "hSucursal", width: 130,minWidth:40,sortable:true},
        {id: "hReporte", name: "Reporte", field: "hReporte", minWidth: 310},
        {id: "hRegistros", name: "Registro", field: "hRegistros",sortable:true, minWidth: 125,formatter:formatter},
        {id:"hAsignado",name:"Asignado",field:"hAsignado",sortable:true,minWidth:125,formatter:formatter},
        {id:"hPromesa",name:"Promesa",field:"hPromesa",width:80,formatter:formatter},
        {id:"hEstatus",name:"Estatus",field:"hEstatus",minWidth:50,sortable:true},
        {id:"hNivel",name:"Prioridad",field:"hNivel",sortable:true,minWidth:65,cssClass: "text-center",formatter:formatter}
    ];

    //Opciones para Mandar a la Tabla
    var options = {
        enableCellNavigation: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        editable: true,
        enableAddRow: true
    };
    data =<?=json_encode($rowData)?>;

    // Cargar el Grid con los Datos Devueltos de JSON
    grid = new Slick.Grid("#myGrid", data, columns, options);
    //Metodo para Ordenar la tabla
    grid.onSort.subscribe(function (e, args) {
        var cols = args.sortCols;
        data.sort(function (dataRow1, dataRow2) {
            for (var i = 0, l = cols.length; i < l; i++) {
                var field = cols[i].sortCol.field;
                var sign = cols[i].sortAsc ? 1 : -1;
                var value1 = dataRow1[field], value2 = dataRow2[field];
                var result = (value1 == value2 ? 0 : (value1 > value2 ? 1 : -1)) * sign;
                if (result != 0) {
                    return result;
                }
            }
            return 0;
        });
        grid.invalidate();
        grid.render();
    });



</script>

