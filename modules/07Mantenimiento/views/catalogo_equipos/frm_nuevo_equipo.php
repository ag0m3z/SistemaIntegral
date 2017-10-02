<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 08/06/2017
 * Time: 04:30 PM
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
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

?>
<script>
</script>
<div class="row">
    <div class="col-md-7">

        <div class="col-md-12">
            <div class="form-group">
                Nombre del equipo
                <input id="nombre" class="form-control input-sm" placeholder="Nombre del equipo" />
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                Descripción del equipo
                <textarea id="descripcion" class="form-control input-sm" placeholder="Descripción del equipo" ></textarea>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                Proveedor de Equipo
                <select id="idproveedor" class="form-control input-sm">
                    <option value="0">-- Sin Proveedor --</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                Tipo de Equipo
                <select id="idtipo_equipo" class="form-control input-sm">
                    <option value="0">-- Seleccionar Tipo de Equipo --</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                Marca
                <select id="idmarca" class="form-control input-sm">
                    <option value="0">-- Seleccionar Marca del Equipo --</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                Modelo
                <select id="idmodelo" class="form-control input-sm">
                    <option value="0">-- Seleccionar Marca del Equipo --</option>
                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                Serie del equipo<i class="text-red">*</i>
                <input id="serie" class="form-control input-sm" placeholder="Nombre del equipo" />
            </div>
        </div>

    </div>
</div>

