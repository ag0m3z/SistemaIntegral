<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 21/04/2017
 * Time: 11:31 AM
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
$connect->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


$Zona = $_POST['zona'];
$Sucursal = $_POST['sucursales'];


if($_POST['fecha_inicial']== ""){$FechaInicial= "0"; }else{$FechaInicial = $connect->getFormatFecha($_POST['fecha_inicial'],1);}
if($_POST['fecha_final']==""){$FechaFinal="0";}else{$FechaFinal = $connect->getFormatFecha($_POST['fecha_final'],1);}


$datos = array(
    "a.NoZona"=>$Zona,
    "a.NoSucursal"=>$Sucursal,
    "a.FechaAlta >"=>$FechaInicial,
    "a.FechaAltaF <"=>$FechaFinal
);

foreach($datos as $key1=> $val){
    if($val != "0"){

        if($key1 == 'a.FechaAltaF <'){$key1 = 'a.FechaAlta <';};

        $mad[] = array($key1,$val);
    }
}

$tam = count($mad)."<br>";

for($i=0;$i<$tam;$i++){
    if($tam > $i){
        $and = " and ";
    }else{
        $and="";
    }

    if($mad[$i][0] == "a.Descripcion"){
        $operador = " like ";
    }else{
        $operador = "=";
    }

    $where[] = $mad[$i][0].$operador.$mad[$i][1].$and." ";
}

$where_final = "WHERE ".substr($where[0].$where[1].$where[2].$where[3].$where[4].$where[5].$where[6].$where[7],0,-5);



$connect->_query = "SELECT b.OpcCatalogo,b.Descripcion,count(a.NoAtendidos) as TotalNoAtendidos
FROM 
BGEEncuestaProducto as a 
LEFT JOIN BGECatalogoGeneral as b 
ON a.NoAtendidos = b.OpcCatalogo AND b.CodCatalogo = 17
".$where_final." group by b.Descripcion ORDER BY b.Descripcion ASC";

$connect->get_result_query();
$Competencia = $connect->_rows;




for($i=0;$i< count($Competencia);$i++){

    if($Competencia[$i][0] == 2){
        $Competencia[$i][1] = "Malas Condiciones";
    }
    $data_competencia .= "['".$Competencia[$i][1]."',".$Competencia[$i][2]."],";
}

?>
<script>
    $(function(){
        var chart4 = new Highcharts.Chart({
            chart: {
                renderTo:'container',
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'Clientes no atendidos por motivo'
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
                name: 'cliente no antedido',
                data: [<?=$data_competencia?>]
            }]
        });

    });
</script>
<div class="row">
    <div class="col-md-7">
        <div id="container"></div>
    </div>
    <div class="col-md-5">
        <div class="box box-primary">
            <div class="box-header"><i class="fa fa-dashboard"></i> Clientes no atendidos por motivo</div>
            <div class="box-body">
                <table class="table table-hove table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Descripciónn</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $contador = 0;

                    for($i=0;$i <count($Competencia);$i++){

                        $contador++;
                        echo "<tr><td>$contador</td><td>".$Competencia[$i][1]."</td><td><span class='badge'>".$connect->getFormatFolio($Competencia[$i][2],4)."</span></td></tr>";

                    }
                    ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
