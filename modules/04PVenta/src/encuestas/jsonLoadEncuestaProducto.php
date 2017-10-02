<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 24/03/2017
 * Time: 09:14 AM
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
unset($_SESSION['EXPORT']);

$FechaACtual = date("Ymd");


if($_SESSION['data_departamento']['NoTipo'] == "S"){
    $Condicion = " a.FechaAlta >= ".$FechaACtual." AND a.NoSucursal = '".$_SESSION['data_departamento']['NoDepartamento']."' ";
}else{
    $Condicion = " a.FechaAlta >= ".$FechaACtual." ";
}

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
    if($val != 0){
        if($key == 'Fecha2'){$key = 'a.FechaAlta <';}
        if($key == 'a.Clasificacion'){

            $DesClass = "SELECT Descripcion FROM BGECatalogoGeneral WHERE OpcCatalogo = ".$val." AND CodCatalogo = 7";
            $connect->get_result_query();


            if(count($connect->_rows) > 0 ){
                $idClass = $connect->_rows[0];

            }

            $val = "'$idClass[0]'";

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

// traer encuestas en formato json
$rowData = $connect->buscar_encuesta_producto($_POST['opc'],$Condicion,true);
$_SESSION['EXPORT'] = $datos;

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
        {id: "hDescripcion", name: "Codigo", field: "hDescripcion",width: 80},
        {id: "hECompra", name: "Tipo", field: "hECompra", width: 80,minWidth:40},
        {id: "hBCompra", name: "Tipo producto", field: "hBCompra", width: 215},
        {id: "hMCompra", name: "Marca", field: "hMCompra", width: 110},
        {id:"hCteNuevo",name:"Producto",field:"hCteNuevo",width:290},
        {id:"hBuenCte",name:"Clasificación",field:"hBuenCte",width:85,cssClass: "text-center"},
        {id:"hECliente",name:"Sucursal",field:"hECliente",width:130},
        {id:"hUsuarioUM",name:"Usuario",field:"hUsuarioUM",sortable:true,minWidth:65,formatter:formatter},
        {id:"FEch",name:"Fecha",field:"hHoraUM",sortable:true,minWidth:75,cssClass: "text-center",formatter:formatter}
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

    $(".badge").html("<?=count($rowData)?>");

</script>
<div id="myGrid" style="height: 75vh;font-size: 12px"></div>

