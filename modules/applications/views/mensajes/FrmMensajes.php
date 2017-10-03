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

    if(<?=$_POST['opc']?> != 4 ){
        if($(".bellMensajes").text() > 0){
            // Mensajes sin leer
            fnBuscarContactoChat(2);
        }else{
            //Los mensajes mas recientes
            fnBuscarContactoChat(3);
        }
    }else{

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

                        <div style="background: #c8c8cb;">
                            <div class="btn-group">
                                <button type="button" class="btn btn-link btn-sm text-black dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-comment"></i> Mensajes <span class="bellMensajes small badge"></span> </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li onclick="fnBuscarContactoChat(3)"><a href="#"><i class="fa fa-list-ul"></i> Recientes</a></li>
                                    <li onclick="fnBuscarContactoChat(2)"><a href="#"><i class="fa fa-commenting"></i> Sin Leer </a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-link btn-sm text-black dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-users"></i> Grupos <span class="caret"></span></button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#"><i class="fa fa-users"></i> Nuevo</a></li>
                                    <li><a href="#"><i class="fa fa-list-ol"></i> Mis Grupos</a></li>
                                    <li><a href="#"><i class="fa fa-th-list"></i> Todos</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="">
                            <div class="input-group">
                                <input id="searchContact" class="form-control" placeholder="Buscar Contacto....">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" onclick="fnBuscarContactoChat(1)"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </div>

                        <div class="padding-x5 scroll-auto sidebar" style="background:  rgba(214,237,250,0.42);height: 60vh">

                            <div class="hidden">
                                <button onclick="fnBuscarContactoChat(3)" class="btn btn-info btn-xs">Recientes</button>
                            </div>
                            <div id="titulo_lista" class="">
                                Top 10 - Contactos recientes
                            </div>
                            <ul id="lista_search" class="control-sidebar-menu text-white">

                            </ul>

                        </div>

                    </div>
                    <div class="col-md-8 padding-x3"  style="border-left: 1px solid rgba(244,247,250,0.6);">


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
