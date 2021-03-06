<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 11/04/2017
 * Time: 09:43 AM
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
include "../../../../core/model_graficas.php";

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

$connect = new \core\model_graficas($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


$AnoActual = $_POST['v_anio'];
$Mes = $_POST['v_mes'];
$NoArea = $_POST['noarea'];

?>
<script type="text/javascript">
    $(function () {
       var chart = new Highcharts.Chart({
            chart: {
                renderTo:'container3',
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'Prioridad A\u00f1o <?=$AnoActual?>'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'A\u00f1o <?=$AnoActual?>',
                data: [
                    <?php
                    $connect->MyReports_GraficaPrioridad($_SESSION['data_departamento']["NoDepartamento"],$_SESSION['data_login']['NoPerfil'],$AnoActual,$Mes,1);
                    ?>
                ]
            }]
        });
    });
</script>

<div class="row" id="panel2">
    <div class="col-md-8">
        <div id="container3" style="height: 60vh;"></div>

    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header"><span class="fa fa-dashboard"></span> Indicador por prioridad</div>
            <div style="overflow-y: scroll;height:60vh;">
                <table class="table table-hover table-condensed table-condensed" style="font-size: 12px;">
                    <thead>
                    <th>#</th><th>Categor&iacute;a</th><th><span class="pull-right">Total</span></th>
                    </thead>
                    <tbody>
                    <?php
                    $connect->Myreports_Prioridad($_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],$AnoActual,$Mes,1);
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>