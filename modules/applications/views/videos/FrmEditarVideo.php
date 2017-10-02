<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/08/2017
 * Time: 09:45 AM
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
unset($_SESSION['IMAGENES']);
unset($_SESSION['VIDEOVIMEO']);

$idVideo = $_POST['idVideo'];
$connect->_query = "
SELECT a.idVideo,a.idEmpresa,a.Titulo,a.Descripcion,a.Url,a.Imagen,b.Descripcion,a.OpcionVideo,a.UrlLocal FROM BGETablaVideos as a  
LEFT JOIN BGEEmpresas as b 
ON a.idEmpresa = b.idEmpresa
WHERE a.idVideo = $idVideo";

$connect->get_result_query();
$dataVideo = $connect->_rows[0];

?>
<script>

    if($('#etypeVideo').val() == 1){
        //Local
        $("#ebtnLocal").removeClass('btn-default').addClass('active btn-success');

    }else{
        //Vimeo
        $("#ebtnVimeo").removeClass('btn-default').addClass('active btn-success');
    }

    $("#evideoLocal").change(function() {
        var file = $(this)[0].files[0]
        if (file){

            $('#ebtnvideoLocal').val(file.name);

            var filename = document.getElementById('evideoLocal');
            var file = filename.files[0];
            var data = new FormData();

            data.append('archivo',file);
            $.ajax({
                url:"modules/applications/src/videos/fnAddVideo.php",
                type:"post",
                contentType:false,
                data:data,
                processData:false,
                cache:false,
                beforeSend:function () {
                    fnloadSpinner(1);
                },
                success:function(data){
                    fnloadSpinner(2);
                }
            });
        }
    });



    $(".btn-toggle button").on('click',function(e){

        $(".btn-toggle button").removeClass('active btn-success').addClass('btn-default');

        $(this).removeClass('btn-default').addClass('btn-success active');

    });

    $('#ebtnLocal').click(function () {

        $("#etypeVideo").val(1);

    });
    $('#ebtnVimeo').click(function () {
        $("#etypeVideo").val(2);
    });

    $("#eimagenVideo").change(function() {
        var file = $(this)[0].files[0]
        if (file){

            $('#etxtImagen').val(file.name);

            var filename = document.getElementById('eimagenVideo');
            var file = filename.files[0];
            var data = new FormData();

            data.append('archivo',file);
            $.ajax({
                url:"modules/applications/src/videos/fnAddImagen.php",
                type:"post",
                contentType:false,
                data:data,
                processData:false,
                cache:false,
                success:function(data){
                }
            });



        }
    });
</script>
<div class="col-md-12 no-padding">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#activity" data-toggle="tab"><i class="fa fa-file-video-o"></i> Datos Generales</a></li>
        </ul>

        <div class="tab-content">


            <div class="tab-pane active " id="activity">

                <form id="formEditarVideo" enctype="multipart/form-data" method="post" onsubmit="return false;">
                    <div class="row row-md">

                        <div class="col-md-2">
                            <div class="form-group">
                                Empresa
                                <select class="form-control" id="etxtEmpresa" >
                                    <option value="<?=$dataVideo[1]?>"><?=$dataVideo[6]?></option>
                                    <?php
                                    $connect->_query = "SELECT idEmpresa,Descripcion FROM BGEEmpresas WHERE idEmpresa <> $dataVideo[1]";
                                    $connect->get_result_query();
                                    for($i=0;$i < count($connect->_rows);$i++){
                                        echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-10">
                            <div class="form-group">
                                Titulo
                                <input class="form-control" value="<?=$dataVideo[2]?>" id="etxtTitulo" placeholder="Título del video">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                Descripción
                                <textarea class="form-control" maxlength="100" id="etxtDescripcion" placeholder="Descripción del video"><?=$dataVideo[3]?></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="btn-group btn-toggle">
                                <button id="ebtnVimeo" class="btn btn-sm btn-default">URL</button>
                                <button id="ebtnLocal" class="btn btn-sm btn-default">LOCAL</button>
                                <input id="etypeVideo" value="<?=$dataVideo[7]?>" class="hidden" readonly />
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                Url de Video
                                <input class="form-control" value="<?=$dataVideo[4]?>" id="etxtUrl" placeholder="Url del video">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div id="elocal" class="form-group">
                                Seleccionar Video
                                <input class="form-control" placeholder="Seleccionar Video Local" value="<?=$dataVideo[8]?>" id="ebtnvideoLocal" onclick="$('#evideoLocal').click();" readonly />
                                <input  class="form-control hidden" id="evideoLocal" type="file"  accept="video/*" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                Imagen
                                <input class="form-control" value="<?=$dataVideo[5]?>" id="etxtImagen" onclick="$('#eimagenVideo').click(); " readonly />
                                <input  class="form-control hidden" id="eimagenVideo" type="file"  accept="file_extension| ,.gif, .jpg, .png," name="imagenVideo" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success btn-block" onclick="fngnEditarVideos(2,<?=$dataVideo[0]?>)" ><i class="fa fa-save"></i> Guardar</button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn hidden btn-default btn-block"><i class="fa fa-trash"></i> Limpiar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

