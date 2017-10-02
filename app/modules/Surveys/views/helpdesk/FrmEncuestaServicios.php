<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 30/06/2017
 * Time: 12:44 PM
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

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


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

        $qExistSurvey = $connect->Consulta("SELECT * FROM BGEEncuestaServicios WHERE Anio = '".base64_decode($AnioTicket)."' AND NoDepartamento = '".base64_decode($NoDepartamento)."' AND Folio = ".base64_decode($NoTicket)."");
        if($connect->num_rows($qExistSurvey)>0){
            $template = 5;
        }else{
            if(!$query = $connect->Consulta("SELECT a.Folio,a.Anio,a.NoDepartamento FROM BSHReportes as a
            where a.Anio = '".base64_decode($AnioTicket)."' And a.NoDepartamento = '".base64_decode($NoDepartamento)."' AND a.Folio = ".base64_decode($NoTicket)."")){
                $template = 2; //Error en Query;
            }else{
                $template = 3; //Query Correcto
            }
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
    <link rel="shortcut icon" href="/SistemaIntegral/app/images/icons/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="/SistemaIntegral/app/images/icons/favicon.ico" type="image/x-icon" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Hojas de Estilos -->
    <link type="text/css" rel="stylesheet" href="../../../lib/theme/css/cssLogin.css"/>
    <link type="text/css" rel="stylesheet" href="../../../lib/theme/css/SistemaIntegral.css"/>
    <link type="text/css" rel="stylesheet" href="../../../lib/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../../../lib/fonts/font-awesome/css/font-awesome.min.css" type="text/css"/>

    <!-- Incluir Libreria Jquery -->
    <script type="text/javascript" src="../../../lib/jquery/jQuery-2.2.0.min.js"></script>
    <script type="text/javascript" src="../../../lib/jquery/jQuery-ui.js"></script>
    <script type="text/javascript" src="../../../lib/theme/js/jsGeneral.js"></script>
    <script type="text/javascript" src="../../../lib/theme/js/jsSurveys.js"></script>

    <script type="text/javascript" src="../../../lib/bootstrap/js/bootstrap.js"></script>
    <script src="../../../lib/bootstrap/js/bootbox.min.js" type="text/javascript"></script>
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
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>