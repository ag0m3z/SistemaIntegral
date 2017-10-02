<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 01/02/2017
 * Time: 05:21 PM
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
include "../../../../core/model_tickets.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$tickets = new \core\model_tickets($_SESSION['data_login']['BDDatos']);

//validar sesion del usuario
$tickets->valida_session_id($_SESSION['data_login']['NoUsuario']);

\core\core::setTitle("Nuevo Ticket");

/** @var
 * Permisos
 * if($_SESSION['MenuOpciones'][1][2][1][0]['OpcionA'] == 1){
 * $connect->CargarBotones("alta");
 * }
 * if($_SESSION['MenuOpciones'][1][2][1][0]['OpcionB'] == 1){
 * $connect->CargarBotones("baja");
 * }
 * if($_SESSION['MenuOpciones'][1][2][1][0]['OpcionC'] == 1){
 * $connect->CargarBotones("cambio");
 * }
 * if($_SESSION['MenuOpciones'][1][2][1][0]['OpcionV'] == 1){
 * $connect->CargarBotones("vista");
 * }
 * */

$btnDisabled = "";
$NoDepartamento = $_SESSION['data_departamento']['NoDepartamento'];
if($_SESSION['data_departamento']['NoDepartamento'] != '0109'){$btnDisabled = "disabled";}
if($_SESSION['data_departamento']['NoDepartamento'] == '0205'){$campos = "disabled";}
if( $_SESSION['data_login']['NoPerfil'] <> 5){$campos2 = 'disabled';}

$btnListar = '<button class="btn btn-primary btn-xs" onclick="fnsdMenu(3,0)"><i class="fa fa-list"></i> Lista </button>';
$btnNuevo = '<button class="btn btn-primary btn-xs" onclick="fnsdMenu(2,0)"><i class="fa fa-file"></i> Nuevo</button>';
$GuardarSolicitante = '<button class="btn btn-success btn-xs" data-option="alta" id="btnTicketrequest" onclick="fnsdRegistraTicket(2,\''.$NoDepartamento.'\' )"><i class="fa fa-save"></i> Guardar Ticket</button>' ;
$GuardarTecnico = '<button class="btn btn-success btn-xs" data-option="alta" id="btnSaveTicket" onclick="fnsdRegistraTicket(1,\''.$NoDepartamento.'\',$(\'#mesa_de_ayuda\').val())"><i class="fa fa-save"></i> Guardar</button>';
$btnPlantillas = '<button class="btn btn-primary btn-xs" data-option="vista" disabled><i class="fa fa-code"></i> Plantillas <i class="fa fa-caret-down"></i></button>';
$btnNuevoEmpleado = '&nbsp;<button class="btn btn-primary btn-xs" data-option="alta" data-toggle="modal" title="Alta de Empleado" data-target="#myModal" data-backdrop="static" data-keyboard="false" onclick="fnsdmostrar_diagrama(2)" ><i class="fa fa-user-plus"></i></button>';

?>

<!--  Scripts para Tickets -->
<script language="JavaScript" type="text/javascript" src="site_design/js/jsServiceDesk.js"></script>
<script language="JavaScript" type="text/javascript" src="site_design/js/jsCalendario.js"></script>

<script>
    confirm_close = true;
</script>

<!-- Inicio de Modal, para cargar la Imagen del Diagrama de la Sucursal -->
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div id="diag-suc">
            </div>
        </div>
    </div>
</div><!-- Fin del Modal-->

<div class="panel panel-info">
    <div class="panel-heading padding-x3"><i class="fa fa-list-alt"></i> Registro de Tickets</div>
    <div class="toolbars">

        <?php
        $btnDisabled = "disabled";
        echo $btnListar .'&nbsp;'. $btnNuevo .'&nbsp;';
        if($_SESSION['data_departamento']['AsignarReportes'] == 'NO'){
            //per fil solicitante
            echo $GuardarSolicitante ;
        }else {
            $btnDisabled ="";
            echo $GuardarTecnico ;
        }
        echo "&nbsp;".$btnPlantillas ;
        if($_SESSION['data_login']['NoPerfil'] == 1 ){
            echo $btnNuevoEmpleado ;
        }
        ?>
        <button class="btn btn-primary btn-xs" data-option="alta" data-toggle="modal" title="Buscar Empleado" data-target="#myModal" data-backdrop="static" data-keyboard="false" onclick="fnsdmostrar_diagrama(3)" <?=$btnDisabled?> ><i class="fa fa-search"></i></button>
        <span id="loadicon"></span>
        <div class="pull-right">Ticket Anterior: <span class="label label-success badge"><?=$tickets->getTicketAnterior($_SESSION['data_departamento']['NoDepartamento'])?></span></div>
    </div>


    <div class="panel-body">

        <div class="cont-nvo-tk">
            <div class="row">

                <?php
                if($_SESSION['data_departamento']['AsignarReportes'] == 'NO'){
                    //Perfil Solicitante
                    ?>

                    <div class="col-md-7">
                        <table class="classtable">
                            <tbody>
                            <tr>
                                <td>Mesa de ayuda: </td>
                                <td>
                                    <select name="rAsignenDpto" id="rAsignenDpto" class="formInput">
                                        <option value="0">-- --</option>
                                        <?php
                                        $tickets->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE AsignarReportes = 'SI' ORDER BY Descripcion ASC " ;
                                        $tickets->get_result_query();
                                        $data_departamentos = $tickets->_rows;

                                        for( $i=0; $i < count($data_departamentos) ;$i++) {
                                            echo "<option value='" . $data_departamentos[$i]['NoDepartamento'] . "'>" .$data_departamentos[$i]['Descripcion']. "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 130px;">Nombre:</td>
                                <td colspan="3">
                                    <div id="namesucen">
                                        <select id="rNombreSolicita" class="formInput">
                                            <option value="<?=$_SESSION['data_login']['idEmpleado']?>"><?=$_SESSION['data_login']['NombreDePila']?></option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Medio de Contacto</td>
                                <td colspan="3">
                                    <select name="MedioDeContacto" disabled id="rMedioDeContacto" class="formInput">
                                        <option value="3">Web</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Descripci&oacute;n: </td>
                                <td colspan="3">
                                    <input type="text" maxlength="65" name="Descripcion" id="rDescripcion" placeholder="Descripcion Corta" class="formInput mayus" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">Descripci&oacute;n Detallada: </td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <textarea cols="75" class="form-control mayus" placeholder="Descripción detallada" rows="10" name="Informacion" id="rInformacion"></textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-5" id="cont2">
                        <table class="classtable">
                            <tbody>
                            <tr>
                                <td>Fecha Alta:</td>
                                <td><input type="text" disabled class="formInput datepicker" value="<?=$tickets->getFormatFecha(date("Ymd"),2) ?>" </td>
                            </tr>
                            <tr>
                                <td>Estatus: </td>
                                <td>
                                    <select disabled class="formInput">
                                        <?php
                                        $tickets->_query = "SELECT NoEstatus,Descripcion FROM BSHCatalogoEstatus";
										$tickets->get_result_query();
                                        for ($i=0; $i < count($tickets->_rows);$i++) {
                                            echo "<option value='" . $tickets->_rows[0]["NoEstatus"] . "'>" . $tickets->_rows[0]["Descripcion"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Tipo Atenci&oacute;n: </td>
                                <td>
                                    <select disabled class="formInput">
                                        <?php
                                        echo "<option value='1'>Correctivo</option>";
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <?php
                }else{

                    //Perfil Tenico
                    ?>
                    <div class="col-md-7">
                        <table class="classtable">
                            <tbody>
                            <tr>
                                <td>Mesa de ayuda</td>
                                <td colspan="3">
                                    <select name="mesa_de_ayuda" class="formInput" onchange="cargar_frm_ticket('<?=$_SESSION['data_departamento']['NoDepartamento']?>',this.value)" style="background-color: #ffefa3;" id="mesa_de_ayuda" class="formInput">
                                        <option value="<?=$_SESSION['data_departamento']['NoDepartamento']?>"><?=$_SESSION['data_departamento']['NombreDepartamento']?></option>
                                        <?php
                                        $tickets->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE NoEstado=1 AND AsignarReportes = 'SI' AND NoDepartamento != $NoDepartamento ORDER BY Descripcion";
                                        $tickets->get_result_query();

                                        for ( $i=0; $i < count($tickets->_rows);$i++ ) {
                                            echo "<option value='" . $tickets->_rows[$i]['NoDepartamento'] . "'>" . $tickets->_rows[$i]['Descripcion'] . " (" . $tickets->_rows[$i]['NoDepartamento'] . ")</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 19%;">Dpto Solicita: </td>
                                <td colspan="3">
                                    <select name="Departamento" class="formInput" id="Departamento" onchange="fnsdShowHistoryTicket_Sucursal(this.value,'<?=$_SESSION['data_departamento']['NoDepartamento']?>',true)" class="formInput">
                                        <option value="0">-- --</option>
                                        <?php
                                        $tickets->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE NoEstado=1 ORDER BY Descripcion";
                                        $tickets->get_result_query();

                                        for ( $i=0; $i < count($tickets->_rows);$i++ ) {
                                            echo "<option value='" . $tickets->_rows[$i]['NoDepartamento'] . "'>" . $tickets->_rows[$i]['Descripcion'] . " (" . $tickets->_rows[$i]['NoDepartamento'] . ")</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td style="width: 2px;">
                                    <a href="javascript:void(0)" onclick="fnsdticketinfouser(1)" title="Datos Sucursal" class="btn btn-info btn-xs">
                                        <span class="fa fa-home"></span>
                                    </a>
                                </td>
                                <td style="width: 1px;padding: 0px;margin: 0px;">
                                    <div id="btn_diag"></div>
                                </td>
                            </tr>
                            <tr>
                                <td>Nombre:</td>
                                <td colspan="3">
                                        <select name="NombreSolicita" id="NombreSolicita" class="formInput mayus">
                                            <option value="0">-- --</option>
                                        </select>
                                </td>
                                <td style="width: 2px;">
                                    <a href="javascript:void(0)" onclick="fnsdCargarUsuarios($('#Departamento').val())" title="Datos Sucursal" class="btn btn-info btn-xs">
                                        <span class="fa fa-refresh"></span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Medio:</td>
                                <td colspan="3">
                                    <select name="MedioDeContacto" id="MedioDeContacto" class="formInput">
                                        <?php
                                        $tickets->_query = "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos WHERE idCatalogo=2 AND NOT idDescripcion=0" ;
                                        $tickets->get_result_query();
                                        $rows = $tickets->_rows;
                                        for ( $i=0; $i < count($tickets->_rows);$i++  ) {
                                            echo "<option value='" . $rows[$i]["idDescripcion"] . "'>" . $rows[$i]["Descripcion"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id="dpto_area">
                                <td>&Aacute;rea: </td>
                                <td style="width: 40%">
                                    <select name="area" id="area" <?=$campos?> id="cont" onchange="fnsdloadCategorias(this.value,$('#mesa_de_ayuda').val())" class="formInput">
                                        <option selected>-- --</option>
                                        <?php
                                        $tickets->_query = "SELECT NoArea,Descripcion FROM BSHCatalogoAreas WHERE NoDepartamento= " . $_SESSION['data_departamento']['NoDepartamento'] ." ORDER BY Descripcion DESC" ;
                                        $tickets->get_result_query();

                                        $rows = $tickets->_rows;

                                        for ( $i = 0 ; $i < count($tickets->_rows); $i++) {

                                            echo "<option value='" . $rows[$i]["NoArea"] . "'>" . $rows[$i]["Descripcion"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td style="width: 100%;">
                                    <select id="id_categorias" <?=$campos?> class="formInput"><option>-- --</option></select>
                                </td>
                            </tr>
                            <tr>
                                <td>Descripci&oacute;n: </td>
                                <td colspan="3"><input type="text" maxlength="65" name="Descripcion" id="Descripcion" placeholder="Descripcion Corta" class="formInput mayus" /> </td>
                            </tr>
                            <tr>
                                <td colspan="4">Descripci&oacute;n Detallada: </td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <textarea cols="75" class="form-control mayus" placeholder="Descripcion del Reporte" rows="12" name="Informacion" id="Informacion"></textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- ## Contenedor para Mostrar la Informacion de la Sucursal Seleccionada. ## -->
                    <div class="col-md-5" id="ticket_infouser" style="display: none">
                        <div class="row">
                            <div class="col-md-12">
                                <div style="background: #FAFAFA;padding: 4px;">
                                    Informaci&oacute;n de Sucursal
                                    <span class="pull-right">
                            <?php
                            if($_SESSION['data_login']['NoPerfil'] == 1 ){
                                echo '<a href="javascript:void(0)" class="btn btn-success btn-xs" onclick="fnsdGuardaCambios_InfoSucursal()">Guardar Cambios</a>';
                            }
                            ?>

                                        <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="fnsdticketinfouser(2)">Cerrar</a>
                        </span>
                                </div>
                                <div id="infouser2" style="overflow-y: scroll;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ## ## -->
                    <div class="col-md-5" id="cont2">
                        <form id="frm_data_asign">
                        <table class="classtable">
                            <tbody>
                            <tr>
                                <td>Fecha Alta:</td>
                                <td><input type="text" id="fchalta" class="formInput datepicker" value="<?=$tickets->getFormatFecha(date("Ymd"),2)?>" </td>
                            </tr>

                            <tr>
                                <td style="width: 120px;">Prioridad: </td>
                                <td>
                                    <select name="Prioridad" onchange="fnsdCalculaFechaPromesa(this.value)" id="Prioridad" class="formInput">
                                        <?php
                                        $tickets->_query = 'SELECT * FROM BSHCatalogoCatalogos WHERE idCatalogo=1 AND NOT idDescripcion=0';
                                        $tickets->get_result_query();
                                        $array = $tickets->_rows;

                                        for( $i=0;$i < count($tickets->_rows); $i++ ){
                                            echo "<option value='".$array[$i]["idDescripcion"]."'>".$array[$i]['Descripcion']."</option>";
                                        }

                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Estatus: </td>
                                <td>
                                    <select <?=$campos?> name="Estatus" id="Estatus" class="formInput">
                                        <?php
                                        $tickets->_query = "SELECT NoEstatus,Descripcion FROM BSHCatalogoEstatus";
                                        $tickets->get_result_query();
                                        $rows = $tickets->_rows;
                                        for ($i=0;$i < count($rows); $i++  ) {
                                            echo "<option value='" . $rows[$i]["NoEstatus"] . "'>" . $rows[$i]["Descripcion"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Fecha Promesa: </td>
                                <td><input id="fecha_promesa" type="text" disabled="true" value="<?=$tickets->getFormatFecha($tickets->getFechaPromesa(1,date("Ymd")),2)?>" class="formInput datepicker" /></td>
                            </tr>
                            <tr>
                                <td>Tipo Atenci&oacute;n: </td>
                                <td>
                                    <select name="tipomantenimiento" id="tipomantenimiento" class="formInput">
                                        <?php
                                        $tickets->_query = "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos where idCatalogo=5";
                                        $tickets->get_result_query();
                                        $rows = $tickets->_rows;

                                        for ( $i=0;$i < count($rows); $i++ ) {
                                            echo "<option value='" . $rows[$i]["idDescripcion"] . "'>" . $rows[$i]["Descripcion"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Asignar a: </td>
                                <td>
                                    <select name="AsignenPerson" id="AsignenPerson" class="formInput">
                                        <option value="0">Sin Asignar</option>
                                        <?php
                                        $tickets->_query = "SELECT NoUsuario,NombreDePila FROM SINTEGRALGNL.BGECatalogoUsuarios where NoDepartamento=" . $_SESSION['data_departamento']['NoDepartamento'] . " AND NoEstado=1 ORDER BY NombreDePila ASC ";
                                        $tickets->get_result_query();
                                        $rows = $tickets->_rows;

                                        for ($i=0;$i<count($rows);$i++  ) {
                                            echo "<option value='" . $rows[$i]["NoUsuario"] . "' >" . $rows[$i]["NombreDePila"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        </form>
                    </div>
                    <!-- Caja para mostrar el Historial de Reportes por Sucursal -->
                    <div class="col-md-5" id="HistoryReport"></div>
                    <?php
                }
                ?>
            </div>
        </div>

    </div>
</div>
