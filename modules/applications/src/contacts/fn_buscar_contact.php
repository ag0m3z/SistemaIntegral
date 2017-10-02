<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/01/2017
 * Time: 04:16 PM
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
include "../../../../core/model_empleados.php";

use core\model_empleados;

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */


$NombreContacto = $_POST['sData'];
$limit = " LIMIT 0, ".$_POST['limit'];

if($limit == 99){
    $limit = " ";
}


if(!trim($NombreContacto)){
    echo '<h2 class="text-muted text-center">No Se Encontró ningún contacto</h2>';
}else{
    //Buscar contacto

    //conectarse a la base de datos del usuario
    $empleados = new model_empleados($_SESSION['data_login']['BDDatos']);

    // validar el tiempo de la sesion
    $empleados->valida_session_id($_SESSION['data_login']['NoUsuario']);

    //Sanatizar datos
    $NombreContacto = $empleados->get_sanatiza($NombreContacto);

    //llamar a metodo para traer la lista de empleados
    $empleados->list_card_contacts(
        array(
            "nombre_contacto"=>$NombreContacto,
            "limite"=>$limit
        )
    );

    if($empleados->_confirm){
        $TotalEm = count($empleados->_rows);

        // $empleados->_query;
        $cards = $empleados->_rows;
        echo '<div class="row row-sm">';
        for( $i = 0; $i < count($cards); $i++ ){


            // Mostrar solo los activos

                if($_SESSION['data_login']['NoPerfil'] == 1){
                    $btn_edit = '<button type="button" onclick="fngnEditarEmpleado(1,'.$cards[$i]['idEmpleado'].',1,1)" title="Editar empleado" class="btn btn-danger  btn-xs" ><i class="fa fa-edit"></i> </button>';
                    $btn_edit_departamento = '<button type="button" onclick="fnCatFrmNuevoDepartamento(4,\''.$cards[$i]['NoDepartamento'].'\',2)" title="Editar departamento" class="btn btn-warning  btn-xs" ><i class="fa fa-home"></i> </button>';
                }

                $disabled = "disabled";
                if (filter_var($cards[$i]['Correo'], FILTER_VALIDATE_EMAIL)) {

                    if($cards[$i]['Correo'] != "none@no-reply.com"){
                        $disabled = " ";
                    }

                }
                $TipoDpto = $cards[$i]['TipoDepartamento'];
                $DireccionEm = "";
                $Telefono01  = "";
                $Telefono02  = "";
                $Telefono03  = "";

                if( $TipoDpto == "S"){

                    $DireccionEm = $cards[$i]['DomicilioDepartamento'];
                    $Telefono01 = $cards[$i]['TelDepto01'];
                    $Telefono02 = $cards[$i]['TelDepto02'];
                    $Telefono03 = $cards[$i]['TelDepto03'];



                }else{

                    $DireccionEm = $cards[$i]['Direccion'];
                    $Telefono01 = $cards[$i]['Telefonoe01'];
                    $Telefono02 = $cards[$i]['Telefonoe02'];
                    $Telefono03 = $cards[$i]['Telefonoe03'];

                }

                $tarjer = '<div class="col-md-4 animated flipInX">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div onclick="fnMostrarPerfil(1,'.$cards[$i]['idEmpleado'].')" class="widget-user-header  waves-effect" style="background: url(site_design/img/faces/avatars/portada/default.jpg)">
                            <div class="widget-user-image">
                                <img class="img-circle" src="' . \core\core::ROOT_APP."site_design/img/" . $cards[$i]['idphoto'] . '" alt="User Avatar">
                            </div>
                            <!-- /.widget-user-image -->
                            <h3 class="widget-user-username text-bold text-white"> ' . $cards[$i]['NombreContacto'] . ' ' . $cards[$i]['ApPaternoContacto'] . '</h3>
                            <h5 class="widget-user-desc text-white">
                               ' . $cards[$i]['Descripcion'] . '
                            </h5>
                        </div>
                        <div class="box-footer" style="max-height:255px;">
                            <div class="row" style="font-size: 12px !important;">
                                <div class="col-md-5">
                                    <i class="glyphicon glyphicon-earphone"></i> ' . $Telefono01 . '<br>
                                    <i class="glyphicon glyphicon-phone-alt"></i> ' . $Telefono02 . '<br>
                                    <i class="glyphicon glyphicon-phone"></i> ' . $Telefono03 . '<br>
                                </div>
                                <div class="col-md-7">
                                            <span>
                                                <i class="fa fa-map-marker"></i>
                                            ' . trim($DireccionEm) . '
                                            </span>
                                </div>
                            </div>
                            <span class="pull-right">
                                        <a href="mailto:' . $cards[$i]['Correo'] . '"   type="button" class="btn ' . $disabled . ' btn-success btn-xs"> <i class="fa fa-envelope">
                                            </i> Enviar Correo </a>
                                        <button type="button" onclick="fnMostrarPerfil(1,'.$cards[$i]['idEmpleado'].')" class="btn btn-primary btn-xs"> <i class="fa fa-user">
                                            </i> Ver perfil </button>
                                            '.$btn_edit.' '.$btn_edit_departamento.'
                            </span>
                        </div>
                    </div>
                    <!-- /.widget-user -->
                </div> ';

                echo $tarjer ;



        }
        echo '</div>';

    }else{

        \core\core::MyAlert($empleados->_message,"alert");
    }
}