<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 01/02/2017
 * Time: 12:41 PM
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



/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */
?>
<div class="panel panel-info">
    <div class="panel-heading padding-x3"><i class="fa fa-cloud"></i> Lista de Aplicaciones</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <a href="modules/applications/storage/apps/BBVABancomer.exe" class="btn btn-default btn-apps" style="text-align: center" data-toggle="tooltip" data-placement="bottom" title="BBVA para Terminales">
                    <img  style="text-align: center" src="site_design/img/icons/apps/BBVABancomer.png" width="60" class="img-responsive" />
                </a>
                <a href="modules/applications/storage/apps/WesternUnion.exe" class="btn btn-default btn-apps" style="text-align: center" data-toggle="tooltip" data-placement="bottom" title="Western Union">
                    <img  style="text-align: center" src="site_design/img/icons/apps/WU.png" width="60" class="img-responsive" />
                </a>
                <a href="modules/applications/storage/apps/SistemaIntegral.exe" class="btn btn-default btn-apps" style="text-align: center" data-toggle="tooltip" data-placement="bottom" title="Sistema Integral">
                    <img  style="text-align: center" src="site_design/img/icons/apps/SI.ico" width="60" class="img-responsive" />
                </a>
                <!-- Aplicacion Web cic Bancomer Reportes -->
                <a href="modules/applications/storage/apps/Web CIC Reportes.exe" class="btn btn-default btn-apps" style="text-align: center" data-toggle="tooltip" data-placement="bottom" title="Web CIC Reportes">
                    <img  style="text-align: center" src="site_design/img/icons/apps/logo04.png" width="60" class="img-responsive" />
                </a>
                <!-- Aplicacion Web cic Bancomer Administrador -->
                <a href="modules/applications/storage/apps/Web CIC Admin.exe" class="btn btn-default btn-apps" style="text-align: center" data-toggle="tooltip" data-placement="bottom" title="Web CIC Administrador">
                    <img  style="text-align: center" src="site_design/img/icons/apps/logo04.png" width="60" class="img-responsive" />
                </a>
                <!-- -->
            </div>
        </div>
    </div>
</div>
