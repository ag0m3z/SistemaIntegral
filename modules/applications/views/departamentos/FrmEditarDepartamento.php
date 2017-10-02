<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/03/2017
 * Time: 05:15 PM
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

if(!array_key_exists('dpto',$_POST) || $_POST['dpto'] == 0 || $_POST['dpto'] == ""){

    \core\core::MyAlert("No se encontro el departamento","alert");
    exit();
}

$datos = $connect->get_info_departamento($_POST['dpto']);




?>
<script language="JavaScript">
    $(document).ready(function(){
        $('#modal_editar_departamento').modal('toggle');
        $("#modal_editar_departamento").draggable({
            handle: ".modal-header"
        });
    });
</script>
<div class="modal fade" id="modal_editar_departamento" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" style="font-size: 14px"> Actualizar Departamento - <?=$_POST['dpto']?></h4>
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
                                    <input  id="NoDepartamento" disabled value="<?=$datos[0][0]?>" placeholder="( No Segmento )" class="formInput">
                                </td>
                                <td>
                                    <select id="idEmpresa" class="formInput">
                                        <option value="<?=$datos[0][1]?>"><?=$datos[0][33]." - ".$datos[0][2]?></option>
                                        <?php
                                        $connect->_query="SELECT idEmpresa,Descripcion,Abreviacion FROM BGEEmpresas WHERE idEmpresa != ".$datos[0][1]."  ORDER BY idEmpresa ASC;";
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
                                        <option value="<?=$datos[0][3]?>"><?=$datos[0][34]?></option>
                                        <?php
                                        $connect->_query = "SELECT Descripcion,Texto1 FROM BGECatalogoGeneral WHERE Descripcion != '".$datos[0][3]."' AND CodCatalogo = 26 ";
                                        $connect->get_result_query();
                                        $ConsultaSql = $connect->_rows ;
                                        for($i=0;$i < count($ConsultaSql);$i++ ){
                                            echo "<option value='".$ConsultaSql[$i][0]."'>".$ConsultaSql[$i][1]."</option>";
                                        }

                                        ?>
                                    </select>
                                </td>

                            </tr>
                            <tr>
                                <td>No Sucursal</td>
                                <td colspan="3">Nombre Departamento </td>
                            </tr>
                            <tr>
                                <td>
                                    <input id="NoSucursal" value="<?=$datos[0][5]?>"  class="formInput" placeholder="No Sucursal">
                                </td>
                                <td colspan="3">
                                    <input id="NombreDpto" value="<?=$datos[0][6]?>" class="formInput" placeholder="Nombre Departamento">
                                </td>
                            </tr>
                            <form id="infoUser" name="infoUser">
                                <tr>
                                    <td>No Zona: </td>
                                    <td colspan="3">
                                        <select id="idZona" class="formInput">
                                            <option value="<?=$datos[0][15]?>"><?=$datos[0][17]?></option>
                                            <?php
                                            $connect->_query = "SELECT OpcCatalogo,Texto1 FROM BGECatalogoGeneral WHERE OpcCatalogo!= ".$datos[0][15]." AND CodCatalogo = 18 ORDER BY Texto1 ASC";
                                            $connect->get_result_query();
                                            $ConsultaSql = $connect->_rows ;
                                            for($i=0;$i < count($ConsultaSql);$i++ ){
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
                                            <option value="<?=$datos[0][18]?>"><?=$datos[0][19]?></option>
                                            <?php
                                            $connect->_query = "SELECT idEmpleado,CONCAT_WS(' ',Nombre,ApPaterno,ApMaterno) as NombreSupervisor FROM SINTEGRALGNL.BGEEmpleados WHERE NoEstado = 1 AND idEmpleado != ".$datos[0][18]." AND NoPuesto = 1 ORDER BY NombreSupervisor ASC";
                                            $connect->get_result_query();
                                            $ConsultaSql = $connect->_rows ;
                                            for($i=0;$i < count($ConsultaSql);$i++ ){
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
                                            <option value="<?=$datos[0][20]?>"><?=$datos[0][21]?></option>
                                            <?php
                                            $connect->_query = "SELECT idEmpleado,concat_ws('',Nombre,' ',ApPaterno,' ',ApMaterno) as NombreCompleto 
                                              FROM SINTEGRALGNL.BGEEmpleados WHERE idEmpleado !=".$datos[0][20]." AND NoEstado = 1 ORDER BY NombreCompleto ASC";
                                            $connect->get_result_query();
                                            $ConsultaSql = $connect->_rows ;
                                            for($i=0;$i < count($ConsultaSql);$i++ ){
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
                                            <?php
                                            switch($datos[0][4]){
                                                case 'SI':
                                                    echo "<option value='SI'>SI</option><option value='NO'>NO</option>";
                                                    break;
                                                case 'NO':
                                                    echo "<option value='NO'>NO</option><option value='SI'>SI</option>";
                                                    break;
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Estatus: </td>
                                    <td colspan="3">
                                        <select id="NoEstatus" class="formInput">
                                            <?php
                                            switch($datos[0][23]){
                                                case 1:
                                                    echo "<option value='1'>Activo</option><option value='0'>Desactivado</option>";
                                                    break;
                                                case 0:
                                                    echo "<option value='0'>Desactivado</option><option value='1'>Activado</option>";
                                                    break;
                                            }
                                            ?>
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
                                <td style="width: 55px;">Domicilio: </td>
                                <td colspan="2"><input id="direccion" value="<?=$datos[0][7]?>" placeholder="Direcci&oacute;n" class="formInput" ></td>
                            </tr>
                            <tr>
                                <td>Estado: </td>
                                <td colspan="2">
                                    <select id="idEstado" onchange="loadMunicipios(this.value)" class="formInput">
                                        <option value="<?=$datos[0][8]?>"><?=$datos[0][35]?></option>
                                        <?php
                                        $connect->_query = "SELECT id_estado,estado FROM BGEEstados  ORDER BY estado ASC";

                                        $connect->get_result_query();
                                        $ConsultaSql = $connect->_rows ;
                                        for($i=0;$i < count($ConsultaSql);$i++ ){
                                            echo "<option value='".$ConsultaSql[$i][0]."'>".$ConsultaSql[$i][1]."</option>";
                                        }

                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Municipio:
                                </td>
                                <td colspan="2">
                                    <div id="load_municipios">
                                        <select id="idMunicipio" class="formInput">
                                            <option value="<?=$datos[0][9]?>"><?=$datos[0][36]?></option>
                                            <?php
                                            $estad = $datos[0][8];
                                            if($estad != ""){
                                                $connect->_query = "SELECT id_municipio,nombre_municipio,estado FROM BGEMunicipios WHERE estado = '$estad'   ORDER BY nombre_municipio ASC";
                                                $connect->get_result_query();
                                                $ConsultaSql = $connect->_rows ;
                                                for($i=0;$i < count($ConsultaSql);$i++ ){
                                                    echo "<option value='".$ConsultaSql[$i][0]."'>".$ConsultaSql[$i][1]."</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td style="width: 55px;">Correo: </td>
                                <td colspan="2"><input id="correo" value="<?=$datos[0][14]?>" placeholder="Correo" class="formInput" ></td>
                            </tr>
                            <tr>
                                <td>Tel&eacute;fono: </td>
                                <td><input id="tel01" placeholder="Tel&eacute;fono 1" value="<?=$datos[0][10]?>"  class="formInput" ></td>
                                <td><input id="tel02" placeholder="Tel&eacute;fono 2" value="<?=$datos[0][11]?>"  class="formInput" ></td>
                            </tr>
                            <tr>
                                <td>Tel&eacute;fono: </td>
                                <td><input id="tel03" placeholder="Tel&eacute;fono 3" value="<?=$datos[0][12]?>"  class="formInput" ></td>
                                <td><input id="tel04" placeholder="Tel&eacute;fono 4" value="<?=$datos[0][13]?>"  class="formInput" ></td>
                            </tr>
                        </table>
                        <table class="tablaDetailticket" style="width: 100%">
                            <tr>
                                <td>Alta: </td>
                                <td><input disabled value="<?=$datos[0][26]?>" class="formInput"></td>
                                <td><input disabled value="<?=$connect->getFormatFecha($datos[0][29],2)?>" class="formInput"></td>
                                <td><input disabled value="<?=$datos[0][31]?>" class="formInput"></td>
                            </tr>
                            <tr>
                                <td>UM: </td>
                                <td><input disabled value="<?=$datos[0][28]?>" class="formInput"></td>
                                <td><input disabled value="<?=$connect->getFormatFecha($datos[0][30],2)?>" class="formInput"></td>
                                <td><input disabled value="<?=$datos[0][32]?>" class="formInput"></td>
                            </tr>
                        </table>
                        <div id="imgLoad2"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="btnSave" onclick="fnCatFrmNuevoDepartamento(3,'<?=$_POST['dpto']?>',<?=$_POST['listar']?>)" ><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger btn-sm" id="BtnCloseModalDepartamento" onclick="$('#modal_editar_departamento').modal('toggle')"><i class="fa fa-close"></i> Cancelar</button>
            </div>
        </div>
    </div>
</div>
