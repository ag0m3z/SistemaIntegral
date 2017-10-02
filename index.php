<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 27/01/2017
 * Time: 06:59 PM
 */

namespace Aplicacion;

use core\seguridad;
use core\sesiones;
use core\views;

class SistemaIntegral
{

    public static function init(){

        define("ROOT" , __DIR__);

        include 'core/views.php';
        include 'core/sesiones.php';

        $vista = new views();
        $sesion = new sesiones();

        // validar sesion de usuario
        if($sesion->validar_acceso()){

            // ya cuenta con una sesion iniciada
            $vista->call_view(
                array(
                    'applications',
                    'index',
                    'index'
                )
            );

        }else{

            // no hay una sesion iniciada
            $vista->call_view(
                array(
                    'applications',
                    'login',
                    'frm_login'
                )
            );
        }

    }

}

SistemaIntegral::init();


