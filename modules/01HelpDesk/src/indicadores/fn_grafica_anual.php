<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/04/2017
 * Time: 05:37 PM
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

?>
<script type="text/javascript">
    $(function () {
        var chart = new Highcharts.Chart({
            chart: {
                renderTo:"container3",
                type: 'column',
                margin: 75,
                options3d: {
                    enabled: true,
                    alpha: 7,
                    beta: 28,
                    depth: 55
                }
            },
            title: {
                text: 'Grafica de Tickets Anuales'
            },
            plotOptions: {
                column: {
                    depth: 25
                }
            },
            xAxis: {
                categories: [
                    <?php
                    $row = $connect->MyReports_grafica4($_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],1);
                    for($i=0;$i < count($row);$i++){
                        echo "'".$row[$i][0]."',";
                    }
                    ?>
                ]
            },
            yAxis: {
                title: {
                    text: null
                }
            },
            series: [{
                name: 'Tickets',
                data: [
                    <?php
                    $row = $connect->MyReports_grafica4($_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],2);
                    for($i=0;$i < count($row);$i++){
                        echo $row[$i][0].",";
                    }
                    ?>
                ]
            }]
        });
    });
</script>

<div class="row" id="panel2">
    <div class="col-md-8" >
        <div id="container3" style="height: 60vh;"></div>

    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header"><span class="fa fa-dashboard "></span> Indicador anual</div>
            <div class="box-body" style="overflow-y: scroll;height:60vh;">
                <table class="table table-hover table-condensed table-condensed">
                    <tbody>
                    <?php
                    $data = $connect->MyReports_for_year($_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil']);
                    $all_reports = 0;

                    for($i=0;$i < count($data) ;$i++){
                        $all_reports = $all_reports + $data[$i][1];
                    }

                    for($i=0;$i < count($data) ;$i++){
                        $porcentaje = ($data[$i][1] * 100)/$all_reports;
                        echo '<tr><td><span class="pull-left">'.$data[$i][0].' - <span class="badge">'.number_format($data[$i][1]).'</span> &nbsp;</span><div class="progress progress-striped" style="padding: 0px;margin: 0px;">';
                        echo '<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1" aria-valuemin="30" aria-valuemax="'.$all_reports.'" style="width: '.$porcentaje.'%;">';
                        echo '<span class="sr-only">100% completado</span></div></div></td><td width="10">';
                        echo '</td></tr>';

                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>