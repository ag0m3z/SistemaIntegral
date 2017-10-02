<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 21/02/2017
 * Time: 11:32 AM
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
include "../../../../core/model_aparatos.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$connect = new \core\model_aparatos($_SESSION['data_login']['BDDatos']);

//validar session del usuario y tiempo de conexion
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

//Traer lista de aparatos y guardarlos en arreglo
$rowData = $connect->listar_aparatos(0,'DESC',0,25,null);


if($_SESSION['menu_opciones'][5][1][1][0]['OpcionC'] == 1){
    $bolean = "true";
}else{
    $bolean = "false" ;
}
?>
<script language="JavaScript">
    //funcion para formatos de celdas
    function formatter(row,cel,value,columDef,dataContext){

        return value;

    }



    var grid;

    //Declaracion de Columnas
    // class Bg: bg-light-blue-gradient
    var columns = [
        {id:"hProducto",name:"Producto",field:"hProducto",width:80,cssClass: "text-center  btn-link", formatter: formatter},
        {id: "hDescripcion", name: "Descripcion", field: "hDescripcion", width: 270,sortable:true},
        {id: "hCategoria", name: "Categoria", field: "hCategoria", width: 120,sortable:true},
        {id: "hTProducto", name: "Tipo Producto", field: "hTProducto",width: 100,sortable:true},
        {id: "hMarca", name: "Marca", field: "hMarca", width: 130,minWidth:40},
        {id: "hPrecioNvo", name: "Precio Venta", field: "hPrecioNvo", minWidth: 90,cssClass:"text-right bg-light-yellow",editor:Slick.Editors.Text,formatter:Slick.Formatters.CurrencyFormatter},
        {id: "hClasificaNvo", name: "Clasificacion", field: "hClasificaNvo", minWidth: 60,cssClass:"text-center text-right bg-light-yellow",options: "A,B,C,D",editor:Slick.Formatters.SelectCellEditor},
        {id: "hcEmpeno", name: "(C) Empeño", field: "hcEmpeno", minWidth: 100,cssClass:"text-right",formatter: formatter,formatter:Slick.Formatters.CurrencyFormatter},
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


    $(document).ready(function () {

        grid = new Slick.Grid($("#myGrid"), data, columns, options);

        $('.slick-cell').mouseenter(function () {
            $(this.parentNode.children).addClass('slick-cell-hovered') ;
        });


        $('.slick-cell').on("click",function () {


            if($(this.parentNode.children).hasClass('slick-cell-select') ){

                $(this.parentNode.children).removeClass('slick-cell-select') ;


            }else{

                $(this.parentNode.children).addClass('slick-cell-select') ;

            }


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

    });

</script>
<div id="myGrid" style="height: 76vh;font-size: 12px;"></div>
