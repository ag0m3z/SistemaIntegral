<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/02/2017
 * Time: 06:28 PM
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
include "../../../../core/model_encuestas.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$connect = new \core\model_encuestas($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);


$CamposFiltro = array("FechaInicial"=>$connect->getFormatFecha($_POST['fch1'],1),"FechaFinal"=>$connect->getFormatFecha($_POST['fch2'],1),"b.NoSucursal"=>$_POST['nosucursal'],"b.NoUsuarioCierre"=>$_POST['nouser']);


foreach($CamposFiltro as $id=>$valor){
    if($valor != 0){


        if($id == "FechaInicial"){
            $id = "a.Fecha >";
            $valor = "'$valor'";
        }

        if($id == "FechaFinal"){
            $id = "a.Fecha <";
            $valor = "'$valor'";
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

$where_final = substr($where[0].$where[1].$where[2].$where[3].$where[4].$where[5],0,-5);
$_SESSION['EXPORT'] = $where_final;

$qlistaencuesta = $connect->BuscarEncuestaServicio(1,$where_final);

$total = count($qlistaencuesta);

echo "<script>$('#num').text('".$total."');</script>";

for($i=0;$i < count($qlistaencuesta);$i++){
    $dataRow[] = array(
    "gFolio"=>"<a href='javascript:void(0)' onclick='fnsdVerEncuesta(".$qlistaencuesta[$i][8].")'><span class='text-info'>".$connect->getFormatFolio($qlistaencuesta[$i][8],4)."</span></a>",
    "gTicket"=>$connect->getFormatFolio($qlistaencuesta[$i][2],4),
    "gSucursal"=>$qlistaencuesta[$i][3],
    "gObservaciones"=>$qlistaencuesta[$i][4],
    "gUserCierre"=>$qlistaencuesta[$i][5],
    "gFecha"=>$connect->getFormatFecha($qlistaencuesta[$i][6],2),
    "gHora"=>$qlistaencuesta[$i][7],
    );
}


?>
<script language="JavaScript">
    function formatter(row, cell, value, columnDef, dataContext) {
        return value;

    }
    var grid;

    //Declaracion de Columnas
    var columns = [
        {id:"gFolio",name:"Folio",field:"gFolio",width:60,cssClass: "text-center btn-link", formatter: formatter},
        {id: "gTicket", name: "Ticket", field: "gTicket", width: 70,sortable:true ,cssClass: "text-center", formatter: formatter},
        {id: "gSucursal", name: "Sucursal", field: "gSucursal", width: 280},
        {id: "gObservaciones", name: "Observaciones", field: "gObservaciones", width: 310},
        {id: "gUserCierre", name: "Agente Cierre", field: "gUserCierre", minWidth: 310},
        {id:"gFecha",name:"Fecha",field:"gFecha",minWidth:125},
        {id:"gHora",name:"Hora",field:"gHora",minWidth:125}
    ];
    //Opciones para Mandar a la Tabla
    var options = {
        enableCellNavigation: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        editable: true,
        enableAddRow: true
    };

    var data = <?php echo json_encode($dataRow);?>;

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
        $("#num").text("<?=$total?>");
        $("#modalbtnclose").click();
    });
</script>


<div id="lListarTabla" style="margin-top: 1vh">
    <div id="myGrid" style="height: 80vh;font-size: 12.55px;"></div>
</div>
