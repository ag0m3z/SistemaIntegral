<!DOCTYPE html>
<html>
<head>
    <title>Error en la Conexi&oacute;n</title>
    <link type="text/css" rel="stylesheet" href="../../../../site_design/css/cssLogin.css"/>
    <link type="text/css" rel="stylesheet" href="../../../../plugins/bootstrap/css/bootstrap.css"/>
    <link type="text/css" rel="stylesheet" href="../../../../plugins/bootstrap/css/bootstrap-theme.css"/>

</head>
<body style="background: #fff !important">
<div id="alert">
    <div class="head">Sistema Integral</div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-2">
                <h1>Error 502</h1>
                <p class="blockquote">Tiempo de Espera Agotado</p>
            </div>
            <div class="col-md-4">
                <h1>&nbsp;</h1>
                <p>error de conexi&oacute;n: <?=date("Y-m-d H:i:s")?></p>
            </div>

        </div>
        <div class="row">
            <img src="../../../../site_design/img/pages/error_conexion3.png" style="width: 100%"/>
        </div>
        <div class="row text-center">
            <button class="btn btn-success" onclick="location.href='/SistemaIntegral';"> Reintentar la Conexi&oacute;n</button>
        </div>
    </div>
</div>
</body>
</html>