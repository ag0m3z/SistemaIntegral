<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/04/2017
 * Time: 05:10 PM
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
                type: 'column'
            },
            title: {
                text: 'Grafica Por Usuarios A\u00f1o <?=$AnoActual?>'
            },
            xAxis: {
                categories: [
                    'Ene',
                    'Feb',
                    'Mar',
                    'Abr',
                    'May',
                    'Jun',
                    'Jul',
                    'Ago',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dic'
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Tickets Cerrados (A\u00f1o <?=$AnoActual?>)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [<?php
                $connect->MyReports_for_UserMensual($_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['Perfil'],$AnoActual);
                ?>
            ]
        });
    });
</script>

<div class="row" id="panel2">
    <div class="col-md-8">
        <div id="container3" style="height: 60vh;"></div>

    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header" ><span class="fa fa-user"></span> indicador por usuario</div>
            <div class="box-body" style="overflow-y: scroll;height:60vh;">
                <table class="table table-hover table-condensed table-condensed" style="font-size: 12px;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre Usuario</th>
                        <th><span class="pull-right"> Reportes Cerrados</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $data = $connect->MyReports_for_User($AnoActual,$Mes,$_SESSION['data_login']['NoPerfil'],$_SESSION['data_departamento']['NoDepartamento']);
                        $id_us = 0;
                        if(count($data) > 0 ){
                            for($i=0;$i < count($data);$i++){

                                echo '<tr><td width="2"><strong>'.$id_us++.'</strong></td><td>'.$data[$i][0].'</td><td><span class="badge bg-blue-gradient pull-right">'.$connect->getFormatFolio($data[$i][1],4).'</span></td></tr>';

                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>