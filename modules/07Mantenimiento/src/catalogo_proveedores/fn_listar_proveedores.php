<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 26/05/2017
 * Time: 05:54 PM
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
include "../../controller/ControllerCatalogoProveedores.php";

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

$connect = new ControllerCatalogoProveedores($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$rowData = $connect->get_list_proveedores($_POST['idestatus'],$_POST['opc'],$_POST['textSearch']);

if(count($rowData) > 0){
    for($i = 0 ; $i < count($rowData);$i++){

        $idProveedor = $rowData[$i][0];
        $Estatus = $connect->getFormatoEstatus($rowData[$i][10]);

        $_SESSION['EXPORT'] = $rowData;

        $dataView[] = array(
            "hidProveedor"=>"<a href='#'><span class='text-primary' onclick='fn07EditarProveedor(1,$idProveedor)' >".$connect->getFormatFolio($rowData[$i][0],4)."</span></a>",
            "hNombreProveedor"=>$rowData[$i][1],
            "hNombreContacto"=>$rowData[$i][2],
            "hCorreo"=>$rowData[$i][15],
            "hTelefono01"=>$rowData[$i][4],
            "hTelefono02"=>$rowData[$i][5],
            "hCelular"=>$rowData[$i][6],
            "hExt"=>$rowData[$i][7],
            "hColonia"=>$rowData[$i][9],
            "hCalleNumero"=>$rowData[$i][8],
            "hUsuarioAlta"=>$rowData[$i][11],
            "hFechaAlta"=>$rowData[$i][12],
            "hUsuarioUM"=>$rowData[$i][13],
            "hFechaUM"=>$rowData[$i][14],
            "hidestatus"=>$Estatus
        ) ;
    }
}else{
    $dataView = array();
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
        {id:"hidProveedor",name:"# Proveedor",field:"hidProveedor",width:100,cssClass: "text-center text-info  btn-link", formatter: formatter},
        {id: "hNombreProveedor", name: "Nombre", field: "hNombreProveedor", width: 130,sortable:true, formatter: formatter},
        {id: "hNombreContacto", name: "Contacto", field: "hNombreContacto",width: 150},
        {id: "hCorreo", name: "Correo", field: "hCorreo", width: 180,minWidth:40},
        {id: "hTelefono01", name: "Telefono", field: "hTelefono01", width: 120},
        {id: "hTelefono02", name: "Telefono 2", field: "hTelefono02", width: 130},
        {id:"hCelular",name:"Celular",field:"hCelular",width:100},
        {id:"hExt",name:"Extencion",field:"hExt",width:85,cssClass: "text-center"},
        {id:"hCalleNumero",name:"Calle y Numero",field:"hCalleNumero",sortable:true,minWidth:200,formatter:formatter},
        {id:"hColonia",name:"Colonia",field:"hColonia",width:200},
        {id:"hidestatus",name:"Estado",field:"hidestatus",width:80,formatter: formatter},
        {id:"hUsuarioAlta",name:"Usuario Alta",field:"hUsuarioAlta",sortable:true,minWidth:130,cssClass: "text-center",formatter:formatter},
        {id:"hFechaAlta",name:"Fecha Alta",field:"hFechaAlta",sortable:true,minWidth:100,cssClass: "text-center",formatter:formatter},
        {id:"hUsuarioUM",name:"Usuario UM",field:"hUsuarioUM",sortable:true,minWidth:130,cssClass: "text-center",formatter:formatter},
        {id:"hFechaUM",name:"Fecha UM",field:"hFechaUM",sortable:true,minWidth:100,cssClass: "text-center",formatter:formatter}

    ];
    //Opciones para Mandar a la Tabla
    var options = {
        enableCellNavigation: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        editable: true,
        enableAddRow: true
    };
    data =<?=json_encode($dataView)?>;
    // Cargar el Grid con los Datos Devueltos de JSON
    grid = new Slick.Grid("#myGrid", data, columns, options);

    $(".badge").html("<?=count($rowData)?>");

</script>
<div id="myGrid" style="height: 75vh;font-size: 12px"></div>
