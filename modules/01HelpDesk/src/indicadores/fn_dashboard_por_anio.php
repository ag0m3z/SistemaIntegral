<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/04/2017
 * Time: 12:27 PM
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
                    alpha: 12,
                    beta: 33,
                    depth: 70
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
<div id="container3" style="height: 35vh;"></div>

