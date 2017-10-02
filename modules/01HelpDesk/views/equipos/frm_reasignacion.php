<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 21/06/2017
 * Time: 10:01 AM
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

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


$connect->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos  WHERE NoEstado = 1 ORDER BY Descripcion ASC";
$connect->get_result_query();
$listaDepartamentos = $connect->_rows;

?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsCalendario.js" ></script>
<script>
    setOpenModal("mdl_reasignacion_equipo");
    $(".select2").select2();
    $("#rNoEmpleado").select2({
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
</script>
<div class="modal fade" id="mdl_reasignacion_equipo" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reasignación de Equipo</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group has-success">
                            <input readonly id="rFolio" class="form-control hidden input-lg" value="<?=$_POST['folio']?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            Empleado
                            <select id="rNoEmpleado" class="form-control input-sm" style="width: 100%">
                                <option value="0">-- Buscar Empleado --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            Puesto
                            <input id="rPuesto" placeholder="Nombre del puesto" class="form-control input-sm">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            Departamento
                            <select id="rNoDepartamento" class="form-control select2 input-sm" style="width: 100%">

                                <option value="0">-- Selecciona el Departamento -- </option>
                                <?php

                                for($i=0;$i<count($listaDepartamentos);$i++){
                                    echo "<option value='".$listaDepartamentos[$i][0]."'>".$listaDepartamentos[$i][1]."</option>";
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            Fecha Registro
                            <input id="rFechaRegistro" readonly value="<?=date('d/m/Y')?>" class="form-control datepicker input-sm">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" onclick="fnReasignarEquipo(2)"><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger btn-sm" id="mdlBtnClose" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>
