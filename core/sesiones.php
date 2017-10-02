<?php

/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 23/01/2017
 * Time: 05:19 PM
 */

namespace core;

session_name("d23fc76dad7a207ed00625460be2ebe3");
session_start();
session_id();

class sesiones
{

    public function set($nombre_array,$datos = array()){

        $_SESSION[$nombre_array] = $datos ;
    }

    public function get($nombre_array,$nombre) {

        if (isset ( $_SESSION [$nombre_array][$nombre] )) {

            return $_SESSION [$nombre_array][$nombre];

        } else {

            return false;

        }
    }

    public function borrar_variable($nombre_array,$nombre) {

        unset ($_SESSION [$nombre_array][$nombre] );

    }

    public function validar_acceso(){

        if(array_key_exists('data_login',$_SESSION)){

            if(!isset($_SESSION['data_login']['NoUsuario'])){
                $this->delete_sesion();
                return false;
            }else{
                return true;
            }

        }else{
            $this->delete_sesion();
            return false;
        }

    }

    public function delete_sesion() {

        session_unset ();
        session_destroy ();
        session_start();
        session_regenerate_id(true);
    }

}