<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/02/2017
 * Time: 06:19 PM
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

$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);


?>
<script language="JavaScript" src="<?=\core\core::ROOT_APP?>site_design/js/jsServiceDesk.js"></script>
<script language="JavaScript" src="<?=\core\core::ROOT_APP?>site_design/js/jsCalendario.js"></script>
<!-- Inicio de Modal  -->
<div class="modal fade animated fadeInDown" id="myModal" data-backdrop="static" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"><i class="fa fa-search"></i> Reporte de encuestas</h4>
            </div>
            <div class="modal-body" style="margin-bottom: -5px;">
                <form id="frm_modal_search_encuestas">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="tablesorter">
                                <tr>
                                    <td style="width: 20%">Fecha Encuesta: </td>
                                    <td>
                                        <input type="text" id="fch1" placeholder="Fecha Inicial" class="datepicker formInput">
                                    </td>
                                    <td>
                                        <input type="text" id="fch2" placeholder="Fecha Final" class="datepicker formInput">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sucursal: </td>
                                    <td colspan="2">
                                        <select id="nosuc" class="formInput">
                                            <option value="0"></option>
                                            <?php
                                            $seguridad->_query = "select NoDepartamento,Descripcion from BGECatalogoDepartamentos WHERE NoEstado= 1 ORDER BY Descripcion ASC ";
                                            $seguridad->get_result_query();
                                            for($i=0; $i < count($seguridad->_rows);$i++){
                                                echo '<option value="'.$seguridad->_rows[$i][0].'">'.utf8_encode($seguridad->_rows[$i][1]).'</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Agente Cierre: </td>
                                    <td colspan="2">
                                        <select id="nouser" class="formInput">
                                            <option value="0"></option>
                                            <?php
                                            $seguridad->_query = "select NoUsuario,NombreDePila from SINTEGRALGNL.BGECatalogoUsuarios WHERE NoDepartamento = '".$_SESSION['data_departamento']['NoDepartamento']."' AND NoEstado = 1 ORDER BY NombreDePila ASC ";
                                            $seguridad->get_result_query();
                                            for($i=0; $i < count($seguridad->_rows);$i++){
                                                echo '<option value="'.$seguridad->_rows[$i][0].'">'.utf8_encode($seguridad->_rows[$i][1]).'</option>';
                                            }
                                            ?>
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
            <div class="modal-footer" style="text-align: left;margin-top: -18px;">
                <button id="mdl_search_encuestas" class="btn btn-primary btn-sm" onclick="fnsdBuscarEncuesta()"><i class="fa fa-search"></i> Buscar</button> &nbsp;
                <button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
                <button class="btn btn-default btn-sm" onclick="$('#frm_modal_search_encuestas').Frmreset();"><i class="fa fa-trash"></i> Limpiar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin del Modal Filtro -->

<div class="panel panel-info">
    <div class="panel-heading padding-x3"><i class="fa fa-list-alt"></i> Reporte de encuestas </div>
    <div class="toolbars">
        <button class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Actualizar" onclick="fnsdMenu(5,null);"><span class="fa fa-refresh"></span></button>
        <button data-opcion="vista" class="btn btn-default btn-xs dropdown" onclick="$('#myModal').modal('show')" ><i class="fa fa-search"></i> Buscar</button>
        <button data-opcion="reporte" class="btn btn-default btn-xs" onclick="fnsdExportarConsulta(2)" data-toggle="tooltip" data-placement="top" title="Imprimir"><i class="fa fa-file-excel-o"></i> Exportar</button>
        <span class="pull-right">Se Encontraron <span id="num" class="label label-success badge">0</span> Reportes</span>
    </div>
    <div class="panel-body no-padding">
        <div id="lListarTabla">
            <div id="myGrid" style="height: 80vh;font-size: 12.55px;"></div>
        </div>
        <div id="modl"></div>
    </div>
</div>


