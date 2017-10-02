<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 03/03/2017
 * Time: 12:25 PM
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
include "../../../../core/model_usuarios.php";

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


$connect = new \core\model_usuarios($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$fecha = new DateTime();

$NoUsuario = $_POST['usuario'];
$NoDepartamento = $_POST['udpto'];
$idEmpleado = $_POST['uempleado'];
$Perfil = $_POST['idPerfil'];
$NombreDePila = $connect->get_sanatiza($_POST['unombre']);
$UsuarioLogin = $connect->get_sanatiza($_POST['ulogin']);

$NewPassLogin = $connect->get_sanatiza($_POST['uclave']);
$OldPassLogin = $connect->get_sanatiza($_POST['uclaveOld']);
$BDDatos = $_POST['BDDatos'];

$Reportes = "SI";
$NoEstado = $_POST['uestado'];
$NoUsuarioAlta = $_SESSION['data_login']['NoUsuario'];
$FechaActual = date("Ymd");
$HoraActual = date("H:i:s");

if($NewPassLogin != $OldPassLogin){
    $PassLogin = md5($NewPassLogin);
}else{
    $PassLogin = $OldPassLogin;
}

$connect->_query = "CALL sp_editar_usuario
        (
            '$NoUsuario',
            '$idEmpleado',
            '$NoDepartamento',
            '$Perfil',
            '$NombreDePila',
            '$UsuarioLogin',
            '$PassLogin',
            '$PassLogin',
            '$BDDatos',
            '$Reportes',
            '$NoEstado',
            '$NoUsuarioAlta',
            '$FechaActual',
            '$HoraActual',
            '1'
        )";
$connect->execute_query();

$array = json_decode($_POST['ids']);

for( $i = 0; $i < count( $array ); $i++ ){

    $lista = $array[$i]->value;

    list(
        $idModulo,$idSeccion,$idOpcion,$sopcion
        ) = explode (
        "-",$lista
    );
    $NombreModulo = $array[$i]->name;
    $cantidad = $i;

    switch($sopcion){
        case "A":
            $sopcion = "OpcionAlta";
            break;
        case "B":
            $sopcion = "OpcionBaja";
            break;
        case "C":
            $sopcion = "OpcionCambio";
            break;
        case "V":
            $sopcion = "OpcionVista";
            break;
        case "R":
            $sopcion = "OpcionReportes";
            break;
    }

    set_error_handler(array($connect,"appendError"));

    $connect->_query = "UPDATE 00GNMenuAccesos
            SET $sopcion =  1,
                NoUsuarioAlta = ".$NoUsuarioAlta.",
                FechaAlta = '".$fecha->format('Y-m-d H:i:s')."',
                NoUsuarioUM = ".$NoUsuarioAlta.",
                FechaUM = '".$fecha->format('Y-m-d H:i:s')."'
            WHERE
                NoUsuario = ". $NoUsuario. " AND idModulo =  ". $idModulo." AND idSeccion =  ". $idSeccion." AND idOpcion =  '".$idOpcion." '" ;

    try{

        $connect->execute_query();


    }catch (Exception $e){
        echo "Error: " . $e->getMessage()."<br>\n";
        var_dump($e->getTrace());
    }
}


echo "<script>fnCatListarUsuarios(2)</script>";






