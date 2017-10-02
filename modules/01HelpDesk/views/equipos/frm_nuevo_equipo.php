<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 15/02/2017
 * Time: 04:15 PM
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

$FechaActual = date("Ymd");

?>
<h4 id="titulo_opc" style="text-align: center; margin:5px;background: #f4f4f4;padding: 2px; border-radius:5px;">Registro de Equipo</h4>
<div class="row">
    <div class="col-md-6">
        <table class="tablaDetailticket">
            <tr>
                <td>Nombre Completo: </td>
                <td colspan="3">


                    <select class="form-control" id="nombrecompleto"  >
                        <option value="0">-- Seleccione un empleado --</option>
                    </select>


                </td>
            </tr>
            <tr>
                <td>Puesto: </td>
                <td><input id="puesto" name="puesto" class="form-control input-sm" size="30%" type="text" /></td>
                <td>Departamento: </td>
                <td>
                    <select id="depto" name="depto" class="form-control input-sm">
                        <?php
                        $Equipos->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos ORDER BY Descripcion ASC";
                        $Equipos->get_result_query();

                        $result = $Equipos->_rows;

                        for($i = 0 ; $i < count($result) ; $i++){
                            echo "<option value='".$result[$i][0]."'>".$result[$i][1]."</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Estatus: </td>
                <td>
                    <select class="form-control input-sm" name="estadoequipo" id="estadoequipo">
                        <?php
                        $Equipos->_query = "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos WHERE idCatalogo = 8 AND idDescripcion = 4";
                        $Equipos->get_result_query();

                        $result = $Equipos->_rows;

                        echo "<option value='".$result[0][0]."'>".$result[0][1]."</option>";

                        ?>
                    </select>
                </td>
                <td>Fecha Registro: </td>
                <td><input id="fecha_registro" name="fecha_registro" class="form-control input-sm datepicker" value="<?=$Equipos->getFormatFecha($FechaActual,2)?>" type="text" /></td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <table class="tablaDetailticket" width="89%">
            <tr>
                <td>Equipo</td>
                <td>
                    <select class="form-control input-sm" id="equipo" name="equipo">
                        <?php
                        $Equipos->_query = "SELECT idDescripcion,Descripcion  FROM BSHCatalogoCatalogos WHERE idCatalogo = 7";
                        $Equipos->get_result_query();

                        $result = $Equipos->_rows;

                        for($i = 0 ; $i < count($result) ; $i++){
                            echo "<option value='".$result[$i][0]."'>".$result[$i][1]."</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>Marca</td>
                <td><input class="form-control input-sm" type="text" id="marca" name="marca" /></td>
            </tr>
            <tr>
                <td>Modelo</td>
                <td><input class="form-control input-sm" type="text" id="modelo" name="modelo" /></td>
                <td>Procesador</td>
                <td><input class="form-control input-sm" type="text" id="procesador" name="procesador" /></td>
            </tr>
            <tr>
                <td>Memoria</td>
                <td><input size="15" class="form-control input-sm" type="text" id="memoria" name="memoria" /></td>
                <td>Disco</td>
                <td><input size="15" class="form-control input-sm" type="text" id="disco" id="disco" /></td>
            </tr>
            <tr>
                <td>Codigo Cedis</td>
                <td><input size="10"  maxlength="6" class="form-control input-sm" type="text" id="codigo" name="codigo" /></td>
                <td>Serie Cedis</td>
                <td><input class="form-control input-sm"  maxlength="11" type="text" id="serie" name="serie" /></td>
            </tr>
            <tr>
                <td>Serie Equipo</td>
                <td colspan="3"><input size="20" class="form-control input-sm" type="text" id="serieequipo" name="serieequipo" /></td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-xs-12" style="margin: 5px;">
        <button class="btn btn-success hidden btn-xs" id="imgLoad" onclick="fnsdAsignarEquipo()"><i class="glyphicon glyphicon-floppy-disk"></i> Guardar</button>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?=\core\core::ROOT_APP?>site_design/js/jsCalendario.js"></script>
<script language="JavaScript">
    $(document).ready(function(){
        $("#eSaveEdit").hide();
        $("#eReasignacion").addClass("hidden");
        $("#eSave").show();
        $("#eImprimeDocumento").hide();
        $("#eAddDoc").hide();
        $("#eAddImg").hide();
        $("#btnHome").hide();
        $("#elista").show();
        confirm_close = true;
        $("input").focus(function (){
            $(this).select();
        });
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
    });
</script>
