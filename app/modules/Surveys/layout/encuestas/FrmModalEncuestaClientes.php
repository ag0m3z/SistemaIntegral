<?php
include "../../../../controller/Aparatos.class.php";

$connect = new \controller\Aparatos();
//Validar que el usuario este logueado
if(!$connect->ValidaAcceso()){$connect->returnHomePage();}

//validar tiempo de actividad
$connect->ValidaSession_id();

if($_SESSION['Perfil'] <> 2){
    $btnDisabled = "disabled";
}else{
    $btnDisabled = "";
}

?>
<script language="JavaScript">
        $('#myModal').modal('show');
        $("#myModal").draggable({
            handle: ".modal-header"
        });

        $("input[type=text]").focus(function(){
            this.select();
        });

        $(".btn-primary").focus();

        $('.currency').numeric({prefix:'$ ', cents: true});

</script>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 14px"><img src="../../images/icons/tarea2.png"> Encuesta</h4>
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
                                                $Query = $connect->Consulta("SELECT OpcCatalogo,Descripcion FROM BSISHELPDESK.BGECatalogoGeneral WHERE CodCatalogo = 17 AND NoEstatus = 1");
                                                while($res = mysqli_fetch_array($Query)){
                                                    echo "<option value='".$res['0']."'>".utf8_encode($res['1'])."</option>";
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
                                                $Query = $connect->Consulta("SELECT OpcCatalogo,Descripcion FROM BSISHELPDESK.BGECatalogoGeneral WHERE CodCatalogo = 14 AND NoEstatus = 1");
                                                if($_POST['CodProd']<>0){
                                                    $Query = $connect->Consulta("SELECT OpcCatalogo,Descripcion FROM BSISHELPDESK.BGECatalogoGeneral WHERE CodCatalogo = 14 AND OpcCatalogo <> 3 AND NoEstatus = 1");
                                                }
                                                while($result = mysqli_fetch_array($Query)){
                                                    echo "<option value='".$result[0]."'>".utf8_encode($result[1])."</option>";
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
                                                    $Consulta = $connect->Consulta("SELECT OpcCatalogo,Descripcion FROM BSISHELPDESK.BGECatalogoGeneral where CodCatalogo = 9 ORDER BY Descripcion ASC");
                                                    while($result = mysqli_fetch_array($Consulta)){

                                                        echo "<option value='".$result[0]."'>".$result[1]."</option>";

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
                                                <select id="tpo_articulo" onchange="loadMarcas(this.value,3)"  class="formInput prod">
                                                    <option value="0">-- --</option>
                                                    <?php
                                                    $query = $connect->Consulta("SELECT OpcCatalogo,Descripcion FROM BSISHELPDESK.BGECatalogoGeneral WHERe  CodCatalogo = 5 AND NoEstatus = 1 ORDER BY Descripcion ASC");
                                                    if($connect->num_rows($query)>0){
                                                        while($result = mysqli_fetch_array($query)){
                                                            echo "<option value='".$result[0]."'>".$result[1]."</option>";
                                                        }
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
                                    $Query = $connect->Consulta('SELECT OpcCatalogo,concat(Descripcion," - ",Texto1) FROM BSISHELPDESK.BGECatalogoGeneral WHERE CodCatalogo = 15 AND NoEstatus = 1');
                                    while($result = mysqli_fetch_array($Query)){
                                        echo "<option value='".$result[0]."'>".utf8_encode($result[1])."</option>";
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
                                        $Query = $connect->Consulta('SELECT OpcCatalogo,Descripcion FROM BSISHELPDESK.BGECatalogoGeneral WHERE CodCatalogo = 16 AND NoEstatus = 1');
                                        while($result = mysqli_fetch_array($Query)){
                                            echo "<option value='".$result[0]."'>".utf8_encode($result[1])."</option>";
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
        <button class="btn btn-primary btn-sm" <?=$btnDisabled?> onclick="GuardarEncuesta(0)"> Guardar</button> &nbsp;<button id="modalbtnclose" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
    </div>
</div>
</div>
</div>
