<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 01/03/2017
 * Time: 10:51 AM
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


$idCotizacion = $_POST['kil'];

switch ($_POST['opt']){
    case 1:
        $title = "Alta de nuevo kilataje";
        $FechaAlta = $connect->getFormatFecha(date("Ymd"), 2);
        $FechaUM = $connect->getFormatFecha(date("H:i:s"), 2);
     break;
    case 2:
        $title = "Edición de Cotizaci&oacute;n ";
        $connect->_query = "SELECT a.idCotizacion,a.TipoCotizacion,b.Descripcion as 'Tipo Metal',a.NoCategoria,c.Descripcion,a.Descripcion,a.Cotizacion01,a.Cotizacion02,a.Cotizacion03,a.Cotizacion04,a.Cotizacion05,
        a.FechaAlta,a.FechaUM,d.NombreDePila,a.NoUsuarioAlta,e.NombreDePila,a.NoUsuarioUM,a.HoraAlta,a.HoraUM,a.Cotizacion06,a.Cotizacion07
        FROM BGECotizaciones as a
        LEFT JOIN BGECatalogoGeneral as b
        ON a.TipoCotizacion = b.OpcCatalogo AND b.CodCatalogo = 23
        LEFT JOIN BGECatalogoGeneral as c
        ON a.NoCategoria = c.OpcCatalogo AND c.CodCatalogo = 9
        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as d
        ON a.NoUsuarioAlta = d.NoUsuario
        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as e
        ON a.NoUsuarioUM = e.NoUsuario
        WHERE a.idCotizacion = $idCotizacion
        ORDER BY a.Descripcion ASC";

        $connect->get_result_query();

        $dataInfo = $connect->_rows[0];
        $FechaAlta = $connect->getFormatFecha($dataInfo[11], 2);
        $FechaUM = $connect->getFormatFecha($dataInfo[12], 2);
    break;
}
//Mostrar Solo Metales de Oro

$connect->_query = "SELECT a.idCotizacion,a.ecompra,a.bcompra,a.mcompra,a.clientenuevo,a.buencliente,a.excelentecliente,b.NombreDePila,a.Fecha,a.Hora,ifnull(a.excelentecliente_venta,0),ifnull(a.preciocomision,0),ifnull(a.preciocomercializacion,0)
FROM BGEHistorialCotizaciones as a LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b ON a.NoUsuario = b.NoUsuario WHERE a.idCotizacion = $idCotizacion ORDER BY a.Fecha DESC ";

$connect->get_result_query();

$rows2 = $connect->_rows;
$TotalReportes = count($rows2);

if( count($rows2) > 0){

    for($i = 0 ; $i < count($rows2) ; $i++){

        $rowData2[] = array(
            "hFolio2"=>$TotalReportes,
            "hCot01"=>$rows2[$i][3],
            "hCot02"=>$rows2[$i][4],
            "hCot03"=>$rows2[$i][5],
            "hCot04"=>$rows2[$i][1],
            "hCot05"=>$rows2[$i][5],
            "hCot06"=>$rows2[$i][6],
            "hCot07"=>$rows2[$i][10],
            "hPComision"=>$rows2[$i][11],
            "hPComercia"=>$rows2[$i][12],
            "hUsuarioUM2"=>$rows2[$i][7],
            "hHoraUM2"=>$rows2[$i][9],
            "hFechaUM2"=>$connect->getFormatFecha($rows2[$i][8],2)
        );

        $TotalReportes = $TotalReportes - 1;
    }
}
?>
<script language="JavaScript" type="text/javascript" src="<?=\core\core::ROOT_APP?>site_design/js/jsFormatoMoneda.js"></script>
<script language="JavaScript">
    //Metodo para Ordenar la tabla
    $("input[type=text]").focus(function(){
        this.select();
    });
    $('#myModal').modal('toggle');
    $("#myModal").draggable({
        handle: ".modal-header"
    });

    $(".btn-primary").focus();
    $( "#tabsde" ).tabs();

    $(".currency").numeric({prefix:'$',cents:true});

    //funcion para Formatear las Celdas
    function formatter(row, cell, value, columnDef, dataContext) {
        return value;
    }
    var gridhis;
    //Declaracion de Columnas
    var columns = [
        {id:"hFolio2",name:"#",field:"hFolio2",width:25},
        {id: "hCot01", name: "Cte Nueva Compra", field: "hCot01",cssClass:"text-right", width: 100,formatter:Slick.Formatters.CurrencyFormatter},
        {id: "hCot02", name: "Cte Buena Compra", field: "hCot02", width: 90,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id: "hCot03", name: "Cte Maxima Compra", field: "hCot03", width: 90,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id:"hCot04",name:"Cte Nuevo Empeño",field:"hCot04",width:90,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id:"hCot05",name:"Cte Excelente Empeño",field:"hCot05",width:90,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id:"hCot06",name:"Ultima Opcion",field:"hCot06",width:100,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id:"hCot07",name:"Historial Impecable",field:"hCot07",width:100,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id:"hPComision",name:"Precio Comision",field:"hPComision",width:100,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id:"hPComercia",name:"Precio Comercializacion",field:"hPComercia",width:100,cssClass:"text-right",formatter:Slick.Formatters.CurrencyFormatter},
        {id:"hUsuarioUM2",name:"Usuario",field:"hUsuarioUM2",width:110,cssClass:"text-center"},
        {id:"hHoraUM2",name:"Hora",field:"hHoraUM2",width:60},
        {id:"hFechaUM2",name:"Fecha",field:"hFechaUM2",width:80,formatter:formatter}
    ];
    //Opciones para Mandar a la Tabla
    var optionsh= {
        enableCellNavigation: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        enableAddRow: true,
        autoHeight: true
    };
    datah = <?=json_encode($rowData2)?>;
    // Cargar el Grid con los Datos Devueltos de JSON
    gridhis = new Slick.Grid("#myGrid2", datah, columns, optionsh);




</script>
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"><?=$title?></h4>
            </div>
            <div class="modal-body" style="margin-bottom: -5px;margin-top: -10px;padding-top: 0px;">
                <div class="row">
                    <div class="col-md-12" style="margin-top: 2.5vh">
                        <table style="width: 100%" class="tablaDetailticket">
                            <tr>
                                <td style="width: 85px">Tipo Metal: </td>
                                <td style="width: 125px">
                                    <select class="formInput" disabled="">
                                        <?php
                                        if($idCotizacion==0){echo "<option value='0'>-- --</option>";}else{ echo "<option value='".$dataInfo[1]."'>".$dataInfo[2]."</option>";}

                                        ?>
                                    </select>
                                </td>
                                <td style="width: 85px">Descripción: </td>
                                <td colspan="2"><input type="text" readonly value="<?=$dataInfo[5]?>" class="formInput" /></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <di class="row"  style="margin-top: -1vh;">
                    <div id="tabsde">
                        <ul>
                            <li><a href="#nvo">General</a></li>
                            <li><a href="#hist">Historial</a></li>
                        </ul>
                        <div id="nvo" style="padding: 0px;">
                            <div class="row">
                                <div class="col-md-7">
                                    <table style="width: 100%;" class="tablaDetailticket">
                                        <tr>
                                            <td>Excelente Compra</td>
                                            <td><input type="text" readonly value="<?=$dataInfo[6]?>" class="formInput text-right currency" /></td>
                                            <td>Cte. Nuevo</td>
                                            <td><input type="text" readonly value="<?=$dataInfo[7]?>" class="formInput text-right currency"/></td>
                                        </tr>
                                        <tr>
                                            <td>Buena Compra</td>
                                            <td><input type="text" readonly value="<?=$dataInfo[10]?>" class="formInput text-right currency" /></td>
                                            <td>Buen Cte.</td>
                                            <td><input type="text" readonly value="<?=$dataInfo[8]?>" class="formInput text-right currency"/></td>
                                        </tr>
                                        <tr>
                                            <td>Maxima Compra</td>
                                            <td><input type="text" readonly value="<?=$dataInfo[19]?>" class="formInput text-right currency" /></td>
                                            <td>Excelente Cte.</td>
                                            <td><input type="text" readonly value="<?=$dataInfo[9]?>" class="formInput text-right currency"/></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-5">
                                    <table class="tablaDetailticket">
                                        <tr>
                                            <td style="width: 85px;">Fecha Alta:</td>
                                            <td><input type="text" readonly value="<?=$FechaAlta?>" class="formInput" /></td>
                                            <td><input type="text" readonly value="<?=$dataInfo[17]?>" class="formInput" /></td>
                                        </tr>
                                        <tr>
                                            <td>Fecha UM:</td>
                                            <td><input type="text" readonly value="<?=$FechaUM?>" class="formInput" /></td>
                                            <td><input type="text" readonly value="<?=$dataInfo[18]?>" class="formInput" /></td>
                                        </tr>
                                        <tr>
                                            <td>User Alta:</td>
                                            <td colspan="2"><input type="text" readonly value="<?=$dataInfo[13]?>" class="formInput" /></td>
                                        </tr>
                                        <tr>
                                            <td>User UM:</td>
                                            <td colspan="2"><input type="text" readonly value="<?=$dataInfo[15]?>" class="formInput" /></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div id="hist" style="padding: 0px;margin-bottom: 5px">
                            <div id="myGrid2" class="scroll"  style=";height: 55vh;width: 860px;font-size: 12px;"></div>

                        </div>
                    </div>
                </di>
            </div>
            <div class="modal-footer" style="text-align: left;margin-top: -18px;">
                <button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
