<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 09/02/2017
 * Time: 04:08 PM
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

if(!empty($_REQUEST['fl']) || !empty($_REQUEST['anio']) || !empty($_REQUEST['dpto']) ){

    $Tickets->get_informacion_ticket($_REQUEST['fl'],$_REQUEST['anio'],$_REQUEST['dpto'],0);
    $data_ticket = $Tickets->_rows[0];


    $BtnHiden = '';

    if($data_ticket[18] == 4){$BtnHiden = "hidden";}

    /**
     * Informacion de Variable $data_ticket[num];
     * 0.- Anio
     * 1.- Folio
     * 2.- NoDepartmaento
     * 3.- NombreDepartamento
     * 4.- FechaAlta
     * 5.- Hora Inicio Reporte
     * 6.- Descripcion Reporte
     * 7.- Reporte
     * 8.- NoMedio contacto
     * 9.- Nombre Medio Contacto
     * 10.- NoPrioridad
     * 11.- NombrePrioridad
     * 12.- NoTipoMantenimiento
     * 13.- Nombre Tipo Mantenimiento
     * 14.- NoUsuarioREcibe
     * 15.- Nombre Usuario Recibe
     * 16.- NoUsuarioAsignado
     * 17.- Nombre Usuario Asignado
     * 18.- NoEstatus
     * 19.- NombreEstatus
     * 20.- NoArea
     * 21.- NombreArea
     * 22.- NoSucursal
     * 23.- NombreSucursal
     * 24.- Usuario Reporta
     * 25.- NoCategoria
     * 26.- NombreCategoria
     * 27.- FechaPromesa
     */
}
?>



<script src="<?=\core\core::ROOT_APP?>site_design/js/jsServiceDesk.js"></script>
<script>

    $("#tabs").tabs();


</script>

<div id="ModalTicket"></div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading" style="padding: 3px">
                <span class="fa fa-pencil-square-o"></span>Informaci&oacute;n de Reporte <?=$BtnHiden?>
                <span class="pull-right"><?=$data_ticket[3]?></span>
            </div>
            <div id="panel-boton" class="toolbars">

                <button id="btnReload" class="btn btn-primary btn-xs" onclick="fnsdMenu(11,'fl=<?=$_REQUEST['fl']?>&dpto=<?=$_REQUEST['dpto']?>&anio=<?=$_REQUEST['anio']?>')" data-toggle="tooltip" data-placement="top" title="Actualizar" ><i class="fa fa-refresh"></i></button>
                <button class="btn btn-primary btn-xs"  onclick="fnsdMenu(3,0)"><i class="fa fa-list"></i> Lista </button>
                <?php

                if($_SESSION['data_departamento']['AsignarReportes']== 'SI'){
                    //Mostrar Botones de Escritura si el Usuario es de un Departamento de Tecnicos (que pueda registrar Tickets)

                    if($_SESSION['data_departamento']['NoDepartamento'] == $_REQUEST['dpto']) {

                        //Mostrar los botones siempre y cuando el departamento del usuario pertenezca al departameto del ticket

                        ?>
                        <button class="btn btn-default btn-xs <?= $BtnHiden ?>  "
                                onclick="fnsdAbrirModalTicket(1,<?= $_REQUEST['fl'] ?>,<?= $_REQUEST['anio'] ?>,'<?= $_REQUEST['dpto'] ?>')">
                            <i class="fa fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-default btn-xs <?= $BtnHiden ?>" data-opcion="cambio"
                                onclick="fnsdAbrirModalTicket(2,<?= $_REQUEST['fl'] ?>,<?= $_REQUEST['anio'] ?>,'<?= $_REQUEST['dpto'] ?>')">
                            <i class="fa fa-retweet"></i> Asig./Re Asign.
                        </button>
                        <!--<button class="btn btn-primary btn-xs" onclick="fnsdAbrirModalTicket(3,<?/*=$_REQUEST['fl']*/
                        ?>,<?/*=$_REQUEST['anio']*/
                        ?>,'<?/*=$_REQUEST['dpto']*/
                        ?>')"><i class="fa fa-reply"></i> Re Abrir</button>-->
                        <button class="btn btn-default btn-xs <?= $BtnHiden ?>" data-opcion="alta"
                                onclick="fnsdAbrirModalTicket(4,<?= $_REQUEST['fl'] ?>,<?= $_REQUEST['anio'] ?>,'<?= $_REQUEST['dpto'] ?>')">
                            <i class="fa fa-share-square-o"></i> Seguimiento
                        </button>
                        <button class="btn btn-danger btn-xs <?= $BtnHiden ?>" data-opcion="alta"
                                onclick="fnsdAbrirModalTicket(5,<?= $_REQUEST['fl'] ?>,<?= $_REQUEST['anio'] ?>,'<?= $_REQUEST['dpto'] ?>')">
                            <i class="fa fa-close"></i> Cerrar
                        </button>
                        <?php
                    }

                }
                ?>

                <?php
                if($_SESSION['data_departamento']['NoDepartamento'] == "0205"){
                    ?>
                    <button class="btn btn-default btn-xs <?=$BtnHiden?>"  data-opcion="alta"
                            onclick="fnsdAbrirModalTicket(10,<?=$_REQUEST['fl']?>,<?=$_REQUEST['anio']?>,'<?=$_REQUEST['dpto']?>')">
                        <i class="fa fa-plus"></i> Orden de Trabajo
                    </button>
                    <?php
                }
                ?>
                <button class="btn btn-default btn-xs <?=$BtnHiden?>"  data-opcion="alta" onclick="fnsdAbrirModalTicket(6,<?=$_REQUEST['fl']?>,<?=$_REQUEST['anio']?>,'<?=$_REQUEST['dpto']?>')" data-toggle="tooltip" data-placement="top" title="Adjuntar Documento" ><i class="fa fa-paperclip"></i></button>
                <button class="btn btn-default btn-xs <?=$BtnHiden?>"  data-opcion="alta" onclick="fnsdAbrirModalTicket(7,<?=$_REQUEST['fl']?>,<?=$_REQUEST['anio']?>,'<?=$_REQUEST['dpto']?>')" data-toggle="tooltip" data-placement="top" title="Adjuntar Imagen"><i class="fa fa-camera"></i></button>
                <button class="btn btn-warning btn-xs"  data-opcion="reportes"   onclick="fnsdAbrirModalTicket(8,'<?=base64_encode($_REQUEST['fl'])?>','<?=base64_encode($_REQUEST['anio'])?>','<?=base64_encode($_REQUEST['dpto'])?>')" data-toggle="tooltip" data-placement="top" title="Imprimir Reporte"><i class="fa fa-print"></i> Imprimir</button>
                <button class="btn btn-default btn-xs"  onclick="fnsdAbrirModalTicket(12,'<?=base64_encode($_REQUEST['fl'])?>','<?=base64_encode($_REQUEST['anio'])?>','<?=base64_encode($_REQUEST['dpto'])?>')" ><i class="fa fa-pencil-square-o"></i> Firma</button>

                <?php
                if($_SESSION['data_login']['NoPerfil'] == 1){
                    // Perfil Administrador
                    ?>
                    <button class="btn btn-warning btn-xs" data-opcion="alta" onclick="fnsdAbrirModalTicket(9,<?=$_REQUEST['fl']?>,<?=$_REQUEST['anio']?>,'<?=$_REQUEST['dpto']?>')" data-toggle="tooltip" data-placement="top" title="Enviar Correo"><i class="fa fa-envelope"></i></button>

                    <?php
                }
                ?>
            </div>
            <div class="panel panel-info ">
                <div class="panel-heading" style="padding: 3px;">
                    <div class="row" style="margin-right: 1px;">
                        <span class="pull-right"><span style="color: red;">Ticket: </span><?=$Tickets->getFormatFolio($data_ticket[1],4)?></span>
                    </div>
                </div>
                <div class="panel-body" style="padding: 2px;">

                    <table class="tablaDetailticket" style="width: 100%;">
                        <tr>
                            <td style="width: 8%;"><strong>Solicitante: </strong></td>
                            <td style="width: 65%;" class="text-danger"><input type="text" readonly class="formInput" value="<?=$data_ticket['NombreSolicitante']?>"/> </td>
                            <td ><strong>Fecha Alta: </strong></td>
                            <td class="text-danger"><input type="text" class="formInput" readonly value="<?=$Tickets->getFormatFecha($data_ticket[4],2)?>"></td>
                        </tr>
                        <tr>
                            <td><strong>Localidad: </strong></td>
                            <td class="text-danger"><input type="text" class="formInput" value="<?=$data_ticket[23]?>" readonly/> </td>
                            <td><strong>Fecha Promesa: </strong></td>
                            <td class="text-danger"><input type="text" class="formInput" readonly value="<?= $Tickets->getFormatFecha($data_ticket[27],2)?>"/> </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="panel-body" style="padding: 0px;">
                <div id="tabs" style="padding: 0px;margin-top:-3px;">
                    <ul style="border:none;background: #EFEFF7" >
                        <li><a href="#tabs-1">Informaci&oacute;n</a></li>
                        <li><a href="#tabs-2">Info. del Solicitante</a></li>
                        <?php
                        if($_SESSION['data_departamento']['NoDepartamento'] == '0205')
                        {
                            echo '<li><a href="#workorder" data-toggle="tab">Ordenes de Trabajo</a></li>';
                        }
                        ?>
                        <li><a href="#tabs-4">Seguimiento <span class="label label-default badge"><?=$Tickets->getAlertStats(17,$_REQUEST['dpto'],$_SESSION['data_login']['NoUsuario'],$_REQUEST['anio'],$_REQUEST['fl'],2)?></span> </a></li>
                        <li><a href="#tabs-5">Documentos <span class="label label-default badge"><?=$Tickets->getAlertStats(21,$_REQUEST['dpto'],$_SESSION['data_login']['NoUsuario'],$_REQUEST['anio'],$_REQUEST['fl'],2)?></span> </a></li>
                        <li><a href="#tabs-6">Fotos <span class="label label-default badge"><?=$Tickets->getAlertStats(22,$_REQUEST['dpto'],$_SESSION['data_login']['NoUsuario'],$_REQUEST['anio'],$_REQUEST['fl'],2)?></span> </a></li>
                    </ul>

                    <div id="tabs-1">
                        <table class="tablaDetailticket" style="width: 100%">
                            <tr style="background: #f4f4f4;">
                                <td style="width: 80%;" colspan="3"><strong>Descripci&oacute;n:</strong> <?=$data_ticket[6]?> </td>
                                <td><strong>Prioridad: </strong></td>
                                <td><?=$data_ticket[11]?></td>
                            </tr>
                            <tr>
                                <td><strong>Asignado a: </strong><?php if($data_ticket[17]==""){echo "No Asignado";}else {echo $data_ticket[17];}?></td>
                                <td></td>
                                <td>&nbsp;</td>
                                <td><strong>Estado: </strong></td>
                                <td><?=$data_ticket[19]?></td>
                            </tr>
                            <tr style="background: #f4f4f4;">
                                <td colspan="5"><strong>Descripci&oacute;n Detallada: </strong><br/>
                                    <textarea cols="55" rows="5"  readonly class="form-control scroll-auto"><?=$data_ticket[7]?></textarea>
                                </td>
                            </tr>
                            <tr style="border-bottom: none; border-left: none; border-right: none;">
                                <td colspan="2"></td>
                            </tr>
                        </table>
                    </div>

                    <div id="tabs-2" class="table-responsive">
                        <table class="tablaDetailticket">
                            <tr>
                                <th colspan="2" width="500">Detalles de Solicitante</th>
                                <th colspan="2" width="350">Detalles del Ticket</th>
                            </tr>
                            <tr>
                                <td width="150">Nombre:</td>
                                <td><?=$data_ticket['NombreSolicitante']?></td>
                                <td>&Aacute;rea</td>
                                <td><?=$data_ticket[21]?></td>
                            </tr>
                            <tr style="background: #f4f4f4;">
                                <td>Localidad:</td>
                                <td><?=$data_ticket[23]?></td>
                                <td>Categoria:</td>
                                <td><?=$data_ticket[26]?></td>
                            </tr>
                            <tr>
                                <td>Tel&eacute;fono:</td>
                                <td><?=$data_ticket['Telefono1Sucursal']?></td>
                                <td>Tipo Mantenimiento:</td>
                                <td><?=$data_ticket[13]?></td>
                            </tr>
                            <tr style="background: #f4f4f4;">
                                <td>Celular:</td>
                                <td><?=$data_ticket['Telefono2Sucursal']?></td>
                                <td>Medio Contacto:</td>
                                <td><?=$data_ticket[9]?></td>
                            </tr>
                            <tr>
                                <td>Correo:</td>
                                <td><?=$data_ticket['CorreoSucursal']?></td>
                                <td>Registro: </td>
                                <td><?=$data_ticket[15]?></td>
                            </tr>
                            <tr style="background: #f4f4f4;">
                                <td> Direcci&oacute;n: </td>
                                <td  colspan="3"> <?=$data_ticket[28]?> </td>
                            </tr>
                        </table>
                    </div>

                    <div id="workorder" style="padding-left: 0px;padding-right: 0px;">
                        <?php
                        if($_SESSION['data_departamento']['NoDepartamento'] == '0205')
                        {
                            ?>
                            <div id="divresult">
                                <?php
                                //Cargar lista de Rubros agregados
//                                $cons = $Tickets->MostrarRubros($_REQUEST['fl'], $_REQUEST['dpto'], $_REQUEST['anio'],true);
//                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div id="tabs-4" class="no-padding table-responsive">
                        <table class="tableHistory table-striped  table-hover">
                            <thead>
                            <tr>
                                <th width="25">Id</th>
                                <th>Informaci&oacute;n</th>
                                <!-- <th>Tipo Atenci&oacute;n</th> -->
                                <th width="105">Fecha</th>
                                <th width="105">Hora</th>
                                <th width="150">Usuario</th>
                                <th width="145">Tipo de Seguimiento</th>
                                <th width="90">Estatus</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            //Cargar el Historial del Ticket
                            $Tickets->Historial($data_ticket[0],$data_ticket[2],$data_ticket[1]);
                            $data_history = $Tickets->_rows;

                            if(count($data_history) >  0 ){

                                for($i=0; $i < count($data_history); $i++){

                                    echo "
                                         <tr>
                                            <td>".$data_history[$i][0]."</td>
                                            <td>".$data_history[$i][1]."</td>
                                            <td>".$Tickets->getFormatFecha($data_history[$i][6],2)."</td>
                                            <td>".$data_history[$i][7]."</td>
                                            <td>".$data_history[$i][3]."</td>
                                            <td>".$data_history[$i][8]."</td>
                                            <td>".$data_history[$i][5]."</td>
                                         </tr>";
                                }

                            }
                            ?>
                            </tbody>
                        </table>

                    </div>
                    <div id="tabs-5" class="no-padding">
                        <table class="tableHistory table-responsive table-striped table-hover">
                            <thead>
                            <tr>
                                <th width="25">Id</th>
                                <th>Nombre del Adjunto</th>
                                <th width="105">Fecha</th>
                                <th width="105">Hora</th>
                                <th width="155">Usuario</th>
                                <th width="205">Funciones</th>
                            </tr>
                            </thead>
                            <?php
                            //Cargar los Documentos Adjuntos al Ticket
                            $Tickets->mostrar_adjuntos($data_ticket[1],$data_ticket[0],$data_ticket[2],2);
                            ?>
                        </table>
                    </div>

                    <div id="tabs-6">

                        <?php
                        //Lista de Imagenes Adjuntas
                        $Tickets->mostrar_imagenes_adjuntas($data_ticket[1],$data_ticket[2],$data_ticket[0],1);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
