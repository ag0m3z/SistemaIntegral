<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/02/2017
 * Time: 12:15 PM
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

$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


if($_SESSION['data_departamento']['AsignarReportess'] == 'SI'){
    $btnDisabled = "disabled";
}else{
    $btnDisabled = "";
}

?>
<script type="text/javascript" language="JavaScript" src="<?=\core\core::ROOT_APP?>site_design/js/jsFormatoMoneda.js"></script>
<script language="JavaScript">
    $('#myModal').modal('show');
    $("#myModal").draggable({
        handle: ".modal-header"
    });

    $("input[type=text]").focus(function(){
        this.select();
    });
    $('.currency').numeric({prefix:'$ ', cents: true});
</script>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" ><img src="<?=\core\core::ROOT_APP?>site_design/img/icons/tarea2.png"> Encuesta</h4>
            </div>
            <div class="modal-body" style="margin-bottom: -5px;">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="tablaDetailticket">
                                    <tr>
                                        <td style="width: 115px;">Cte. No Atendido: </td>
                                        <td style="width: 250px;">
                                            <select id="frmNoAtedidos" class="formInput" onchange="cargaCombosEncuesta(this.value)">
                                                <option value="0">-- --</option>
                                                <?php
                                                $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 17 AND NoEstatus = 1";
                                                $connect->get_result_query();

                                                for($i=0; $i < count($connect->_rows) ; $i++ ){
                                                    echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Servicio: </td>
                                        <td style="width: 250px;">
                                            <select style="width: 100%" id="frmServicioEncuesta" onchange="loadCategoriaServicio(this.value)" class="formInput">
                                                <option value="0">-- --</option>
                                                <?php
                                                $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 14 AND NoEstatus = 1";

                                                if($_POST['CodProd']<>0){
                                                    $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 14 AND OpcCatalogo <> 3 AND NoEstatus = 1";
                                                }
                                                $connect->get_result_query();

                                                for($i=0; $i < count($connect->_rows) ; $i++ ){
                                                    echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr style="height: 36px;">
                                        <td class="idservice01">
                                            <div id="loadServiciostxt">
                                                No Categoria:
                                            </div>

                                        </td>
                                        <td class="idservice01">
                                            <div id="loadServicios">
                                                <select class="formInput" onchange="loadCalidadMetal(this.value)" id="CategoriaServicio">
                                                    <option value="0">-- --</option>
                                                    <?php
                                                    $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral where CodCatalogo = 9 ORDER BY Descripcion ASC";
                                                    $connect->get_result_query();

                                                    for($i=0; $i < count($connect->_rows) ; $i++ ){
                                                        echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                                    }
                                                    ?>

                                                </select>

                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="height: 38px;">
                                        <td class="idservice02">
                                            <div id="loadCalidadMetalTxt">

                                            </div>
                                        </td>
                                        <td class="idservice02">
                                            <div id="loadCalidadMetal">

                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tipo Producto: </td>
                                        <td>
                                            <form class="formDataProducto">
                                                <select id="tpo_articulo" onchange="fnpv_loadMarcas(this.value,$('#CategoriaServicio').val())"  class="formInput prod">
                                                    <option value="0">-- --</option>
                                                    <?php
                                                    $connect->_query = "SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERe  CodCatalogo = 5 AND NoEstatus = 1 ORDER BY Descripcion ASC";
                                                    $connect->get_result_query();

                                                    for($i=0; $i < count($connect->_rows) ; $i++ ){
                                                        echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Marca: </td>
                                        <td style="width: 14em;">
                                            <form class="formDataProducto">
                                                <div id="loadMarca">
                                                    <select id="id_marca" onchange="loadProductos(this.value)" class="formInput prod">
                                                        <option value="0">-- --</option>
                                                    </select>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nombre Producto: </td>
                                        <td>
                                            <form class="formDataProducto">
                                                <div id="loadproductos">
                                                    <select id="nombreprod" class="formInput prod">
                                                        <option value="0">-- --</option>
                                                    </select>
                                            </form>
                            </div>
                        </div>
                        </td>
                        </tr>
                        <tr>
                            <td>Condiciones: </td>
                            <td>
                                <select id="frmCondicionesEncuesta" class="formInput">
                                    <option value="0">-- --</option>
                                    <?php
                                    $connect->_query = 'SELECT OpcCatalogo,concat(Descripcion," - ",Texto1) FROM BGECatalogoGeneral WHERE CodCatalogo = 15 AND NoEstatus = 1';
                                    $connect->get_result_query();

                                    for($i=0; $i < count($connect->_rows) ; $i++ ){
                                        echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        </table>
                    </div>
                    <div class="col-md-6" style="border-left:1px solid #ccc; ">
                        <table class="tablaDetailticket" style="width: 100%">
                            <tr>
                                <td>Importe cliente solicita: </td>
                                <td>
                                    <input id="frmMontoSolicitaEncuesta" type="text" class="formInput text-right currency">
                                </td>
                            </tr>
                            <tr>
                                <td>Competidor: </td>
                                <td>
                                    <select id="frmCompetidorEncuesta" class="formInput">
                                        <option value="0">-- --</option>
                                        <?php
                                        $connect->_query = 'SELECT OpcCatalogo,Descripcion FROM BGECatalogoGeneral WHERE CodCatalogo = 16 AND NoEstatus = 1 ORDER BY Descripcion ASC';
                                        $connect->get_result_query();

                                        for($i=0; $i < count($connect->_rows) ; $i++ ){
                                            echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Importe competidor: </td>
                                <td>
                                    <input id="frmMontoCompetidorEncuesta" type="text" class="formInput text-right currency">
                                </td>
                            </tr>
                            <tr>
                                <td style="height:3px" colspan="2">
                                    Descripción Producto &nbsp;<span class="small">(Si no esta dentro del catalogo)</span>
                                    <textarea id="DescripcionProducto" style="height: 5em;" class="form-control" disabled ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:3px"  colspan="2">
                                    Observaciones
                                    <textarea id="observaciones" style="height: 5em;" class="form-control"></textarea>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
                <div id="divresult"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer" style="text-align: left;margin-top: -18px;">
        <button class="btn btn-primary btn-sm" <?=$btnDisabled?> onclick="fnpv_guardar_encuesta_producto(0)"> Guardar</button> &nbsp;<button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
    </div>
</div>
</div>
</div>