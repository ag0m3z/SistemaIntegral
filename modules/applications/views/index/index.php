<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 24/01/2017
 * Time: 12:31 PM
 */

include "core/core.php";
include "core/seguridad.php";

$sessiones = new \core\sesiones();

$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);

$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);

?>
<!DOCTYPE html>
<html>
<head>
    <?php include "modules/applications/layout/master_page/meta_links.inc"; ?>
</head>
<body class="skin-vino fixed sidebar-mini" >

<div id="HomeWrapper" class="wrapper">

    <?php include "modules/applications/layout/master_page/cabecera.inc" ;?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <?php//var_dump($_SESSION)?>
        <section  id="HomeContent" class="content">
            <!-- /.content-wrapper -->

        </section>
        <br/>
    </div>

    <div id="sendMail"></div>
    <div id="gnloading"></div>
    <div id="idgeneral"></div>
    <div id="idgeneral2"></div>
    <div id="chat"></div>
    <div id="imgchat"></div>
    <?php include "modules/applications/layout/master_page/pie_de_pagina.inc" ;  ?>
    <?php include "modules/applications/layout/master_page/menu_right.inc" ;?>

    <div class="control-sidebar-bg"></div>

</div><!-- END WRAPPER -->
<?php include "modules/applications/layout/master_page/js_scripts.inc";?>
<script language="JavaScript">
    $(document).ready(function(){

        $( ".datepicker" ).datepicker();$(".select2").select2();
        $.fn.Frmreset = function () {$(this).each (function() { this.reset(); });/*$("input:text:visible:first").focus();*/ }
        $('button').addClass('waves-effect');
        $("input[type=text]").focus(function(){this.select();});
        $("#myModal").draggable({handle: ".modal-header"});
        jsgn_cargar_tablero();

        window.onClose = function(e) {
            return 'Texto de aviso';
        };
        getlongPollingChat();
        //window.onbeforeunload = function (e) {jsgn_close_system(<?=$_SESSION['data_login']['NoUsuario']?>);};

    });
    jQuery.noConflict();
</script>
</body>
</html>
