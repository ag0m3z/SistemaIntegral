<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 28/03/2017
 * Time: 11:01 AM
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
include "../../../../core/seguridad.php";
include "../productos_web/ClassController.php";

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

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

$sqlConnect = new ClassController();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


$sqlConnect->get_list($_POST['opc']);
$lista = $sqlConnect->_sqlRows;
for($i=0;$i < count($lista);$i++){

    if($lista[$i][5] == 1){
        $Estatus = $connect->getFormatoEstatus(1);
    }else{
        $Estatus = $connect->getFormatoEstatus(2);
    }

    $rowData[] = array(
        'idCaracteristica'=>'<a href="#" onclick="fn_caracteristicas_producto_web(3,1)"><span class="text text-primary">'.$connect->getFormatFolio($lista[$i][0],4).'</span></a>',
        'NombreCategoria'=>$lista[$i][2],
        'Descripcion'=>$lista[$i][3],
        'tEstatus'=>$Estatus
    );
}


?>
<script language="JavaScript">
    //funcion para formatos de celdas
    function formatter(row,cel,value,columDef,dataContext){
        return value;
    }

    var grid;

    //Declaracion de Columnas
    // class Bg: bg-light-blue-gradient
    var columns = [
        {id:"idCaracteristica",name:"Codigo",field:"idCaracteristica",width:70,cssClass: "text-center  btn-link", formatter: formatter},
        {id: "NombreCategoria", name: "Categoria", field: "NombreCategoria", width: 120,sortable:true},
        {id: "Descripcion", name: "Descripcion", field: "Descripcion", minWidth: 320},
        {id: "tEstatus", name: "Estatus", field: "tEstatus", minWidth: 70,cssClass:"text-center",formatter: formatter}
    ];

    //Opciones para Mandar a la Tabla
    var options = {
        enableCellNavigation: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        editable: false,
        autoEdit:true,
        enableAddRow: true
    };
    data =<?=json_encode($rowData)?>;

    // Cargar el Grid con los Datos Devueltos de JSON


    $(document).ready(function () {

        grid = new Slick.Grid($("#myGrid"), data, columns, options);



    });

</script>
<div id="myGrid" style="height: 76vh;font-size: 12px;"></div>
