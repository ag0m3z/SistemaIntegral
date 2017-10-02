<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 14/02/2017
 * Time: 01:04 PM
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

//include "RUTA/core.php";
//include "RUTA/sesiones.php";
//include "RUTA/seguridad.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */
?>
<script language="JavaScript">
    $(document).ready(function(){
        $('#myModal').modal('toggle');
        $("#myModal").draggable({
            handle: ".modal-header"
        });
    });
</script>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content profile">

            <div class="modal-body no-padding">
                <div class="profile">
                    <img src="modules/01HelpDesk/Adjuntos/pictures/<?=$_POST['fl']?>" title="<?=$_POST['fl']?>"  class="img-responsive" alt="imagen Adjunta: <?=$_POST['fl']?>" />
                </div>
            </div>
            <div class="modal-footer no-padding ">
                <a href="modules/01HelpDesk/Adjuntos/pictures/<?=$_POST['fl']?>" download class="btn btn-success btn-block btn-sm"><i class="fa fa-download"></i> Descargar Imagen </a>
            </div>

        </div>
    </div>
</div>
