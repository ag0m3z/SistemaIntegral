<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 21/06/2017
 * Time: 11:00 AM
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
include "../../../../core/model_equipos.php";

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

$connect = new \core\model_equipos($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

header("Content-type:application/json; chartset-utf8");

if(
    array_key_exists('NoEmpleado',$_POST) &&
    array_key_exists('Puesto',$_POST) &&
    array_key_exists('NoDepartamento',$_POST) &&
    array_key_exists('FechaRegistro',$_POST) &&
    array_key_exists('Folio',$_POST) &&
    !empty($_POST['NoEmpleado']) &&
    !empty($_POST['Puesto']) &&
    !empty($_POST['NoDepartamento']) &&
    !empty($_POST['FechaRegistro']) &&
    !empty($_POST['Folio'])
){

    //Convertir la Fecha

    $Fecha = $connect->getFormatFecha($_POST['FechaRegistro'],1);
    //Sanatizar Datos
    $_POST = $connect->get_sanatiza($_POST);


    $connect->set_equipos(
        array(
            "folio"=>$_POST['Folio'],
            "opcion"=>2,
            "nombre_empleado"=>$_POST['NombreEmpleado'],
            "departamento_empleado"=>$_POST['NoDepartamento'],
            "puesto_empleado"=>$_POST['Puesto'],
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
            "motivo_asignacion"=>"",
            "fecha_registro"=>$Fecha,
            "estatus"=>4
        )
    );

    if($connect->_confirm){

        $connect->_query = "SELECT @@identity AS id";
        $connect->get_result_query();
        $Folio = $connect->_rows[0][0];

        echo json_encode(
            array(
                "result"=>"ok",
                "mensaje"=>"Reasignacion de Equipo correctamente",
                "data"=>array(
                    "Folio"=>$Folio
                )
            )
        );

    }else{
        echo json_encode(
            array(
                "result"=>"error",
                "mensaje"=>$connect->_message,
                "data"=>array(
                )
            )
        );

    }




}else{
    echo json_encode(array("result"=>"error","mensaje"=>"no se encontraron las campos para el registro"));
}


