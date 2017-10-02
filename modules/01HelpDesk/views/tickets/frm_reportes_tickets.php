<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/02/2017
 * Time: 12:19 PM
 */
/**
 * Incluir las Librerias Principales del Sistema
 * En el Siguiente Orden
 * ruta de libreias: @@/SistemaIntegral/core/
 *
 * 1.- core.php;
 * 2.- sesiones.php
 * 3.- seguridad.php
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);

$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

$FechaActual = $connect->getFormatFecha(date("Ymd"),2);

// Consulta para traerd los departamentos a los que se les pueden asignar reporte
$connect->_query ="SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE AsignarReportes = 'SI' AND NoDepartamento != ".$_SESSION['data_departamento']['NoDepartamento']." ORDER BY Descripcion ASC";
$connect->get_result_query();

$Departamentos = $connect->_rows;

$array_permisos = array('3','1');

//if($_SESSION['AsignarReporte'] == 'SI' && !in_array($_SESSION['Perfil'],$array_permisos) ){


if($_SESSION['data_departamento']['AsignarReportes'] == 'SI' ){
    // si tecnico
    $select_option01 = "<option value='".$_SESSION['data_departamento']['NoDepartamento']."'>".$_SESSION['data_departamento']['NombreDepartamento']."</option>";
    //$disabled = "disabled";
}else{
    // no tecnico
    $select_option01 = "<option value='0'>-- --</option>";
    $disabled = "";
}


?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsServiceDesk.js"></script>
<script language="JavaScript" src="<?=\core\core::ROOT_APP?>site_design/js/jsCalendario.js"></script>
<script language="JavaScript">
    $("#HomeContent").on("click",'#searchReport',function(){$('#myModal').modal('show');});
    $("input[type=text]").focus(function(){
        this.select();
    });
    $("#myModal").draggable({
        handle: ".modal-header"
    });
</script>

<!-- Iniico del Panel Principal -->
<div class="panel panel-info">
    <div class="panel-heading padding-x3"><i class="fa fa-list-alt"></i> Reportes mesa de ayuda <span class="pull-right" id="txt_nombre_departamento"></span></div>
    <div class="toolbars">
        <button class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Actualizar" onclick="fnsdMenu(4,null);"><span class="fa fa-refresh"></span></button>
        <button data-opcion="vista" class="btn btn-default btn-xs dropdown" onclick="$('#myModal').modal('show')" ><i class="fa fa-search"></i> Buscar</button>
        <div data-opcion="reporte" class="btn-group">
            <button id="btn-print" class="btn btn-default btn-xs"
                    data-toggle="dropdown"><span class="fa fa-file-excel-o"></span> Exportar <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:void(0)" onclick="fnsdImprimirReportes(2)">Imprimir en Pdf</a></li>
                <li><a href="javascript:void(0)" onclick="fnsdImprimirReportes(1)">Imprimir en Excel</a></li>
            </ul>
        </div>
        <span class="pull-right">Se encontraron <span id="num" class="label label-success badge">0</span> Tickets</span>
    </div>
    <div class="panel-body no-padding">
        <div id="content-report"h">
        <div id="myGrid" style="height: 76vh;font-size: 12.55px;"></div>
    </div>
</div>

<!-- Inicio de Modal para la Busqueda -->
<div class="modal fade animated"   id="myModal" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"><i class="fa fa-search"></i> Reportes mesa de ayuda</h4>
            </div>
            <div class="modal-body" style="margin-bottom: -5px;">
                <form id="frm_search_ticket">
                    <div id="search-avanz" class="row">
                        <div class="col-md-6">
                            <table class="tablesorter">
                                <tr>
                                    <td>
                                        Mesa de ayuda:
                                    </td>
                                    <td>
                                        <select <?=$disabled?> onchange="fn_select_usuarios(this.value);fn_select_areas(this.value)" id="NoDepartamento" class="formInput">

                                            <?php
                                            echo $select_option01;

                                            if(count($Departamentos) > 0){
                                                for($i=0; $i < count($Departamentos);$i++ ){
                                                    echo "<option value='".$Departamentos[$i][0]."'>".$Departamentos[$i][1]."</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sucursal: </td>
                                    <td>
                                        <select id="suc" class="formInput">
                                            <option value="0"> Todas</option>
                                            <?php
                                            $connect->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos ORDER BY Descripcion ASC";
                                            $connect->get_result_query();

                                            for($i=0; $i < count($connect->_rows);$i++ ){
                                                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                            }

                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Estatus: </td>
                                    <td>
                                        <select id="est" class="formInput">
                                            <option value="0">Todos</option>
                                            <option selected value="98">Pendientes</option>
                                            <option value="99">Pendientes historico</option>
                                            <option disabled="true"></option>
                                            <?php
                                            $connect->_query = "SELECT NoEstatus,Descripcion FROM BSHCatalogoEstatus";
                                            $connect->get_result_query();
                                            for($i=0; $i < count($connect->_rows);$i++ ){
                                                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Usuario: </td>
                                    <td>
                                        <select id="user" class="formInput">
                                            <option value="0"> Todos</option>
                                            <?php
                                            $connect->_query = "SELECT NoUsuario,NombreDePila FROM SINTEGRALGNL.BGECatalogoUsuarios WHERE NoDepartamento = '".$_SESSION['data_departamento']['NoDepartamento']."' AND NoEstado = 1 ORDER BY NombreDePila ASC";
                                            $connect->get_result_query();
                                            for($i=0; $i < count($connect->_rows);$i++ ){
                                                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tipo Seguimiento: </td>
                                    <td>
                                        <select id="seg" class="formInput">
                                            <option value="0">Todos</option>
                                            <?php
                                            $connect->_query = "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos WHERE idCatalogo = 2 ORDER By Descripcion ASC";
                                            $connect->get_result_query();
                                            for($i=0; $i < count($connect->_rows);$i++ ){
                                                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="tablesorter">
                                <tr>
                                    <td>Fecha:</td>
                                    <td><input id="f01" class="formInput datepicker" value="<?=$FechaActual?>" type="text" ></td>
                                    <td><input id="f02" class="formInput datepicker" value="<?=$FechaActual?>" type="text" ></td>
                                </tr>
                                <tr>
                                    <td>Medio Contacto:</td>
                                    <td colspan="2">
                                        <select id="cont" class="formInput">
                                            <option value="0">Todos</option>
                                            <?php
                                            $connect->_query = "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos WHERE idCatalogo = 2 ORDER By Descripcion ASC";
                                            $connect->get_result_query();
                                            for($i=0; $i < count($connect->_rows);$i++ ){
                                                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Área:</td>
                                    <td colspan="2">
                                        <select id="id_noarea" class="formInput" onchange="fnsdloadCategorias(this.value,$('#NoDepartamento').val())">
                                            <option value="0">Todas</option>
                                            <?php
                                            $connect->_query = "SELECT NoArea,Descripcion FROM BSHCatalogoAreas WHERE NoDepartamento = '".$_SESSION['data_departamento']['NoDepartamento']."' ORDER BY Descripcion ASC";
                                            $connect->get_result_query();
                                            for($i=0; $i < count($connect->_rows);$i++ ){
                                                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Categoria:</td>
                                    <td colspan="2">
                                            <select id="id_categorias" class="formInput">
                                                <option value="0">Todas</option>
                                            </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-6">
                        <div id="imgLoad"></div>
                        <br/>
                    </div>
                </div>
            </div>
            <div class="modal-footer" >
                <button class="btn btn-primary btn-sm" id="btn-search" onclick="fnsdBuscaReporte()"><i class="fa fa-search"></i> Buscar</button>
                <button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
                <button class="btn btn-default btn-sm" onclick="$('#frm_search_ticket').Frmreset();"><i class="fa fa-trash"></i> Limpiar</button>

            </div>
        </div>
    </div>
</div>
