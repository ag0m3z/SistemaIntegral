<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 15/02/2017
 * Time: 04:52 PM
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
include "../../../../core/model_equipos.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$Equipos = new \core\model_equipos($_SESSION['data_login']['BDDatos']);

$Equipos->valida_session_id($_SESSION['data_login']['NoUsuario']);

//Sanatizar Datos

$NombreEmpleado = $Equipos->get_sanatiza($_POST['nombrecompleto']);
$DepartamentoEmpleado = $_POST['dpto'];
$PuestoEmpleado = $Equipos->get_sanatiza($_POST['puestou']);
$idEquipo = $_POST['equipou'];
$Marca = $Equipos->get_sanatiza($_POST['marcau']);
$Modelo = $Equipos->get_sanatiza($_POST['modelou']);
$Procesador = $Equipos->get_sanatiza($_POST['procesadoru']);
$Memoria = $Equipos->get_sanatiza($_POST['memoriau']);
$DiscoDuro = $Equipos->get_sanatiza($_POST['discou']);
$Caracteristicas = $Equipos->get_sanatiza($_POST['caracteristicasu']);
$Codigo = $Equipos->get_sanatiza($_POST['codigou']);
$SerieCedis = $Equipos->get_sanatiza($_POST['serieu']);
$SerieEquipo = $Equipos->get_sanatiza($_POST['serieequipou']);
$Motivo = $Equipos->get_sanatiza($_POST['motivou']);
$FechaRegistro = $_POST['fecha_registro'];



//Opcion 1 Registro de nuevo Equipo
//Opcion 2 Reasignacion de Equipo Existente

$datos_equipo = array(
    "folio"=>0,
    "opcion"=>1,
    "nombre_empleado"=>$NombreEmpleado,
    "departamento_empleado"=>$DepartamentoEmpleado,
    "puesto_empleado"=>$PuestoEmpleado,
    "id_equipo"=>$idEquipo,
    "nombre_marca"=>$Marca,
    "nombre_modelo"=>$Modelo,
    "nombre_procesador"=>$Procesador,
    "nombre_memoria"=>$Memoria,
    "nombre_disco"=>$DiscoDuro,
    "caracteristicas"=>$Caracteristicas,
    "codigo_cedis"=>$Codigo,
    "serie_cedis"=>$SerieCedis,
    "serie_equipo"=>$SerieEquipo,
    "motivo_asignacion"=>$Motivo,
    "fecha_registro"=>$FechaRegistro,
    "estatus"=>4
);

$Equipos->set_equipos($datos_equipo);


if($Equipos->_confirm){
    echo '<script language="javascript">fnsdMostrarListaEquipos(1,null);</script>';

}else{
    \core\core::MyAlert($Equipos->_message,"error");
}



