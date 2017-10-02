<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 02/05/2017
 * Time: 04:05 PM
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
include "../../../../core/sqlconnect.php";

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

$connect = new \core\seguridad($_SESSION['data_home']['BDDatos']);
$connect->valida_session_id();
/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

if(!array_key_exists('idcodigo',$_POST) && !array_key_exists('idcategoria',$_POST) && !array_key_exists('idserie',$_POST)){
    \core\core::MyAlert("no se encontro el codigo o la serie para registrar <br>la caracteristica","alert");
}

$SqlConnect = new \core\sqlconnect();

$SqlConnect->_sqlQuery = "
                         SELECT 
                            a.idCaracteristica,
                            b.Descripcion,
                            a.ValorCaracteristica 
                        FROM 
                            SAyT.dbo.INVProdCarDet as a 
                        LEFT JOIN SAyT.dbo.INVProdCaracteristica as b 
                            ON a.idCaracteristica = b.idCaracteristica  
                        WHERE idSerie = '$_POST[idserie]' ORDER BY b.Orden ASC ";

$SqlConnect->get_result_query();
$lista_car = $SqlConnect->_sqlRows;
$SqlConnect->_sqlQuery = "
                         SELECT 
                            idCaracteristica,
                            Descripcion
                        FROM 
                            SAyT.dbo.INVProdCaracteristica
                        WHERE Estatus = '1' AND idCategoria = '$_POST[idcategoria]' ORDER BY Descripcion ASC  ";

$SqlConnect->get_result_query();
$car = $SqlConnect->_sqlRows;

$SqlConnect->_sqlQuery = "
                         SELECT 
                            a.idCaracteristica,
                            b.Descripcion,
                            a.ValorCaracteristica 
                        FROM 
                            SAyT.dbo.INVProdCarDet as a 
                        LEFT JOIN SAyT.dbo.INVProdCaracteristica as b 
                            ON a.idCaracteristica = b.idCaracteristica  
                        WHERE a.idSerie = '' AND a.idCodigo = '$_POST[idcodigo]'";

$SqlConnect->get_result_query();
$lista_car_codigo = $SqlConnect->_sqlRows;


?>
<script>
    setOpenModal('mdl_agregar_caracteristica');
    $("#tabs").tabs();
    $("input").focus(function(){
        this.select();
    });
    $("#btncambiar").hide();
    $(".select2").select2();


    $("#select_all").click(function(){
        $('input[type="checkbox"]').attr('disabled',true);
        $('input[type="checkbox"]').attr('checked',true);
    });

    $('input[type="checkbox"]').click(function(){$(this).attr('disabled',true);});


    fn_registrar_caracteristica(5,'<?=$_POST['idcategoria']?>','<?=$_POST['idcodigo']?>','<?=$_POST['idserie']?>')

    <?php
    if(count($lista_car)>0){echo "$('#table_car_codigo').hide()";}else{echo "$('#tabla_caracteristica').hide()";;}
    ?>
</script>

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="mdl_agregar_caracteristica"  >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Caracteristicas - Nivel Serie</h4>
            </div>
            <div class="modal-body">

                    <div id="tabla_caracteristica" class="row">
                        <div class="col-md-12 table-responsive">
                            <table  class="table table-condensed">
                                <thead>
                                <tr>
                                    <td width="250">
                                        <select id="idcaracteristica" class="form-control select2" style="width: 100%;">
                                            <option value="0">-- --</option>
                                            <?php
                                            for($i=0;$i < count($car);$i++){
                                                echo "<option value='".$car[$i][0]."' >".$car[$i][1]."</option>";
                                            }

                                            ?>
                                        </select>
                                    </td>
                                    <td><input id="valor_caracteristica" class="form-control input-sm" placeholder="Descripcion" /></td>
                                    <td width="100px">
                                        <button id="btnagregar" onclick="fn_registrar_caracteristica(2,'<?=$_POST['idcategoria']?>','<?=$_POST['idcodigo']?>','<?=$_POST['idserie']?>')" class="btn btn-success btn-xs  " ><i class="fa fa-plus"></i> Agregar</button>
                                        <button hidden id="btncambiar" onclick="fn_registrar_caracteristica(4,'<?=$_POST['idcategoria']?>','<?=$_POST['idcodigo']?>','<?=$_POST['idserie']?>')" class="btn btn-info btn-xs " ><i class="fa fa-save"></i> Guardar</button>
                                    </td>
                                </tr>
                                </thead>
                                <tbody id="tblista">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="table_car_codigo" class="row">
                        <div class="col-md-12">
                            <label>Seleccionar Caracteristicas del Codigo</label>
                            <table class='table table-condensed table-hover' >
                                <thead>
                                <tr>
                                    <th>Caracteristica</th>
                                    <th>Descripción</th>
                                    <th class='text-center'>
                                        <button class='btn btn-link' disabled id="select_all" >Todas</button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $idCategoria = $_POST['idcategoria'];
                                $idCodigo = $_POST['idcodigo'];
                                $idSerie = $_POST['idserie'];
                                for($i=0;$i < count($lista_car_codigo);$i++){

                                    $idCaracteristica = $lista_car_codigo[$i][0];
                                    echo "<tr><td>".$lista_car_codigo[$i][1]."</td><td>".$lista_car_codigo[$i][2]."</td><td class='text-center'><input onclick='fn_registrar_caracteristica(14,\"".$idCategoria."\",\"".$idCodigo."\",\"".$idSerie."\",\"".$idCaracteristica."\")' type='checkbox' /></td></tr>";

                                }
                                ?>
                                </tbody>
                            </table>
                            <span class='btn btn-link' id="show_tables" onclick="$('#table_car_codigo').hide();$('#tabla_caracteristica').show();" >Terminar y Agregar Mas Caracteristicas</span>
                        </div>
                    </div>

                <div id="result_modal"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>


