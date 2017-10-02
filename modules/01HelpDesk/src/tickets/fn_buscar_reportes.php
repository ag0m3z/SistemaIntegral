<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/02/2017
 * Time: 12:57 PM
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

$Tickets = new \core\model_tickets($_SESSION['data_login']['BDDatos']);
$Tickets->valida_session_id($_SESSION['data_login']['NoUsuario']);

//Datos Recibidos por Jscript
$NoSucursal = $_POST['suc'];
$NoDepartamento = $_POST['nodepartamento'];
$NoEstado = $_POST['est'];
$NoUsuario = $_POST['user'];
$NoTipoAtencion = $_POST['seg'];
$FechaInicial = $Tickets->getFormatFecha($_POST['f01'],1);
$FechaFinal = $Tickets->getFormatFecha($_POST['f02'],1);
$MedioContacto = $_POST['cont'];
$NoArea = $_POST['are'];
$NoCategoria = $_POST['cat'];
$FechaACtual = date("Ymd");

//Condicion de Estatus para Determinar NoUsuarioRecibe,NoUsuarioAsignado o NoUsuarioCierre
if($NoEstado <= 3 or $NoEstado == 98){
    $CampoUsuario = 'c.NoUsuarioAsignado';

}else{
    $CampoUsuario = 'c.NoUsuarioCierre';
}

if($NoTipoAtencion == 0){
    $query = "SELECT U.NombreDePila,c.Folio,E.Descripcion,S.Descripcion, c.DescripcionReporte,b.TipoAtencion, c.Fecha,c.HoraInicioReporte,c.FechaCierre,
                c.HoraCierre,c.FechaPromesa,c.SolucionCierre,c.Anio,c.NoDepartamento,c.PrioridadTicket,c.NoArea,g.Descripcion,c.Categoria,h.descripcion,f.Descripcion,x.Descripcion as NombreDepartamentoTecnico
                FROM (SELECT Anio,Folio,NoDepartamento,Max(FolioSeguimiento) UltimoSeguimiento,TipoAtencion
                        FROM BSHSeguimientoReportes GROUP BY Anio,Folio,NoDepartamento) a
                INNER JOIN BSHSeguimientoReportes AS b
                    ON a.Anio = b.Anio AND a.Folio = b.Folio AND a.NoDepartamento = b.NoDepartamento AND a.UltimoSeguimiento = b.FolioSeguimiento
                INNER JOIN BSHReportes AS c
                    ON a.Anio = c.Anio AND a.Folio = c.Folio AND a.NoDepartamento = c.NoDepartamento
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
                    ON ".$CampoUsuario." = U.NoUsuario AND c.NoDepartamento = U.NoDepartamento
                JOIN BGECatalogoDepartamentos AS S
                    ON c.NoSucursal = S.NoDepartamento
                JOIN BSHCatalogoEstatus AS E
                    ON c.Estatus = E.NoEstatus 
                    LEFT JOIN BSHCatalogoCatalogos as f
                 ON b.TipoAtencion = f.idDescripcion AND idCatalogo =2 
				LEFT JOIN BSHCatalogoAreas as g 
                 ON c.NoArea = g.NoArea AND c.NoDepartamento = g.NoDepartamento 
                 LEFT JOIN BSHCatalogoCategoria as h 
                 ON c.Categoria = h.nocategoria AND c.NoDepartamento = h.NoDepartamento 
                 LEFT JOIN BGECatalogoDepartamentos as x 
                 ON a.NoDepartamento = x.NoDepartamento
                ";
}else{
    /*$query = "SELECT U.NombreDePila,c.Folio,E.Descripcion,S.Descripcion, c.DescripcionReporte,b.TipoAtencion, c.Fecha,c.HoraInicioReporte,c.FechaCierre,
            c.HoraCierre,c.FechaPromesa,c.SolucionCierre,c.Anio,c.NoDepartamento,c.PrioridadTicket
            FROM (SELECT Anio,Folio,NoDepartamento,FechaSeguimiento,Max(FolioSeguimiento) UltimoSeguimiento,TipoAtencion
                    FROM BSHSeguimientoReportes WHERE FechaSeguimiento>=$FechaInicial AND FechaSeguimiento<=$FechaFinal GROUP BY Anio,Folio,NoDepartamento) a
            INNER JOIN BSHSeguimientoReportes AS b
                ON a.Anio = b.Anio AND a.Folio = b.Folio AND a.NoDepartamento = b.NoDepartamento AND a.UltimoSeguimiento = b.FolioSeguimiento
            INNER JOIN BSHReportes AS c
                ON a.Anio = c.Anio AND a.Folio = c.Folio AND a.NoDepartamento = c.NoDepartamento
            LEFT JOIN BGECatalogoUsuarios AS U
                ON ".$CampoUsuario." = U.NoUsuario AND c.NoDepartamento = U.NoDepartamento
            JOIN BGECatalogoDepartamentos AS S
                ON c.NoSucursal = S.NoDepartamento
            JOIN BSHCatalogoEstatus AS E
                ON c.Estatus = E.NoEstatus
                LEFT JOIN BSHCatalogoCatalogos as f
             ON b.TipoAtencion = f.idDescripcion AND idCatalogo =2
            LEFT JOIN BSHCatalogoAreas as g
             ON c.NoArea = g.NoArea AND c.NoDepartamento = g.NoDepartamento
             LEFT JOIN BSHCatalogoCategoria as h
             ON c.Categoria = h.nocategoria AND c.NoDepartamento = h.NoDepartamento";*/
    $query = "SELECT U.NombreDePila,c.Folio,E.Descripcion,S.Descripcion, c.DescripcionReporte,b.TipoAtencion, c.Fecha,c.HoraInicioReporte,c.FechaCierre,
                c.HoraCierre,c.FechaPromesa,c.SolucionCierre,c.Anio,c.NoDepartamento,c.PrioridadTicket,c.NoArea,g.Descripcion,c.Categoria,h.descripcion,f.Descripcion,x.Descripcion as NombreDepartamentoTecnico
                FROM (SELECT Anio,Folio,NoDepartamento,Max(FolioSeguimiento) UltimoSeguimiento,TipoAtencion
                        FROM BSHSeguimientoReportes GROUP BY Anio,Folio,NoDepartamento) a
                INNER JOIN BSHSeguimientoReportes AS b
                    ON a.Anio = b.Anio AND a.Folio = b.Folio AND a.NoDepartamento = b.NoDepartamento AND a.UltimoSeguimiento = b.FolioSeguimiento
                INNER JOIN BSHReportes AS c
                    ON a.Anio = c.Anio AND a.Folio = c.Folio AND a.NoDepartamento = c.NoDepartamento
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
                    ON ".$CampoUsuario." = U.NoUsuario AND c.NoDepartamento = U.NoDepartamento
                JOIN BGECatalogoDepartamentos AS S
                    ON c.NoSucursal = S.NoDepartamento
                JOIN BSHCatalogoEstatus AS E
                    ON c.Estatus = E.NoEstatus 
                    LEFT JOIN BSHCatalogoCatalogos as f
                 ON b.TipoAtencion = f.idDescripcion AND idCatalogo =2 
				LEFT JOIN BSHCatalogoAreas as g 
                 ON c.NoArea = g.NoArea AND c.NoDepartamento = g.NoDepartamento 
                 LEFT JOIN BSHCatalogoCategoria as h 
                 ON c.Categoria = h.nocategoria AND c.NoDepartamento = h.NoDepartamento 
                 LEFT JOIN BGECatalogoDepartamentos as x 
                 ON a.NoDepartamento = x.NoDepartamento ";
}

$Arreglo = array(
    'c.NoDepartamento'=>$NoDepartamento,
    'c.NoSucursal'=>$NoSucursal,
    'c.Estatus'=>$NoEstado,
    $CampoUsuario=>$NoUsuario,
    ' b.TipoAtencion'=>$NoTipoAtencion,
    'FechaInicial'=>$FechaInicial,
    'FechaFinal'=>$FechaFinal,
    'c.MedioContacto'=>$MedioContacto,
    'c.NoArea'=>$NoArea,
    'c.Categoria'=>$NoCategoria
);

foreach($Arreglo as $id=>$valor){
    if($valor != 0){

        if($id == 'FechaInicial'){
            if($NoEstado == 4){
                $valor = "'$valor'";
                $id = "c.FechaCierre >";
            }else{
                $valor = "'$valor'";
                $id = "c.Fecha >";
            }
        }

        if($id == 'FechaFinal'){
            if($NoEstado == 4){
                $valor = "'$valor'";
                $id = "c.FechaCierre <";
            }else{
                $valor = "'$valor'";
                $id = "c.Fecha <";
            }
        }
        if($id == 'c.Estatus'){

            if($valor == 98){
                $valor = 3;
                $id = "c.Estatus <";
            }
        }
        $Cond[] = array($id,$valor);
    }
}
$size = count($Cond);
for($i=0;$i <= $size;$i++){
    if($size > $i){
        $and = " and ";
    }else{
        $and="";
    }
    $where[] = $Cond[$i][0]."=".$Cond[$i][1].$and;
}
$where_final = 'WHERE '.substr($where[0].$where[1].$where[2].$where[3].$where[4].$where[5].$where[6].$where[7].$where[8],0,-5);

if($NoEstado == 99){

    if($FechaInicial == ""){

        $cond_Fecha_Inicial = " ";//$NoDepartamento

    }else {
        $cond_Fecha_Inicial = " AND c.FechaCierre  >= '$FechaInicial' ";//$NoDepartamento
    }

    if($FechaFinal == ""){
        $cond_Fecha_Final = " ";//$NoDepartamento

    }else {
        $cond_Fecha_Final = " AND c.FechaCierre  <= '$FechaFinal' ";//$NoDepartamento
    }

    if($NoDepartamento == 0){
        $cond_departamento = "" ;
    }else{
        $cond_departamento = " AND c.NoDepartamento = '$NoDepartamento' " ;
    }

    if($NoUsuario == 0 ){
        $cond_nousuario_asignado = "";
    }else{
        $cond_nousuario_asignado = " AND c.NoUsuarioAsignado = '$NoUsuario'";
    }

    if($NoUsuario == 0 ){
        $cond_nousuario_cierre = "";
    }else{
        $cond_nousuario_cierre = " AND c.NoUsuarioCierre = '$NoUsuario'";
    }



    $query_01 = "SELECT U.NombreDePila,c.Folio,E.Descripcion,S.Descripcion, c.DescripcionReporte,b.TipoAtencion, c.Fecha,c.HoraInicioReporte,c.FechaCierre,
                c.HoraCierre,c.FechaPromesa,c.SolucionCierre,c.Anio,c.NoDepartamento,c.PrioridadTicket,c.NoArea,g.Descripcion,c.Categoria,h.descripcion,f.Descripcion,x.Descripcion as NombreDepartamentoTecnico
                FROM (SELECT Anio,Folio,NoDepartamento,Max(FolioSeguimiento) UltimoSeguimiento,TipoAtencion
                        FROM BSHSeguimientoReportes GROUP BY Anio,Folio,NoDepartamento) a
                INNER JOIN BSHSeguimientoReportes AS b
                    ON a.Anio = b.Anio AND a.Folio = b.Folio AND a.NoDepartamento = b.NoDepartamento AND a.UltimoSeguimiento = b.FolioSeguimiento
                INNER JOIN BSHReportes AS c
                    ON a.Anio = c.Anio AND a.Folio = c.Folio AND a.NoDepartamento = c.NoDepartamento
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
                    ON c.NoUsuarioAsignado = U.NoUsuario AND c.NoDepartamento = U.NoDepartamento
                JOIN BGECatalogoDepartamentos AS S
                    ON c.NoSucursal = S.NoDepartamento
                JOIN BSHCatalogoEstatus AS E
                    ON c.Estatus = E.NoEstatus LEFT JOIN BSHCatalogoCatalogos as f
                 ON b.TipoAtencion = f.idDescripcion AND idCatalogo =2 
				LEFT JOIN BSHCatalogoAreas as g 
                 ON c.NoArea = g.NoArea AND c.NoDepartamento = g.NoDepartamento 
                 LEFT JOIN BSHCatalogoCategoria as h 
                 ON c.Categoria = h.nocategoria AND c.NoDepartamento = h.NoDepartamento 
                 LEFT JOIN BGECatalogoDepartamentos as x 
                 ON a.NoDepartamento = x.NoDepartamento 
                    WHERE c.Estatus <= 3 ". $cond_departamento . $cond_nousuario_asignado ." ";

    $query_02 = "SELECT U.NombreDePila,c.Folio,E.Descripcion,S.Descripcion, c.DescripcionReporte,b.TipoAtencion, c.Fecha,c.HoraInicioReporte,c.FechaCierre,
                c.HoraCierre,c.FechaPromesa,c.SolucionCierre,c.Anio,c.NoDepartamento,c.PrioridadTicket,c.NoArea,g.Descripcion,c.Categoria,h.descripcion,f.Descripcion,x.Descripcion as NombreDepartamentoTecnico
                FROM (SELECT Anio,Folio,NoDepartamento,Max(FolioSeguimiento) UltimoSeguimiento,TipoAtencion
                        FROM BSHSeguimientoReportes GROUP BY Anio,Folio,NoDepartamento) a
                INNER JOIN BSHSeguimientoReportes AS b
                    ON a.Anio = b.Anio AND a.Folio = b.Folio AND a.NoDepartamento = b.NoDepartamento AND a.UltimoSeguimiento = b.FolioSeguimiento
                INNER JOIN BSHReportes AS c
                    ON a.Anio = c.Anio AND a.Folio = c.Folio AND a.NoDepartamento = c.NoDepartamento
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
                    ON c.NoUsuarioCierre = U.NoUsuario AND c.NoDepartamento = U.NoDepartamento
                JOIN BGECatalogoDepartamentos AS S
                    ON c.NoSucursal = S.NoDepartamento
                JOIN BSHCatalogoEstatus AS E
                    ON c.Estatus = E.NoEstatus  
                    LEFT JOIN BSHCatalogoCatalogos as f
                 ON b.TipoAtencion = f.idDescripcion AND idCatalogo =2 
				LEFT JOIN BSHCatalogoAreas as g 
                 ON c.NoArea = g.NoArea AND c.NoDepartamento = g.NoDepartamento 
                 LEFT JOIN BSHCatalogoCategoria as h 
                 ON c.Categoria = h.nocategoria AND c.NoDepartamento = h.NoDepartamento 
                 LEFT JOIN BGECatalogoDepartamentos as x 
                 ON a.NoDepartamento = x.NoDepartamento
                 
                    WHERE c.Estatus >= 4 AND c.Estatus <= 5 ". $cond_departamento . $cond_nousuario_cierre . $cond_Fecha_Inicial . $cond_Fecha_Final ;

    $final_cons = $query_01." UNION " . $query_02 ;
    $_SESSION['EXPORT'] = '';
    $_SESSION['EXPORT_QUERY'] = $final_cons ;




}else{

    $final_cons = $query.$where_final." ORDER BY c.Fecha DESC";
    $_SESSION['EXPORT_QUERY'] = $final_cons ;

}

$Tickets->_query = $final_cons;
$Tickets->get_result_query();
$data = $Tickets->_rows;

$TotalReportes = count($data);
$NombreDepartamento = $data[0]['NombreDepartamentoTecnico'];



for($i=0;$i < $TotalReportes ;$i++){

    $alert = $Tickets->getAlertFechaPromesa($FechaActual,$data[$i][10]);
    if($alert < 1 or $alert == 0){
        $FechaPromesa = "<span style='color:red;'>".$Tickets->getFormatFecha($data[$i][10],2)."</span>";
        $NoTickets =  "<span style='color:red;'>".$Tickets->getFormatFolio($data[$i][1],4)."</span>";
    }elseif($alert >= 1 && $alert <= 2){
        $FechaPromesa = "<span style='color:#F0AD4E;'>".$Tickets->getFormatFecha($data[$i][10],2)."</span>";
        $NoTickets =  "<span style='color:#F0AD4E;'>".$Tickets->getFormatFolio($data[$i][1],4)."</span>";
    }else{
        $FechaPromesa =$Tickets->getFormatFecha($data[$i][10],2);
        $NoTickets =  $Tickets->getFormatFolio($data[$i][1],4);
    }

    $UrlTicket = "MenuSd(11,'ref=".$data[$i][1]."')";
    $rowData[] = array(
        "hStats"=>$Tickets->getStatAdjuntosTicket($data[$i][1],$data[$i][11],$data[$i][10],$data[$i][1]),
        "hTicket"=>"<a href='#' onclick='fnsdMenu(11,\"fl=".$data[$i][1]."&dpto=".$data[$i][13]."&anio=".$data[$i][12]."  \")' data-toggle='tooltip' data-placement='top' title='".utf8_encode($data[$i][8])."'><span class='text text-primary'>".$NoTickets."</span></a>",
        "hFecha"=>$Tickets->getFormatFecha($data[$i][6],2),
        "hSucursal"=>$data[$i][3],
        "hReporte"=>$data[$i][4],
        "hAsignado"=>$data[$i][0],
        "hPromesa"=>$Tickets->getFormatFecha($data[$i][10],2),
        "hCierre"=>$Tickets->getFormatFecha($data[$i][8],2),
        "hEstatus"=>$data[$i][2],
        "hPrioridad"=>$Tickets->getPrioridadTicket(2,$data[$i][14])
    );
}

?>
<script language="JavaScript">

    $("#txt_nombre_departamento").text("<?=$NombreDepartamento?>");
    //funcion para Formatear las Celdas
    function formatter(row, cell, value, columnDef, dataContext) {
        return value;
    }
    var grid;


    //Declaracion de Columnas
    var columns = [
        {id:"hStats",name:"",field:"hStats",width:45,cssClass: "cell-title", formatter: formatter},
        {id: "hTicket", name: "Ticket", field: "hTicket", width: 70,sortable:true ,cssClass: "text-center  btn-link", formatter: formatter},
        {id: "hFecha", name: "Fecha",sorteable:true, field: "hFecha", width: 90},
        {id: "hSucursal", name: "Sucursal", field: "hSucursal", width: 210},
        {id: "hReporte", name: "Reporte", field: "hReporte", minWidth: 310},
        {id:"hAsignado",name:"Asignado",field:"hAsignado",sortable:true ,minWidth:125},
        {id:"hPromesa",name:"Promesa",field:"hPromesa",minWidth:90},
        {id:"hCierre",name:"Cierre",field:"hCierre",minWidth:90},
        {id:"hEstatus",name:"Estatus",field:"hEstatus",minWidth:50},
        {id:"hPrioridad",name:"Prioridad",field:"hPrioridad",sortable:true,formatter:formatter,cssClass: "text-center",minWidth:50}
    ];

    //Opciones para Mandar a la Tabla
    var options = {
        enableCellNavigation: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        editable: true,
        enableAddRow: true
    };

    var data = <?php echo json_encode($rowData);?>;


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
    $(document).ready(function(){
        $("#num").text("<?=$TotalReportes?>");
    });

</script>
<div id="myGrid" style="height: 80vh;font-size: 12.55px;margin-top: 2px;"></div>
<div id="sql_query" style="display: none;"><?= base64_encode($where_final)."_".$query?></div>
<div id="sql_query2"></div>

<script language="JavaScript">
    $("#modalbtnclose").click();
</script>
