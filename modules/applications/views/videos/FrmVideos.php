<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 09/08/2017
 * Time: 12:35 PM
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

?>

<script>
    $(document).bind("contextmenu",function(e){
       //return false;
    });

    fngnListarVideos(1);

    $("#scNombreEmpleado").focus();

    $('#txtString').on('keyup',function () {

        var largo = $(this).val().length;

        if(largo >= 3){
            fngnListarVideos(2);
            $(this).focus();
        }

    });

    $("#imagenVideo").change(function() {
        var file = $(this)[0].files[0]
        if (file){

            $('#txtImagen').val(file.name);

            var filename = document.getElementById('imagenVideo');
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

    $("#videoLocal").change(function() {
        var file = $(this)[0].files[0]
        if (file){

            $('#btnvideoLocal').val(file.name);

            var filename = document.getElementById('videoLocal');
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

    $('#btnLocal').click(function () {
       $("#typeVideo").val(1);
    });
    $('#btnVimeo').click(function () {
        $("#typeVideo").val(2);
    });

    $('#mdlVideoDemostracion').on('hidden.bs.modal', function (e) {
        // do something...
        document.getElementById('videoDemo').pause();      // Returns the number of seconds the browser has played
        var video = document.getElementById("videoDemo");
        video.currentTime = 0;
    });
</script>
<div id="toolbarVideos" class="row">
    <form action="?" id="searchVideo" onsubmit="fngnListarVideos(2); return false;"  >
        <div class="col-md-11 padding-x5 ">
            <div class="form-group">
                <div class="input-group input-group">
                    <span class="input-group-addon" id="btn_search_contact" onclick="fngnListarVideos(2)" style="cursor: pointer"><span class="glyphicon glyphicon-search"></span></span>
                    <input type="text" id="txtString" class="form-control" placeholder="Buscar Video" />
                </div>

            </div>
        </div>
    </form>
    <div class="col-md-1 padding-x5 ">
        <div class="btn-group">
            <button type="button" class="btn pull-right btn-floating bg-vino dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-gears"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <li><a href="#dat=1" onclick="$('#listaVideos').addClass('hidden');$('#panelNuevoVideo').removeClass('hidden').addClass('animated fadeInUp');$('#txtTitulo').focus();$('#formNuevoVideo').Frmreset()"><i class="fa fa-upload"></i> Subir</a></li>
                <li><a href="#dat=2" onclick="fngnAdministrarVideos(1)"><i class="fa fa-edit"></i> Editar</a></li>
            </div>
        </div>

        <button class="btn btn-floating bg-aqua" onclick="fnsdMenu(40,40)"><i class="fa fa-home"></i></button>
    </div>
</div>

<div  id="listaVideos" style="height: 60vh" class="row row-sm scroll-auto"></div>

<div id="panelNuevoVideo" class="row hidden padding-x5">

    <div class="col-md-12 no-padding">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab"><i class="fa fa-file-video-o"></i> Datos Generales</a></li>
            </ul>

            <div class="tab-content">


                <div class="tab-pane active " id="activity">

                    <form id="formNuevoVideo" enctype="multipart/form-data" method="post" onsubmit="return false;">
                        <div class="row row-md">

                            <div class="col-md-2">
                                <div class="form-group">
                                    Empresa
                                    <select class="form-control" id="txtEmpresa" >
                                        <?php
                                        $connect->_query = "SELECT idEmpresa,Descripcion FROM BGEEmpresas";
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
                                    <input class="form-control" id="txtTitulo" placeholder="Título del video">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    Descripción
                                    <textarea class="form-control" maxlength="100" id="txtDescripcion" placeholder="Descripción del video"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="btn-group btn-toggle">
                                    <button id="btnVimeo" class="btn active btn-sm btn-success">URL</button>
                                    <button id="btnLocal" class="btn btn-sm btn-default">LOCAL</button>
                                    <input id="typeVideo" value="2" class="hidden" readonly />
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div id="vimeo" class="form-group">
                                    Url de Video
                                    <input class="form-control" id="txtUrl" placeholder="Url del video">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div id="local" class="form-group">
                                    Seleccionar Video
                                    <input class="form-control" placeholder="Seleccionar Video Local" id="btnvideoLocal" onclick="$('#videoLocal').click(); " readonly />
                                    <input  class="form-control hidden" id="videoLocal" type="file"  accept="video/*"/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    Imagen
                                    <input class="form-control" id="txtImagen" placeholder="Seleccionar Imagen" onclick="$('#imagenVideo').click(); " readonly />
                                    <input  class="form-control hidden" id="imagenVideo" type="file"  accept="file_extension| ,.gif, .jpg, .png," name="imagenVideo" />
                                </div>
                            </div>


                            <div class="col-md-2">
                                <button class="btn btn-success btn-block" onclick="fngnNuevoVideo(1)" ><i class="fa fa-upload"></i> Subir Video</button>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-default btn-block" onclick="$('#formNuevoVideo').Frmreset()"><i class="fa fa-trash"></i> Limpiar</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>



    </div>

</div>

<div class="row">
    <div class="col-md-12">

        <div class="modal fade" id="mdlVideoDemostracion" data-backdrop="static" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-black text-white no-border">
                        <h4  id="mdlTituloVideo" class="modal-title">Titulo de video</h4>
                    </div>
                    <div class="modal-body hoverable bg-black no-padding">
                        <video id="videoDemo" class="img-responsive" controlsList="nodownload" controls ></video>
                    </div>
                    <div class="modal-footer no-border bg-black">
                        <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
<!--                        <button class="btn btn-info btn-sm pull-right"><i class="fa fa-trash"></i> Desactivar</button>-->
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
