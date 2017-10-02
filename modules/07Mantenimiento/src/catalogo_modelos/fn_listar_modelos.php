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
include "../../controller/ControllerCatalogoModelos.php";

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

$connect = new ControllerCatalogoModelos($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$rowData = $connect->get_list_modelos($_POST['idestatus'],$_POST['opc'],$_POST['textSearch']);

if(count($rowData) > 0){
    for($i = 0 ; $i < count($rowData);$i++){

        $idTabla = $rowData[$i][0];
        $idUbicacion = $rowData[$i][1];
        $Estatus = $connect->getFormatoEstatus($rowData[$i][4]);


        $_SESSION['EXPORT'] = $rowData;

        $dataView[] = array(
            "hidUbicacion"=>"<a href='#'><span class='text-primary' onclick='fn07EditarModelo(1,$idUbicacion)' >".$connect->getFormatFolio($idUbicacion,4)."</span></a>",
            "hNombre"=>$rowData[$i][2],
            "hDescripcion"=>$rowData[$i][3],
            "hidestatus"=>$Estatus,
            "hUsuarioAlta"=>$rowData[$i][6],
            "hFechaAlta"=>$rowData[$i][7],
            "hUsuarioUM"=>$rowData[$i][9],
            "hFechaUM"=>$rowData[$i][10]
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
        {id:"hidUbicacion",name:"#",field:"hidUbicacion",width:100,cssClass: "text-center text-info  btn-link", formatter: formatter},
        {id: "hNombre", name: "Nombre", field: "hNombre", width: 130,sortable:true, formatter: formatter},
        {id: "hDescripcion", name: "Descripción", field: "hDescripcion",width: 200},
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
