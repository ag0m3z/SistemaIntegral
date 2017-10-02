<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 14/02/2017
 * Time: 06:57 PM
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
include "../../../../core/seguridad.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$Equipos = new \core\seguridad($_SESSION['data_login']['BDDatos']);

$Equipos->valida_session_id($_SESSION['data_login']['NoUsuario']);

switch ($_POST['opt']){
    case 1:
        //Mostrar Todos los Equipos

        $SqlWhere = " ";
        break;
    case 2:
        //Mostrar Solo Asignados
        $SqlWhere = " WHERE I.Estatus = 1 ";
        break;
    case 3:
        //Mostrar Solo Entregados
        $SqlWhere = " WHERE I.Estatus = 2 ";
        break;
    case 4:
        //Mostrar Solo Enviados
        $SqlWhere = " WHERE I.Estatus = 3 ";
        break;
    case 5:
        //Mostrar Solo En Proceso
        $SqlWhere = " WHERE I.Estatus = 4 ";
        break;
    case 6:
        //Mostrar por Busqueda

        $array = array(
            "I.Equipo"=>$_POST['tpo_equipo'],
            "I.NoDepartamento"=>$_POST['departamento'],
            "I.Estatus"=>$_POST['estado'],
            "I.UsuarioRecibe"=>$_POST['user']
        );
        $cond02 = $Equipos->Constructor_Where($array,"cadena");
        $cond01 = " WHERE
                            I.Folio LIKE '%".$_POST['mtext']."%' OR
                            I.NombreCompleto LIKE '%".$_POST['mtext']." %' OR
                            D.Descripcion LIKE '%".$_POST['mtext']."%' OR
                            I.CodigoCedis LIKE '%".$_POST['mtext']."%' OR
                            I.SerieCedis LIKE '%".$_POST['mtext']."%' OR
                            I.SerieEquipo LIKE '%".$_POST['mtext']."%' " ;

        if(trim($_POST['mtext'])){
            if(!$cond02 == ""){
                echo $SqlWhere = $cond01 . " AND " .$cond02 ;
            }else{
                echo $SqlWhere = $cond01 ;
            }

        }else{

            echo $SqlWhere = " WHERE ".$cond02;

        }
        break;
    case 7:
        //Mostrar Todos los Equipos

        $array = array(
            "FechaInicial"=>$Equipos->getFormatFecha($_POST['fch01'],1),
            "FechaFinal"=>$Equipos->getFormatFecha($_POST['fch02'],1),
            "I.Equipo"=>$_POST['tpo_equipo'],
            "I.NoDepartamento"=>$_POST['departamento'],
            "I.Estatus"=>$_POST['estado'],
            "I.UsuarioRecibe"=>$_POST['user']
        );

        foreach($array as $id => $valor){
            if($valor != 0){

                if($id == "FechaInicial"){
                    $id = "I.FechaRegistro >";
                    $valor = "'$valor'";
                }

                if($id == "FechaFinal"){
                    $id = "I.FechaRegistro <";
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
        $_SESSION['EXPORT'] = $where_final ;
        $SqlWhere = " WHERE " . $where_final ;
        break;
    case 8: //Mostrar Solo los re asignados
        $SqlWhere = " WHERE I.Estatus = 5 ";
        break;
}


$SqlSelect = "SELECT I.Folio,I.FechaAsignacion,I.NombreCompleto,D.Descripcion,C.Descripcion,I.CodigoCedis,I.SerieCedis,CC.Descripcion,us.NombreDePila,I.FechaRegistro
              FROM BSHInventarioEquipos AS I
              JOIN BGECatalogoDepartamentos AS D
              ON I.NoDepartamento = D.NoDepartamento
              JOIN BSHCatalogoCatalogos AS C
              ON I.Equipo = C.idDescripcion AND C.idCatalogo = 7
              JOIN BSHCatalogoCatalogos AS CC
              ON I.Estatus = CC.idDescripcion AND CC.idCatalogo = 8
              JOIN SINTEGRALGNL.BGECatalogoUsuarios as us
              ON I.UsuarioRecibe = us.NoUsuario ";

$SqlOrderBy = "ORDER BY I.Folio DESC";

$Equipos->_query = $SqlSelect.$SqlWhere.$SqlOrderBy;

$Equipos->get_result_query();

$TotalReportes = count($Equipos->_rows);

$data = $Equipos->_rows;

for($i=0; $i < count($Equipos->_rows);$i++){

    $rowData[] = array(
        "eFolio"=>"<a href='#' onclick='fnsdMenu(13,\"?fl=".$data[$i][0]."\")' class='text-danger' style='color: #0073ea'>".$Equipos->getFormatFolio($data[$i][0],4)."</a>",
        "eFechaRegistro"=>$Equipos->getFormatFecha($data[$i]['FechaRegistro'],2),
        "eNombre"=>$data[$i][2],
        "eDepartamento"=>utf8_encode($data[$i][3]),
        "eEquipo"=>$data[$i][4],
        "eCodigo"=>$data[$i][5],
        "eSerie"=>$data[$i][6],
        "eEstatus"=>$data[$i][7],
        "eRegistro"=>$data[$i][8],
        "eFechaAsignacion"=>$Equipos->getFormatFecha($data[$i][1],2)

    );

}
?>
<script language="JavaScript">
    $("#eSaveEdit").hide();
    $("#eImprimeDocumento").hide();
    $("#eAddDoc").hide();
    $("#eAddImg").hide();
    $("#eReasignacion").addClass("hidden");
    //funcion para Formatear las Celdas
    function formatter(row, cell, value, columnDef, dataContext) {
        return value;
    }
    var grid;


    //Declaracion de Columnas
    var columns = [
        {id:"eFolio",name:"Folio",field:"eFolio",width:70,sortable:true,sortable:true,cssClass: "cell-title text-center btn-link", formatter: formatter},
        {id: "eNombre", name: "Nombre Asignado", field: "eNombre", width: 150,sortable:true},
        {id: "eDepartamento", name: "Departamento", field: "eDepartamento", width: 140,sortable:true},
        {id: "eEquipo", name: "Equipo", field: "eEquipo", minWidth: 120,sortable:true},
        {id:"eCodigo",name:"Codigo Cedis",field:"eCodigo",minWidth:90,sortable:true},
        {id:"eSerie",name:"Serie Cedis",field:"eSerie",minWidth:90,sortable:true},
        {id:"eEstatus",name:"Estatus",field:"eEstatus",minWidth:50,sortable:true},
        {id:"eRegistro",name:"Registro",field:"eRegistro",minWidth:120,sortable:true},
        {id: "eFechaRegistro", name: "Fecha Registro", field: "eFechaRegistro", width: 120,sortable:true ,cssClass: "text-center", formatter: formatter},
        {id: "eFechaAsignacion", name: "Fecha Asignación", field: "eFechaAsignacion", width: 120,sortable:true ,cssClass: "text-center", formatter: formatter}



    ];

    //Opciones para Mandar a la Tabla
    var options = {
        enableCellNavigation: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        editable: true,
        enableAddRow: true
    };

    var data = <?=json_encode($rowData)?>;


    // Cargar el Grid con los Datos Devueltos de JSON
    grid = new Slick.Grid("#listTable", data, columns, options);

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
        $("#lbl-num").text("<?=$TotalReportes?>");
        $("#mdl_btn_close").click();
    });
</script>
