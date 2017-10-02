<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/03/2017
 * Time: 12:26 PM
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
include "../../../../core/model_departamentos.php";

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

$connect = new \core\model_departamentos($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


$condicion = null ;

if($_POST['opcion'] == 8 ){

    if($_POST['nombre']){

        $condicion = " a.NoDepartamento LIKE '%".$_POST['nombre']."%' OR a.Descripcion LIKE '%".$_POST['nombre']."%' AND " ;

    }else{ $condicion = "" ;}

    $array = array(
        "a.NoEstado"=>$_POST['estado'],
        "a.NoUsuarioAlta"=>$_POST['useralta'],
        "a.NoUsuarioUM"=>$_POST['userum'],
        "a.NoDepartamento"=>$_POST['nodepto']
    );

    $condicion.= $connect->Constructor_Where($array,"cadena");
}

$rowData = $connect->myGrid_departamentos($_POST['opcion'],true,$condicion);
//var_dump($rowData);
?>
<script language="JavaScript">
    function formatter(row,cell,value,columnDef,dataContext){
        return value;
    }
    var grid;

    //declaracion de Columnas
    var columns = [
        {id:"jNoDepartamento",field:"jNoDepartamento",name:"NoDepartamento",minWidth:105,cssClass:"btn-link text-center",formatter:formatter,sortable:true},
        {id:"jNombreDepartamento",field:"jNombreDepartamento",name:"Nombre",minWidth:190,sortable:true},
        {id:"jEmpresa",field:"jEmpresa",name:"Empresa",minWidth:150,sortable:true},
        {id:"jTipo",field:"jTipo",name:"Tipo",minWidth:20,cssClass:"text-center",sortable:true},
        {id:"jReportes",field:"jReportes",name:"Reportes",cssClass:"text-center",minWidth:35,sortable:true},
        {id:"jCorreo",field:"jCorreo",name:"Correo",minWidth:250},
        {id:"jEstado",field:"jEstado",name:"Estado",minWidth:50,sortable:true,cssClass:"text-center",formatter:formatter},
        {id:"jDomicilio",field:"jDomicilio",name:"Domicilio",minWidth:350,formatter:formatter},
        {id:"jTelefono1",field:"jTelefono1",name:"Telefono",minWidth:100},
        {id:"jTelefono2",field:"jTelefono1",name:"Telefono",minWidth:100},
        {id:"jTelefono3",field:"jTelefono3",name:"Telefono",minWidth:100},
        {id:"jTelefono4",field:"jTelefono4",name:"Telefono",minWidth:100},

        {id:"jNoZona",field:"jNoZona",name:"Zona",minWidth:145,sortable:true},
        {id:"jSupervisor",field:"jSupervisor",name:"Supervisor",sortable:true,minWidth:120},
        {id:"jEncargado",field:"jEncargado",name:"Encargado (a)",minWidth:150,sortable:true},

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
