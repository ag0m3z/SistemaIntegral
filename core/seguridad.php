<?php

/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 23/01/2017
 * Time: 05:19 PM
 */
namespace core;

include 'contenido.php';

class seguridad extends contenido
{

    public function loginIn($usuario,$password){


        //Obtener Nombre del Equipo
        $is_ipaddress = $this->get_obtener_ip();

        $nombre_host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $User_agent = $_SERVER['HTTP_USER_AGENT'];
        $Request_time = session_id();
        $Fecha =  date("Y-m-d H:i:s");

        if(empty($nombre_host)){$nombre_host = 'NONE' ; }

        $this->_query = "CALL sp_login_user(
            '$usuario',
            '$password',
            '$is_ipaddress',
            '$is_ipaddress',
            '$nombre_host',
            '$User_agent',
            '$Request_time',
            '$Fecha'
            )";

        $this->get_result_query();

        if(count($this->_rows) >= 1 ){

            $this->_confirm = true;

        }else{

            $this->_confirm = false;
            $this->_message = "No se encontró el usuario o contraseña";
        }
    }

    public function loginOut($NoUsuario){

        $Fecha =date("Y-m-d H:i:s");
        $Request_time = session_id();

        $this->_query = "UPDATE SINTEGRALGNL.BGEConexiones SET Estatus = 'D', FechaDesconexion = '$Fecha', NoUsuarioUM = $NoUsuario,REQUEST_TIME ='$Request_time' WHERE NoUsuario = $NoUsuario ";
        $this->get_result_multi_query();

    }


    public function valida_session_id($NoUsuario){

        //NoUsuario,init_time,session_id
        $FechaActual = date("Y-m-d H:i:s");
        $Request_time = $_SESSION['data_login']['REQUEST_TIME'];

        $NoUsuario = $_SESSION['data_login']['NoUsuario'];


        if(array_key_exists('data_login',$_SESSION)){

            if(!isset($_SESSION['data_login']['NoUsuario'])){

                // No Existe Session
                //$this->_query = "UPDATE SINTEGRALGNL.BGEConexiones SET Estatus = 'D', FechaDesconexion = '$FechaActual', NoUsuarioUM = $NoUsuario,REQUEST_TIME ='$Request_time' WHERE NoUsuario = $NoUsuario ";
                //$this->execute_query();
                session_unset ();
                session_destroy ();
                session_start();
                session_regenerate_id(true);
                echo "<script>location.href ='".core::ROOT_MODULES."applications/layout/error/?type=issetuser&error=".md5(3)."&id=".$Request_time."';</script>";


            }else{

                // si cuenta con acceso
                $ahora = date("Y-m-d H:i:s") ;
                $init_time = $_SESSION['data_login']['init_time'];

                // Si Existe la session

                $this->_query ="
                    SELECT REQUEST_TIME FROM SINTEGRALGNL.BGEConexiones
                    WHERE NoUsuario = ".$NoUsuario." AND Estatus = 'C' AND REQUEST_TIME = '$Request_time' ";

                $this->get_result_query();
                $id_session = $this->_rows[0]['REQUEST_TIME'];

                if($Request_time != $id_session){
                    session_unset ();
                    session_destroy ();
                    session_start();
                    session_regenerate_id(true);

                    echo "<script>location.href ='".core::ROOT_MODULES."applications/layout/error/?type=iddiferent&error=".md5(3)."&id=".$Request_time."&init_time=".base64_encode($init_time)."&time=".base64_encode($ahora)."&ids=".$id_session."&rq=".$Request_time."';</script>";
                }

                $tiempo_transcurrido = (strtotime($ahora)- strtotime($init_time));

                // 30 minutos = 1800
                if($tiempo_transcurrido >= 1800){


                    $this->_query = "UPDATE SINTEGRALGNL.BGEConexiones SET Estatus = 'D', FechaDesconexion = '$FechaActual', NoUsuarioUM = $NoUsuario,REQUEST_TIME ='$Request_time' WHERE NoUsuario = $NoUsuario ";
                    $this->execute_query();

                    session_unset ();
                    session_destroy ();
                    session_start();
                    session_regenerate_id(true);
                    echo "<script>location.href ='".core::ROOT_MODULES."applications/layout/error/?type=timer&error=".md5(3)."&id=".$Request_time."&init_time=".base64_encode($init_time)."&time=".base64_encode($ahora)."';</script>";

                }else{

                    //renovar el tiempo de sesión
                    $_SESSION['data_login']['init_time'] = date("Y-m-d H:i:s") ;

                }

            }

        }else{
            //no existe session
            //$this->_query = "UPDATE SINTEGRALGNL.BGEConexiones SET Estatus = 'D', FechaDesconexion = '$FechaActual', NoUsuarioUM = $NoUsuario,REQUEST_TIME ='$Request_time' WHERE NoUsuario = $NoUsuario ";
            //$this->execute_query();
            session_unset ();
            session_destroy ();
            session_start();
            session_regenerate_id(true);
            echo "<script>location.href ='".core::ROOT_MODULES."applications/layout/error/?&type=keyexists&error=".md5(3)."&id=".$Request_time."';</script>";
        }

    }

    //Funcion para obtener la ip del cliente
    public function get_obtener_ip(){


        return $_SERVER['REMOTE_ADDR'] ;
    }

}