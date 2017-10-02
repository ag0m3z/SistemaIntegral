<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 14/02/2017
 * Time: 05:20 PM
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
include "../../../../core/model_empleados.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$Empleado = new \core\model_empleados($_SESSION['data_login']['BDDatos']);

$Empleado->valida_session_id($_SESSION['data_login']['NoUsuario']);


$CadenaSearch =$Empleado->get_sanatiza( $_POST['cadena']);

//Buscar el empleado en frm de buscada
if(trim($CadenaSearch) == ""){
    echo "<script>MyAlert('El Campo no debe estar vacio','alert');";
}else{
    $Result = $Empleado->buscar_empleado(1,$CadenaSearch);
}



?>
<script language="JavaScript">
    function formatter(row, cell, value, columnDef, dataContext) {
        return value;
    }

    var grid;
    //declaracion de columnas

    var columns = [
        {id:"rFila00",name:"Empleado",field:"rFila00", width: 80,minWidth:40,formatter: formatter,cssClass:"text-center"},
        {id:"rFila01",name:"Departamento",field:"rFila01", width: 150,minWidth:40,formatter:formatter},
        {id:"rFila02",name:"Nombre Completo",field:"rFila02", width: 170,minWidth:40,formatter:formatter},
        {id:"rFila03",name:"Estatus",field:"rFila03", width: 190,minWidth:40,formatter:formatter},
        {id:"rFila04",name:"Fecha Alta",field:"rFila04", width: 210,minWidth:40,formatter:formatter},
        {id:"rFila05",name:"Fecha UM",field:"rFila05", width: 210,minWidth:40,formatter:formatter}
    ];

    //Opciones para Mandar a la Tabla
    var options = {
        enableCellNavigation: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        editable: true,
        enableAddRow: true
    };

    var data = <?php echo json_encode($Result);?>;
    // Cargar el Grid con los Datos Devueltos de JSON
    grid = new Slick.Grid("#myGrid", data, columns, options);


</script>
<div id="myGrid" style="height: 75vh;font-size: 12px"></div>
