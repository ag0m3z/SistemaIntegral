<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 20/04/2017
 * Time: 01:25 PM
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
$FechaACtual = date('d/m/Y');
/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsCalendario.js"></script>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsPVenta.js"></script>
<script>
    fn_cargar_indicadores(1);

    function fnTipoGraficaPVenta(val){

        if(val == 1){
            $("#tipo_grafica").removeClass("hidden");
            $("#fch_inicial").removeClass("hidden");
            $("#fch_final").removeClass("hidden");
            $("#zona").removeClass("hidden");
            $("#sucursales").removeClass("hidden");
            fn_cargar_indicadores(1);

        }else if(val == 2){
            $("#tipo_grafica").addClass("hidden");
            $("#fch_inicial").addClass("hidden");
            $("#fch_final").addClass("hidden");
            $("#zona").addClass("hidden");
            $("#sucursales").addClass("hidden");

            fn_cargar_indicadores(5);
        }

    }
</script>
<div class="panel panel-info">
    <div class="panel-heading"><i class="fa fa-line-chart"></i> Indicadores encuesta clientes no atendidos</div>
    <div class="toolbars">
        <select class="formInput2" onchange="fnTipoGraficaPVenta(this.value)">
            <option value="1">indicador clientes no atendidos</option>
            <option value="2">Historial Tipo de Cambio</option>
        </select>

        <select id="tipo_grafica" class="formInput2" onchange="fn_cargar_indicadores(this.value)">
            <option value="0">Seleccionar Grafica</option>
            <option value="1">Por motivo</option>
            <option value="2">Por producto</option>
            <option value="3">Por competidor</option>
            <option value="4">Por clasificación</option>
        </select>


        <input id="fch_inicial" style="width: 98px;" onchange="fn_cargar_indicadores($('#tipo_grafica').val())" class="formInput2 datepicker" value="<?=$FechaACtual?>" placeholder="Fecha inicial"/>
        <input id="fch_final" style="width: 98px;" onchange="fn_cargar_indicadores($('#tipo_grafica').val())" class="formInput2 datepicker" value="<?=$FechaACtual?>" placeholder="Fecha final">
        <select id="zona" class="formInput2" onchange="fn_cargar_indicadores($('#tipo_grafica').val())">
            <option value="0">Todas las zonas</option>
            <?php
            $connect->_query = "SELECT OpcCatalogo,Texto1 FROM BGECatalogoGeneral WHERE CodCatalogo = 18 AND NoEstatus = 1 ORDER BY Texto1 ASC";
            $connect->get_result_query();
            for($i=0;$i < count($connect->_rows);$i++){
                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
            }
            ?>
        </select>
        <select id="sucursales" class="formInput2" onchange="fn_cargar_indicadores($('#tipo_grafica').val())" >
            <option value="0">Todas las sucursales</option>
            <?php
            $connect->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE NoTipo = 'S' AND NoEstado = 1 ORDER BY Descripcion ASC";
            $connect->get_result_query();
            for($i=0;$i < count($connect->_rows);$i++){
                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
            }
            ?>
        </select>
    </div>
    <div class="panel-body" id="dashboard">
    </div>
</div>
