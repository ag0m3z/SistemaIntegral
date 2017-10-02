<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 02/03/2017
 * Time: 10:47 AM
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
include "../../../../core/model_usuarios.php";

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

$connect = new \core\model_usuarios($_SESSION['data_login']['BDDatos']);

$connect->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);

?>
<script>
    $('.tabs').tabs();
    $('label').css("font-weight", "normal");
    $('label').css("font-family", "Arial, Helvetica, Verdana");
    $('label').css("font-size", "12px");
    $("#btnList").show();
    $("#btnHome").hide();
    $('#btn_off_sesion').addClass('hidden');
    $('#btn_lock_sesion').addClass('hidden');

</script>
<div id="resultTemp"></div>

<div class="row">

    <div class="col-md-12">

        <div class="tabs">
            <ul>
                <li><a href="#tab1"> Asignación de Empleado</a> </li>
                <li><a href="#tab2"> Datos de Acceso</a> </li>
                <li><a href="#tab3"> Asignación de Permisos</a> </li>
            </ul>
            <div id="tab1">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Seleccionar Departamento</label>
                            <select class="form-control input-sm" onchange="fnGenListaEmpleados(1,this.value,null)" id="dpto">
                                <option value="0">-- --</option>
                                <?php
                                $connect->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE NoEstado = 1 ORDER BY Descripcion ASC";
                                $connect->get_result_query();

                                for($i = 0 ; $i < count($connect->_rows) ; $i++){
                                    echo "<option value='".$connect->_rows[$i][0]."'>".$connect->_rows[$i][1]."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div id="loadUsers" onchange="ValidEmpleado(1,this.val())" class="form-group">
                            <label>Seleccionar Empleado</label>
                            <select class="form-control input-sm" onchange="GenerarNombreDePila(this.options[this.selectedIndex].text,this.value)" id="idEmpleado" >
                                <option value="0">-- --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="btn btn-default btn-xs" style="margin-left: -25px;;margin-top: 25px;" ><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tab2">
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label>Usuario login</label>
                            <input type="text" onblur="ValidEmpleado(2,this.value);if(this.value == ''){$(this).parent().addClass('has-error')}else{$(this).parent().removeClass('has-error')}"  id="UsuarioLogin" class="input-sm form-control">
                        </div>

                        <div class="form-group">
                            <label>Contrasena</label>
                            <input type="password" onblur="if(this.value == ''){$(this).parent().addClass('has-error')}else{$(this).parent().removeClass('has-error')}" id="claveLogin" class="form-control input-sm">
                        </div>

                        <div class="form-group">
                            <label>Base de Datos</label>
                            <select id="idBDatos" class="form-control input-sm" >
                                <option value="0">-- --</option>
                                <?php

                                $connect->_query = "SELECT Nombre,Descripcion FROM SINTEGRALGNL.BGEDataBases WHERE NoEstatus = 1 ";
                                $connect->get_result_query();

                                for( $i = 0 ; $i < count($connect->_rows); $i++ ){
                                    echo '<option value="'.$connect->_rows[$i][0].'"> BD '.$connect->_rows[$i][1].'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Perfil</label>
                            <select id="idPerfil" class="form-control input-sm" >
                                <?php

                                $connect->_query = "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos WHERE idCatalogo = 6 ORDER BY idDescripcion DESC";
                                $connect->get_result_query();

                                for( $i = 0 ; $i < count($connect->_rows); $i++ ){
                                    echo '<option value="'.$connect->_rows[$i][0].'">'.$connect->_rows[$i][1].'</option>';
                                }
                                ?>
                            </select>
                        </div>

                    </div>

                    <div class="col-md-6 ">

                        <div class="form-group">
                            <label>Nombre para mostrar</label>
                            <input type="text" onblur="if(this.value == ''){$(this).parent().addClass('has-error')}else{$(this).parent().removeClass('has-error')}" class="form-control input-sm" id="NombreDePila">
                        </div>

                        <div class="form-group">
                            <label>Seleccionar estado</label>
                            <select class="form-control input-sm" id="noEstado">
                                <option value="1">Activo</option>
                                <option value="0">Desactivado</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
            <div id="tab3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="container-fluid " style="margin-left: 5px;margin-right: 5px;">
                            <h4 class="text-muted text-center"> Asignaci&oacute;n de Permisos  </h4>
                            <div class="row">
                                <div class="col-md-3 padding-x3">
                                    <div class="list-group">
                                        <a href="#" class="list-group-item padding-x4  active">
                                            Lista de Modulos
                                        </a>
                                        <?php
                                        $connect->MostrarModulos();
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-4 padding-x3">
                                    <?php
                                    $connect->MostrarSecciones();
                                    ?>

                                </div>

                                <div class="col-md-5 padding-x3">
                                    <?php
                                    $connect->MostrarAplicaciones();
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#btnSaveChange").hide();

    $(".select2").select2();
    ShowHidenGroups(2,null);

    function ValidEmpleado(opc,valor){

        var urlPhp2,txtString;

        switch (opc){
            case 1:
                // validar si el empleado ya cuenta con un usuario asociado
                urlPhp2 = "modules/applications/src/empleados/fn_validar_empleado.php";
                txtString = "El Empleado seleccionado ya cuenta con un usuario asociado:<strong> ";
                break;
            case 2:
                // validar si el nombre de usuario ya existe
                urlPhp2 = "modules/applications/src/usuarios/fn_validar_usuario.php";
                txtString = "El Usuario ya se encuentra registrado a nombre de: <strong>";
                break;
            default :
                MyAlert("La opcion solicitada no existe","error");
                break;
        }


        $.ajax({
            data:{opt:opc,myval:valor},
            type:"POST",
            dataType:"JSON",
            url:urlPhp2
        }).done( function(data) {

            if(data.dexists == "ok"){
                MyAlert(txtString + data.dUserName +"</strong>","alert");
            }

        }).fail(function(jqXHR,textStatus,errorThrown){
            if ( console && console.log ) {
                MyAlert( "La solicitud a fallado: "+ textStatus  + errorThrown+ ".","alert");
            }
        });

    }

    function GenerarNombreDePila(nombre,valor){

        var cadena = nombre,
            separador = " ", // un espacio en blanco
            limite    = 4,
            arregloDeSubCadenas = cadena.split(separador, limite);

        if(nombre == ""){
            $("#NombreDePila").val("");
        }else{
            if(arregloDeSubCadenas.length >= 4){

                $("#NombreDePila").val(arregloDeSubCadenas[0] + " " + arregloDeSubCadenas[2]);

            }else{
                $("#NombreDePila").val(arregloDeSubCadenas[0] + " " + arregloDeSubCadenas[1]);
            }


            ValidEmpleado(1,valor);
        }



    }


</script>
