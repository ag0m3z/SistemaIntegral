<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 02/03/2017
 * Time: 10:52 AM
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
include "../../../../core/model_usuarios.php";

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

$connect = new \core\model_usuarios($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$rowData = $connect->ListarUsuarios($_REQUEST['opcion'])


?>
<script language="JavaScript">
    function formatter(row,cell,value,columnDef,dataContext){
        return value;
    }
    var grid;

    //declaracion de Columnas
    var columns = [
        {id:"hIdUsuario",field:"hIdUsuario",name:"id Usuario",minWidth:97,cssClass:"btn-link text-center",sortable:true,formatter:formatter},
        {id:"hUsuario",field:"hUsuario",name:"Usuario",minWidth:150,sortable:true},
        {id:"hNombre",field:"hNombre",name:"Nombre",minWidth:250,sortable:true},
        {id:"hDepto",field:"hDepto",name:"Departamento",minWidth:210,sortable:true},
        {id:"hReportes",field:"hReportes",name:"Reportes",minWidth:40,cssClass:"text-center",formatter:formatter},
        {id:"hPerfil",field:"hPerfil",name:"Perfil",minWidth:40},
        {id:"hEstado",field:"hEstado",name:"Estado",minWidth:40,formatter:formatter,cssClass:"text-center"},
        {id:"hUnloack",field:"hUnloack",name:" Desbloqueo  ",headerCssClass:"fa fa-unlock",minWidth:100,cssClass:"btn-link text-center",formatter:formatter,cssClass:"text-center"},
        {id:"hFechaAlta",field:"hFechaAlta",name:"Fecha Alta",minWidth:50},
        {id:"hUsuarioA",field:"hUsuarioA",name:"Usuario Alta",minWidth:150},
        {id:"hFechaUM",field:"hFechaUM",name:"Fecha UM",minWidth:50},
        {id:"hUsuarioU",field:"hUsuarioU",name:"Usuario UM",minWidth:150}
    ];

    //Opciones para Mandar a la Tabla
    var options = {
    enableCellNavigation: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        editable: false,
        enableAddRow: true
    };

    data = <?=json_encode($rowData)?>;

// Cargar el Grid con los Datos Devueltos de JSON
grid = new Slick.Grid("#myGrid", data, columns, options);

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

$('#btn_off_sesion').addClass('hidden');
$('#btn_lock_sesion').addClass('hidden');



//MyAlert("Vista cargada correctamente","ok");
</script>
<div id="myGrid" style="height: 75vh;font-size: 12px"></div>