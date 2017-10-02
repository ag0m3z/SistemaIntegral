<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 05/04/2017
 * Time: 12:04 PM
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
include "../../../../core/model_areas_sd.php";

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

$area = new \core\model_areas_sd($_SESSION['data_login']['BDDatos']);
$area->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$filtro = 0;

if($_SESSION['data_login']['NoPerfil'] == 1){

    $filtro = 1;
}

$area->get_list($_POST['opcion'],$filtro,$_SESSION['data_departamento']['NoDepartamento']);


if(count($area->_rows) >= 1){
    for($i = 0 ; $i < count($area->_rows); $i++){


        $idArea = $area->_rows[$i][0];

        $rowData[] = array(
            "jNoArea"=>"<a href='#' ><span class='btn-link' onclick='fn_cat_editar_area(1,".$idArea.",\"".$area->_rows[$i][2]."\")'>".$area->getFormatFolio($idArea,4)."<span></a>",
            "jDescripcion"=>$area->_rows[$i][1],
            "jNoDepartamento"=>$area->_rows[$i][5],
            "jFechaAlta"=>$area->getFormatFecha($area->_rows[$i][3],2),
            "jFechaUM"=>$area->getFormatFecha($area->_rows[$i][4],2)
        );

    }
}

?>
<script language="JavaScript">
    function formatter(row,cell,value,columnDef,dataContext){
        return value;
    }
    var grid;

    //declaracion de Columnas
    var columns = [
        {id:"jNoArea",field:"jNoArea",name:"ID Área",minWidth:105,cssClass:"btn-link text-center",formatter:formatter,sortable:true},
        {id:"jNoDepartamento",field:"jNoDepartamento",name:"Departamento",minWidth:150,sortable:true},
        {id:"jDescripcion",field:"jDescripcion",name:"Área",minWidth:190,sortable:true},
        {id:"jFechaAlta",field:"jFechaAlta",name:"Fecha Alta",minWidth:90,cssClass:'text-center',sortable:true,formatter:formatter},
        {id:"jFechaUM",field:"jFechaUM",name:"Fecha UM",minWidth:100}
    ];

    //Opciones para Mandar a la Tabla
    var options = {
        enableCellNavigation: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        editable: false,
        enableAddRow: true
    };

    data =<?=json_encode($rowData)?>;

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
        $("#mdl_close").click();
    });

    //    MyAlert("Vista cargada correctamente","ok");
</script>
<div id="myGrid" style="height: 80vh;font-size: 12px"></div>

