<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 04/04/2017
 * Time: 11:53 AM
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
include "../../../../core/model_puestos.php";

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

$puestos = new \core\model_puestos($_SESSION['data_login']['BDDatos']);
$puestos->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$puestos->get_list($_POST['opcion']);

if(count($puestos->_rows) >= 1){
for($i = 0 ; $i < count($puestos->_rows); $i++){

if($puestos->_rows[$i]['NoEstatus'] == 1){
$estado = '<span class="label label-success">Activo&nbsp;&nbsp;&nbsp;</span>';
}else{
$estado = '<span class="label label-danger">Inactivo</span>';
}
$idPuesto = $puestos->_rows[$i]['OpcCatalogo'];

$rowData[] = array(
"jidCatalogo"=>"<a href='#' onclick='fnCatEditarPuesto(1,".$idPuesto.")' ><span class='btn-link'>".$puestos->getFormatFolio($idPuesto,4)."<span></a>",
"jNombrePuesto"=>$puestos->_rows[$i]['Descripcion'],
"jDescripcion"=>$puestos->_rows[$i]['Texto1'],
"jNoestado"=>$estado,
"jNoUsuarioAlta"=>$puestos->_rows[$i]['NoUsuarioAlta'],
"jNoUsuarioUM"=>$puestos->_rows[$i]['NoUsuarioUM'],
"jFechaA"=>$puestos->getFormatFecha($puestos->_rows[$i]['FechaAlta'],2),
"jHoraA"=>$puestos->_rows[$i]['HoraAlta'],
"jFechaUM"=>$puestos->getFormatFecha($puestos->_rows[$i]['FechaUM'],2),
"jHoraUM"=>$puestos->_rows[$i]['HoraUM']
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
        {id:"jidCatalogo",field:"jidCatalogo",name:"#",minWidth:105,cssClass:"btn-link text-center",formatter:formatter,sortable:true},
        {id:"jNombrePuesto",field:"jNombrePuesto",name:"Puesto",minWidth:190,sortable:true},
        {id:"jDescripcion",field:"jDescripcion",name:"Descripción",minWidth:150,sortable:true},
        {id:"jNoestado",field:"jNoestado",name:"Estado",minWidth:90,cssClass:'text-center',sortable:true,formatter:formatter},

        {id:"jFechaA",field:"jFechaA",name:"Fecha Alta",minWidth:100},
        {id:"jHoraA",field:"jHoraA",name:"Hora Alta",minWidth:100},
        {id:"jNoUsuarioAlta",field:"jNoUsuarioAlta",name:"Usuario Alta",minWidth:100},

        {id:"jFechaUM",field:"jFechaUM",name:"Fecha UM",minWidth:100},
        {id:"jHoraUM",field:"jHoraUM",name:"Hora UM",minWidth:100},
        {id:"jNoUsuarioUM",field:"jNoUsuarioUM",name:"Usuario UM",minWidth:100},
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

