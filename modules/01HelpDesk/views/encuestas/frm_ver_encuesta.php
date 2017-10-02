<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 30/06/2017
 * Time: 02:05 PM
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


$connect->_query = "SELECT a.Folio,a.idEncuesta,a.idPregunta,b.Descripcion,a.idRespuesta,c.Descripcion,a.Comentarios,d.DescripcionReporte,e.NombreDePila,f.Descripcion,a.Fecha,a.Hora,a.NoDepartamento
FROM BGEEncuestaServicios as a
JOIN BGECatalogoPreguntas as b
on a.idPregunta = b.idPregunta
JOIN BGECatalogoRespuestas as c
on a.idRespuesta = c.idRespuesta
JOIN BSHReportes as d
on a.Folio = d.Folio and a.Anio = d.Anio AND a.NoDepartamento = d.NoDepartamento
LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as e
on d.NoUsuarioCierre = e.NoUsuario
LEFT JOIN BGECatalogoDepartamentos as f
on d.NoSucursal = f.NoDepartamento
where a.idFolioEncuesta = ".$_POST['fl']." ";
$connect->get_result_query();

$data = $connect->_rows[0];

$qcomentarios = $connect->_query = "SELECT Comentarios FROM BGEEncuestaServicios WHERE idFolioEncuesta  = ".$_POST['fl']." AND idRespuesta = 99 ";
$connect->get_result_query();

$data2 = $connect->_rows[0];

?>

<script>
    setOpenModal("myModal2");
</script>
<style type="text/css">
    .addbold{
        font-weight: bold;
        color: #2C33B2 !important;
    }
    .rmbold{
        margin-left: 25px;
    }
    label{
        font-weight: normal !important;
        color:#474747 !important;
    }
</style>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"> Encuesta de Servicio <?=$connect->getFormatFolio($_POST['fl'],4)?> - Ticket <?=$connect->getFormatFolio($data[0],4)?> </h4>
            </div>
            <div class="modal-body" style="margin-bottom: -5px;">
                <div class="row">
                    <div class="col-md-12">
                        <form name="dencuesta" id="dencuesta">
                            <table class="tablaDetailticket2" style="display: none">
                                <tr>
                                    <td>Sucursal: </td>
                                    <td><input type="text" readonly value="<?=$data[9]?>" class="formInput"></td>
                                    <td>Agente Cierre: </td>
                                    <td><input type="text" readonly value="<?=$data[8]?>" class="formInput"></td>
                                </tr>
                                <tr>
                                    <td>Descripci&oacute;n: </td>
                                    <td colspan="4"><input type="text" readonly value="<?=$data[7]?>" class="formInput"></td>
                                </tr>
                            </table>

                            <table class="tablesorter">
                                <?php

                                $id=0;
                                $connect->_query = "SELECT idPregunta,Descripcion FROM BGECatalogoPreguntas where NoEncuesta = ".$data[1]." AND NoDepartamento = '".$data[12]."' AND NoEstatus = 1" ;
                                $connect->get_result_query();
                                if(count($connect->_rows)>0){

                                    $preg = $connect->_rows;

                                    for($i=0;$i < count($preg);$i++){
                                        $id++;
                                        echo "<tr>
                                        <td class='addbold'>".$id.".- ".$preg[$i][1]."</td>";
                                        echo "</tr>";

                                        $idPregunta = $preg[$i][0];
                                        $connect->_query = "SELECT a.idPregunta,a.idRespuesta,a.Descripcion,a.Texto ,(SELECT b.idRespuesta FROM BGEEncuestaServicios as b where b.idFolioEncuesta = ".$_POST['fl']." AND idPregunta = ".$idPregunta.")
                                        FROM BGECatalogoRespuestas as a
                                        WHERE a.NoEstatus = 1 AND a.idPregunta = ".$idPregunta." ";
                                        $connect->get_result_query();

                                        echo "<tr><td>";
                                        $resp = $connect->_rows;
                                        for($i2=0;$i2 < count($resp);$i2++){

                                            if($resp[$i2][1] == $resp[$i2][4]){
                                                $checkBox = "checked";
                                            }else{
                                                $checkBox = "disabled";
                                            }
                                            echo "<label class='rmbold'><input type='radio' ".$checkBox." value='".$resp[$i2][1]."' name='resp".$id."' > ".$resp[$i2][2]." <input class='pull-right' style='display:none; width:35em;' id='otras".$resp[$i2][1]."' type='text'></label>";
                                        }
                                        echo "</td></tr>";


                                    }
                                }

                                ?>
                                <tr>
                                    <td>
                                        <textarea id="comentarios" readonly class="formInput" style="height: 105px; "><?=$data2[0]?></textarea>
                                    </td>
                                </tr>
                            </table>
                        </form>

                    </div>
                </div>

            </div>
            <div class="modal-footer" style="text-align: left;margin-top: -18px;">
                <button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
