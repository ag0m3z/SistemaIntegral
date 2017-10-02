<?php
include "../../../../core/core.php";

$_REQUEST['init_time'] = base64_decode($_REQUEST['init_time']);
$_REQUEST['time'] = base64_decode($_REQUEST['time']);


switch ($_REQUEST['type']){
    case 'issetuser':
        $Mensaje = "El Numero de Usuario esta vacio";
        break;
    case 'iddiferent':
        $Mensaje = "El id de sesion no es igual";
        break;
    case 'timer':
        $Mensaje = "Desconexion por tiempo de inactividad";
        break;
    case 'keyexists':
        $Mensaje = "No se encontro la llave principal";
        break;
    case 5:
        $Mensaje = "Desconexion por tiempo de inactividad";
        break;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Tiempo de Espera Agotado o Sesión Desconectada</title>
    <link type="text/css" rel="stylesheet" href="../../../../site_design/css/cssLogin.css"/>
    <link type="text/css" rel="stylesheet" href="../../../../plugins/bootstrap/css/bootstrap.css"/>
    <link type="text/css" rel="stylesheet" href="../../../../plugins/bootstrap/css/bootstrap-theme.css"/>

</head>
<body >
<div id="alert">
    <div class="head">Sistema Integral</div>
    <div class="container-fluid">
        <div class="row hidden">
            <div class="col-md-2"></div>
            <div class="col-md-2">
                <h1>Error 513</h1>
                <p class="blockquote">Tiempo de Espera Agotado o Sesión Desconectada</p>
            </div>
            <div class="col-md-4">
                <h1>&nbsp;</h1>
                <p>error de conexi&oacute;n: <?=date("Y-m-d H:i:s")?></p>
            </div>

        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <img src="../../../../site_design/img/pages/SesionExpirada_express.png" class="img-responsive" style="text-align: center !important;"/>
            </div>
            <div class="col-md-2"></div>
        </div>
        <div class="row text-center">
            <br>
            <button class="btn btn-success" onclick="location.href = '<?=\core\core::ROOT_APP?>';"> Iniciar Sesion</button>
            <button class="btn btn-info" id="btn_detalles" > Detalles</button>
        </div>
        <br>

        <div id="detallesd" class="row hidden">
            <div class="col-md-4"></div>
            <div class="col-md-4 text-center" style="background: #f1f1f1;padding: 2px;border: 1px solid #a8a8a8;border-radius: 4px;box-shadow: #ccc 2px 6px 4px;"><br>
                <p class="blockquote">Tiempo de Espera Agotado o Sesión Desconectada</p>
                <b>Tipo:</b> <?=$Mensaje?>
                <b>Inactividad:</b> <?=$_REQUEST['init_time']?><br>
                <b>Hora Actual:</b> <?=$_REQUEST['time']?><br>
                <b>Total Segundos:</b> <?=( strtotime($_REQUEST['time']) - strtotime($_REQUEST['init_time']) )?> Segundos<br><br>
            </div>

        </div>

    </div>
</div>
</body>
<script src="../../../../plugins/jquery/jQuery-2.2.0.min.js"></script>
<script>

    $(function(){
       $("#btn_detalles").click(function () {
           $("#detallesd").toggleClass("hidden");
       })
    });

</script>
</html>