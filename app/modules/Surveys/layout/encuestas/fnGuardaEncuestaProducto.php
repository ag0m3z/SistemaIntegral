<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 14/10/15
 * Time: 03:41 PM
 */
include "../../../../controller/genContenido.class.php";
$connect = new \controller\Contenido();
//Validar que el usuario este logueado
if(!$connect->ValidaAcceso()){$connect->returnHomePage();}

//validar tiempo de actividad
$connect->ValidaSession_id();

//Datos del Servidor Hora,Año y Fecha
$FechaActual = $connect->getFechaAndHora(1);
$HoraActual  = $connect->getFechaAndHora(2);
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
    $q = $connect->Consulta($Sql);
    $dat = mysqli_fetch_array($q);
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
}
//Recuperando Datos de Session del Usuario.

$NoUsuario = $_SESSION['NoUsuario'];
$NombreCorto = $_SESSION['NombreDePila'];
$NombreCompleto = $_SESSION ['NombreCompleto'];
$NoSucursal = $_SESSION['NoDepartamento'];
$NoZona = $_SESSION['NoZona'];
$NoSupervisor = $_SESSION['NoSupervisor'];




$CallSpRegistraEncuesta = "CALL sp_BGE_RegistraEncuesta('$AnioActual','$NoSucursal','$NoZona','$NoSupervisor','$CodProducto','$NoCategoria','$NoUsuario','$NombreCompleto',
'$NombreCorto','$TpoProducto','$NoMarca','$Descripcion','$Clasificacion','$PorcentajeEmpeno','$PorcentajeCompra','$ImporteVenta','$OpcNoAtendido',
'$TpoServicio','$Condiciones','$MontoSolicita','$Competencia','$MontoCompetencia','$FechaActual','$HoraActual','$Observacion','$CalidadMetal','$CategoriaServicio')";
// $CallSpRegistraEncuesta;
if($connect->Consulta($CallSpRegistraEncuesta)){
    echo "<script language='JavaScript'>MyAlert('Encuesta registrada correctamente.','ok');$('#modalbtnclose').click();</script>";
}else{
    echo "<script language='JavaScript'>MyAlert('Error al realizar el registro de la Encuesta.','error');</script> ";
}

