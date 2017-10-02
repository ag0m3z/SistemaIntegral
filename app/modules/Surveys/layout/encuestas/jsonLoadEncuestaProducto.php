<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 16/10/15
 * Time: 08:50 AM
 */
include "../../../../controller/ReportesEncuestas.class.php";

$connect = new \controller\ReportesEncuestas();
//Validar que el usuario este logueado
if(!$connect->ValidaAcceso()){$connect->returnHomePage();}

//validar tiempo de actividad
$connect->ValidaSession_id();
$FechaACtual = date("Ymd");

if($_REQUEST['opc'] == 1){
    if($_SESSION['Perfil'] == 2){
        $Condicion = " a.FechaAlta >= ".$FechaACtual." AND a.NoSucursal = '".$_SESSION['NoDepartamento']."' ";
    }else{
        $Condicion = " a.FechaAlta >= ".$FechaACtual." ";
    }

}else{
//Validar Campos Enviados de Ajax no esten vacios.
$NoSucursal = $_POST['nosuc'];
$NoUsuarioSucursal = $_POST['nousr'];
$NoSupervisor = $_POST['nosup'];
$NoZona = $_POST['nozon'];
$NoProducto = $_POST['nopro'];
$NoMarca = $_POST['nomar'];
$Clasificacion = $_POST['clasificacion'];

$NoCategoria = $_POST['Nocategoria'];
$NoTpoServicio = $_POST['TpoServicio'];
$NoCompetidor = $_POST['NoCompetidor'];

if($_POST['fchaini'] == ""){$FechaInicial = '0';}else{$FechaInicial = $connect->getFormatFecha($_POST['fchaini'],1);}
if($_POST['fchafin'] == ""){$FechaFinal = '0';}else{$FechaFinal = $connect->getFormatFecha($_POST['fchafin'],1);}

if($FechaInicial == '0'){
    $CampoFecha2 = "Fecha2";
}else{
    $CampoFecha = "a.FechaAlta >";
}

if($FechaFinal == '0'){
    $CampoFecha = "a.FechaAlta >";
}else{
    $CampoFecha2= "Fecha2";
}

$datos = array(
    "a.NoSucursal"=>$NoSucursal,
    "a.NoUsuario"=>$NoUsuarioSucursal,
    "a.NoZona"=>$NoZona,
    "a.NoSupervisor"=>$NoSupervisor,
    "a.TipoProducto"=>$NoProducto,
    "a.NoMarca"=>$NoMarca,
    "a.Clasificacion"=>$Clasificacion,
    "a.NoCategoria"=>$NoCategoria,
    "a.IncNoCompetidor"=>$NoCompetidor,
    "a.IncidenciaTipoServicio"=>$NoTpoServicio,
    $CampoFecha=>$FechaInicial,
    $CampoFecha2=>$FechaFinal
);

//var_dump($datos);
foreach($datos as $key=> $val){
    if($val != '0'){
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
//var_dump($matriz);
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
}
$rowData= $connect->BuscarEncuesta($Condicion,"ASC",1);

if(count($rowData) == 0 and $_POST['opc'] != 1 ){
    echo "<script>$('#imgLoad').html('No se encontraron resultados');$('#imgLoad').addClass('alert alert-success text-center');$('#btnexport').attr('disabled',false);</script>";
}else{
    echo "<script>$('#imgLoad').html('');$('#imgLoad').removeClass('alert alert-success text-center')</script>";
}

?>
<script language="JavaScript">

    //funcion para Formatear las Celdas
    function formatter(row, cell, value, columnDef, dataContext) {
        return value;
    }
    var grid;

    //Declaracion de Columnas
    var columns = [
        {id:"hFolio",name:"Folio",field:"hFolio",width:55,cssClass: "text-center  btn-link", formatter: formatter},
        {id: "hCategoria", name: "Categoria", field: "hCategoria", width: 70,sortable:true, formatter: formatter},
        {id: "hDescripcion", name: "Descripcion", field: "hDescripcion",width: 80},
        {id: "hECompra", name: "Excelente Compra", field: "hECompra", width: 116,minWidth:40},
        {id: "hBCompra", name: "Buena Compra", field: "hBCompra", width: 100},
        {id: "hMCompra", name: "Maxima Compra", field: "hMCompra", width: 110},
        {id:"hCteNuevo",name:"Cliente Nuevo",field:"hCteNuevo",width:90},
        {id:"hBuenCte",name:"Buen Cliente",field:"hBuenCte",width:85},
        {id:"hECliente",name:"Excelente Cliente",field:"hECliente",width:110},
        {id:"hUsuarioUM",name:"Usuario UM",field:"hUsuarioUM",sortable:true,minWidth:65,cssClass: "text-center",formatter:formatter},
        {id:"hHoraUM",name:"Hora UM",field:"hHoraUM",sortable:true,minWidth:65,cssClass: "text-center",formatter:formatter}
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
    $("#btnexport").attr("disabled",false);
    $("#whereConsulta").html("<?=$Condicion?>");
    $(".badge").html("<?=count($rowData)?>");
</script>
