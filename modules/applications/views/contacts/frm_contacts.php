<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/01/2017
 * Time: 03:56 PM
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

?>
<div class="row">
    <form method="post" action="#" onsubmit="jsgnSearchContact(); return false;"  >
        <div class="col-md-11 padding-x5 ">
            <div class="form-group">
                <div class="input-group input-group">
                    <span class="input-group-addon" id="btn_search_contact" onclick="jsgnSearchContact()" style="cursor: pointer"><span class="glyphicon glyphicon-search"></span></span>
                    <input type="text" id="scNombreEmpleado" class="form-control" placeholder="Buscar Contacto: Nombre, Apellidos, Departamento" />
                </div>
            </div>
        </div>
        <div class="col-md-1 padding-x5 ">
            <select onchange="jsgnSearchContact()" class="form-control" id="searchLimit">
                <option value="9">1-9</option>
                <option value="50">1-50</option>
                <option selected value="100">1-100</option>
                <option value="150">1-150</option>
                <option value="200">1-200</option>
                <option value="99">Todos</option>
            </select>
        </div>
    </form>
</div>
<div id="imgLoad"></div>
<div id="searchContent" class="scroll-auto" style="height: 80vh">
    <h2 class="text-center" style="color: #8e9297;" >Buscar Contacto</h2>
</div>
<div id="edit_empleado"></div>


<script language="JavaScript">
    init_botons();
    confirm_close = true;
    $("#scNombreEmpleado").focus();
</script>
