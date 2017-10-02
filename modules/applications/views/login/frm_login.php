<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 24/01/2017
 * Time: 12:33 PM
 */


include 'core/core.php';

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Last-Modified" content="0">
<meta http-equiv="expires" content="0">
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<meta http-equiv="Pragma" content="no-cache">
<title>Sistema Integral </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="site_design/img/icons/favicon.ico" type="image/x-icon" />
<link rel="icon" href="site_design/img/icons/favicon.ico" type="image/x-icon" />
<!-- Tell the browser to be responsive to screen width -->
<!-- Hojas de Estilos -->
<?php
    \core\core::includeCSS('plugins/bootstrap/css/bootstrap.css');
    \core\core::includeCSS('plugins/fonts/font-awesome/css/font-awesome.min.css');
    \core\core::includeCSS('plugins/bootstrap/css/bootstrap.css');
    \core\core::includeCSS('site_design/css/skins/_all-skins.css');
    \core\core::includeCSS('site_design/css/',true);

?>

</head>
<body class="full">

<!-- Inicio de la cabecera principal -->
<nav class="navbar navbar-inverse navbar-fixed-top" style="box-shadow: 5px 4px 4px #4e0000;font-family: 'TIMES NEW ROMAN';font-size:44px !important;font-weight:bold;background:#793240;opacity: 0.9;filter: alpha(opacity=50)" role="navigation">
    <div class="container">
        <div class="navbar-header">
            &nbsp;<a class="navbar-brand text-white text-bold" style="font-size: 41px;text-shadow: 2px 3px 3px #aaa;margin-left: 10px; ; margin-top: 34px;position: absolute" href="#">Sistema Integral</a>
        </div>
    </div>
</nav>
<!-- Fin de la Cabecera principal -->

<!-- Inicio del contendor principal para el inicio de sesionn -->
<div class="contenedor">
    <div class="row">
        <div class="container animated zoomIn">
            <div class="row" style="margin-top: -65px">
                <div class="col-sm-6 col-md-4">
                    <div class="text-center form-group">
                        <img style="opacity: 0.7;filter: alpha(opacity=50)"  src="site_design/img/logos/pexpress_01.png"/>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="row">
                <form name="formlogIn01" action="#" onsubmit="loginIn(); return false;" method="post">
                    <div class="col-sm-6 col-md-4">

                        <div class="form-group has-feedback">
                            <label class="text-left text-white">Usuario</label>
                            <input id="luser" type="text" placeholder="Nombre de Usuario" class="input-login ">
                            <span class="form-control-feedback"><i class="fa fa-user"></i></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label class="text-left text-white">Contraseña</label>
                            <input id="lpass" type="password" placeholder="Contraseña" class="input-login ">
                            <span class="form-control-feedback"><i class="fa fa-lock"></i></span>
                        </div>
                        <div id="imgLoad"></div>
                        <div id="divresult"></div>
                        <br>
                        <div class="form-group" >
                            <button type="submit" data-loading-text="Loading..." autocomplete="off" id="btn_login" class="btn bg-vino  col-md-5 text-white btn-sm" value="Iniciar Sesion" ><i class="fa fa-sign-in"></i> Iniciar Sesi&oacute;n</button>
                            <span class="col-md-2"></span>
                            <a href="#" id="btn_recovery" class="btn btn-default animated bounce col-md-5 btn-sm" onclick="$('#mdl_recovery').modal('show')" ><i class="fa fa-lock"></i> Recuperar Clave</a>
                        </div>

                        <div id="alert_mayus" class=" hidden">
                            <hr style="height: 21px; !important;" >
                            <span  class="callout callout-danger btn-block text-white" >Mayusculas Activadas</span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4"></div>
                    <div class="col-sm-4 col-md-4"></div>
                </form>
            </div>
        </div>
    </div>
<!-- Fin del Contendor principal para el inicio de sesion -->

<!-- Inicio d emodal para recuperar la contraseña  -->
    <div class="modal fade" id="mdl_recovery" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> Recuperar contraseña</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>Nombre</label>
                        <input class="form-control" placeholder="Ingresa tu nombre">
                    </div>
                    <div class="form-group">
                        <label>Departamento</label>
                        <select class="form-control">
                            <option value="0">-- --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input placeholder="Número telefónico " class="form-control">
                    </div>

                    <blockquote>
                        <span style="display: block;font-size: 80%;line-height: 1.42857143;color: #595959;">
                            Ingrese los datos solicitados y en un momento le enviaremos un correo con la información solicitada
                            <br><br>
                            Para más información puede comunicarse al departamento de Sistemas al siguiente número telefónico (81) 1946-3600 Ext. 711
                        </span>
                    </blockquote>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm" ><i class="fa fa-send"></i> Enviar</button>
                    <button class="btn btn-danger btn-sm" data-dismiss="modal" ><i class="fa fa-close"></i> Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fin del modal para recuperar contraseña -->

<!-- Pie de Pagina -->
<div class="navbar navbar-default navbar-fixed-bottom" style="box-shadow: 5px 4px 4px #4e0000;opacity: 0.25;filter: alpha(opacity=40)" >
    <div class="container">
        <p class="navbar-text text-red text-bold pull-left">© 2017 Pr&eacute;stamo Express - Sistema Integral</p>
    </div>
</div>
<!-- Fin del Pie de Pagina -->

<?php
    \core\core::includeJS('plugins/jquery/jQuery-2.2.0.min.js');
    \core\core::includeJS('plugins/jquery/jQuery-ui.js');
    \core\core::includeJS('plugins/bootstrap/js/bootstrap.js');
    \core\core::includeJS('plugins/bootstrap/js/bootbox.min.js');
    \core\core::includeJS('site_design/js/js_login.js');
?>

<script language="JavaScript">

    $("#luser").focus();

</script>

</body>
</html>