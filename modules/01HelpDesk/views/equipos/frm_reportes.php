<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/02/2017
 * Time: 05:34 PM
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
include "../../../../core/model_equipos.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */


$Equipos = new \core\model_equipos($_SESSION['data_login']['BDDatos']);
$Equipos->valida_session_id($_SESSION['data_login']['NoUsuario']);




?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsServiceDesk.js" type="text/javascript"></script>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsCalendario.js" type="text/javascript"></script>
<!--Panel INFO -->
<div class="panel panel-info">
    <div class="panel-heading padding-x3"><i class="fa fa-list-alt"></i> Reporte de equipos</div>
    <div class="toolbars">
        <button class="btn btn-primary btn-xs" onclick="fnsdMenu(22,22)" ><i class="fa fa-refresh"></i></button>
        <button class="btn btn-default btn-xs" data-toggle="modal" data-target="#myModal" ><i class="fa fa-search"></i> Buscar</button>
        <button class="btn btn-default btn-xs" onclick="if($('#lbl-num').text() != '0'){window.open('modules/01HelpDesk/reportes/equipos/rpt.equipos_internos.php')}else{MyAlert('No se encontraron resultados','alert');}" ><i class="fa fa-file-excel-o"></i> Exportar</button>
        <span class="pull-right">Se encontraron <span id="lbl-num" class="badge label label-success">0</span> resultados</span>
    </div>
    <div class="panel-body no-padding">
        <div id="listTable" style="height: 75vh;font-size: 12px;">
        </div>
    </div>
</div>

<!-- END Panel INFO -->

<!-- Modal Buscar -->
<div id="myModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><i class="fa fa-search"></i> Reporte de equipos</h2>
            </div>
            <div class="modal-body">
                <form id="FrmSearchEquipos" action="#" onsubmit="return false;" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <input type="text" id="e_fch01" placeholder="Fecha Inicial" class="datepicker formInput">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <input type="text" id="e_fch02" placeholder="Fecha Final" class="datepicker formInput">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                Tipo de Equipo:
                                <select id="e_tipo_equipo" class="form-control input-sm">
                                    <option value="0"> Todos </option>
                                    <?php

                                    $Equipos->_query =  "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos WHERE idCatalogo = 7 ORDER BY Descripcion ASC";
                                    $Equipos->get_result_query();

                                    for($i=0;$i < count($Equipos->_rows);$i++){
                                        echo "<option value='".$Equipos->_rows[$i][0]."'>".$Equipos->_rows[$i][1]."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                Departamento:
                                <select id="e_departamento" class="form-control input-sm">
                                    <option value="0"> Todos </option>
                                    <?php
                                    $Equipos->_query =  "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE NoEstado = 1 ORDER BY Descripcion ASC";
                                    $Equipos->get_result_query();

                                    for($i=0;$i < count($Equipos->_rows);$i++){
                                        echo "<option value='".$Equipos->_rows[$i][0]."'>".$Equipos->_rows[$i][1]."</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                Estado:
                                <select id="e_estado" class="form-control input-sm">
                                    <option value="0"> Todos </option>
                                    <?php
                                    $Equipos->_query =  "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos WHERE idCatalogo = 8 ORDER BY Descripcion ASC";
                                    $Equipos->get_result_query();

                                    for($i=0;$i < count($Equipos->_rows);$i++){
                                        echo "<option value='".$Equipos->_rows[$i][0]."'>".$Equipos->_rows[$i][1]."</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                            <div class="form-group">
                                Usuario Registra:
                                <select id="e_usuario_registra" class="form-control input-sm">
                                    <option value="0"> Todos </option>
                                    <?php
                                    $Equipos->_query =  "SELECT NoUsuario,NombreDePila FROM SINTEGRALGNL.BGECatalogoUsuarios WHERE NoDepartamento = '0109' AND NoEstado = 1 ORDER BY NombreDePila ASC";
                                    $Equipos->get_result_query();

                                    for($i=0;$i < count($Equipos->_rows);$i++){
                                        echo "<option value='".$Equipos->_rows[$i][0]."'>".$Equipos->_rows[$i][1]."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="mdl_btn_search" onclick="fnsdBuscar_equipo_inerno(2)" id="modal_btn_search" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Buscar</button>
                <button id="mdl_btn_close" data-dismiss="modal" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Cerrar</button>
                <button onclick="$('#FrmSearchEquipos').Frmreset();" class="btn btn-default btn-sm"><i class="fa fa-trash"></i> Limpiar</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Buscar -->

