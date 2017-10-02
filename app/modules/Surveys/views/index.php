<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 21/07/16
 * Time: 01:01 PM
 */
include "../../../core/contenido.php";

$connect = new \core\contenido('SINTEGRALPRD');

$param = $_REQUEST['ref'];
$AnioTicket;
$NoDepartamento;
$NoTicket;

if(!empty($param)){
    // No Esta Vacia la Variable $param
    if($param == md5("savesuccesfull")){
        $template = 4;
    }else{
        list($AnioTicket,$NoTicket,$NoDepartamento) = explode("_",base64_decode($param));

        $connect->_query = "SELECT * FROM BGEEncuestaServicios WHERE Anio = '".base64_decode($AnioTicket)."' AND NoDepartamento = '".base64_decode($NoDepartamento)."' AND Folio = ".base64_decode($NoTicket)." ";
        $connect->get_result_query();
        if(count($connect->_rows)>0){
            $template = 5;
        }else{

            $connect->_query = "SELECT a.Folio,a.Anio,a.NoDepartamento FROM BSHReportes as a
            where a.Anio = '".base64_decode($AnioTicket)."' And a.NoDepartamento = '".base64_decode($NoDepartamento)."' AND a.Folio = ".base64_decode($NoTicket)." " ;$connect->get_result_query();
            $template = 3;
        }
    }
}else{
    //Si esta vacia la Variable $param
    $template = 1;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Encuesta de Satisfacción </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="/SistemaIntegral/site_design/img/icons/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="/SistemaIntegral/site_design/img/icons/favicon.ico" type="image/x-icon" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Hojas de Estilos -->
    <link type="text/css" rel="stylesheet" href="../../../site_design/css/cssLogin.css"/>
    <link type="text/css" rel="stylesheet" href="../../../site_design/css/SistemaIntegral.css"/>
    <link type="text/css" rel="stylesheet" href="../../../plugins/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../../../plugins/fonts/font-awesome/css/font-awesome.min.css" type="text/css"/>

    <!-- Incluir Libreria Jquery -->
    <script type="text/javascript" src="../../../plugins/jquery/jQuery-2.2.0.min.js"></script>
    <script type="text/javascript" src="../../../plugins/jquery/jQuery-ui.js"></script>

    <script type="text/javascript" src="../../../site_design/js/jsGeneral.js"></script>
    <script type="text/javascript" src="../../../site_design/js/jsSurveys.js"></script>

    <script type="text/javascript" src="../../../plugins/bootstrap/js/bootstrap.js"></script>
    <script src="../../../plugins/bootstrap/js/bootbox.min.js" type="text/javascript"></script>
    <script language="JavaScript">
        $(document).ready(function(){
            $( ".datepicker" ).datepicker();
            $.fn.Frmreset = function () {$(this).each (function() { this.reset(); });$("input:text:visible:first").focus();}
        });
    </script>
</head>
<body>

<div id="alert">
    <div class="head">Encuestas de Servicio</div>
    <div class="boxs" style="padding-left: 10%;padding-right: 10%;">
        <div style="background: #fff;min-width:800px;padding: 5px;">
            <?php
            switch($template){
                case 1:
                    include('../layout/encuestas/FrmSurveySatisUser_Error.php');
                    break;
                case 2:
                    echo 'Error al realizar la consulta, por favor contacte a sistemas';
                    break;
                case 3:
                    include('../layout/encuestas/FrmSurveySatisfacionUsuario.php');
                    break;
                case 4:
                    include('../layout/encuestas/FrmSurveySatisUser_savesuccess.php');
                    break;
                case 5:
                    include('../layout/encuestas/FrmSurveySatisUser_Vencida.php');
                    break;
                default:
                    echo 'Error al realizar la consulta, por favor contacte a sistemas';
                    break;
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>