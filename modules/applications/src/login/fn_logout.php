<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/01/2017
 * Time: 08:46 AM
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";


$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);

$seguridad->loginOut($_SESSION['data_login']['NoUsuario']);

session_unset ();
session_destroy ();
session_start();
session_regenerate_id(true);




echo \core\core::returnHome();