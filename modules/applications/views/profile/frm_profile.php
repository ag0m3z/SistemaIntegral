<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/01/2017
 * Time: 06:14 PM
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
include "../../../../core/seguridad.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);

//validar tiempo de sesiion
$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);

$seguridad->_query = "
    SELECT 
      a.NoDepartamento,c.Descripcion as NombreDepartamento,a.Nombre,b.NombreDePila,
      a.Correo,a.Telefonoe01,a.Telefonoe02,a.Telefonoe03,a.Direccion,
      a.ApPaterno,a.ApMaterno,
      a.NoEmpleado,a.idEmpleado,
      b.NoUsuario,b.PassLogin,
      a.idphoto,
      SUBSTRING_INDEX(idphoto,'/',-1)as foto_lg,
      c.Domicilio as domicilio_departamento,c.Correo as correo_departamento,c.Telefono01 as telefono1_departamento,c.Telefono02 as telefono02_departamento,c.telefono03 as telefono03_departamento
    FROM SINTEGRALGNL.BGEEmpleados as a
    LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b
    ON a.idEmpleado = b.idEmpleado
    LEFT JOIN BGECatalogoDepartamentos as c
    ON a.NoDepartamento = c.NoDepartamento WHERE a.idEmpleado = ".$_POST['idEmpleado']."
    ";

$seguridad->get_result_query();

$data = $seguridad->_rows;

$imgPortada = 'site_design/img/faces/avatars/portada/default.jpg';
$disabled = "";
$hidden = "";
$hidden_departamento = "";
$id_btn_upload = "selectedFile";
if($_POST['opc'] == 1){
    // mostrar perfil en modo de lectura
    $disabled = "disabled";
    $hidden = "hidden";
    $id_btn_upload = "btn_none";
}else{
    $hidden_departamento = "hidden";
}

?>
<script>
    $("#selectedFile").change(function(){
        fnreadURL(this);
    });
    confirm_close = true;
</script>
<div class="col-md-12">
    <!-- modal ver imagen de perfil -->
    <div class="modal fade" id="mdl_picture_profile">
        <div class="modal-dialog" >
            <div class="modal-content profile">

                <div class="modal-body no-padding">
                    <div class="profile">
                        <img src="site_design/img/faces/fullscreen/<?=$data[0]['foto_lg']?>" class="img-responsive" />
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END modal ver imagen de perfil -->
</div>
<div class="row animated fadeInDown">
    <div class="col-md-3 padding-x3">
        <!-- Widget: user widget style 1 -->
        <div  id="fotoperfil" class="box box-widget widget-user">
            <from id="form1" runat="server">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header" ondblclick="$('#selectedFile').click()" title="Cambiar imagen de portada"  style="background: url(<?=$imgPortada?>);cursor: pointer; " >
                </div>
                <div class="widget-user-image">
                    <img  class="img-circle" data-toggle="modal" data-target="#mdl_picture_profile" title="Cambiar imagen de perfil" style="cursor: pointer; " src="site_design/img/<?=$data[0]['idphoto']?>" alt="User Avatar">
                </div>
                <div class="box-footer text-center">
                    <h3 class="widget-user-username"><?=$data[0]['NombreDePila']?></h3>
                    <h5 class="widget-user-desc"><?=$data[0]['NombreDepartamento']?></h5>
                    <span id="btn_return" class="pull-right hidden"><button onclick="$('#btn_search_contact').click();" class="btn btn-danger btn-xs fa fa-close"></button></span>
                    <!-- /.row -->
                </div>
            </from>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Sobre mi</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

                <address>
                    <strong><i class="fa fa-map-marker margin-r-5"></i> Localidad</strong><br>
                    <?=$data[0]['Direccion']?><br><br>
                    <abbr class="text-bold" title="Phone">Tel.:</abbr> <?=$data[0]['Telefonoe01']?><br>
                    <abbr class="text-bold" title="Phone">Ext.:</abbr> <?=$data[0]['Telefonoe02']?><br>
                    <abbr class="text-bold" title="Phone">Cel.:</abbr> <?=$data[0]['Telefonoe03']?>
                </address>

                <address class="hidden">
                    <strong><i class="fa fa-envelope margin-r-5"></i> Correo</strong><br>
                    <a href="mailto:<?=$data[0]['Correo']?>"><small><?=$data[0]['Correo']?></small></a>
                </address>

                <hr>
                <strong><i class="fa fa-book margin-r-5"></i> Departamento</strong>

                <p class="text-muted">
                    <?=$data[0]['NombreDepartamento']?>
                </p>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-9 padding-x3">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="hidden"><a href="#activity" data-toggle="tab"><i class="fa fa-newspaper-o"></i> Actividad</a></li>
                <li class="hidden"><a href="#timeline" data-toggle="tab"><i class="fa fa-camera"></i> Fotos / Archivos</a></li>
                <li class="active"><a href="#settings" data-toggle="tab"><i class="fa fa-user"></i> Usuario</a></li>
                <li class="<?=$hidden?>" ><a href="#security" data-toggle="tab"><i class="fa fa-lock"></i> Seguridad </a></li>
                <li class="<?=$hidden_departamento?>"><a href="#data_depto" data-toggle="tab"><i class="fa fa-home"></i> Departamento</a></li>
            </ul>

            <div class="tab-content scroll" style="min-height: 75vh;">


                <div class="tab-pane active " id="settings">
                    <div class="row <?=$hidden?> margin-bottom">
                        <div class="col-md-12 ">
                            <button class="btn btn-success <?=$hidden?> btn-xs" id="btnSave" onclick="gnActualizaPerfil(2,<?=$_SESSION['data_login']['NoUsuario']?>)"><i class="fa fa-save"></i> Guardar cambios </button>
                            <button class="btn btn-default <?=$hidden?>  btn-xs" onclick="$('#selectedFile').click()">
                                <i class="fa fa-camera"></i> Cambiar imagen de perfil
                            </button>

                            <input type="file" class="<?=$disabled?>" id="<?=$id_btn_upload?>"  accept="file_extension| ,.gif, .jpg, .png," height="200" style="display: none;">

                        </div>
                    </div>

                    <div id="resultsave"></div>

                    <div class="row">
                        <div class="col-md-6 ">

                            <label class="<?=$hidden_departamento?>">Datos del Usuario</label>

                            <input type="text" class="form-control <?=$disabled?> mayus hidden" id="e-idEmpleado" value="<?=$data[0]['idEmpleado']?>" placeholder="No Empleado" readonly />
                            <input type="text" class="form-control <?=$disabled?> mayus hidden" id="changeemployed" value="NO" placeholder="No Empleado" readonly />
                            <input type="text" class="form-control <?=$disabled?> mayus hidden" id="e-nouser" value="<?=$data[0]['NoUsuario']?>" placeholder="No Empleado" readonly />


                            <div class="form-group hidden">
                                <div class="input-group input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span>
                                    <input type="text" class="form-control <?=$hidden?> mayus" id="e-nodepartamento" value="<?=$data[0]['NoDepartamento']?>" placeholder="No Departamento" readonly />
                                </div>
                            </div>
                            <div class="form-group <?=$hidden?>">
                                <div class="input-group input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span>
                                    <input type="text" class="form-control  mayus" id="e-nodepartamento2" value="<?=$data[0]['NoEmpleado']?>" placeholder="No Empleado" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-home"></span></span>
                                    <input <?=$disabled?> type="text"  id="e-nombresucursal" class="form-control" value="<?=$data[0]['NombreDepartamento']?>" data-toggle="tooltip" data-placement="right" placeholder="Nombre Departamento" disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                    <input <?=$disabled?> type="text" id="e-encargada" class="form-control mayus" value="<?=$data[0]['Nombre']?>" onkeypress="this.value=this.value.toLowerCase()" onblur="this.value=this.value.charAt(0).toUpperCase() + this.value.slice(1)" data-toggle="tooltip" data-placement="right" title="Nombre completo" placeholder="Nombre Completo" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                    <input <?=$disabled?> type="text" id="e-ap01" class="form-control mayus" value="<?=$data[0]['ApPaterno']?>" onkeypress="this.value=this.value.toLowerCase()" onblur="this.value=this.value.charAt(0).toUpperCase() + this.value.slice(1)" data-toggle="tooltip" data-placement="right" title="Apellido Paterno" placeholder="Apellido Paterno" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                    <input <?=$disabled?> type="text" id="e-ap02" class="form-control mayus" value="<?=$data[0]['ApMaterno']?>" onkeypress="this.value=this.value.toLowerCase()" onblur="this.value=this.value.charAt(0).toUpperCase() + this.value.slice(1)" data-toggle="tooltip" data-placement="right" title="Apellido Materno" placeholder="Apellido Materno" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                    <input <?=$disabled?> type="text" id="e-encargada2" class="form-control mayus" value="<?=$data[0]['NombreDePila']?>" data-toggle="tooltip" data-placement="right" title="Nombre para mostrar" placeholder="Nombre para mostrar" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                                    <input <?=$disabled?> type="text" id="e-correo"  class="form-control"  value="<?=$data[0]['Correo']?>" data-toggle="tooltip" onblur="this.value=this.value.toLowerCase()" data-placement="right" placeholder="Correo" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></span>
                                    <input <?=$disabled?> type="text" id="e-tel1" class="form-control"  value="<?=$data[0]['Telefonoe01']?>"  data-toggle="tooltip" data-placement="right" title="Telefono" placeholder="Telefono 1" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></span>
                                    <input <?=$disabled?> type="text" id="e-tel2" class="form-control"  value="<?=$data[0]['Telefonoe02']?>"  data-toggle="tooltip" data-placement="right" title="Telefono 2 o Ext." placeholder="Telefono 2" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></span>
                                    <input <?=$disabled?> type="text" id="e-celular" class="form-control"  value="<?=$data[0]['Telefonoe03']?>"  data-toggle="tooltip" data-placement="right" title="Celular Asignado" placeholder="Celular" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span>
                                    <input <?=$disabled?> type="text" id="e-direccion" class="form-control"  value="<?=$data[0]['Direccion']?>"  data-toggle="tooltip" data-placement="right" title="Direcci&oacute;n" placeholder="Direcci&oacute;n" />
                                </div>
                            </div>

                        </div>



                    </div>

                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane " id="security">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                Contraseña Anterior
                                <input type="password" class="form-control input-sm" id="new_pass" value="" placeholder="Cambiar Contraseña" />
                                <input type="password" class="form-control hidden" id="last_pass" value="<?=$data[0]['PassLogin']?>" placeholder="Cambiar Contraseña" readonly />
                            </div>
                            <div class="form-group">
                                Nueva Contraseña
                                <input type="password" class="form-control input-sm" id="new_pass" value="" placeholder="Cambiar Contraseña" />
                            </div>
                            <div class="form-group">
                                Confirmar Contraseña
                                <input type="password" class="form-control input-sm" id="new_pass" value="" placeholder="Cambiar Contraseña" />
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success btn-xs" ><i class="fa fa-save"></i> Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.tab-pane -->
                <div class="tab-pane" id="data_depto">
                    <!-- DATOS DEL DEPARTAMENTO -->
                    <div class="col-md-6 <?=$hidden_departamento?> padding-x3">
                        <label>Datos del Departamento</label>

                        <div class="form-group">
                            <div class="input-group input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-home"></span></span>
                                <input <?=$disabled?> type="text" class="form-control"  value="<?=$data[0]['NombreDepartamento']?>" title="Nombre Sucursal" placeholder="Nombre sucursal"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span>
                                <input <?=$disabled?> type="text" class="form-control"  value="<?=$data[0]['domicilio_departamento']?>"  title="Direcci&oacute;n" placeholder="Direcci&oacute;n" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                                <input <?=$disabled?> type="text" class="form-control"  value="<?=$data[0]['correo_departamento']?>"  title="Correo" placeholder="Correo" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></span>
                                        <input <?=$disabled?> type="text" class="form-control"  value="<?=$data[0]['telefono1_departamento']?>" title="Telefono 1" placeholder="Telefono 1" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></span>
                                        <input <?=$disabled?> type="text" class="form-control"  value="<?=$data[0]['telefono02_departamento']?>"  title="Telefono 2" placeholder="Telefono 2" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></span>
                                <input <?=$disabled?> type="text" class="form-control"  value="<?=$data[0]['telefono03_departamento']?>"  title="Celular" placeholder="Celular" />
                            </div>
                        </div>

                    </div>
                </div>

            </div>



            <!-- /.tab-content -->
        </div>
        <!-- /.nav-tabs-custom -->
    </div>
    <!--    <div class="col-md-2">-->
    <!--        <div class="box box-warning">-->
    <!--            <div class="box-header with-border">-->
    <!--                <h3 class="box-title">Departamento</h3>-->
    <!--            </div>-->
    <!--            <div class="box-body">-->
    <!--                <ul class="list-group list-group-unbordered">-->
    <!--                    <li class="list-group-item">-->
    <!--                        <b>Followers</b> <a class="pull-right">1,322</a>-->
    <!--                    </li>-->
    <!--                    <li class="list-group-item">-->
    <!--                        <b>Following</b> <a class="pull-right">543</a>-->
    <!--                    </li>-->
    <!--                    <li class="list-group-item">-->
    <!--                        <b>Friends</b> <a class="pull-right">13,287</a>-->
    <!--                    </li>-->
    <!--                </ul>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
</div>
