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
include "../../../../core/sqlconnect.php";

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
$sqlConnect = new \core\sqlconnect();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


$sqlConnect->_sqlQuery =
    "SELECT 
	a.idCodigo,
	a.Descripcion,
	b.NombreCategoria,
	c.Descripcion,
	d.Descripcion,
	a.Estatus,
	a.idCategoria
FROM SAyT.dbo.INVProductos as a 
	LEFT JOIN BDSPSAYT.dbo.BPFCatalogoCategoriasPrestamo as b
		ON a.idCategoria = b.NoCategoria 
	LEFT JOIN BDSPAPARATOS.dbo.BAPCatalogoTiposAparatos as c 
		ON a.idTipo = c.NoTipoAparato AND a.idCategoria = c.NoCategoria 
	LEFT JOIN BDSPAPARATOS.dbo.BAPCatalogoMarcas as d 
		ON a.idMarca = d.NoMarca AND a.idCategoria = d.NoCategoria
WHERE 
	a.Estatus = 1";
$sqlConnect->get_result_query();


for($i=0;$i < count($sqlConnect->_sqlRows);$i++){

    $Estatus = $connect->getFormatoEstatus($sqlConnect->_sqlRows[$i][5]);


    $rowData[] = array(
        'tidCodigo'=>'<a href="#" onclick="fn_editar_producto_web(1,\''.$sqlConnect->_sqlRows[$i][0].'\',\''.$sqlConnect->_sqlRows[$i][6].'\')" onclick="false"><span class="text text-primary">'.$sqlConnect->_sqlRows[$i][0].'</span></a>',
        'tidCategoria'=>$sqlConnect->_sqlRows[$i][2],
        'tidTipo'=>$sqlConnect->_sqlRows[$i][3],
        'tidMarca'=>$sqlConnect->_sqlRows[$i][4],
        'tDescripcion'=>$sqlConnect->_sqlRows[$i][1],
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
        {id:"tidCodigo",name:"Codigo",field:"tidCodigo",width:70,cssClass: "text-center  btn-link", formatter: formatter},
        {id: "tDescripcion", name: "Descripcion", field: "tDescripcion", minWidth: 320},
        {id: "tidCategoria", name: "Categoria", field: "tidCategoria", width: 120,sortable:true},
        {id: "tidMarca", name: "Marca", field: "tidMarca",width: 100,sortable:true},
        {id: "tidTipo", name: "Tipo", field: "tidTipo", width: 120,sortable:true},
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
