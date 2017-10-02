<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 12/09/2017
 * Time: 09:24 AM
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();
$Disabled = "";
if($_SESSION['data_login']['NoPuesto']!=1){
    $Disabled = "disabled";
}

?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsCalendario.js"></script>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsPVenta.js"></script>
<script>
    if(<?=$_SESSION['menu_opciones'][5][2][1][0]['OpcionA']?> == 1){
        $("[type-alta]").removeClass('hidden');
    }
    if(<?=$_SESSION['menu_opciones'][5][2][1][0]['OpcionR']?> == 1){
        $("[type-reporte]").removeClass('hidden');
    }
    fn04ListarCotizaciones(1,1);
    $(".modal label").addClass('no-bold');
</script>
<div class="panel panel-info">
    <div class="panel-heading  padding-x3">
        <i class="fa fa-list"></i> Lista de Cotizaciones
    </div>
    <div class="toolbars">
        <button class="btn btn-xs btn-primary" onclick="fnsdMenu(41,41)"><i class="fa fa-refresh"></i></button>

        <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button" class="btn dropdown-toggle btn-xs btn-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list"></i> Filtrar Lista <i class="fa fa-caret-down"></i></button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#dat=1" onclick="fn04ListarCotizaciones(1,1);" > Cotizaciones de Hoy</a></li>
                <li><a href="#dat=2" onclick="fn04ListarCotizaciones(2,1);" > Todas</a></li>
            </ul>
        </div>

        <button class="btn btn-xs hidden btn-default" type-alta onclick="fn04MenuCotizaciones(1)"><i class="fa fa-file"></i> Nueva Cotización</button>
        <button class="btn btn-xs hidden btn-primary" type-reporte onclick='setOpenModal("mdlBuscarCotizaciones")' ><i class="fa fa-search"></i> Buscar</button>
        <span class="pull-right">Total <span id="idtotal"  class="badge bg-green">00</span></span>
    </div>
    <div class="panel-body no-padding">

        <div id="myGrid" style="height: 75vh"></div>

    </div>
</div>

<div class="modal fade " id="mdlBuscarCotizaciones">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-search"></i> Busqueda</h4>
            </div>
            <div class="modal-body">

                <div class="row row-sm">

                    <div class="col-md-6">
                        <div class="form-group">
                            Folio
                            <input id="folio" class="form-control input-sm" placeholder="Folio Cotización" />
                        </div>

                        <div class="form-group">
                           Medio de Contacto
                            <select id="mcontacto" class="form-control input-sm" id="NoUsuario">
                                <option value="0">Todos</option>
                                <?php
                                $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 30 AND NoEstatus = 1 ORDER BY Descripcion ASC";
                                $connect->get_result_query();

                                for($i=0; $i < count($connect->_rows); $i++ ){
                                    echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                }
                                ?>

                            </select>
                        </div>

                        <div class="form-group">
                            Tipo Cotización
                            <select id="tcotizacion" class="form-control input-sm" id="NoUsuario">
                                <option value="0">Todos</option>
                                <?php
                                $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 31 AND NoEstatus = 1 ORDER BY Descripcion ASC";
                                $connect->get_result_query();

                                for($i=0; $i < count($connect->_rows); $i++ ){
                                    echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                }
                                ?>

                            </select>
                        </div>

                        <div class="form-group">
                            Categoría
                            <select id="idcategoria" onchange="fnpv_load_categorias_clasificacion(11,this.value)" class="form-control input-sm" id="NoUsuario">
                                <option value="0">Todas</option>
                                <?php
                                $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 9 AND NoEstatus = 1 ORDER BY Descripcion ASC";
                                $connect->get_result_query();

                                for($i=0; $i < count($connect->_rows); $i++ ){
                                    echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                }
                                ?>

                            </select>
                        </div>

                        <div class="form-group">
                            Tipo
                            <select id="id_tpoproducto" class="form-control input-sm" id="NoUsuario">
                                <option value="0">Todos</option>

                            </select>
                        </div>


                    </div>
                    <div class="col-md-6">

                        <div class="row row-sm">
                            <div class="col-md-12">
                                <div class="form-group">
                                    Usuario
                                    <select <?=$Disabled?> id="usuarioregistra" class="form-control input-sm" id="NoUsuario">
                                        <option value="<?=$_SESSION['data_login']['NoUsuario']?>"><?=$_SESSION['data_login']['NombreDePila']?></option>
                                        <option value="0">Todos</option>
                                        <?php
                                        $connect->_query = "SELECT NoUsuario,NombreDePila FROM SINTEGRALGNL.BGECatalogoUsuarios WHERE NoDepartamento = '".$_SESSION['data_departamento']['NoDepartamento']."' AND NoUsuario <> '".$_SESSION['data_login']['NoUsuario']."' AND NoEstado = 1 ORDER BY NombreDePila ASC";
                                        $connect->get_result_query();

                                        for($i=0; $i < count($connect->_rows); $i++ ){
                                            echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    Estatus
                                    <select id="noestatus" class="form-control input-sm" id="NoUsuario">
                                        <option value="0">Todos</option>
                                        <?php
                                        $connect->_query = "SELECT OpcCatalogo,Texto1 FROM BGECatalogoGeneral WHERE CodCatalogo = 29 AND NoEstatus = 1 ORDER BY Descripcion ASC";
                                        $connect->get_result_query();

                                        for($i=0; $i < count($connect->_rows); $i++ ){
                                            echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    Fecha inicial
                                    <input id="fecha1"  value="<?=date("d/m/Y")?>" class="form-control datepicker input-sm"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    Fecha final
                                    <input id="fecha2"  value="<?=date("d/m/Y")?>" class="form-control datepicker input-sm"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" onclick="fnBuscarCotizacion(1)" ><i class="fa fa-search"></i> Buscar</button>
                <button id="modalbtnclose" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>