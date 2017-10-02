<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/02/2017
 * Time: 08:59 AM
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
include "../../../../core/model_equipos.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$Equipos = new \core\model_equipos($_SESSION['data_login']['BDDatos']);

$Equipos->valida_session_id($_SESSION['data_login']['NoUsuario']);



if($_POST['opt'] == 1){
    $WindowsTitle = "Agregar Archivos";
    $Parametro = $_POST['opt'] + 2;
}elseif($_POST['opt'] == 2){
    $WindowsTitle = "Agregar Imagen";
    $Parametro = $_POST['opt'] + 2;
}

?>
<script language="JavaScript">

    $('#myModal').modal('toggle');
    $("#myModal").draggable({
        handle: ".modal-header"
    });
</script>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?=$WindowsTitle?> </h4>
            </div>
            <div class="modal-body">
                <from enctype="multipart/form-data" id="formuploadajax" method="post">
                    <input type="file" id="selectedFile" name="selectedFile">
                    <input type="submit" id="btnsend" value="Subir archivos" style="display: none;" />
                </from>
                <div id="imgLoad"></div>
                <div id="uploadfile"></div>
            </div>
            <div class="modal-footer" style="text-align: left;">
                <button class="btn btn-primary btn-sm" onclick="$('#btnsend').click(fnsd_uploadAjax(<?=$_POST['fl']?>,null,null,<?=$Parametro?>));"  >Guardar</button>
                <button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
