<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 23/03/2017
 * Time: 09:36 AM
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


/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$FechaActual = date("Ymd");


if($_SESSION['data_departamento']['NoTipo'] == "D"){
// Si el Usuario Pertenece a un Departameto
    $connect->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE NoEstado = 1 ORDER BY Descripcion ASC";

    $connect->get_result_query();
    $listaDepartamentos = $connect->_rows;

    $connect->_query = "SELECT NoUsuario,NombreDePila FROM SINTEGRALGNL.BGECatalogoUsuarios WHERE NoDepartamento = ".$_SESSION['data_departamento']['NoDepartamento']." AND NoEstado = 1 ORDER BY NombreDePila ASC";
    $connect->get_result_query();
    $queryUser  = $connect->_rows;

}else{
    // si el usuario pertenece a una sucursal
    $connect->_query = "SELECT NoUsuario,NombreDePila FROM SINTEGRALGNL.BGECatalogoUsuarios WHERE NoDepartamento = ".$_SESSION['data_departamento']['NoDepartamento']." AND NoEstado = 1 ORDER BY NombreDePila ASC";
    $connect->get_result_query();
    $queryUser  = $connect->_rows;
}

?>
<!-- JavaScripts -->
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsCalendario.js" type="text/javascript"></script>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsPVenta.js" type="text/javascript"></script>
<script language="JavaScript">
    fn_pv_BuscarEncuesta(1);
    $("#myModal").draggable({handle: ".modal-header"});
    $("input[type=text]").focus(function(){this.select();});

</script>
<!-- END JavaScripts -->
<div id="modalDataEncuesta"></div>
<!-- Panel Info -->
<div class="panel panel-info">
    <div class="panel-heading">
        <i class="fa fa-table"></i> Reporte de Encuestas
    </div>
    <div class="toolbars">
        <!-- Panel de Botones -->
        <button class="btn btn-primary btn-xs" onclick="fnsdMenu(20,20)" ><i class="fa fa-list-ul"></i> Lista de Encuestas</button>
        <button data-toggle="modal" data-target="#myModal" class="btn btn-default btn-xs"><i class="fa fa-search"></i> Buscar</button>
        <button class="btn btn-default btn-xs" onclick="fn_pv_exportar(1)"><i class="fa fa-file-excel-o"></i> Exportar</button>
        <span class="pull-right"><span id="total_encuestas" class="badge label label-success ">0</span> Encuestas
        </span>
        <!-- END Panel de Botones -->
    </div>
    <div id="lListTable" class="panel-body no-padding">
        <!-- Cargar grid de Encuestas-->
    </div>
</div>
<!-- END Panel Info -->

<!-- Modal para Buscar Encuestas -->
<div class="modal fade" data-backdrop="static"  id="myModal" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-search"></i> Reporte de Encuestas </h4>


            </div>
            <div class="modal-body">
                <form id="frmBuscarEncuesta">

                    <div class="row">
                        <div class="col-md-6">
                            <table class="tablesorter">
                                <tr>
                                    <td>Sucursal: </td>
                                    <td>
                                        <select id="id_suc" onchange="fn_pv_cargar_usuarios(this.value)" class="formInput">
                                            <?php
                                            if($_SESSION['data_departamento']['NoTipo'] == "S"){
                                                echo "<option value='".$_SESSION['data_departamento']['NoDepartamento']."'>
                                                                ".$_SESSION['data_departamento']['NombreDepartamento']."
                                                                </option>";
                                            }else{
                                                echo "<option value='0'>Todos</option>";
                                                for($i=0; $i < count($listaDepartamentos);$i++){
                                                    echo "<option value='".$listaDepartamentos[$i][0]."'>".$listaDepartamentos[$i][1]."</option>";
                                                }
                                            }
                                            ?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Usuario: </td>
                                    <td>
                                        <div id="loadUsersSucursal">
                                            <select id="filUser" class="formInput">
                                                <option value="0">Todos</option>
                                                <?php
                                                if($_SESSION['data_departamento']['NoTipo'] == "S"){

                                                    for($i=0; $i < count($queryUser);$i++){
                                                        echo "<option value='".$queryUser[$i][0]."'>".$queryUser[$i][1]."</option>";
                                                    }

                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Supervisor: </td>
                                    <td>
                                        <select id="id_super" class="formInput">
                                            <option value="0"> Todos</option>
                                            <?php
                                            $connect->_query = "SELECT OpcCatalogo,Descripcion,Texto1 FROM BGECatalogoGeneral where CodCatalogo = 19";
                                            $connect->get_result_query();
                                            $querySuc  = $connect->_rows;

                                            for($i=0;$i < count($querySuc);$i++){
                                                echo "<option value='".$querySuc[$i][0]."'>".$querySuc[$i][2]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Zona: </td>
                                    <td>
                                        <select id="id_zona" class="formInput">
                                            <option value="0"> Todas</option>
                                            <?php
                                            $connect->_query = "SELECT OpcCatalogo,Descripcion,Texto1 FROM BGECatalogoGeneral where CodCatalogo = 18";
                                            $connect->get_result_query();
                                            $querySuc = $connect->_rows;
                                            for($i=0;$i < count($querySuc);$i++){
                                                echo "<option value='".$querySuc[$i][0]."'>".$querySuc[$i][2]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tipo Servicio: </td>
                                    <td>
                                        <select id="idTpoServicio" class="formInput">
                                            <option value="0"> Todos</option>
                                            <?php
                                            $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 14 AND NoEstatus = 1";
                                            $connect->get_result_query();
                                            $Query = $connect->_rows;
                                            for($i=0;$i < count($Query);$i++){
                                                echo "<option value='".$Query[$i][0]."'>".$Query[$i][1]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Categoria: </td>
                                    <td>
                                        <select id="idNoCategoria" class="formInput">
                                            <option value="0"> Todas</option>
                                            <?php
                                            $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral where CodCatalogo = 9 AND NoEstatus = 1 ORDER BY Descripcion ASC";
                                            $connect->get_result_query();
                                            $Consulta = $connect->_rows;
                                            for($i=0;$i < count($Consulta);$i++){
                                                echo "<option value='".$Consulta[$i][0]."'>".$Consulta[$i][1]."</option>";
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
                                    <td>Fch Inicio: </td>
                                    <td><input type="text" id="fch_ini" value="<?=$connect->getFormatFecha($FechaActual,2)?>" onfocus='$("#fch_fin").val(this.value);'  class="formInput datepicker" /></td>
                                </tr>
                                <tr>
                                    <td>Fcha Fin:</td>
                                    <td><input type="text" id="fch_fin" value="<?=$connect->getFormatFecha($FechaActual,2)?>"  class="formInput datepicker" /></td>
                                </tr>
                                <tr>
                                    <td>Tipo Producto: </td>
                                    <td colspan="2">
                                        <select id="id_prod" class="formInput" onchange="fn_pv_cargar_marcas(this.value,$('#idNoCategoria').val())">
                                            <option value="0"> Todos</option>
                                            <?php
                                            $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral where CodCatalogo = 5";
                                            $connect->get_result_query();
                                            $querySuc = $connect->_rows;
                                            for($i=0;$i < count($querySuc);$i++){
                                                echo "<option value='".$querySuc[$i][0]."'>".$querySuc[$i][1]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Marca: </td>
                                    <td colspan="2">
                                        <div id="loadMarca">
                                            <select id="id_marca" class="formInput">
                                                <option value="0"> Todas</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Clasificación: </td>
                                    <td colspan="2">
                                        <select disabled id="idClass" class="formInput">
                                            <option value="0"> Todas</option>
                                            <?php
                                            $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral where CodCatalogo = 7";
                                            $connect->get_result_query();
                                            $querySuc = $connect->_rows;
                                            for($i=0;$i < count($querySuc);$i++){
                                                echo "<option value='".$querySuc[$i][0]."'>".$querySuc[$i][1]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Competidor: </td>
                                    <td colspan="2">
                                        <select id="idCompetidor" class="formInput">
                                            <option value="0">Todos</option>
                                            <?php
                                            $connect->_query = 'SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 16 AND NoEstatus = 1';
                                            $connect->get_result_query();
                                            $Query = $connect->_rows;
                                            for($i=0;$i < count($Query);$i++){
                                                echo "<option value='".$Query[$i][0]."'>".$Query[$i][1]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="fn_pv_BuscarEncuesta(2)" class="btn btn-primary btn-sm" id="btn-Buscar"><i class="fa fa-search"></i> Buscar</button>
                <button data-dismiss="modal" class="btn btn-danger btn-sm" id="closemodal"><i class="fa fa-close"></i> Cerrar</button>
                <button class="btn btn-default btn-sm" onclick="$('#frmBuscarEncuesta').Frmreset()"><i class="fa fa-trash"></i> Limpiar</button>
            </div>
        </div>
    </div>
</div>
<!-- END modal para buscar Encuestas  ->
