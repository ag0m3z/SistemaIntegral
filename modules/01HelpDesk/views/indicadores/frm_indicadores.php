<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/04/2017
 * Time: 11:06 AM
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
$NoDepartamento = $_SESSION['data_departamento']['NoDepartamento'];

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

?>

<script src="<?=\core\core::ROOT_APP?>site_design/js/jsServiceDesk.js"></script>

<script language="JavaScript">
    fnsd_cargar_graficas(2,1);
</script>

<div class="panel panel-info">
    <div class="panel-heading">
        <i class="fa fa-line-chart"></i> Panel de Indicadores
    </div>
    <div class="toolbars">
        <select id="select_grafica" onchange="fnsd_cargar_graficas(2,this.value)" class="formInput2">
            <option value="1">Grafica mensual</option>
            <option value="2">Grafica por estatus</option>
            <option value="3">Grafica por usuarios</option>
            <option value="4">Grafica anual</option>
            <option value="5">Grafica por áreas</option>
            <option value="6">Grafica por prioridad</option>
            <option value="7">Grafica por sucursales</option>

            <!--

            <option value="8">Grafica Por Tiempos</option>
            <option value="10">Grafica Personalizada</option>-->
        </select>
        <select id="select_anio" onchange="fnsd_cargar_graficas(2,$('#select_grafica').val()    )" class="formInput2">
            <?php
            $connect->_query = "SELECT Anio FROM BSHReportes GROUP BY Anio ORDER BY Anio DESC";
            $connect->get_result_query();
            $dat = $connect->_rows;
            for($i=0;$i < count($dat);$i++){
                echo '<option value="'.$dat[$i][0].'">'.$dat[$i][0].'</option>';
            }
            ?>
        </select>
        <select id="select_mes" name="select_mes" onchange="fnsd_cargar_graficas(2,$('#select_grafica').val() )" class="formInput2">
            <option value="13">Todos Los Meses</option>
            <option value="1">Enero</option>
            <option value="2">Febrero</option>
            <option value="3">Marzo</option>
            <option value="4">Abril</option>
            <option value="5">Mayo</option>
            <option value="6">Junio</option>
            <option value="7">Julio</option>
            <option value="8">Agosto</option>
            <option value="9">Septiembre</option>
            <option value="10">Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
        </select>
        <select id="select_area" onchange="fnsd_cargar_graficas(2,$('#select_grafica').val() )" class="formInput2">
            <?php

            $connect->_query = "SELECT NoArea,Descripcion FROM BSHCatalogoAreas WHERE NoDepartamento = '$NoDepartamento' ORDER BY Descripcion DESC";
            $connect->get_result_query();
            $dat = $connect->_rows;
            for($i=0;$i<count($dat);$i++){
                echo '<option value="'.$dat[$i][0].'">'.$dat[$i][1].'</option>';
            }
            ?>
        </select>
        <select id="select_categoria" onchange="fnsd_cargar_graficas(2,0)" class="formInput2">
            <option>Seleccionar Categoria</option>
        </select>


    </div>
    <div class="panel-body">

    </div>
</div>

