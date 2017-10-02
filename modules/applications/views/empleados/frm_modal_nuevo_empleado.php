<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 14/02/2017
 * Time: 03:38 PM
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
include "../../../../core/model_empleados.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$Empleado = new \core\model_empleados($_SESSION['data_login']['BDDatos']);

$Empleado->valida_session_id($_SESSION['data_login']['NoUsuario']);



if($_POST['opc'] != 2){
    // parametro para indicar que se esta registrando desde catalogos

    $_POST['opc'] = 1;
}

?>
<script language="JavaScript">
    $(document).ready(function(){
        $('#myModal').modal({backdrop: 'static', keyboard: false})
        $("#myModal").draggable({
            handle: ".modal-header"
        });

        $(':input').blur(function(){
            valor = $.this.val(55);
            //...... codigo para llamar al php pasandole valor
        });
    });
</script>

<div class="modal-header">
    <h1 class="modal-title">
        <i class="fa fa-user-plus"></i> Alta de Empleado
    </h1>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="TituloTab">Datos del Empleado</div>
            <table class="tablaDetailticket">
                <tr>
                    <td># Empresa</td>
                    <td>Tpo. Empleado</td>
                    <td># Nomina</td>
                </tr>
                <tr>
                    <td>
                        <select id="idempresa" style="width: 120px" class="formInput">
                            <option value="0">-- --</option>
                            <?php
                            $Empleado->_query = "SELECT idEmpresa,Descripcion,Abreviacion FROM BGEEmpresas ORDER BY idEmpresa ASC;";
                            $Empleado->get_result_query();
                            for($i = 0 ; $i < count($Empleado->_rows); $i++){
                                echo "<option value='".$Empleado->_rows[$i][0]."'>".$Empleado->_rows[$i][2]." - ".$Empleado->_rows[$i][1]."</option>";                            }
                            ?>

                        </select>
                    </td>
                    <td>
                        <select id="idtpoempleado" onchange="if(this.value == 2){ $('#noempleado').attr('disabled', true); }else{ $('#noempleado').attr('disabled', false); }" class="formInput">
                            <option value="0">-- --</option>
                            <option value="1">Empleado Interno</option>
                            <option value="2">Empleado Externo</option>
                        </select>
                    </td>
                    <td>
                        <input id="noempleado" placeholder="Numero de Nomina" class="formInput" >
                    </td>
                </tr>

                <tr>
                    <td>Nombre (s): </td>
                    <td colspan="2"><input id="nombreempleado" placeholder="Nombre (s)" class="formInput" ></td>
                </tr>
                <tr>
                    <td>Ap Paterno: </td>
                    <td colspan="2"><input id="appaterno" placeholder="Apellido Paterno" class="formInput" ></td>
                </tr>
                <tr>
                    <td>Ap Materno: </td>
                    <td colspan="2"><input id="apmaterno" placeholder="Apellido Materno" class="formInput" ></td>
                </tr>
                <tr>
                    <td>Departamento: </td>
                    <td colspan="2">
                        <select id="nodpto" class="formInput">
                            <option value="0">-- --</option>
                            <?php
                            $Empleado->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE NoEstado = 1 ORDER BY Descripcion ASC;";
                            $Empleado->get_result_query();
                            for($i = 0 ; $i < count($Empleado->_rows); $i++){
                                echo "<option value='".$Empleado->_rows[$i][0]."'>".$Empleado->_rows[$i][1]."</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Puesto: </td>
                    <td colspan="2">
                        <select id="idpuesto" class="formInput">
                            <option value="0">-- --</option>
                            <?php
                            $Empleado->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 28 AND NoEstatus = 1 ORDER BY Descripcion ASC;";
                            $Empleado->get_result_query();
                            for($i = 0 ; $i < count($Empleado->_rows); $i++){
                                echo "<option value='".$Empleado->_rows[$i][0]."'>".$Empleado->_rows[$i][1]."</option>";
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Correo: </td>
                    <td colspan="2"><input id="correo" placeholder="Correo del Empleado" type="email" class="formInput" ></td>
                </tr>
                <tr>
                    <td>Tel&eacute;fono: </td>
                    <td colspan="2"><input id="tel01" type="tel" placeholder="Tel&eacute;fono" class="formInput" ></td>
                </tr>
                <tr>
                    <td>Ext.: </td>
                    <td colspan="2"><input id="tel02" placeholder="Extensi&oacute;n" class="formInput" ></td>
                </tr>
                <tr>
                    <td>Celular: </td>
                    <td colspan="2"><input id="tel03" placeholder="Celular" class="formInput" ></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <div class="TituloTab" >Datos Personales del Empleado</div>
            <table class="tablaDetailticket" style="width: 100%">
                <tr>
                    <td style="width: 55px;">Direcci&oacute;n: </td>
                    <td><input id="direccion" placeholder="Direcci&oacute;n" class="formInput" ></td>
                </tr>
                <tr>
                    <td>Tel&eacute;fono: </td>
                    <td><input id="tel04" placeholder="Tel&eacute;fono"  class="formInput" ></td>
                </tr>
                <tr>
                    <td>Tel&eacute;fono: </td>
                    <td><input id="tel05" placeholder="Tel&eacute;fono"  class="formInput" ></td>
                </tr>
            </table>
            <hr/>
            <table class="tablaDetailticket">
                <tr>
                    <td style="width: 55px;">Estado: </td>
                    <td>
                        <select id="estatus" class="formInput">
                            <option value="1">Activo</option>
                            <option value="0" >Desactivado</option>
                        </select>
                    </td>
                    <td>Fecha: </td>
                    <td><input class="formInputXs" disabled value="<?=$Empleado->getFormatFecha(date("Ymd"),2)?>" ></td>
                </tr>
            </table>
            <div id="imgLoad"></div>
        </div>
    </div>
    <div class="row">
        <div id="resultrge" class="col-md-12">

        </div>
    </div>
</div>
<div class="modal-footer" style="text-align: left;">
    <button class="btn btn-primary btn-sm" onclick="fngnAltaEmpleado(<?=$_POST['opc']?>)" id="btnSave"><span id="spinner"></span> Guardar</button>
    <button id="modalbtnclose" class="btn btn-danger btn-sm" onclick="fnsdcerrarModal('myModal')">Cerrar</button>
    <button id="modalbtnclose2" class="btn btn-danger hidden btn-sm"  data-dismiss="modal">Cerrar</button>

</div>
