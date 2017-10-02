<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 01:57 PM
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
include "../../../../core/model_metales.php";

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

$connect = new \core\model_metales($_SESSION['data_login']['BDDatos']);

$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$Categoria = $_POST['opt'];
//Mostrar Solo Metales de Oro
$where = "";

if($_POST['opt'] != 99){
    $where = "WHERE a.NoCategoria = $Categoria ";
}

$connect->_query = "SELECT a.idCotizacion,b.Descripcion as 'Tipo Metal',c.Descripcion,a.Descripcion,a.Cotizacion01,a.Cotizacion02,a.Cotizacion03,a.Cotizacion04,a.Cotizacion05,d.NombreDePila,a.FechaUM,a.Cotizacion06,a.HoraUM,a.Cotizacion07,a.NoCategoria
        FROM BGECotizaciones as a
        LEFT JOIN BGECatalogoGeneral as b
        ON a.TipoCotizacion = b.OpcCatalogo AND b.CodCatalogo = 23
        LEFT JOIN BGECatalogoGeneral as c
        ON a.NoCategoria = c.OpcCatalogo AND c.CodCatalogo = 9
				LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as d
				ON a.NoUsuarioUM = d.NoUsuario
        ".$where." ORDER BY a.NoCategoria,a.idCotizacion ASC";

$connect->get_result_query();

$TotalReportes = count($connect->_rows);

if( $TotalReportes > 0){

    $arrayMoneda = array('$',',');

    for($i=0; $i < count($connect->_rows); $i++ ){


        if($connect->_rows[$i][14] == 1){

            $Cotizacion01 = str_replace($arrayMoneda,'',number_format($connect->_rows[$i][4]));
            $Cotizacion02 = str_replace($arrayMoneda,'',number_format($connect->_rows[$i][5]));
            $Cotizacion03 = str_replace($arrayMoneda,'',number_format($connect->_rows[$i][6]));
            $Cotizacion04 = str_replace($arrayMoneda,'',number_format($connect->_rows[$i][7]));
            $Cotizacion05 = str_replace($arrayMoneda,'',number_format($connect->_rows[$i][8]));
            $Cotizacion06 = str_replace($arrayMoneda,'',number_format($connect->_rows[$i][11]));
            $Cotizacion07 = str_replace($arrayMoneda,'',number_format($connect->_rows[$i][13]));
        }else{
            $Cotizacion01 = str_replace($arrayMoneda,'',number_format($connect->_rows[$i][4],1));
            $Cotizacion02 = str_replace($arrayMoneda,'',number_format($connect->_rows[$i][5],1));
            $Cotizacion03 = str_replace($arrayMoneda,'',number_format($connect->_rows[$i][6],1));
            $Cotizacion04 = str_replace($arrayMoneda,'',number_format($connect->_rows[$i][7],1));
            $Cotizacion05 = str_replace($arrayMoneda,'',number_format($connect->_rows[$i][8],1));
            $Cotizacion06 = str_replace($arrayMoneda,'',number_format($connect->_rows[$i][11],1));
            $Cotizacion07 = str_replace($arrayMoneda,'',number_format($connect->_rows[$i][13],1));
        }

        //Ocultar Joyeria 925
        if($connect->_rows[$i][0] != 19){

            $rowData[] = array(
                "hFolio"=>"<a href='#' onclick='fnmt_VerMetal(2,\"".$connect->_rows[$i][0]."\")'><span class='text text-primary'>".$connect->getFormatFolio($connect->_rows[$i][0],4)."</span></a>",
                "hCategoria"=>$connect->_rows[$i][2],
                "hDescripcion"=>$connect->_rows[$i][3],
                "hECompra"=>$Cotizacion01,
                "hBCompra"=>$Cotizacion02,
                "hMCompra"=>$Cotizacion03,
                "hCteNuevo"=>$Cotizacion04,
                "hBuenCte"=>$Cotizacion05,
                "hECliente"=>$Cotizacion06,
                "hEClienteVenta"=>$Cotizacion07,
                "hUsuarioUM"=>$connect->_rows[$i][9],
                "hHoraUM"=>$connect->_rows[$i][12],
                "hFechaUM"=>$connect->getFormatFecha($connect->_rows[$i][10], 2)
            );

        }


    }
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
        {id: "hDescripcion", name: "Descripcion", field: "hDescripcion",width: 120},

        {id: "hMCompra", name: "Cte Nueva Compra", field: "hMCompra", width: 130,formatter:Slick.Formatters.CurrencyFormatter,cssClass:'text-right'},
        {id:"hCteNuevo",name:"Cte Buena Compra",field:"hCteNuevo",width:130,formatter:Slick.Formatters.CurrencyFormatter,cssClass:'text-right'},
        {id:"hBuenCte",name:"Cte Maxima Compra",field:"hBuenCte",width:130,formatter:Slick.Formatters.CurrencyFormatter,cssClass:'text-right'},
        {id: "hECompra", name: "Cte Nuevo Empeño", field: "hECompra", width: 130,minWidth:40,formatter:Slick.Formatters.CurrencyFormatter,cssClass:'text-right'},
        {id: "hBCompra", name: "Cte Excelente Empeño", field: "hBCompra", width: 130,formatter:Slick.Formatters.CurrencyFormatter,cssClass:'text-right'},
        {id:"hECliente",name:"Ultima Opción",field:"hECliente",width:120,formatter:Slick.Formatters.CurrencyFormatter,cssClass:'text-right'},
        {id:"hEClienteVenta",name:"Historial Impecable",field:"hEClienteVenta",width:120,formatter:Slick.Formatters.CurrencyFormatter,cssClass:'text-right'},
        {id:"hUsuarioUM",name:"Usuario UM",field:"hUsuarioUM",sortable:true,minWidth:65,cssClass: "text-center",formatter:formatter},
        {id:"hHoraUM",name:"Hora UM",field:"hHoraUM",sortable:true,minWidth:65,cssClass: "text-center",formatter:formatter},
        {id:"hFechaUM",name:"Fecha UM",field:"hFechaUM",sortable:true,minWidth:65,cssClass: "text-center",formatter:formatter}
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
        $("#lblTipoFiltro").text("<?=$Label?>");
    });
</script>
<div id="myGrid" style="height: 75vh;font-size: 12px;"></div>
