<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 09/02/2017
 * Time: 09:23 AM
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

$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);

//validar sesion activa
$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**
 * Funcion para editar la Sucursales y Departamentos
 *
 */

if($_POST['tipo_cambio']){


    switch ($_POST['tipo_cambio']){
        case 1:
            // Opcion para editar datos de la sucursal desde el formulario de nuevo ticket

            $NoDepartamento = $_POST['nosucursal'];
            $NombreDepartamento = $seguridad->get_sanatiza($_POST['namesuc']);
            $Encargado = $_POST['nameenca'];
            $CorreoDepartamento = $seguridad->get_sanatiza($_POST['email']);
            $Telefono01Dpto = $seguridad->get_sanatiza($_POST['telefono1']);
            $Telefono02Dpto = $seguridad->get_sanatiza($_POST['telefono2']);
            $Telefono03Dpto = $seguridad->get_sanatiza($_POST['cel']);
            $DireccionDepto = $seguridad->get_sanatiza($_POST['direcion']);

            $FechaActual = date("Ymd");
            $HoraActual = date("H:i:s");
            $NoUsuarioUM = $_SESSION['data_login']['NoUsuario'];

            $seguridad->_query = "UPDATE BGECatalogoDepartamentos
                                  SET Descripcion = '$NombreDepartamento',
                                      Encargado = '$Encargado',
                                      Correo = '$CorreoDepartamento',
                                      Telefono01 = '$Telefono01Dpto',
                                      Telefono02 = '$Telefono02Dpto',
                                      Telefono03 = '$Telefono03Dpto',
                                      Domicilio = '$DireccionDepto',
                                      FechaUM = '$FechaActual',
                                      HoraUM = '$HoraActual',
                                      NoUsuarioUM = $NoUsuarioUM
                                WHERE NoDepartamento = '$NoDepartamento' ";

            $seguridad->execute_query();

            echo "<script>fnsdticketinfouser(2);MyAlert('Datos guardados Correctamente','OK');</script>";


            break;
        case 2:
            break;
    }



}