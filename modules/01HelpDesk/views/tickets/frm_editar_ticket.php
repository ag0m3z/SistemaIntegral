<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/02/2017
 * Time: 11:13 AM
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

$Tickets = new \core\model_tickets($_SESSION['data_login']['BDDatos']);

$Tickets->valida_session_id($_SESSION['data_login']['NoUsuario']);

if($_POST['fl'] || $_POST['anio'] || $_POST['dpto']){

    $NoTicket = $_POST['fl'];
    $AnioTicket = $_POST['an'];
    $NoDepartamento = $_POST['nodpto'];

    $Tickets->get_informacion_ticket($NoTicket,$AnioTicket,$NoDepartamento,0);

    $array  = $Tickets->_rows[0];


}

?>
<script language="JavaScript" type="text/javascript" src="<?=\core\core::ROOT_APP?>site_design/js/jsCalendario.js"></script>

<script language="JavaScript">
    $(document).ready(function(){
        $('#myModal').modal('toggle');
        $("#myModal").draggable({
            handle: ".modal-header"
        });
    });
</script>
<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" ><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"> Editar Ticket </h4>
            </div>
            <div class="modal-body" style="margin-bottom: -5px;">

                <div class="row">
                    <div class="col-md-6">
                        <table class="classtable">
                            <tbody>
                            <tr>
                                <td>Dpto. Sucursal: </td>
                                <td colspan="3">
                                    <select name="Departamento" class="formInput" id="eDepartamento" class="formInput">
                                        <option value="<?=$array[22]?>"><?=$array[23]."( ".$array[22]." )" ?> </option>
                                        <?php
                                        $Tickets->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE NoEstado=1 AND NoDepartamento <> '$array[22]' ORDER BY Descripcion";
                                        $Tickets->get_result_query();
                                        for ($i=0; $i < count($Tickets->_rows);$i++ ) {
                                            echo "<option value='" . $Tickets->_rows[$i]['NoDepartamento'] . "'>" . $Tickets->_rows[$i]['Descripcion'] . " (" . $Tickets->_rows[$i]['NoDepartamento'] . ")</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 120px;">Nombre:</td>
                                <td colspan="3">
                                    <div id="namesucen">
                                        <select name="NombreSolicita" class="formInput" id="eNombreSolicita" class="formInput">
                                            <option value="<?=$array[24]?>" ><?=$array['NombreSolicitante']?> </option>
                                            <?php

                                            $queryEmpleyod = "SELECT idEmpleado,concat_ws(' ',Nombre,ApPaterno,ApMaterno)as NombreEmpleado FROM SINTEGRALGNL.BGEEmpleados WHERe idEmpleado != $array[24] AND NoEstado = 1 AND NoDepartamento = $array[22] ";

                                            $Tickets->_query = $queryEmpleyod ;
                                            $Tickets->get_result_query();

                                            for($i=0;$i < count($Tickets->_rows) ; $i++){
                                                echo "<option value='" . $Tickets->_rows[$i][0] . "'>".$Tickets->_rows[$i][1] . "</option>";
                                            }
                                            ?>
                                            ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Medio de Contacto</td>
                                <td colspan="3">
                                    <select name="MedioDeContacto" id="eMedioDeContacto" class="formInput">
                                        <option value="<?=$array[8]?>"><?=$array[9] ?></option>
                                        <?php
                                        $Tickets->_query = "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos WHERE idCatalogo=2 AND idDescripcion <> $array[8] AND NOT idDescripcion=0" ;
                                        $Tickets->get_result_query();
                                        for( $i=0; $i < count($Tickets->_rows); $i++) {
                                            echo "<option value='" . $Tickets->_rows[$i][0] . "'>" . $Tickets->_rows[$i][1] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>&Aacute;rea: </td>
                                <td style="width: 50%">
                                    <select name="area" <?=$campos?> id="earea"  onchange="fnsdloadCategorias(this.value,'<?=$NoDepartamento?>')" class="formInput">
                                        <option value="<?=$array[20]?>"><?=$array[21]?></option>
                                        <?php
                                        $Tickets->_query = "SELECT NoArea,Descripcion FROM BSHCatalogoAreas WHERE NoDepartamento= '" . $_SESSION['data_departamento']['NoDepartamento'] ."' AND NoArea <> ".$array[20]."  ORDER BY Descripcion DESC";
                                        $Tickets->get_result_query();

                                        for( $i=0;$i < count($Tickets->_rows); $i++ ) {

                                            echo "<option value='" . $Tickets->_rows[$i]["NoArea"] . "'>" . $Tickets->_rows[$i]["Descripcion"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td style="width: 100%;">
                                    <div id="myDiv">
                                        <select class="formInput" name="SelectCategorias" id="id_categorias" <?=$campos?>>
                                            <option value="<?=$array[25]?>"><?=$array[26]?></option>
                                            <?php
                                            $Tickets->_query = "SELECT nocategoria,descripcion,NoArea FROM BSHCatalogoCategoria where NoArea = $array[20] AND NoDepartamento = '$array[2]' AND nocategoria <> $array[25] ORDER BY Descripcion ASC";
                                            $Tickets->get_result_query();
                                            for( $i=0;$i < count($Tickets->_rows); $i++ ) {
                                                echo "<option value='" . $Tickets->_rows[$i][0] . "'>" . $Tickets->_rows[$i][1] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Descripci&oacute;n: </td>
                                <td colspan="3"><input type="text" value="<?=$array[6]?>" name="Descripcion" id="eDescripcion" placeholder="Descripcion Corta" class="formInput mayus" /> </td>
                            </tr>
                            <tr>
                                <td colspan="4">Descripci&oacute;n Detallada: </td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <textarea cols="75" class="form-control mayus" rows="10" name="Informacion" id="eInformacion"><?=$array[7]?></textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table class="classtable">
                            <tbody>
                            <tr>
                                <td>Fecha Alta:</td>
                                <td><input type="text" <?=$campos2?> id="efchalta" value="<?=$Tickets->getFormatFecha($array[4],2)?>" class="formInput datepicker"  </td>
                            </tr>
                            <tr>
                                <td style="width: 120px;">Prioridad: </td>
                                <td>
                                    <select name="Prioridad"  onchange="fnsdCalculaFechaPromesa(this.value)" id="ePrioridad" class="formInput">
                                        <option value="<?=$array[10]?>"><?=$array[11]?></option>
                                        <?php
                                        $Tickets->_query = "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos WHERE idCatalogo=1 AND idDescripcion <> $array[10] AND NOT idDescripcion=0";
                                        $Tickets->get_result_query();
                                        for( $i=0;$i < count($Tickets->_rows); $i++ ) {
                                            echo "<option value='" . $Tickets->_rows[$i][0] . "'>" . $Tickets->_rows[$i][1] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Estatus: </td>
                                <td>
                                    <select  name="Estatus" disabled="disabled" id="eEstatus" class="formInput">
                                        <option value="<?=$array[18]?>"><?=$array[19]?></option>
                                        <?php
                                        $Tickets->_query ="SELECT NoEstatus,Descripcion FROM BSHCatalogoEstatus WHERE NoEstatus <> $array[18] ";
                                        $Tickets->get_result_query();
                                        for( $i=0;$i < count($Tickets->_rows); $i++ ) {
                                            echo "<option value='" . $Tickets->_rows[$i][0] . "'>" . $Tickets->_rows[$i][1] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Fecha Promesa: </td>
                                <td><div id="calc_fch_prm"><input type="text" id="fecha_promesa"  <?=$campos2?>  value="<?=$Tickets->getFormatFecha($array[27],2)?>"  class="formInput datepicker" /> </div></td>
                            </tr>
                            <tr>
                                <td>Tipo Atenci&oacute;n: </td>
                                <td>
                                    <select name="tipomantenimiento" id="etipomantenimiento" class="formInput">
                                        <option value="<?=$array[12]?>"><?=$array[13]?></option>
                                        <?php
                                        $Tickets->_query = "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos where idCatalogo=5 AND idDescripcion <> $array[12] ";
                                        $Tickets->get_result_query();
                                        for( $i=0;$i < count($Tickets->_rows); $i++ ) {
                                            echo "<option value='" . $Tickets->_rows[$i][0] . "'>" . $Tickets->_rows[$i][1] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Asignar a: </td>
                                <td>
                                    <select name="AsignenPerson" id="eAsignenPerson" class="formInput">
                                        <option value="<?=$array[16]?>"><?=$array[17]?></option>
                                        <?php
                                        $Tickets->_query = "SELECT NoUsuario,NombreDePila FROM SINTEGRALGNL.BGECatalogoUsuarios where NoDepartamento = '$array[2]' AND NoUsuario <> $array[16] AND NoEstado = 1 ORDER BY NombreDePila ASC ";
                                        $Tickets->get_result_query();
                                        for( $i=0;$i < count($Tickets->_rows); $i++ ) {
                                            echo "<option value='" . $Tickets->_rows[$i][0] . "'>" . $Tickets->_rows[$i][1] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <?php
                        $pie = "";
                        $Tickets->_query = "
                                            SELECT 
                                              R.Folio,
                                              R.DescripcionReporte,
                                              R.Estatus,
                                              C.Descripcion,
                                              R.Fecha,
                                              R.Anio,
                                              R.NoDepartamento  
                                            FROM BSHReportes AS R
                                              LEFT JOIN BSHCatalogoEstatus AS C ON R.Estatus = C.NoEstatus
                                            WHERe 
                                              R.NoSucursal = '".$array[22]."' AND R.NoDepartamento = ".$array[2]."
                                            ORDER BY R.Estatus,R.Fecha DESC LIMIT 7";
                        $Tickets->get_result_query();

                        if(count($Tickets->_rows)>0){
                            echo '<div class="panel panel-default" style="overflow-y: scroll;height:247px;margin-top: 7px;">
                                <div class="panel-heading" style="padding: 3px;"><span class="fa fa-eye"></span> Historial de Reportes</div>
                                <table class="table table-hover table-condensed" style="font-size: 10.7px;">
                                    <thead>
                                    <th style="text-align: center;">#</th><th>Fecha</th><th>Descripci&oacute;n</th><th style="text-align: right;">Estatus</th>
                                    </thead>
                                    <tbody>';
                            $pie = '</tbody>
                                </table>
                            </div>';
                        }

                        for($i=0;$i < count($Tickets->_rows); $i++){
                            switch($data[2]){
                                case 1:
                                    $Estatus = "<span class='label label-info' style='width: 120px;font-size:9.5px;'>".$Tickets->_rows[$i][3]."&nbsp;&nbsp;</span>";
                                    break;
                                case 2:
                                    $Estatus = "<span class='label label-success' style='width:120px;font-size:9px;'>Progreso</span>";
                                    break;
                                case 4:
                                    $Estatus = "<span class='label label-danger' style='width:120px;font-size:9.5px;'>".$Tickets->_rows[$i][3]."&nbsp;</span>";
                                    break;
                            }
                            echo "<tr><td width='5px' style='vertical-align: middle;'><strong><a href='#'>".$Tickets->getFormatFolio($Tickets->_rows[$i][0],4)."</a></strong></td><td style='vertical-align: middle;'>".$Tickets->getFormatFecha($Tickets->_rows[$i][4],2)."</td><td width='79.5%;'>".$Tickets->_rows[$i][1]."</td><td style='vertical-align: middle;'><span class='pull-right'>".$Estatus."</span></td></tr>";
                        }
                        echo $pie;
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="callBackEdit">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: left;margin-top: -10px;">
                <button class="btn btn-primary btn-sm" id="btnSave" onclick="fnsdEditarTicket(<?=$_POST['fl']?>,<?=$_POST['an']?>,'<?=$_POST['nodpto']?>')"><i class="fa fa-save"></i> Guardar</button>
                <button id="modalbtnclose" class="btn btn-danger btn-sm" onclick="CloseModalAndReload()">Cancelar</button>
            </div>
        </div>
    </div>
</div>

