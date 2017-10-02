<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 29/06/2017
 * Time: 09:59 AM
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
include "../../../../core/model_tickets.php";
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

$connect = new \core\model_tickets($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
$Fecha = date("d/m/Y");

if($connect->getValidarFirma(array('folio'=>base64_decode($_POST['fl']),'anio'=>base64_decode($_POST['an']),'NoDepartamento'=>base64_decode($_POST['nodpto'])))){

    $Hidden = 'hidden';
    $Disabled = 'disabled';
    $Nombre = $connect->_rows[0][4];
    $Fecha = $connect->_rows[0][6];

}else{

    if($connect->_rows[0][2] > 0){
        //Existe Imagen
        $Hidden = 'hidden';
        $Nombre = $connect->_rows[0][4];

    }else{
        //No existe Imagen

    }
}

?>
<link rel="stylesheet" href="plugins/jsSignature/css/signature-pad.css">


<script>
    setOpenModal("mdlFirmaTicket");

    $('input').on('keypress',function () {
        $(this).removeClass('has-error');
    });

    $("canvas").on('touchstart',function mouseMoveDetector(){
        $("#fehca_firma").focus();
        $("#nombre_firma").attr('readonly',true);
    });

        /*var once = false;
        window.addEventListener('touchstart', function mouseMoveDetector(){
            if (!once) {
                once = true;
                // Do what you need for touch-screens only
                $("#fehca_firma").focus();
                $("#nombre_firma").attr('readonly',true);
            }
        });*/



</script>
<div class="modal bounceInUp animated" id="mdlFirmaTicket" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"><span class="fa fa-pencil-square-o"></span> Firma </h4>
            </div>
            <div class="modal-body" style="margin-bottom: -5px;">

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            Nombre
                            <input type="text" <?=$Disabled?> id="nombre_firma" value="<?=$Nombre?>" ondblclick="$('#nombre_firma').attr('readonly', function (_, attr) { return !attr });;"   class="form-control input-sm" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            Fecha
                            <input id="fehca_firma" disabled value="<?=$Fecha?>" type="text" class="form-control input-sm" />
                        </div>
                    </div>
                </div>
                
                
                <div class="row">
                    <div class="col-md-12">
                        <br>
                        <img class="img-responsive" src="<?=$connect->_rows[0]['imagen']?>">
                    </div>
                </div>


                <br>
                <div class="row <?=$Hidden?>">
                    <div class="col-md-12">
                        <div id="signature-pad"  style="height: 190px;" >
                            <div class="m-signature-pad--body" style="height: 200px;margin-top: -20px;border: 1px solid #cbcbcb">
                                <span class="text-gray">Firma</span>
                                <canvas style="width: 100%;height: 85%"></canvas>
                            </div>

                        </div>
                    </div>
                </div>

                <br><br>
                <blockquote class="hidden">
                    <textarea disabled id="code_imagen" class="form-control"></textarea>
                </blockquote>


            </div>
            <div class="modal-footer" style="text-align: left;margin-top: -2px;">
                <button class="btn <?=$Hidden?> btn-primary btn-sm" type="button" onclick="fn01FirmaReportes('<?=$_POST['fl']?>','<?=$_POST['an']?>','<?=$_POST['nodpto']?>')"  ><i class="fa fa-save"></i> Guardar</button>
                <button type="button" class="btn <?=$Hidden?> btn-default button clear btn-sm" onclick="signaturePad.clear()" ><i class="fa fa-trash"></i> Limpiar</button>
                <button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script src="plugins/jsSignature/js/signature_pad.js"></script>
<script src="plugins/jsSignature/js/app.js"></script>
