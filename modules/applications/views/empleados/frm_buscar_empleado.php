<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 14/02/2017
 * Time: 05:15 PM
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
include "../../../../core/model_empleados.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

?>
<script language="JavaScript">

    $('#myModal').modal({backdrop: 'static', keyboard: false})
    $("#myModal").draggable({
        handle: ".modal-header"
    });
    $("#textSearch").focus();
    $("input[type=text]").focus(function(){
        this.select();
    });

</script>
<div class="modal-header">
    <h1 class="modal-title">
        <span class="fa fa-search"></span> Busqueda de Empleado
    </h1>
</div>
<div class="modal-body">
    <div class="row">
        <form name="formlogIn" action="#?" onsubmit="jsgnBuscarEmpleado(1); return false;" method="post">
            <div class="col-md-10">
                <input type="text" id="textSearch" placeholder="Buscar Empleado por: No Empleado, Nombre, Apellidos" class="formInput">
            </div>
            <div class="col-md-2">
                <label ondblclick="$('#opcEstado').prop('checked', false);"><input id="opcEstado" checked type="radio"  name="opcEstado" > Solo Activos </label>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="myGrid" style="margin-top: 3px;height: 35vh;font-size: 12.55px;"></div>
        </div>
    </div>
</div>
<div class="modal-footer" style="text-align: left;">
    <button class="btn btn-primary btn-sm" onclick="jsgnBuscarEmpleado(1)" id="btnSave"><span id="spinner" class="fa fa-search "></span> Buscar</button>
    <button id="modalbtnclose" class="btn btn-danger btn-sm"  data-dismiss="modal"><span class="fa fa-close"></span> Cerrar</button>
</div>
