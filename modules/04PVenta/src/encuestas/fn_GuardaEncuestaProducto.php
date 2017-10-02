<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 01:01 PM
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



/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

//Datos del Servidor Hora,Año y Fecha
$FechaActual = date("Ymd");
$HoraActual  = date("H:i:s");
$AnioActual = date("Y");

$Sql = "SELECT a.Clasificacion02,a.Clasificacion03,a.Descripcion,a.Clasificacion04,b.Numero1N,b.Numero2N,a.ImporteVenta
FROM BOPCatalogoProductos as a
LEFT JOIN BGECatalogoGeneral as b
ON a.Clasificacion04 = b.Descripcion AND b.CodCatalogo = 7 AND b.NoEstatus = 1
WHERE a.CodigoProducto = '".$_POST['codprod']."' ";

//Recuperando Datos Enviados de la Encuesta en Ajax

$CodProducto = $_POST['codprod']; //No Producto

$OpcNoAtendido = $_POST['noatendida']; // Opcion de Radio para los Clientes no atendidos
$TpoServicio = $_POST['tpoServicio']; //  Tipo de Servicio Ejem: Empeño, Venta O Servicios de Pagos
$Condiciones = $_POST['condiciones']; //  Tipo de Condicion de la Prenda en la que viene.
$MontoSolicita = $_POST['mtosolicita']; //Monto de $ que Solicita el Cliente
$Competencia = $_POST['competencia']; //  Empresa de Competidor que fue el cliente
$MontoCompetencia = $_POST['mtocompetidor']; // Monto que el Competidor le Ofrece
$Observacion = $_POST['obsrv']; //Campo de Texto para Las Observaciones
$Descripcion = $_POST['descprod']; // Breve descripcion del Producto
$CategoriaServicio = $_POST['catServ']; // No de Categoria Segun el Servicio Ejem: Aparato,Oro,Plata o Servicios
$NoCategoria = $CategoriaServicio;
$CalidadMetal = $_POST['calidadmetal'];

if($CodProducto <> 0){
    //Si el Producto esta dentro del catalogo
    $connect->_query = $Sql;
    $connect->get_result_query();
    $dat = $connect->_rows[0];

    $TpoProducto = $dat[0];
    $NoMarca = $dat[1];
    $Descripcion = $dat[2];
    $Clasificacion = $dat[3];
    $PorcentajeEmpeno = $dat[4];
    $PorcentajeCompra = $dat[5];
    $ImporteVenta = $dat[6];
    $FormatImporte = array("$",",");
    $NoCategoria = $CategoriaServicio;
    $MontoSolicita = str_replace($FormatImporte,"",$MontoSolicita);
    $MontoCompetencia = str_replace($FormatImporte,"",$MontoCompetencia);
}else{
    $CodProducto = "";
    $FormatImporte = array("$",",");

    $MontoSolicita = str_replace($FormatImporte,"",$MontoSolicita);
    $MontoCompetencia = str_replace($FormatImporte,"",$MontoCompetencia);


    $TpoProducto = 0;
    $NoMarca = 0;
    $Descripcion = $_POST['descprod'];
    $Clasificacion = "";
    $PorcentajeEmpeno = 0;
    $PorcentajeCompra = 0;
    $ImporteVenta = 0;

    if($TpoServicio == 3){
        $NoCategoria = 0;
        $Descripcion = $_POST['obsrv'];
    }
}

if($CalidadMetal >= 1){
    $Descripcion = $_POST['obsrv'];
}else{
	$CalidadMetal = 0 ;
}
//Recuperando Datos de Session del Usuario.

$NoUsuario = $_SESSION['data_login']['NoUsuario'];
$NombreCorto = $_SESSION['data_login']['NombreDePila'];
$NombreCompleto = $_SESSION['data_login']['NombreCompleto'];
$NoSucursal = $_SESSION['data_departamento']['NoDepartamento'];
$NoZona = $_SESSION['data_departamento']['NoZona'];
$NoSupervisor = $_SESSION['data_departamento']['NoSupervisor'];




$CallSpRegistraEncuesta = "CALL sp_BGE_RegistraEncuesta('$AnioActual','$NoSucursal','$NoZona','$NoSupervisor','$CodProducto','$NoCategoria','$NoUsuario','$NombreCompleto',
'$NombreCorto','$TpoProducto','$NoMarca','$Descripcion','$Clasificacion','$PorcentajeEmpeno','$PorcentajeCompra','$ImporteVenta','$OpcNoAtendido',
'$TpoServicio','$Condiciones','$MontoSolicita','$Competencia','$MontoCompetencia','$FechaActual','$HoraActual','$Observacion','$CalidadMetal','$CategoriaServicio')";
// $CallSpRegistraEncuesta;

$connect->_query = $CallSpRegistraEncuesta;
$connect->execute_query();

echo "<script language='JavaScript'>MyAlert('Encuesta registrada correctamente.','ok');$('#modalbtnclose').click();</script>";
