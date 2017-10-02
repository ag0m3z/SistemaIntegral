<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/03/2017
 * Time: 12:35 PM
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
include "../../../../core/model_departamentos.php";

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

$connect = new \core\model_departamentos($_SESSION['data_login']['BDDatos']);

$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

?>
<script language="JavaScript">
    $(document).ready(function(){
        $('#myModal').modal('toggle');
        $("#myModal").draggable({
            handle: ".modal-header"
        });
    });
</script>
<div class="modal fade" id="myModal" data-backdrop="static"  role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" style="font-size: 14px"> Alta de Nuevo Departamento</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="TituloTab">Informaci&oacute;n del Departamento</div>
                        <table class="tablaDetailticket" style="width: 100%">
                            <tr>
                                <td>NoDepartamento</td>
                                <td>Empresa</td>
                                <td>Tipo</td>
                            </tr>
                            <tr>
                                <td style="width: 20%;">
                                    <input  id="NoDepartamento" placeholder="( No Segmento )" class="formInput">
                                </td>
                                <td>
                                    <select id="idEmpresa" class="formInput">
                                        <option value="0">-- --</option>
                                        <?php
                                        $connect->_query="SELECT idEmpresa,Descripcion,Abreviacion FROM BGEEmpresas ORDER BY idEmpresa ASC;";
                                        $connect->get_result_query();
                                        $ConsultaSql = $connect->_rows ;

                                        for($i=0;$i < count($ConsultaSql);$i++ ){
                                            echo "<option value='".$ConsultaSql[$i][0]."'>".$ConsultaSql[$i][2]." - ".$ConsultaSql[$i][1]."</option>";
                                        }

                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select id="NoTipo" onchange="if(this.value == 'D'){$('#NoSucursal').val(9999);$('#NoSucursal').attr('disabled',true)}else{$('#NoSucursal').val('');$('#NoSucursal').attr('disabled',false);}" class="formInput">
                                        <option value="0">-- --</option>
                                        <option value="D">Departamento</option>
                                        <option value="F">Franquicia</option>
                                        <option value="R">Restaurante</option>
                                        <option value="S">Sucursal</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>No Sucursal</td>
                                <td colspan="3">Nombre Departamento </td>
                            </tr>
                            <tr>
                                <td>
                                    <input id="NoSucursal" class="formInput" placeholder="No Sucursal">
                                </td>
                                <td colspan="3">
                                    <input id="NombreDpto" class="formInput" placeholder="Nombre Departamento">
                                </td>
                            </tr>
                            <form id="infoUser" name="infoUser">
                                <tr>
                                    <td>No Zona: </td>
                                    <td colspan="3">
                                        <select id="idZona" class="formInput">
                                            <option value="0">-- --</option>
                                            <?php
                                            $connect->_query = "SELECT OpcCatalogo,Texto1 FROM BGECatalogoGeneral WHERE CodCatalogo = 18";
                                            $connect->get_result_query();
                                            $ConsultaSql = $connect->_rows ;

                                            for($i=0;$i < count($ConsultaSql);$i++){
                                                echo "<option value='".$ConsultaSql[$i][0]."'>".$ConsultaSql[$i][1]."</option>";
                                            }

                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>No Supervisor: </td>
                                    <td colspan="3">
                                        <select id="idSupervisor" class="formInput">
                                            <option value="0">-- --</option>
                                            <?php
                                            $connect->_query = "SELECT idEmpleado,CONCAT_WS(' ',Nombre,ApPaterno,ApMaterno) FROM SINTEGRALGNL.BGEEmpleados WHERE NoEstado = 1 AND NoPuesto = 1";
                                            $connect->get_result_query();
                                            $ConsultaSql = $connect->_rows ;

                                            for($i=0;$i < count($ConsultaSql);$i++){
                                                echo "<option value='".$ConsultaSql[$i][0]."'>".$ConsultaSql[$i][1]."</option>";
                                            }

                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Encargado: </td>
                                    <td colspan="3">
                                        <select id="idEncargadoDpto" class="formInput">
                                            <option value="0">-- --</option>
                                            <?php
                                            $connect->_query = "SELECT idEmpleado,concat_ws('',Nombre,' ',ApPaterno,' ',ApMaterno) as NombreCompleto FROM SINTEGRALGNL.BGEEmpleados WHERE NoEstado = 1 ORDER BY NombreCompleto ASC";
                                            $connect->get_result_query();
                                            $ConsultaSql = $connect->_rows ;
                                            for($i=0;$i < count($ConsultaSql);$i++){
                                                echo "<option value='".$ConsultaSql[$i][0]."'>".$ConsultaSql[$i][1]."</option>";
                                            }

                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Mesa de ayuda: </td>
                                    <td colspan="3">
                                        <select id="AsignarReportes" class="formInput">
                                            <option value="NO">NO</option>
                                            <option value="SI">SI</option>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Estatus: </td>
                                    <td colspan="3">
                                        <select id="NoEstatus" class="formInput">
                                            <option value="1">Activo</option>
                                            <option value="0">Desactivado</option>
                                        </select>
                                    </td>
                                </tr>

                            </form>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <div class="TituloTab" >Datos de Contacto</div>
                        <table class="tablaDetailticket" style="width: 100%">
                            <tr>
                                <td>id Estado: </td>
                                <td>
                                    <select id="idEstado" onchange="loadMunicipios(this.value)" class="formInput">
                                        <option value="0">Seleccione un Estado</option>
                                        <?php
                                        $connect->_query = "SELECT id_estado,estado FROM BGEEstados ORDER BY estado ASC";
                                        $connect->get_result_query();
                                        $ConsultaSql = $connect->_rows ;
                                        for($i=0;$i < count($ConsultaSql);$i++){
                                            echo "<option value='".$ConsultaSql[$i][0]."'>".$ConsultaSql[$i][1]."</option>";
                                        }

                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <div id="load_municipios">
                                        <select id="idMunicipio" class="formInput">
                                            <option value="0">Seleccione una Poblaci&oacute;n</option>

                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 55px;">Domicilio: </td>
                                <td colspan="2"><input id="direccion" placeholder="Direcci&oacute;n" class="formInput" ></td>
                            </tr>
                            <tr>
                                <td style="width: 55px;">Correo: </td>
                                <td colspan="2"><input id="correo" placeholder="Correo" class="formInput" ></td>
                            </tr>
                            <tr>
                                <td>Tel&eacute;fono: </td>
                                <td><input id="tel01" placeholder="Tel&eacute;fono 1"  class="formInput" ></td>
                                <td><input id="tel02" placeholder="Tel&eacute;fono 2"  class="formInput" ></td>
                            </tr>
                            <tr>
                                <td>Tel&eacute;fono: </td>
                                <td><input id="tel03" placeholder="Tel&eacute;fono 3"  class="formInput" ></td>
                                <td><input id="tel04" placeholder="Tel&eacute;fono 4"  class="formInput" ></td>
                            </tr>
                        </table>
                        <table class="tablaDetailticket hidden" style="width: 100%">
                            <tr>
                                <td>Datos Alta: </td>
                                <td><input class="formInput"></td>
                                <td><input class="formInput"></td>
                                <td><input class="formInput"></td>
                            </tr>
                            <tr>
                                <td>Datos UM: </td>
                                <td><input class="formInput"></td>
                                <td><input class="formInput"></td>
                                <td><input class="formInput"></td>
                            </tr>
                        </table>
                        <div id="imgLoad"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="btnSave" onclick="fnCatFrmNuevoDepartamento(2,0)" ><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger btn-sm" id="modalbtnclose" onclick="$('#myModal').modal('toggle')"><i class="fa fa-close"></i> Cancelar</button>
            </div>
        </div>
    </div>
</div>
