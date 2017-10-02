<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 01/03/2017
 * Time: 11:42 AM
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
include "../../../../core/model_empleados.php";

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

$connect = new \core\model_empleados($_SESSION['data_login']['BDDatos']);

$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

$idEmpleado = $_POST['idEmpleado'];

$textSql  = "SELECT a.idEmpleado,a.NoEmpleado,a.NoDepartamento,b.Descripcion,a.Nombre,a.ApPaterno,a.ApMaterno,
a.Correo,a.Telefonoe01,a.Telefonoe02,a.Telefonoe03,a.Direccion,a.Telefonop01,a.Telefonop02,
a.NoEstado,if(a.NoEstado = 1,'Activo','Inactivo'),a.FechaAlta,a.FechaUM,c.Descripcion,a.NoPuesto,d.Descripcion
FROM SINTEGRALGNL.BGEEmpleados as a
LEFT JOIN BGECatalogoDepartamentos as b
ON a.NoDepartamento = b.NoDepartamento
LEFT JOIN BGEEmpresas as c
ON LEFT(a.NoEmpleado,2) = c.idEmpresa
LEFT JOIN BGECatalogoGeneral as d
ON a.NoPuesto = d.OpcCatalogo AND CodCatalogo = 28";

$where = " WHERE a.idEmpleado = $idEmpleado LIMIT 1";

$connect->_query = $textSql.$where;
$connect->get_result_query();

$datarow = $connect->_rows[0];



$idEmpresa = substr($datarow[1],0,2);

$TipoEmpleado = substr($datarow[1],2,1);

if($TipoEmpleado == 1){
    $options = "<option value='".$TipoEmpleado."'>Empleado Interno</option><option value='2'>Empleado Externo</option>";
}else{
    $options = "<option value='".$TipoEmpleado."'>Empleado Externo</option><option value='1'>Empleado Interno</option>";
}

if($datarow[14] == 1){
    $optionsEstado = "<option value='0'>Desactivado</option>";
}else{
    $optionsEstado = "<option value='1'>Activado</option>";
}

?>
<script language="JavaScript">
    $(document).ready(function(){
        $('#myModal_edit').modal('toggle');
        $("#myModal_edit").draggable({
            handle: ".modal-header"
        });
    });
</script>
<div class="modal fade" id="myModal_edit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"> Editar Empleado <?=$datarow[18]?></h4>
            </div>
            <div class="modal-body">

                <form id="frm_editar_empleado">
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
                                        <select id="edit_idempresa" disabled style="width: 120px" class="formInput">
                                            <option value="<?=$idEmpresa?>"><?=$datarow[18]?></option>
                                            <?php
                                            //                                        $ConsultaSql = $connect->Consulta("SELECT idEmpresa,Descripcion,Abreviacion FROM BGEEmpresas WHERE idEmpresa <> $idEmpresa ORDER BY idEmpresa ASC;");
                                            //
                                            //                                        while($row = mysqli_fetch_array($ConsultaSql)){
                                            //                                            echo "<option value='".$row[0]."'>".$row[2]." - ".$row[1]."</option>";
                                            //                                        }

                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="edit_idtpoempleado" disabled onchange="if(this.value == 2){ $('#edit_noempleado').attr('disabled', true); }else{ $('#edit_noempleado').attr('disabled', false); }" class="formInput">
                                            <?=$options?>
                                        </select>
                                    </td>
                                    <td>
                                        <input id="edit_noempleado" disabled placeholder="Numero de Nomina" value="<?=$datarow[1]?>" class="formInput" >
                                    </td>
                                </tr>

                                <form id="infoUser" name="infoUser">
                                    <tr>
                                        <td>Nombre (s): </td>
                                        <td colspan="2"><input id="edit_nombreempleado" value="<?=$datarow[4]?>" placeholder="Nombre (s)" class="formInput" ></td>
                                    </tr>
                                    <tr>
                                        <td>Ap Paterno: </td>
                                        <td colspan="2"><input id="edit_appaterno" value="<?=$datarow[5]?>" placeholder="Apellido Paterno" class="formInput" ></td>
                                    </tr>
                                    <tr>
                                        <td>Ap Materno: </td>
                                        <td colspan="2"><input id="edit_apmaterno" value="<?=$datarow[6]?>" placeholder="Apellido Materno" class="formInput" ></td>
                                    </tr>
                                    <tr>
                                        <td>Departamento: </td>
                                        <td colspan="2">
                                            <select id="edit_nodpto" class="formInput">
                                                <option value="<?=$datarow[2]?>"><?=$datarow[3]?></option>
                                                <option value="0"> </option>
                                                <?php
                                                $ConsultaSql = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos where NoDepartamento <> '$datarow[2]' ORDER BY Descripcion ASC;";
                                                $connect->_query = $ConsultaSql ;
                                                $connect->get_result_query();
                                                $row = $connect->_rows;

                                                for($i=0;$i < count($row);$i++){
                                                    echo "<option value='".$row[$i][0]."'>".utf8_encode($row[$i][1])."</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Puesto: </td>
                                        <td colspan="2">
                                            <select id="edit_idpuesto" class="formInput">
                                                <option value="<?=$datarow[19]?>"><?=$datarow[20]?></option>
                                                <option value="0"></option>
                                                <?php
                                                $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 28 AND OpcCatalogo != $datarow[19]  AND NoEstatus = 1 ORDER BY Descripcion ASC;";
                                                $connect->get_result_query();
                                            for($i = 0 ; $i < count($connect->_rows); $i++){
                                                echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                            }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Correo: </td>
                                        <td colspan="2"><input id="edit_correo" value="<?=$datarow[7]?>" placeholder="Correo del Empleado" type="email" class="formInput" ></td>
                                    </tr>
                                    <tr>
                                        <td>Tel&eacute;fono: </td>
                                        <td colspan="2"><input id="edit_tel01" value="<?=$datarow[8]?>" type="tel" placeholder="Tel&eacute;fono" class="formInput" ></td>
                                    </tr>
                                    <tr>
                                        <td>Ext.: </td>
                                        <td colspan="2"><input id="edit_tel02" value="<?=$datarow[9]?>" placeholder="Extensi&oacute;n" class="formInput" ></td>
                                    </tr>
                                    <tr>
                                        <td>Celular: </td>
                                        <td colspan="2"><input id="edit_tel03"  value="<?=$datarow[10]?>" placeholder="Celular" class="formInput" ></td>
                                    </tr>
                                </form>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <div class="TituloTab" >Datos Personales del Empleado</div>
                            <table class="tablaDetailticket" style="width: 100%;">
                                <tr>
                                    <td style="width: 55px;">Direcci&oacute;n: </td>
                                    <td><input id="edit_direccion1" value="<?=$datarow[11]?>" placeholder="Direcci&oacute;n" class="formInput" ></td>
                                </tr>
                                <tr>
                                    <td>Tel&eacute;fono: </td>
                                    <td><input id="edit_tel04" value="<?=$datarow[12]?>" placeholder="Tel&eacute;fono"  class="formInput" ></td>
                                </tr>
                                <tr>
                                    <td>Tel&eacute;fono: </td>
                                    <td><input id="edit_tel05"  style="width: 100%;" value="<?=$datarow[13]?>" placeholder="Tel&eacute;fono"  class="formInput" ></td>
                                </tr>
                            </table>
                            <hr/>
                            <table class="tablaDetailticket">
                                <tr>
                                    <td style="width: 55px;">Estado: </td>
                                    <td>
                                        <select id="edit_estatus" class="formInput">
                                            <option value="<?=$datarow[14]?>"><?=$datarow[15]?></option>
                                            <?=$optionsEstado?>
                                        </select>
                                    </td>
                                    <td>Fecha: </td>
                                    <td><input class="formInput"  value="<?=$connect->getFormatFecha($datarow[16],2)?>"  disabled  ></td>
                                </tr>
                            </table>
                            <div id="imgLoad"></div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div id="resultrge" class="col-md-12">

                    </div>
                </div>

            </div>
            <div class="modal-footer" style="text-align: left;">
                <button class="btn btn-primary btn-sm" id="btnSave" onclick="fngnEditarEmpleado(2,<?=$idEmpleado?>,1,1)" ><i id="spinner" class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger btn-sm" id="modalbtnclose" onclick="$('#myModal_edit').modal('toggle')"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>
