<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/04/2017
 * Time: 11:52 AM
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

$fchInicial = date("Ymd");
$fchFinal = $fchInicial;

$link = $connect->MyReports_for_dia(1,$fchInicial,$fchFinal,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoPerfil']);

?>
<script language="JavaScript">
    $(function () {
        var chart = new Highcharts.Chart({
            chart: {
                renderTo: 'myGraphic',
                type: 'column'
            },
            title: {
                text: 'Indicador Por D\u00eda'
            },
            xAxis: {
                categories: [
                    <?php
                    for ($i=0;$i < count($link); $i++){
                        $iniciales = explode(" ",$link[$i][4]);
                        $user = substr($iniciales[0],0,1) . substr($iniciales[1],0,1).substr($iniciales[2],0,1);
                        echo "'$user',";
                    }
                    ?>
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Tickets Por D\u00eda'
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
            series: [
                {
                    name:"Asignados",
                    data:[<?php
                        for($i=0;$i < count($link); $i++){
                            echo $link[$i][1].",";
                        }
                        ?>],
                    color: '#FF0000'
                },
                {
                    name:"Registrados",
                    data:[<?php
                        for($i=0;$i < count($link); $i++){
                            echo $link[$i][3].",";
                        }
                        ?>],
                    color:'#00C0EF'
                },
                {
                    name:"Cerrados",
                    data:[<?php
                        for($i=0;$i < count($link); $i++){
                            echo $link[$i][2].",";
                        }
                        ?>],
                    color:'#00A65A'
                }
            ]
        });
    });
</script>
<div id="myGraphic" style="height: 35vh;">
</div>