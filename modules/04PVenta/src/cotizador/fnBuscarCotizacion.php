<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 27/09/2017
 * Time: 10:56 AM
 */
include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);

$parametros = array(
    "a.FolioCotizacion"=>$_POST['Folio'],
    "a.MedioContacto"=>$_POST['MedioContacto'],
    "a.TipoCotizacion"=>$_POST['TipoCotizacion'],
    "a.NoCategoria"=>$_POST['Categoria'],
    "a.NoTipo"=>$_POST['Tipo'],
    "a.NoUsuarioRegistro"=>$_POST['UsuarioRegistra'],
    "a.NoEstatus"=>$_POST['NoEstatus'],
    "FechaInicial"=>$connect->getFormatFecha($_POST['Fecha1'],1),
    "FechaFinal"=>$connect->getFormatFecha($_POST['Fecha2'],1)
);

foreach($parametros as $id=>$valor){
    if($valor != 0){

        if($id == 'FechaInicial'){
            $id = "date(a.FechaRegistro) >";
        }

        if($id == 'FechaFinal'){
            $id = "date(a.FechaRegistro) <";
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

$connect->_query = "
SELECT 
a.FolioCotizacion,lpad(a.FolioCotizacion,6,'0'),a.NombreCliente,
a.MedioContacto,b.Descripcion,a.TipoCotizacion,c.Descripcion,a.NoCategoria,d.Descripcion,a.NoTipo,a.MontoSolicitado,
a.MontoAutorizado,a.CotizacionEmpeno,a.CotizacionCompra,a.Descripcion,a.NoEstatus,f.Descripcion,f.Texto1,
a.NoUsuarioSolicitante,g.NombreDePila,a.NoUsuarioRegistro,h.NombreDePila,a.FechaInicial,a.FechaVigencia,a.FechaRegistro,a.FechaUM,ifnull(a.MontoPrestamo,0),a.BoletaPrestamo ,a.Serie 
FROM BGECotizador as a 
LEFT JOIN BGECatalogoGeneral as b 
ON a.MedioContacto = b.OpcCatalogo AND b.CodCatalogo = 30 
LEFT JOIN BGECatalogoGeneral as c 
ON a.TipoCotizacion = c.OpcCatalogo AND c.CodCatalogo = 31 
LEFT JOIN BGECatalogoGeneral as d 
ON a.NoCategoria = d.OpcCatalogo AND d.CodCatalogo = 9 
LEFT JOIN BGECatalogoGeneral as e 
ON a.NoTipo = e.OpcCatalogo AND e.CodCatalogo = 5 AND e.Numero2 = a.NoCategoria 
LEFT JOIN BGECatalogoGeneral as f 
ON a.NoEstatus = f.OpcCatalogo AND f.CodCatalogo = 29 
LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as g 
ON a.NoUsuarioSolicitante = g.NoUsuario 
LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as h 
ON a.NoUsuarioRegistro = h.NoUsuario 
$where_final
ORDER BY a.FechaRegistro DESC
";
$connect->get_result_query();

$Total = count($connect->_rows);

if($Total > 0){
    for($i=0;$i<count($connect->_rows);$i++){
        $data[] = array(
            "id"=>"<a href='#' onclick='fn04EditarCotizacion(1,\"".$connect->_rows[$i][0]."\",\"".$connect->_rows[$i][28]."\")'><span class='text text-primary'>".$connect->_rows[$i][1]."</span></a>",
            "cliente"=>$connect->_rows[$i][2],
            "descripcion"=>$connect->_rows[$i][14],
            "montoautorizado"=>$connect->_rows[$i][11],
            "montoprestamo"=>$connect->_rows[$i][26],
            "boletaprestamo"=>$connect->_rows[$i][27],
            "estatus"=>$connect->_rows[$i][17],
            "usuarioa"=>$connect->_rows[$i][21],
            "fechainicial"=>$connect->getFormatFecha($connect->_rows[$i][22],'dd/mm/yyyy'),
            "fechavigencia"=>$connect->getFormatFecha($connect->_rows[$i][23],'dd/mm/yyyy'),
            "fecharegistro"=>$connect->getFormatFecha($connect->_rows[$i][24],'dd/mm/yyyy'),
            "TotalRow"=>$Total
        );
    }
}else{
    $data="";
    $Total=0;
}
?>
<script language="JavaScript">

    $("#idtotal").text("<?=$Total?>");
    $("#modalbtnclose").click();
    //funcion para Formatear las Celdas
    function formatter(row, cell, value, columnDef, dataContext) {
        return value;
    }
    var grid;


    //Declaracion de Columnas
    var columns = [
        {id: "id", name: "Folio", field: "id",width:65,cssClass: "text-center  btn-link", formatter: formatterGrid},
        {id: "cliente", name: "Cliente", field: "cliente",width:210},
        {id: "descripcion", name: "Descripcion", field: "descripcion",width:280},
        {id: "montoautorizado", name: "Cotización",cssClass:"text-right", field: "montoautorizado",width:85,formatter:formatterGrid,formatter:Slick.Formatters.CurrencyFormatter},
        {id: "montoprestamo", name: "Prestamo",cssClass:"text-right", field: "montoprestamo",width:85,formatter:formatterGrid,formatter:Slick.Formatters.CurrencyFormatter},

        {id: "boletaprestamo", name: "Boleta", field: "boletaprestamo",width:80},
        {id: "estatus", name: "Estatus", field: "estatus",width:75,cssClass:"text-center"},
        {id: "usuarioa", name: "Usuario Registro", field: "usuarioa",width:135},
        {id: "fechainicial", name: "Fch. Inicial", field: "fechainicial",width:110,formatter:formatterGrid},
        {id: "fechavigencia", name: "Fch. Vigencia", field: "fechavigencia",width:110,formatter:formatterGrid},
        {id: "fecharegistro", name: "Fch. Registro", field: "fecharegistro",width:110,formatter:formatterGrid}
    ];

    //Opciones para Mandar a la Tabla
    var options = {
        enableCellNavigation: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        editable: true,
        enableAddRow: true
    };

    var data = <?=json_encode($data)?>;


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

