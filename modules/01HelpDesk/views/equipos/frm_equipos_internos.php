<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 14/02/2017
 * Time: 06:43 PM
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

$Equipos = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$Equipos->valida_session_id($_SESSION['data_login']['NoUsuario']);


$ArrayPerfil = array(1,3);
$confirm = "false";
if(in_array($_SESSION['data_login']['NoPerfil'],$ArrayPerfil)){
    $confirm = "true";
}




?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsServiceDesk.js"></script>
<script language="javascript">
    $("#Stats").hide(); //Stats de Tickets
    $("#eSaveEdit").hide();
    $("#eImprimeDocumento").hide();
    $("#eAddDoc").hide();
    $("#eAddImg").hide();
    $("#elista").hide();
    $("#btnHome").show();
    $("#eSave").hide();
    //$("#eReasigna").hide();
    $("#myModal").draggable({handle: ".modal-header"});

    fnsdMostrarListaEquipos(1,null);

</script>
<div class="panel panel-info">
    <div class="panel-heading" style="padding: 3px">
        <span class="fa fa-pencil-square-o"></span> Equipos uso interno
        <div class="pull-right">
            <span  data-toggle="tooltip" data-placement="bottom" title="En Proceso">EP: <span class="label-info badge"> <?=$Equipos->getAlertStats(14,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoUsuario'])?></span></span>
            <span  data-toggle="tooltip" data-placement="bottom" title="Asignados">AS: <span class="label-success badge"><?=$Equipos->getAlertStats(13,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoUsuario'])?></span></span>

            <span  data-toggle="tooltip" data-placement="bottom" title="Entregados">EN: <span class="label-waring badge"> <?=$Equipos->getAlertStats(15,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoUsuario'])?></span></span>
            <span  data-toggle="tooltip" data-placement="bottom" title="Enviados">EV: <span class="label-danger badge"> <?=$Equipos->getAlertStats(16,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoUsuario'])?></span></span>
        </div>
    </div>
    <div id="cont-toolbar" class="toolbars">
        <div class="row">
            <div class="col-md-12">
                <button id="btnHome" class="btn btn-primary btn-xs" onclick="fnsdMenu(8,0)"><i class="fa fa-refresh"></i></button>
                <button  id="elista" class="btn btn-primary btn-xs" onclick="fnsdMenu(8,null)"><i class="fa fa-list"></i> Lista </button>
                <button data-opcion="alta" id="eNuevo" class="btn btn-primary btn-xs" onclick="fnsdMenu(12,null)"><i class="fa fa-file"></i> Nuevo</button>
                <button data-opcion="vista" id="eBuscar" class="btn btn-default btn-xs" data-toggle="modal" data-target="#mdl_busqueda_equipo" ><i class="fa fa-search"></i> Buscar </button>
                <button class="btn btn-success btn-xs" id="eSave" onclick="fnsdAsignarEquipo()"><i class="glyphicon glyphicon-floppy-disk"></i> Guardar</button>
                <button data-opcion="cambio" id="eSaveEdit" class="btn btn-success btn-xs" onclick="fnsdEditarEquipoAsignado()"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
                <button data-opcion="alta" id="eAddDoc" class="btn btn-primary btn-xs" onclick="fnsdShowModalAddDocuments(1)"><span class="glyphicon glyphicon-paperclip"></span></button>
                <button data-opcion="alta" id="eAddImg" class="btn btn-primary btn-xs" onclick="fnsdShowModalAddDocuments(2)"><span class="glyphicon glyphicon-camera"></span></button>
                <!-- Grupo de Botonos -->
                <div data-opcion="cambio" class="btn-group dropdown" id="btn-print">
                    <button id="eImprimeDocumento" class="btn btn-success btn-xs active dropdown-toggle"
                            type="button" data-toggle="dropdown">
                        Imprimir <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0)" onclick="fnsdimprimirEquipo(1)"> Carta de Asignaci&oacute;n</a></li>
                        <li><a href="javascript:void(0)" onclick="fnsdimprimirEquipo(2)"> Carta de Entrega</a></li>
                        <li><a href="javascript:void(0)" onclick="fnsdimprimirEquipo(3)"> Carta de Envio</a></li>
                    </ul>
                </div>
                <div data-opcion="vista" class="btn-group dropdown" id="btn-print">
                    <button id="eFiltro" class="btn btn-primary btn-xs gpo-1 dropdown-toggle"
                            type="button" data-toggle="dropdown">
                        <span class="fa fa-filter"></span> Filtrar <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0)" onclick="fnsdMostrarListaEquipos(2,null)"> Asignados</a></li>
                        <li><a href="javascript:void(0)" onclick="fnsdMostrarListaEquipos(3,null)"> Entregados</a></li>
                        <li><a href="javascript:void(0)" onclick="fnsdMostrarListaEquipos(4,null)"> Enviados</a></li>
                        <li><a href="javascript:void(0)" onclick="fnsdMostrarListaEquipos(5,null)"> En Proceso</a></li>
                        <li><a href="javascript:void(0)" onclick="fnsdMostrarListaEquipos(8,null)"> Re Asigados</a></li>
                        <li class="divider"></li>
                        <li><a href="javascript:void(0)" onclick="fnsdMostrarListaEquipos(1,null)"> Todos</a></li>
                    </ul>
                </div>
                <!-- REASIGNACION DE EQUIPOS -->
                <button id="eReasignacion" class="btn hidden btn-danger  btn-xs" onclick="fnReasignarEquipo(1,<?=$confirm?>)"><span class="fa fa-recycle"></span> Reasignar</button>

                <span class="pull-right">Se encontraron <span id="lbl-num" class="badge label label-success">0</span> Equipo(s)</span>
            </div>
        </div>
    </div>
    <div class="panel-body no-padding">
        <div id="listTable" style="height: 75vh;font-size: 12px;">

        </div>
    </div>
</div>


<!-- Modal busqueda de Equipo -->
<div class="modal fade" id="mdl_busqueda_equipo" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-search"></i> Equipos uso interno</h4>
            </div>
            <div class="modal-body">
                <form id="frm_search_equipos" action="#" onsubmit="fnsdBuscar_equipo_inerno(1); return false;" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Buscar por:
                                <input id="e_text_buscar" placeholder="Buscar por: Folio, Nombre, Departamento, Series, Codigo" class="form-control input-sm" />
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
                                    $text_sql =  "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos WHERE idCatalogo = 7 ORDER BY Descripcion ASC";
                                    $Equipos->_query = $text_sql ;
                                    $Equipos->get_result_query();
                                    $row = $Equipos->_rows;

                                    for($i=0; $i < count($row) ; $i++){
                                        echo "<option value='".$row[$i][0]."'>".$row[$i][1]."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                Departamento:
                                <select id="e_departamento" class="form-control input-sm">
                                    <option value="0"> Todos </option>
                                    <?php
                                    $text_sql =  "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE NoEstado = 1 ORDER BY Descripcion ASC";
                                    $Equipos->_query = $text_sql ;
                                    $Equipos->get_result_query();
                                    $row = $Equipos->_rows;

                                    for($i=0; $i < count($row) ; $i++){
                                        echo "<option value='".$row[$i][0]."'>".$row[$i][1]."</option>";
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
                                    $text_sql =  "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos WHERE idCatalogo = 8 ORDER BY Descripcion ASC";
                                    $Equipos->_query = $text_sql ;
                                    $Equipos->get_result_query();
                                    $row = $Equipos->_rows;

                                    for($i=0; $i < count($row) ; $i++){
                                        echo "<option value='".$row[$i][0]."'>".$row[$i][1]."</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                            <div class="form-group">
                                Usuario Registra:
                                <select id="e_usuario_registra" class="form-control input-sm">
                                    <option value="0"> Todos </option>
                                    <?php
                                    $text_sql =  "SELECT NoUsuario,NombreDePila FROM SINTEGRALGNL.BGECatalogoUsuarios WHERE NoDepartamento = '0109' AND NoEstado = 1 ORDER BY NombreDePila ASC";
                                    $Equipos->_query = $text_sql ;
                                    $Equipos->get_result_query();
                                    $row = $Equipos->_rows;

                                    for($i=0; $i < count($row) ; $i++){
                                        echo "<option value='".$row[$i][0]."'>".$row[$i][1]."</option>";
                                    }
                                    ?>

                                </select>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="mdl_btn_search" onclick="fnsdBuscar_equipo_inerno(1)" class="btn btn-primary btn-sm" ><i class="fa fa-search"></i> Buscar</button>
                <button id="mdl_btn_close" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
                <button onclick="$('#frm_search_equipos').Frmreset();" class="btn btn-default btn-sm" ><i class="fa fa-trash"></i> Limpiar</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal busqueda de equipo -->
