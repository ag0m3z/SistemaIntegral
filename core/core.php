<?php

/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 23/01/2017
 * Time: 05:19 PM
 */
namespace core;

// Desactivar toda notificación de error
error_reporting(0);
date_default_timezone_set('America/Monterrey');

class core
{
    CONST NAME_APP = "Sistema Integral ";
    CONST ROOT_APP = "/SistemaIntegral/";
    CONST ROOT_CORE = core::ROOT_APP."core/" ;
    CONST ROOT_MODEL = core::ROOT_CORE . "models/";
    CONST ROOT_MODULES = core::ROOT_APP . "modules/";

    public static function getTitle($title = "ikro System"){

        print "<title>$title</title>";

    }

    public static function setTitle($title = "ikro System"){

        print "<script>
                    $('title').text('$title');
                </script>";

    }

    public static function includeCSS($dir_path,$all_folder = false){
        if($all_folder){
            // Recorrer todas las hojas de estilo y agregarlas
            $path = $dir_path;
            $handle=opendir($path);
            if($handle){
                while (false !== ($entry = readdir($handle)))  {
                    if($entry!="." && $entry!=".."){
                        $fullpath = $path.$entry;
                        if(!is_dir($fullpath)){
                            echo "<link rel='stylesheet' type='text/css' href='".$fullpath."' />";

                        }
                    }
                }
                closedir($handle);
            }
        }else{
            // Adjuntar solo la Hoja de Estilo solicitada
            echo "<link rel='stylesheet' type='text/css' href='".$dir_path."' />";
        }
    }

    public static function includeJS($dir_path,$all_folder = false){
        if($all_folder){
            // Agregar todos los js y agregarlos
            $path = $dir_path;
            $handle=opendir($path);
            if($handle){
                while (false !== ($entry = readdir($handle)))  {
                    if($entry!="." && $entry!=".."){
                        $fullpath = $path.$entry;
                        if(!is_dir($fullpath)){

                            echo "<script type='text/javascript' src='".$fullpath."'></script>";

                        }
                    }
                }
                closedir($handle);
            }
        }else{
            // Agregar solo el js Solicitado
            echo "<script type='text/javascript' src='".$dir_path."'></script>";
        }
    }

    public static function returnHome( $nameView =array(),$parametros = array()){

        if(count($parametros) > 0 ){
            $url_params = null ;

            foreach( $parametros as $opc => $valor ){

                $url_params .= $opc ."=" . $valor ."&";

            }

            echo "<script>location.href = '?".$url_params."ikro=88';</script>";

        }else{
            echo "<script>location.href ='".core::ROOT_APP."';</script>";
        }
    }

    public static function mostrarFecha($size){

        $dia=date("N");
        if ($dia=="1") $dia="Lunes";
        if ($dia=="2") $dia="Martes";
        if ($dia=="3") $dia="Mi&eacute;rcoles";
        if ($dia=="4") $dia="Jueves";
        if ($dia=="5") $dia="Viernes";
        if ($dia=="6") $dia="S&aacute;bado";
        if ($dia=="7") $dia="Domingo";
        $mes=date("F");

        if ($mes=="January") $mes="Enero";
        if ($mes=="February") $mes="Febrero";
        if ($mes=="March") $mes="Marzo";
        if ($mes=="April") $mes="Abril";
        if ($mes=="May") $mes="Mayo";
        if ($mes=="June") $mes="Junio";
        if ($mes=="July") $mes="Julio";
        if ($mes=="August") $mes="Agosto";
        if ($mes=="September") $mes="Setiembre";
        if ($mes=="October") $mes="Octubre";
        if ($mes=="November") $mes="Noviembre";
        if ($mes=="December") $mes="Diciembre";
        $dia2=date("d");
        $ano=date("Y");

        return "$dia, $dia2 de $mes del $ano";


    }

    public static function MyAlert($message,$type){

        echo "<script> MyAlert('".$message."','".$type."'); </script>" ;

    }

}