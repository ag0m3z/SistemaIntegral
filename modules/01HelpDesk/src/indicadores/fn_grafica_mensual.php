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
        var chart = new Highcharts.Chart({
            chart: {
                renderTo:"container3",
                type: 'column',
                margin: 75,
                options3d: {
                    enabled: true,
                    alpha: 7,
                    beta: 31,
                    depth: 80
                }
            },
            title: {
                text: 'Reportes por Mes del A\u00f1o <?=$AnoActual?>'
            },
            plotOptions: {
                column: {
                    depth: 55,
                    width: 120
                }
            },
            xAxis: {
                categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
                    'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: "Total de Reportes"
                }
            },
            series: [{
                name: 'Reportes',
                data: [
                    <?php
                    for($i=1; $i <= 12;$i++){
                        echo $connect->MyReports_for_month($i,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],$AnoActual).",";
                    }
                    ?>
            ]
            },
            ]
        });
    });
</script>

<div class="row">
    <div class="col-md-7">
        <div id="container3" style="height: 60vh;"></div>
    </div>
    <div class="col-md-5">
        <div class="box box-primary">
            <div class="box-header"><span class="fa fa-dashboard "></span> Indicador mensuales</div>
            <div class="box-body no-padding" style="overflow-y: scroll;height:60vh;">
                <?php
                $connect->AnualReports($_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPefil'],$AnoActual);
                ?>
            </div>
        </div>
    </div>
</div>