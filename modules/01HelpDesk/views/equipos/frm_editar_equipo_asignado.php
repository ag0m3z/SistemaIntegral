<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 15/02/2017
 * Time: 05:26 PM
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

$Equipo = new \core\model_equipos($_SESSION['data_login']['BDDatos']);

$Equipo->valida_session_id($_SESSION['data_login']['NoUsuario']);



if($_REQUEST['fl']){

    \core\core::setTitle("Editar Equipo");



    $consulta = "SELECT I.NombreCompleto,I.Estatus,CC.Descripcion,I.Puesto,I.NoDepartamento,D.Descripcion,I.FechaAsignacion,I.Equipo,C.Descripcion,I.Marca,I.Modelo,I.Procesador,I.Memoria,I.Disco,
                I.CodigoCedis,I.SerieCedis,I.SerieEquipo,I.MotivoAsignacion,I.Caracteristicas,I.MotivoEntrega,I.CondicionesEntrega,I.UsuarioEquipo,I.ContrasenaEquipo,I.FechaRegistro,I.FechaAsignacion,I.FechaEntrega,I.FechaEnvio,I.FolioReasignacion
                FROM BSHInventarioEquipos AS I
                JOIN BGECatalogoDepartamentos AS D
                  ON I.NoDepartamento = D.NoDepartamento
                JOIN BSHCatalogoCatalogos AS C
                  ON I.Equipo = C.idDescripcion AND C.idCatalogo = 7
                JOIN BSHCatalogoCatalogos AS CC
                  ON I.Estatus = CC.idDescripcion AND CC.idCatalogo = 8
                WHERE Folio = '".$_REQUEST['fl']."'";

    $Equipo->_query = $consulta ;
    $Equipo->get_result_query();

    $data = $Equipo->_rows[0];
}
?>

<style type="text/css" media="screen">
    .ui-tabs-vertical { width: 89%; }
    .ui-tabs-vertical .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 12em; }
    .ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; margin: 0 -1px .2em 0; }
    .ui-tabs-vertical .ui-tabs-nav li a { display:block; }
    .ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: right; width: 56em;}
</style>
<div class="row" >
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading" style="padding: 4px">
                <h4 class="panel-title">
                    <strong class="pull-right">Folio: <span id="num_folio" style="color:#333333;"><?=$Equipo->getFormatFolio($_REQUEST['fl'],4)?></span></strong> Datos del Usuario
                </h4>
            </div>
            <div class="panel-body">
                <table class="tablaDetailticket" style="margin-top: -10px;">
                    <tr>
                        <td>Nombre Completo</td>
                        <td colspan="3">
                            <input class="hidden" disabled id="NoFolio" value="<?=$_REQUEST['fl']?>">
                            <select class="form-control" id="nombrecompleto"  >
                                <option value="0" selected><?=$data[0]?></option>

                            </select>
                        </td>
                        <td>Estatus</td>
                        <td>
                            <select class="form-control input-sm" name="estadoequipo" id="estadoequipo">
                                <?php
                                echo "<option value='".$data[1]."'>".$data[2]."</option>";
                                /**
                                 * $cat_estado = $Obj->Consulta("SELECT * FROM BSHCatalogoCatalogos WHERE idCatalogo = 8");
                                 *                while($result = mysqli_fetch_array($cat_estado)){
                                 *                     echo "<option value='".$result[1]."'>".$result[3]."</option>";
                                 *                }
                                 */
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Puesto</td>
                        <td><input id="puesto" name="puesto" value="<?=$data[3]?>" class="form-control input-sm" size="30%" type="text" /></td>
                        <td>Departamento</td>
                        <td>
                            <select id="depto" name="depto" class="form-control input-sm">
                                <option value="<?=$data[4]?>"><?=$data[5]?></option>
                                <?php
                                $Equipo->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos ORDER BY Descripcion ASC";
                                $Equipo->get_result_query();
                                for($i=0;$i < count($Equipo->_rows);$i++){
                                    echo "<option value='".$Equipo->_rows[$i][0]."'>".$Equipo->_rows[$i][1]."</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td>Fecha de Registro</td>
                        <td><input id="fechaasig" name="fechaasig" class="form-control input-sm datepicker" value="<?=$Equipo->getFormatFecha($data[23],2)?>" type="text" /></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 table-condensed">
        <div id="tabs" style="padding: 0px;border: none;margin-top:-20px;">
            <ul style="border:none">
                <li><a href="#inf">Informaci&oacute;n General</a></li>
                <li><a href="#his">Historial</a></li>
                <li><a href="#doc">Documentos</a></li>
                <li><a href="#pic">Fotos</a></li>

            </ul>
            <div id="inf" style="padding:0px;">
                <!-- Tab para ver la informacion del Registro -->
                <div class="panel panel-info" style="margin-top:5px;">
                    <div class="panel-heading" role="tab" id="headingOne" style="padding: 5px">
                        <h4 class="panel-title" style="text-align:center;">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" aria-expanded="true" aria-controls="collapseOne">
                                <span id="panel-title2">Datos del Equipo</span>
                            </a>
                        </h4>
                    </div>
                    <div class="panel-body" style="padding-top:14px;padding-left:2px;">
                        <div id="tabs-vertical"  style="padding: 0px;border:none;margin-top:-10px;">
                            <ul>
                                <li><a href="#dEquipo" onclick="TitleHead(1)">Datos del Equipo</a></li>
                                <li><a href="#dAsigna" onclick="TitleHead(2)">Datos de Asignaci&oacute;n</a></li>
                                <li><a href="#dEntrega" onclick="TitleHead(3)">Datos de Entrega</a></li>
                                <li><a href="#dEnvio" onclick="TitleHead(4)">Datos de Envio</a></li>
                            </ul>
                            <div id="dEquipo">
                                <table  class="tablaDetailticket">
                                    <tr>
                                        <td width="100">Equipo</td>
                                        <td>
                                            <select class="form-control input-sm" id="equipo" name="equipo">
                                                <option value="<?=$data[7]?>"><?=$data[8]?></option>
                                                <?php
                                                $Equipo->_query = "SELECT idDescripcion,Descripcion  FROM BSHCatalogoCatalogos WHERE idCatalogo = 7";
                                                $Equipo->get_result_query();
                                                for($i=0;$i < count($Equipo->_rows);$i++){
                                                    echo "<option value='".$Equipo->_rows[$i][0]."'>".$Equipo->_rows[$i][1]."</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>Marca</td>
                                        <td><input class="form-control input-sm" type="text" id="marca" value="<?=$data[9]?>" name="marca" /></td>
                                        <td>Modelo</td>
                                        <td><input size="25" class="form-control input-sm" type="text" id="modelo" value="<?=$data[10]?>" name="modelo" /></td>
                                    </tr>
                                    <tr>
                                        <td>Procesador</td>
                                        <td><input size="15" class="form-control input-sm" type="text" id="procesador" value="<?=$data[11]?>" name="procesador" /></td>
                                        <td>Memoria</td>
                                        <td><input size="15" class="form-control input-sm" type="text" id="memoria" value="<?=$data[12]?>" name="memoria" /></td>
                                        <td>Disco</td>
                                        <td><input size="15" class="form-control input-sm" type="text" id="disco" value="<?=$data[13]?>" id="disco" /></td>
                                    </tr>
                                    <tr>
                                        <td>Codigo Cedis</td>
                                        <td><input size="10" class="form-control input-sm" maxlength="6" type="text" id="codigo" value="<?=$Equipo->getFormatFolio($data[14],5)?>" name="codigo" /></td>
                                        <td>Serie Cedis</td>
                                        <td><input class="form-control input-sm" type="text" maxlength="11" id="serie" value="<?=$data[15]?>" name="serie" /></td>
                                        <td>Serie Equipo</td>
                                        <td><input size="25" class="form-control input-sm" type="text" id="serieequipo" value="<?=$data[16]?>" name="serieequipo" /></td>
                                    </tr>
                                </table>
                            </div>
                            <div id="dAsigna">
                                <table class="tablaDetailticket" style="margin-top: -10px;">
                                    <tr>
                                        <td>Motivo de Asignaci&oacute;n: </td>
                                        <td colspan="5"><input size="120" class="form-control input-sm" type="text" id="motivo" value="<?=$data[17]?>" name="motivo" /></td>
                                    </tr>
                                    <tr>
                                        <td>Caracteristicas/ Accesorios: </td>
                                        <td colspan="5"><input class="form-control input-sm" size="120" type="text" id="caracteristicas" value="<?=$data[18]?>" name="caracteristicas"/></td>
                                    </tr>
                                    <tr>
                                        <td>Fecha Asignaci&oacute;n: </td>
                                        <td colspan="5"><input class="form-control input-sm datepicker" size="120" type="text" value="<?=$Equipo->getFormatFecha($data[24],2)?>" name="caracteristicas"/></td>
                                    </tr>
                                </table>
                            </div>
                            <div id="dEntrega">
                                <table class="tablaDetailticket" style="margin-top: -10px;">
                                    <tr class="estadoclosed">
                                        <td>Condiciones de Entrega:</td>
                                        <td colspan="5"><input id="condicionEntrega" class="form-control input-sm" size="120" value="<?=$data[20]?>" type="text" name="condicionEntrega"/></td>
                                    </tr>
                                    <tr class="estadoclosed">
                                        <td>Motivo de Entrega: </td>
                                        <td colspan="5"><input id="motivoentrega" class="form-control input-sm" size="120" value="<?=$data[19]?>" type="text" name="motivoentrega" /></td>
                                    </tr>
                                    <tr class="estadoclosed">
                                        <td>Usuario: </td>
                                        <td colspan="5"><input id="motivoentrega" class="form-control input-sm" size="120" value="<?=$data[21]?>" type="text" name="motivoentrega" /></td>
                                    </tr>
                                    <tr class="estadoclosed">
                                        <td>Contrase&ntilde;a: </td>
                                        <td colspan="5"><input id="motivoentrega" class="form-control input-sm" size="120" value="<?=$data[22]?>" type="text" name="motivoentrega" /></td>
                                    </tr>
                                    <tr class="estadoclosed">
                                        <td>Fecha Entrega: </td>
                                        <td colspan="5"><input id="motivoentrega " class="form-control input-sm datepicker" size="120" value="<?=$Equipo->getFormatFecha($data[25],2)?>" type="text" name="motivoentrega" /></td>
                                    </tr>
                                </table>
                            </div>
                            <div id="dEnvio">
                                <table class="tablaDetailticket" style="margin-top: -10px;">
                                    <tr class="estadoclosed">
                                        <td>Usuario: </td>
                                        <td colspan="5"><input id="motivoentrega" class="form-control input-sm" size="120" value="<?=$data[21]?>" type="text" name="motivoentrega" /></td>
                                    </tr>
                                    <tr class="estadoclosed">
                                        <td>Contrase&ntilde;a: </td>
                                        <td colspan="5"><input id="motivoentrega" class="form-control input-sm" size="120" value="<?=$data[22]?>" type="text" name="motivoentrega" /></td>
                                    </tr>
                                    <tr class="estadoclosed">
                                        <td>Fecha Envi&oacute;: </td>
                                        <td colspan="5"><input id="motivoentrega" class="form-control input-sm datepicker" size="120" value="<?=$Equipo->getFormatFecha($data[26],2)?>" type="text" name="motivoentrega" /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div id="doc" class="no-padding">
                <table class="tableHistory table-hover" width="100%">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Informaci&oacute;n</th>
                        <!-- <th>Tipo Atenci&oacute;n</th> -->
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Usuario</th>
                        <th>Estatus</th>
                        <th>Funciones</th>
                    </tr>
                    </thead>
                    <?php
                        $Equipo->MostrarDocumentos($_REQUEST['fl'],1);
                    ?>
                </table>
            </div>
            <div id="pic">
                <div class="row">
                    <?php
                    $Equipo->MostrarDocumentos($_REQUEST['fl'],2);
                    ?>
                </div>
            </div>
            <div id="his" class="no-padding">
                <table class="tableHistory   table-hover" width="500">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Informaci&oacute;n</th>
                        <!-- <th>Tipo Atenci&oacute;n</th> -->
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Usuario</th>
                        <th>Estatus</th>
                    </tr>
                    </thead>
                    <?php
                    $Equipo->get_mostrar_historial($_REQUEST['fl']);
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="ShowModal"></div>
<script language="JavaScript" type="text/javascript" src="<?=\core\core::ROOT_APP?>site_design/js/jsCalendario.js"></script>
<script>
    $( "#tabs" ).tabs();
    $("#eSaveEdit").show();
    $("#eSave").hide();
    $("#eImprimeDocumento").show();
    $("#eAddDoc").show();
    $("#eAddImg").show();
    $("#btnHome").hide();
    $("#elista").show();
    $("#eReasignacion").removeClass("hidden");

    $("#nombrecompleto").select2({
        multiple: false,
        tokenSeparators: [','],
        minimumInputLength: 2,
        minimumResultsForSearch: 8,
        ajax: {
            url: "modules/01HelpDesk/src/equipos/fn_buscar_empleado.php",
            dataType: "json",
            type: "GET",
            data: function (params) {

                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {

                return {
                    results: $.map(data, function (item) {

                        return {
                            text: item.tag_value,
                            id: item.tag_id
                        }
                    })
                };

            }
        }
    });

    confirm_close = true;


    $( "#tabs-vertical" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );

    function TitleHead(opc){
        if(opc == 1){
            $("#panel-title2").html("Datos del Equipo");
        }else if(opc == 2){
            $("#panel-title2").html("Datos de Asignaci\u00F3n");
        }else if(opc == 3){
            $("#panel-title2").html("Datos de Entrega");
        }else if(opc == 4){
            $("#panel-title2").html("Datos de Envi\u00F3");
        }
    }
</script>
