<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 15/02/2017
 * Time: 05:48 PM
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

$Equipos->valida_session_id(
    $_SESSION['data_login']['NoUsuario']
);

//Validar que venga el Folio de Asignacion de Equipo

if($_POST['fl']){

    //Sanatizar Datos
    $NombreEmpleado = $Equipos->get_sanatiza($_POST['nombrecompleto']);
    $NoDepartamento = $_POST['dpto'];
    $PuestoEmpleado = $Equipos->get_sanatiza($_POST['puestou']);
    $idEquipo = $_POST['equipou'];
    $Marca = $Equipos->get_sanatiza($_POST['marcau']);
    $Modelo = $Equipos->get_sanatiza($_POST['modelou']);
    $Procesador = $Equipos->get_sanatiza($_POST['procesadoru']);
    $Memoria = $Equipos->get_sanatiza($_POST['memoriau']);
    $Disco = $Equipos->get_sanatiza($_POST['discou']);
    $Caracteristicas = $Equipos->get_sanatiza($_POST['caracteristicasu']);
    $Codigo = $Equipos->get_sanatiza($_POST['codigou']);
    $SerieCedis = $Equipos->get_sanatiza($_POST['serieu']);
    $SerieEquipo = $Equipos->get_sanatiza($_POST['serieequipou']);
    $Motivo = $Equipos->get_sanatiza($_POST['motivou']);
    $Fecha = $_POST['fecha'];
    $NuevoEstado = $_POST['newestado'];
    $Estado = $_POST['newestado'];
    $Condicion_entrega = $Equipos->get_sanatiza($_POST['condicionEntregau']);
    $MotivoEntrega = $Equipos->get_sanatiza($_POST['motivoentregau']);
    $Folio = $_POST['fl'];

    //Guardar datos en arreglo

    $datos_equipo = array(
        "nombre_empleado"=>$NombreEmpleado,
        "departamento_empleado"=>$NoDepartamento,
        "puesto_empleado"=>$PuestoEmpleado,
        "id_equipo"=>$idEquipo,
        "nombre_marca"=>$Marca,
        "nombre_modelo"=>$Modelo,
        "nombre_procesador"=>$Procesador,
        "nombre_memoria"=>$Memoria,
        "nombre_disco"=>$Disco,
        "caracteristicas"=>$Caracteristicas,
        "codigo_cedis"=>$Codigo,
        "serie_cedis"=>$SerieCedis,
        "serie_equipo"=>$SerieEquipo,
        "motivo_asignacion"=>$Motivo,
        "fecha_asignacion"=>$Fecha,
        "estatus"=>$Estado,
        "estatus_actual"=>$NuevoEstado,
        "motivo_entrega"=>$MotivoEntrega,
        "condiciones_entrega"=>$Condicion_entrega,
        "folio_equipo"=>$Folio
    );

    $Equipos->editar_equipo($datos_equipo);

    if($Equipos->_confirm){

        // Todo correcto
        echo '<script language="javascript">';
        echo 'MyAlert("Cambios Guardados","alert");fnsdMostrarListaEquipos(1,null);</script>';


    }else{
        \core\core::MyAlert($Equipos->_message,"error");
    }


}else{

    \core\core::MyAlert("No se encontro el folio de asignación del equipo","error");

}