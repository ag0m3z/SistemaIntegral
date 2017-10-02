<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 11:03 AM
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
include "../../../../core/model_aparatos.php";

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

$connect = new \core\model_aparatos($_SESSION['data_login']['BDDatos']);

$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

//echo var_dump($rowData);
if($_SESSION['menu_opciones'][5][1][1][0]['OpcionC'] == 1){
    $bolean = "true";
}else{
    $bolean = "false" ;
}

//Creando el SELECT para la Estructura de la Consulta
$sql = "SELECT a.CodigoProducto,a.Descripcion,b.Descripcion,c.Descripcion,a.ImporteVenta,a.Clasificacion04,d.OpcCatalogo,d.Numero1N,d.Numero2N,a.NoEstatus,d.Numero3N,d.Numero4N,a.Clasificacion01,e.Descripcion
From BOPCatalogoProductos";

if($_SESSION['data_login']['NoPerfil'] == 2){
    $disabled = "disabled";
}else{

    $disabled = "";
}

//Join de la Estructura de Consulta
$join =" as a
LEFT JOIN BGECatalogoGeneral as b
            ON a.Clasificacion02 = b.OpcCatalogo AND b.CodCatalogo = 5 AND Numero2 = ".$_POST['categoria_producto']."
            LEFT JOIN BGECatalogoGeneral as c
            ON a.Clasificacion03 = c.OpcCatalogo AND c.CodCatalogo = 6 AND Numero2 = ".$_POST['categoria_producto']."
            LEFT JOIN BGECatalogoGeneral as d
            ON a.Clasificacion04 = d.Descripcion AND d.CodCatalogo = 7 left JOIN BGECatalogoGeneral as e
            ON a.Clasificacion01 = e.OpcCatalogo AND e.CodCatalogo = 9";

//Pasando el POST Enviado desde Ajax a Variable
$Clasificacion = $_POST['clasificacion'];
$NoUsuario = $_POST['nouser'];
$NoUsuarioUM = $_POST['nouseru'];
$DescripcionProducto =$_POST['txtDescripProd'];

if(!empty($_POST['producto']) ){
    // si viene el codigo del producto
    $where_final = "WHERE a.CodigoProducto =  ".$_POST['producto']."";

}else{

//Validando Datos Enviados de Ajax no sean null si lo son se pasan a Valor: 0;
    if(empty($_POST['producto'])){$_POST['producto']="0";}

    if($_POST['fchalIni']== ""){$FechaAltaInicial= "0"; }else{$FechaAltaInicial = $connect->getFormatFecha($_POST['fchalIni'],1);}

    if($_POST['fchaFin']==""){$FechaAltaFinal="0";}else{$FechaAltaFinal = $connect->getFormatFecha($_POST['fchaFin'],1);}

    if($_POST['fchumini']==""){$FechaUMInicial="0";}else{$FechaUMInicial = $connect->getFormatFecha($_POST['fchumini'],1);}

    if($_POST['fchumfin']==""){$FechaUMFinal="0";}else{$FechaUMFinal = $connect->getFormatFecha($_POST['fchumfin'],1);}
    if($DescripcionProducto == ""){$DescripcionProducto = 0;}else{$DescripcionProducto ="'%".$_POST['txtDescripProd']."%'";}
//Validando Fechas para Realizar la Consulta de Fecha Inicial a Fecha Fianl de Alta y Modificacion

    if($FechaAltaInicial == '0'){
        $cFechaAlta2 = "a.FechaAlta2 <";
    }else{
        $cFechaAlta = "a.FechaAlta >";
    }

    if($FechaAltaFinal == '0'){
        $cFechaAlta = "a.FechaAlta >";
    }else{
        $cFechaAlta2 = "a.FechaAlta2 <";
    }

    if($FechaUMInicial == '0'){
        $cFechaUM2 = "a.FechaUM2 <";
    }else{
        $cFechaUM = "a.FechaUM >";
    }

    if($FechaUMFinal == '0'){
        $cFechaUM = "a.FechaUM >";
    }else{
        $cFechaUM2 = "a.FechaUM2 <";
    }

//Sacando el id de la Clasificacion Enviada: Ejemplo: A = 1
    $connect->_query = "SELECT Descripcion FROM BGECatalogoGeneral WHERE OpcCatalogo = ".$Clasificacion." AND CodCatalogo = 7";
    $connect->get_result_query();

//Recorrer l resultado de la Consulta Anterior y guardando en un Array
    $row = $connect->_rows[0];

//Validar el Resultado del Arreglo y si el Valor es Null se pasa a 0, Si No se Toma el id de la Tabla(el resultado del arreglo.)
    if($row[0] == ""){$_POST['clasificacion'] = "0";}else{$_POST['clasificacion'] = "'".$row[0]."'";}

//$datos = array(0=>$_POST['producto'],1=>" AND ",2=>$_POST['tpoproducto'],3=>" AND ",4=>$_POST['marca']," AND ",6=>$_POST['clasificacion']);
    $datos = array('a.CodigoProducto'=>$_POST['producto'],
        'a.Clasificacion01'=>$_POST['categoria_producto'],
        'a.Clasificacion02'=>$_POST['tpoproducto'],
        'a.Clasificacion03'=>$_POST['marca'],
        'a.Clasificacion04'=> $_POST['clasificacion'],
        $cFechaAlta=>$FechaAltaInicial,
        $cFechaAlta2=>$FechaAltaFinal,
        $cFechaUM=>$FechaUMInicial,
        $cFechaUM2=>$FechaUMFinal,
        'a.NoUsuarioAlta'=>$NoUsuario,
        'a.NoUsuarioUM'=>$NoUsuarioUM,
        'a.Descripcion'=>$DescripcionProducto);

//var_dump($datos);
    if(empty($_POST['productos'])){$_POST['productos']= "0";}

    foreach($datos as $key1=> $val){
        if($val != "0"){
            if($key1 == 'a.FechaAlta2 <'){$key1 = 'a.FechaAlta <';};
            if($key1 == 'a.FechaUM2 <'){$key1 = 'a.FechaUM <';}
            $mad[] = array($key1,$val);
        }
    }

    $tam = count($mad)."<br>";

    for($i=0;$i<$tam;$i++){
        if($tam > $i){
            $and = " and ";
        }else{
            $and="";
        }
        if($mad[$i][0] == "a.Descripcion"){
            $operador = " like ";
        }else{
            $operador = "=";
        }
        $where[] = $mad[$i][0].$operador.$mad[$i][1].$and." ";
    }

    $where_final = "WHERE ".substr($where[0].$where[1].$where[2].$where[3].$where[4].$where[5].$where[6].$where[7],0,-5);
    $_SESSION["EXPORT"] = $where_final;

}

$rowData = $connect->listar_aparatos(0,'ASC',0,1000,$where_final);
if(count($rowData) == 0){
    echo "<script>$('#closemodal').click();</script>";
}

?>
<script language="JavaScript">
    //funcion para formatos de celdas
    function formatter(row,cel,value,columDef,dataContext){
        return value;
    }

    var grid;

    // class Bg: bg-light-blue-gradient
    var columns = [
        {id:"hProducto",name:"Producto",field:"hProducto",width:80,cssClass: "text-center  btn-link", formatter: formatter},
        {id: "hDescripcion", name: "Descripcion", field: "hDescripcion", width: 170,sortable:true},
        {id: "hCategoria", name: "Categoria", field: "hCategoria", width: 120,sortable:true},
        {id: "hTProducto", name: "Tipo Producto", field: "hTProducto",width: 100,sortable:true},
        {id: "hMarca", name: "Marca", field: "hMarca", width: 130,minWidth:40},
        {id: "hPrecioNvo", name: "Precio Venta", field: "hPrecioNvo", minWidth: 100,cssClass:"text-right bg-light-yellow",editor:Slick.Editors.Text,formatter:Slick.Formatters.CurrencyFormatter},
        {id: "hClasificaNvo", name: "Clasificacion", field: "hClasificaNvo", minWidth: 60,cssClass:"text-center text-right bg-light-yellow",options: "A,B,C,D",editor:Slick.Formatters.SelectCellEditor},
        {id: "hcEmpeno", name: "(C) Empeño", field: "hcEmpeno", minWidth: 100,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id: "hcexcompra", name: "(C) Excelente Compra", field: "hcexcompra", minWidth: 100,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id: "hcbuecompra", name: "(C) Buena Compra", field: "hcbuecompra", minWidth: 100,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id: "hcmaxcompra", name: "(C) Max Compra", field: "hcmaxcompra", minWidth: 100,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id: "hbEmpeno", name: "(B) Empeño", field: "hbEmpeno", minWidth: 100,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id: "hbexcompra", name: "(B) Excelente Compra", field: "hbexcompra", minWidth: 100,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id: "hbbuecompra", name: "(B) Buena Compra", field: "hbbuecompra", minWidth: 100,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id: "hbmaxcompra", name: "(B) Max Compra", field: "hbmaxcompra", minWidth: 100,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id: "haEmpeno", name: "(A) Empeño", field: "haEmpeno", minWidth: 100,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id: "haexcompra", name: "(A) Excelente Compra", field: "haexcompra",cssClass:"text-right", minWidth: 100,formatter:Slick.Formatters.CurrencyFormatter},
        {id: "habuecompra", name: "(A) Buena Compra", field: "habuecompra",cssClass:"text-right", minWidth: 100,formatter:Slick.Formatters.CurrencyFormatter},
        {id: "hamaxcompra", name: "(A) Max Compra", field: "hamaxcompra",cssClass:"text-right", minWidth: 100,formatter:Slick.Formatters.CurrencyFormatter},
        {id: "hFechaA", name: "Fecha Alta", field: "hFechaA", minWidth: 80},
        {id: "hFechaU", name: "Fecha UM", field: "hFechaU", minWidth: 80},
        {id: "hPrecio", name: "Precio Real", field: "hPrecio", minWidth: 100,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id: "hClasifica", name: "Clasificacion Real", field: "hClasifica", minWidth: 60,cssClass:"text-center text-right "},
        {id: "hCodigoProducto", name: "Codigo", field: "hCodigoProducto", minWidth: 80}


    ];

    //Opciones para Mandar a la Tabla
    var options = {
        enableCellNavigation: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        editable: <?=$bolean?>,
        autoEdit:true,
        enableAddRow: true
    };
    data =<?=json_encode($rowData)?>;

    // Cargar el Grid con los Datos Devueltos de JSON
    grid = new Slick.Grid($("#myGrid"), data, columns, options);

    $(document).ready(function () {


        grid = new Slick.Grid($("#myGrid"), data, columns, options);

        $('.slick-cell').mouseenter(function () {
            $(this.parentNode.children).addClass('slick-cell-hovered') ;
        });

        $('.slick-cell').mouseleave(function () {
            $(this.parentNode.children).removeClass('slick-cell-hovered');
        });

        grid.onCellChange.subscribe(function(e, args) {

            var idProducto = args.item['hCodigoProducto'],
                precio = args.item['hPrecio'],
                precionvo = args.item['hPrecioNvo'],
                clasificacion = args.item['hClasifica'],
                clasificacionNvo = args.item['hClasificaNvo'];

            console.log(args);

            if( precio != precionvo || clasificacion != clasificacionNvo){

                // si se realiza cambio no se olvide de actualizar
                // archivo: modules/04PVenta/src/productos/json_listar_productos.php

                $.ajax({
                    url:"modules/04PVenta/src/productos/fn_actualiza_precio.php",
                    type:"POST",
                    data:{
                        eCodigo:idProducto,
                        ePrecio:precio,
                        ePrecioNvo:precionvo,
                        eClasificacion:clasificacion,
                        eClasificacionNvo:clasificacionNvo
                    }
                }).done(function(outData){

                    console.log(outData.result );

                    if(outData.result == 'ok'){
                        grid.invalidateRow(args.row);
//            console.log(grid.getColumns()[args.cell].name);
//            console.log(args.item.hPrecio);
//            console.log(data[args.row][grid.getColumns()[args.cell].field]);
//            console.log(data[args.row]);
                        data[args.row]['hClasifica'] = clasificacionNvo;
                        data[args.row]['hPrecio'] = precionvo;
                        grid.render();
                    }

                }).fail(function(jqXHR,textStatus,errorThrown){

                    if ( console && console.log ) {
                        MyAlert( "La solicitud a fallado:<br>"+jqXHR.getResponseHeader('content-type')+" <br>Mensaje: " +  textStatus + "<br>Tipo: " +errorThrown,'error');
                    }

                });
            }
        });

        $("#badgeTtPtoductos").text(<?=count($rowData)?>);
        $("#btn3").hide();

    });

</script>
<div id="myGrid" style="height: 76vh;font-size: 12px;"></div>
<div id="whereConsulta" class="hidden"><?=base64_encode($where_final)?></div>
