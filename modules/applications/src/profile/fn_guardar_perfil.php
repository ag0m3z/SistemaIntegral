<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 01/02/2017
 * Time: 12:10 PM
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
include "../../../../core/seguridad.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$NoUsuario = $_SESSION['data_login']['NoUsuario'];
$BDDatos = $_SESSION['data_login']['BDDatos'];

$seguridad = new \core\seguridad($BDDatos);

//validar sesion iniciada
$seguridad->valida_session_id($NoUsuario);

$FechaActual = date("Ymd");
$HoraActual = date("H:i:s");

$NoUsuarioUM = $_SESSION['data_login']['NoUsuario'];
$idEmpleado = $_POST['idEmpleado'];

$NoUsuario = $_POST['nousers'];
$UsuarioLogin = $_SESSION['data_login']['NoUsuario'];

$NoDepartamentoLogin = $_SESSION['data_departamento']['NoDepartamento'];
$NoDepartamento = $_POST['nosucursal'];
$NombreDepartamento = $_POST['namesuc'];

$Nombre = ucwords(strtolower($_POST['nameenca']));
$apPaterno = ucwords(strtolower($_POST['ap01']));
$apMaterno = ucwords(strtolower($_POST['ap02']));
$NombreCorto = ucwords(strtolower($_POST['nameshort']));

$email = strtolower($_POST['email']);
$telefono1 = $_POST['telefono1'];
$telefono2 = $_POST['telefono2'];
$celular = $_POST['cel'];
$direccion = ucwords(strtolower($_POST['direcion']));

//Sanatizar Datos para Evitar Injeccion SQL
$Nombre = $seguridad->get_sanatiza($Nombre);
$apPaterno = $seguridad->get_sanatiza($apPaterno);
$apMaterno = $seguridad->get_sanatiza($apMaterno);
$NombreCorto = $seguridad->get_sanatiza($NombreCorto);
$email = $seguridad->get_sanatiza($email);
$telefono1 = $seguridad->get_sanatiza($telefono1);
$telefono2 = $seguridad->get_sanatiza($telefono2);
$celular = $seguridad->get_sanatiza($celular);
$direccion = $seguridad->get_sanatiza($direccion);

$NewPass = $_POST['pass01'];
$LastPass = $_POST['pass02'];

if($NewPass == $LastPass){
    $Password = $LastPass;
}else{
    $Password = md5($NewPass);
}

if(trim($Nombre) == ""){
    echo "<script>MyAlert('Ingrese su Nombre','alert');</script>";

}else if(trim($apPaterno) ==""){
    echo "<script>MyAlert('Ingrese su apellido paterno','alert');</script>";
}else if(trim($apMaterno) == ""){
    echo "<script>MyAlert('Ingrese su apellido materno','alert');</script>";
}else{

    $seguridad->_query = "CALL SINTEGRALGNL.sp_actualiza_perfil('$idEmpleado','$NoDepartamentoLogin','$NombreDepartamento','$Nombre','$NombreCorto','$email','$telefono1','$telefono2',
        '$celular','$direccion','$FechaActual','$HoraActual','$NoUsuarioUM','$apMaterno','$apPaterno','$UsuarioLogin','$Password')";

    $seguridad->execute_query();

    echo "<script>MyAlert('Cambios guardados correctamente','ok');</script>";
}