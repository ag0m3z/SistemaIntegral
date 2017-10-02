<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 04:47 PM
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
include "../../../../core/seguridad.php";

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

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);


/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
?>
<script type="text/javascript" src="<?=\core\core::ROOT_APP?>site_design/js/jsCatalogosGnl.js"></script>

<div id="contentCat">
    <a class="btn btn-default btn-app" onclick="fnCatSeleccionarCatalogo(1,'?ok')" data-toggle="tooltip" data-placement="top" title="Catálogo de Empleados">
        <i class="fa fa-user"></i>Empleados
    </a>

    <a class="btn btn-default btn-app" onclick="fnCatSeleccionarCatalogo(2,'?ok=1')" data-toggle="tooltip" data-placement="top" title="Catálogo de Usuarios">
        <i class="fa fa-user-secret"></i>Usuarios
    </a>

    <a class="btn btn-default btn-app" onclick="fnCatSeleccionarCatalogo(3)" data-toggle="tooltip" data-placement="top" title="Catálogo de Departamentos">
        <i class="fa fa-home"></i>Departamentos
    </a>

    <a class="btn btn-default btn-app" onclick="fnCatSeleccionarCatalogo(5)" data-toggle="tooltip" data-placement="top" title="Catálogo Puestos">
        <i class="fa fa-folder-open"></i>Puestos
    </a>
<!--
    <a class="btn btn-default btn-app" onclick="fnCatSeleccionarCatalogo(4)" data-toggle="tooltip" data-placement="top" title="Catálogo Menu Principal">
        <i class="fa fa-desktop"></i>Menu Principal
    </a>

    <a class="btn btn-default btn-app" data-toggle="tooltip" data-placement="top" title="Parametros del Sistema">
        <i class="fa fa-gears"></i>Parametros
    </a>

    <a class="btn btn-default btn-app" data-toggle="tooltip" data-placement="top" title="Configuraci&oacute;n de Notificaciones">
        <i class="fa fa-bell"></i>Notificaciones
    </a>
    <a class="btn btn-default btn-app" data-toggle="tooltip" data-placement="top" title="Configuraci&oacute;n de Alertas">
        <i class="fa fa-flag"></i>Alertas
    </a>
    <a class="btn btn-default btn-app" data-toggle="tooltip" data-placement="top" title="Configuraci&oacute;n de Correos">
        <i class="fa fa-envelope"></i>Correos
    </a>-->
</div>
