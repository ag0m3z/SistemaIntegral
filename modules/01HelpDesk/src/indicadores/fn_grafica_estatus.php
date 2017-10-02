<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/04/2017
 * Time: 01:52 PM
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
        // Build the chart
        var chart2 = new Highcharts.Chart({
            chart: {
                renderTo:"container3",
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'Categorias A\u00f1o <?=$AnoActual?>'
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
                    {
                        name: 'Abiertos',
                        y: <?=$connect->MyReports_for_Estatus(1,$_SESSION['data_departamento']['NoDepartamento'],$AnoActual)?>,
                        sliced: true,
                        selected: true
                    },
                    ['Sin Asignar',      <?=$connect->MyReports_for_SinAsignar($_SESSION['data_departamento']['NoDepartamento'],$AnoActual,$Mes,$_SESSION['data_login']['NoPerfil'])?>],
                    ['Cancelados',   <?=$connect->MyReports_for_Estatus(5,$_SESSION['data_departamento']['NoDepartamento'],$AnoActual)?>,],
                    ['En Progreso',     <?=$connect->MyReports_for_Estatus(2,$_SESSION['data_departamento']['NoDepartamento'],$AnoActual)?>]
                ]
            }]
        });

    });
</script>

<div class="row">
    <div class="col-md-8">
        <div id="container3" style="height: 60vh;"></div>
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header"><i class="fa fa-dashboard"></i> Indicador de estatus</div>
            <div class="box-body"  style="overflow-y: scroll;height:60vh;">
                <table class="table table-hover table-condensed table-condensed">
                    <thead>
                    <th colspan="2"> Porcentajes</th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Reportes Cerrados</td>
                        <td><span class="badge pull-right"><?=$connect->MyReports_FechaPromesa(1,$_SESSION['data_departamento']['NoDepartamento'],$AnoActual,$_SESSION['data_login']['NoPerfil'])?></span></td>
                    </tr>
                    <tr>
                        <td>Reportes En Tiempo</td>
                        <td><span class="badge pull-right"><?=$connect->MyReports_FechaPromesa(2,$_SESSION['data_departamento']['NoDepartamento'],$AnoActual,$_SESSION['data_login']['NoPerfil'])?></span></td>
                    </tr>
                    <tr>
                        <td>Reportes Fuera de Tiempo</td>
                        <td><span class="badge pull-right"><?=$connect->MyReports_FechaPromesa(3,$_SESSION['data_departamento']['NoDepartamento'],$AnoActual,$_SESSION['data_login']['NoPerfil'])?></span></td>
                    </tr>
                    <tr>
                        <td>Tickets Pendientes</td>
                        <td><span class="badge pull-right"><?=$connect->MyReports_FechaPromesa(4,$_SESSION['data_departamento']['NoDepartamento'],$AnoActual,$_SESSION['data_login']['NoPerfil'])?></span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>