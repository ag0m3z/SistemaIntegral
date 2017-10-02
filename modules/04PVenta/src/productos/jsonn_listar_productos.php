<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 21/02/2017
 * Time: 11:32 AM
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
include "../../../../core/model_aparatos.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$connect = new \core\model_aparatos($_SESSION['data_login']['BDDatos']);

//validar session del usuario y tiempo de conexion
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

//Traer lista de aparatos y guardarlos en arreglo
$rowData = $connect->listar_aparatos(0,'DESC',0,25,null);


if($_SESSION['menu_opciones'][5][1][1][0]['OpcionC'] == 1){
    $bolean = "true";
}else{
    $bolean = "false" ;
}

var_dump($rowData);