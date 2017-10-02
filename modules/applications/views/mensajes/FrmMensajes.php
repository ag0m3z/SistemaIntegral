<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 27/09/2017
 * Time: 01:04 PM
 */
include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/mensajeria.php";

$connect = new \core\mensajeria($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsMensajeria.js" language="JavaScript" ></script>
<script>
    setOpenModal("mdlMensajeria");

    if($(".bellMensajes").text() > 0){
        fnBuscarContactoChat(2);
    }else{
        fnBuscarContactoChat(3);
    }

    $("#searchContact").on('keyup',function(e){
        if (e.keyCode == 13) {
            // Do something
            //alert(123);
            fnBuscarContactoChat(1);
        }
    });

    $("#name_contact").text('Mensajes - Express');

    $("#searchContact").on('focus',function(){
        $("#searchContact").select();
    });

</script>
<div id="mdlMensajeria" class="modal fade" >
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header " >
                <button type="button" class="close " onclick='$("#codigousuario").text("");' data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                <h4 class="modal-title">
                    <i class="fa fa-commenting "></i> <span id="name_contact">Mensajes - Express</span>
                </h4>
            </div>
            <div id="collapse-body-chat" class="modal-body no-padding"  style="height:34.43vmax;" >

                <div class="row hidden">
                    <div class="col-md-12 ">
                       <div class="bg-light-blue-gradient wrapper">
                           Alejandro
                       </div>
                    </div>
                </div>

                <div class="row row-sm">
                    <div class="col-md-4 ">

                        <div class=" hidden toolbars">
                            <button class="btn btn-link btn-xs"><i class="fa fa-gears"></i></button>
                            <button class="btn btn-link btn-xs"><i class="fa fa-user-md"></i></button>
                        </div>
                        <div class="">
                            <div class="input-group">
                                <input id="searchContact" class="form-control" placeholder="Buscar Contacto....">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" onclick="fnBuscarContactoChat(1)"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </div>

                        <div class="padding-x5 scroll-auto sidebar" style="background:  rgba(214,237,250,0.42);height: 64vh">

                            <div>
                                <button onclick="fnBuscarContactoChat(3)" class="btn btn-info btn-xs">Recientes</button>
                                <button onclick="fnBuscarContactoChat(2)" class="btn btn-warning btn-xs">Sin leer <span class="badge bellMensajes"></span> </button>
                            </div>
                            <div id="titulo_lista" class="">
                                Top 10 - Contactos recientes
                            </div>
                            <ul id="lista_search" class="control-sidebar-menu text-white">

                            </ul>

                        </div>

                    </div>
                    <div class="col-md-8 padding-x3"  style="border-left: 1px solid rgba(230,230,230,0.98);">


                        <div id="chatHome">
                            <div id ="headChat"></div>
                            <p class="text-center" style="margin-top: 15vh"><i class="fa fa-comment-o text-gray text-bold text-center" style="font-size: 15em !important;"></i></p>



                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
