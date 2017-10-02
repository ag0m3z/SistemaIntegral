<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 01:47 PM
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

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);



//Extrar los Tipos de Cambio para llenar la grafica

$connect->_query = "select Max(TipoCambio),year(FechaAlta),month(FechaAlta),day(FechaAlta),Min(TipoCambio)  FROM BGEHistorialTipodeCambio group by FechaAlta";
$connect->get_result_query();
$DataTipoCambio = $connect->_rows;

$connect->_query = "SELECT TipoCambio,OnzaTroyUSD,OnzaPlataUSD,date(FechaAlta) FROM SINTEGRALPRD.BGEHistorialTipodeCambio order by FechaAlta DESC limit 0,1";
$connect->get_result_query();

$FechaUltimaActulizacion = $connect->_rows[0][3];
$TipoCambioActual = $connect->_rows[0][0];
$OnzaTroyUSD = $connect->_rows[0][1];
$OnzaPlataUSD = $connect->_rows[0][2];

//Formato
//[[Date.UTC(2013,5,2),0.7695],[Date.UTC(2013,5,2),0.7695]]
if(count($DataTipoCambio) > 0){
    for($i=0;$i < count($DataTipoCambio);$i++){



        $mes = $mes .'[Date.UTC('.$DataTipoCambio[$i][1].','.$DataTipoCambio[$i][2].','.$DataTipoCambio[$i][3].'),'.$DataTipoCambio[$i][0].'],';
        $Minimos[] =$DataTipoCambio[$i][4];

    }
    $TipoCambioMinimo=  min($Minimos);

}else{
    $mes ="";
    $TipoCambioMinimo = 0;
}

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsFormatoMoneda.js"></script>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsRemate.js"></script>
<script>
    $(".info-box-number").numeric({prefix:'$',cents:true});
    $( "#tabs" ).tabs();


    Highcharts.setOptions({
        lang: {
            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            weekdays: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
            shortMonths:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']
        }
    });
    var chart4 = new Highcharts.Chart({
        chart: {
            renderTo:'ContainerChart',
            zoomType: 'x'
        },
        title: {
            text: 'Tipo de Cambio USD'
        },
        subtitle: {
            text:'Historial Tipo de Cambio USD'
        },
        xAxis: {
            type: 'datetime'
        },
        yAxis: {
            title: {
                text: 'Precio'
            },
            labels: {
                formatter: function () {
                    return '$ ' + this.value;
                }
            },
            min: <?=$TipoCambioMinimo?>
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
            }
        },

        series: [{
            type: 'area',
            name: 'Precio $ ',
            data: [<?=$mes?>]
        }]
    });



</script>
<div id="tabs">
    <ul>
        <li><a href="#dash">Dashboard</a></li>
    </ul>
    <div id="dash">

        <div class="row row-sm">
            <div class="col-md-3">

                <div class="row">
                    <div class="col-sm-12 hoverable">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tipo de Cambio USD <br><?=$FechaUltimaActulizacion?></span>
                                <span class="info-box-number"><?=$TipoCambioActual?></span>
                            </div><!-- /.info-box-content -->
                        </div><!-- /.info-box -->
                    </div><!-- /.col -->
                    <div class="col-sm-12 hoverable">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-dollar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Onza Troy USD <br> <?=$FechaUltimaActulizacion?></span>
                                <span class="info-box-number"><?=$OnzaTroyUSD?></span>
                            </div><!-- /.info-box-content -->
                        </div><!-- /.info-box -->
                    </div><!-- /.col -->
                    <div class="col-sm-12 hoverable">
                        <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="fa fa-dollar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Onza Plata USD <br> <?=$FechaUltimaActulizacion?></span>
                                <span class="info-box-number"><?=$OnzaPlataUSD?></span>
                            </div><!-- /.info-box-content -->
                        </div><!-- /.info-box -->
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div>
            <div class="col-md-9">

                <div id="ContainerChart"></div>

            </div>
        </div>


    </div>
</div>

