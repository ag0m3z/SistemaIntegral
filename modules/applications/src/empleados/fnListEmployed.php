<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 05:00 PM
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
include "../../../../core/model_empleados.php";

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

$connect = new \core\model_empleados($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


if($_POST['opcion']== 99){

    //Buscar empleado en el catalogo


    //Quitar espacios de cadena y sanatizar campos
    $txtString = $_POST['cadena'];
    $FechaAlta = $connect->get_sanatiza($connect->getFormatFecha($_POST['efalta'],1));
    $FechaUM = $connect->get_sanatiza($connect->getFormatFecha($_POST['efum'],1));
    $NoEstado = $_POST['enoestado'];
    $NoDepartamento = $connect->get_sanatiza($_POST['enodpto']);
    $NoUsuarioAlta = $connect->get_sanatiza($_POST['euseralta']);
    $NoUsuarioUM = $connect->get_sanatiza($_POST['euserum']);

// si la cadena es vacia el valor es: 0
    if(trim($txtString) == ""){$txtString = 0;}else{
        $txtString = $connect->get_sanatiza("%".$_POST['cadena']."%");
    }
    if(trim($FechaAlta) == ""){$FechaAlta = 0;}
    if(trim($FechaUM) == ""){$FechaUM = 0;}



    $where = array(
        'txtString'=>$txtString,
        'a.NoEstado'=>$NoEstado,
        'a.NoDepartamento'=>$NoDepartamento,
        'a.NoUsuarioAlta'=>$NoUsuarioAlta,
        'a.NoUsuarioUM'=>$NoUsuarioUM,
        'a.FechaAlta'=>$FechaAlta,
        'a.FechaUM'=>$FechaUM
    );



    if($txtString != '0'){
        // traer resultado en arreglo
        $data = $connect->Constructor_Where($where,'array');

        $data2 =  explode("=",$data[0]);
        $data2[1] = substr($data2[1],0,-5);
        $rowData = $connect->listar_empleados(8,$data2[1]);
    }else{
        $data = $connect->Constructor_Where($where,'cadena');
        $rowData = $connect->listar_empleados(9,$data);
    }


}else{

    $rowData = $connect->listar_empleados($_REQUEST['opcion']);

}

?>
<script language="JavaScript">
    function formatter(row,cell,value,columnDef,dataContext){
        return value;
    }
    var grid;

    //declaracion de Columnas
    var columns = [
        {id:"hIdEmpleado",field:"hIdEmpleado",name:"idEmpleado",minWidth:97,cssClass:"btn-link text-center",formatter:formatter,sortable:true},
        {id:"hNoEmpleado",field:"hNoEmpleado",name:"No Empleado",minWidth:97,sortable:true},
        {id:"hNombre",field:"hNombre",name:"Nombre",minWidth:210,sortable:true},
        {id:"hDepto",field:"hDepto",name:"Departamento",minWidth:200,sortable:true},
        {id:"hEstado",field:"hEstado",name:"Estado",minWidth:50,cssClass:"text-center",formatter:formatter},
        {id:"hCorreo",field:"hCorreo",name:"Correo",minWidth:250},
        {id:"hTelefono1",field:"hTelefono1",name:"Telefono",minWidth:100},
        {id:"hTelefono2",field:"hTelefono2",name:"Telefono 2",minWidth:100},
        {id:"hTelefono3",field:"hTelefono3",name:"Telefono 3",minWidth:100},
        {id:"hFechaA",field:"hFechaA",name:"FechaAlta",minWidth:100},
        {id:"hNoUsuarioAlta",field:"hNoUsuarioAlta",name:"Usuario Alta",minWidth:100},
        {id:"hFechaU",field:"hFechaU",name:"FechaUM",minWidth:100},
        {id:"hNoUsuarioUM",field:"hNoUsuarioUM",name:"Usuario UM",minWidth:100}
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

    $('[data-dismiss]').click();

    //    MyAlert("Vista cargada correctamente","ok");
</script>
<div id="myGrid" style="height: 75vh;font-size: 12px"></div>
