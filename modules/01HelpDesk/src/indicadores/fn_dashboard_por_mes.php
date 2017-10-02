<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/04/2017
 * Time: 12:20 PM
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
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
$AnoActual = date("Y");
?>
<script type="text/javascript">
    $(function () {
        var chart = new Highcharts.Chart({
            chart: {
                renderTo:"container2",
                type: 'column',
                margin: 75,
                options3d: {
                    enabled: true,
                    alpha: 12,
                    beta: 33,
                    depth: 110
                }
            },
            title: {
                text: 'Reportes por Mes del A\u00f1o <?=$AnoActual?>'
            },
            plotOptions: {
                column: {
                    depth: 35,
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
                    <?=$connect->MyReports_for_month(1,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],$AnoActual)?>,
                    <?=$connect->MyReports_for_month(2,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],$AnoActual)?>,
                    <?=$connect->MyReports_for_month(3,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],$AnoActual)?>,
                    <?=$connect->MyReports_for_month(4,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],$AnoActual)?>,
                    <?=$connect->MyReports_for_month(5,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],$AnoActual)?>,
                    <?=$connect->MyReports_for_month(6,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],$AnoActual)?>,
                    <?=$connect->MyReports_for_month(7,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],$AnoActual)?>,
                    <?=$connect->MyReports_for_month(8,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],$AnoActual)?>,
                    <?=$connect->MyReports_for_month(9,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],$AnoActual)?>,
                    <?=$connect->MyReports_for_month(10,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],$AnoActual)?>,
                    <?=$connect->MyReports_for_month(11,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],$AnoActual)?>,
                    <?=$connect->MyReports_for_month(12,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil'],$AnoActual)?>]
            },
            ]
        });
    });
</script>
<div id="container2" style="height: 35vh;"></div>