<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 03/03/2017
 * Time: 10:28 AM
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

$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


$fecha = new DateTime();

$NoDepartamento = $_POST['udpto'];
$idEmpleado = $_POST['uempleado'];
$Perfil = $_POST['idPerfil'];
$NombreDePila = $connect->get_sanatiza($_POST['unombre']);
$UsuarioLogin = $connect->get_sanatiza($_POST['ulogin']);
$PassLogin = md5($_POST['uclave']);
$Reportes = "SI";
$NoEstado = $_POST['uestado'];
$NoUsuarioAlta = $_SESSION['data_login']['NoUsuario'];
$FechaActual = date("Ymd");
$HoraActual = date("H:i:s");

$BBDDatos = $_POST['BDDatos'];

if($connect->getExistsEmpleado($idEmpleado)){
    \core\core::MyAlert("EL empleado ya cuenta con un usuario asociado","error");
}

if($connect->getExistsUsuario($UsuarioLogin)){
    \core\core::MyAlert("EL usuario ya existe en la base de datos","error");
}else {

    $connect->_query = "CALL sp_registra_usuario
                        (
                        '$idEmpleado',
                        '$NoDepartamento',
                        '$Perfil',
                        '$NombreDePila',
                        '$UsuarioLogin',
                        '$PassLogin',
                        '$PassLogin',
                        '$BBDDatos',
                        '$Reportes',
                        '$NoEstado',
                        '$NoUsuarioAlta',
                        '$FechaActual',
                        '$HoraActual',
                        @e_NoUsuario
                        )";

    $connect->execute_query();


    $connect->_query = "SELECT @e_NoUsuario as _NoUser";
    $connect->get_result_query();

    if (!count($connect->_rows) > 0) {
        //Si No Regresa el id Arroja el Siguiente Error.
        \core\core::MyAlert("Fallo la obtencion: (@N_NumFolio)", "error");
    } else {

        $fila = $connect->_rows[0];
        $NewUser = $fila['_NoUser'];

        $array = json_decode($_POST['ids']);
        for ($i = 0; $i < count($array); $i++) {

            $lista = $array[$i]->value;

            list(
                $idModulo, $idSeccion, $idOpcion, $sopcion
                ) = explode(
                "-", $lista
            );
            $NombreModulo = $array[$i]->name;
            $cantidad = $i;

            switch ($sopcion) {
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

            set_error_handler(array($connect, "appendError"));

            $connect->_query = "UPDATE 00GNMenuAccesos
                    SET $sopcion =  1,
                        NoUsuarioAlta = " . $NoUsuarioAlta . ",
                        FechaAlta = '" . $fecha->format('Y-m-d H:i:s') . "',
                        NoUsuarioUM = " . $NoUsuarioAlta . ",
                        FechaUM = '" . $fecha->format('Y-m-d H:i:s') . "'
                    WHERE
                        NoUsuario = " . $NewUser . " AND idModulo =  " . $idModulo . " AND idSeccion =  " . $idSeccion . " AND idOpcion =  '" . $idOpcion . " ' ";

            try {

                $connect->execute_query();

            } catch (Exception $e) {

                echo "Error: " . $e->getMessage() . "<br>\n";
                var_dump($e->getTrace());
            }
        }
    }


    echo "<script>fnCatListarUsuarios(6)</script>";
}