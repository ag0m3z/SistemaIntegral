<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 16/08/2017
 * Time: 06:10 PM
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
$connect->valida_session_id();
/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


if($_POST['opc'] == 1){

    $connect->_query = "SELECT idVideo,idEmpresa,Titulo,Descripcion,Url,Imagen,TipoImagen,OpcionVideo,UrlLocal FROM BGETablaVideos WHERE NoEstatus = 1 order BY FechaAlta DESC";
    $connect->get_result_query();

    for($i=0;$i<count($connect->_rows);$i++){


        $Imagen =  \core\core::ROOT_APP."modules/applications/Adjuntos/videos/imgTemp/" .$connect->_rows[$i][5];

        if($connect->_rows[$i][7] == 1){
            //Video Local
            $Url = \core\core::ROOT_APP."modules/applications/Adjuntos/videos/videoTemp/".$connect->_rows[$i][8];

        }else if($connect->_rows[$i][7] == 2){
            //Video en Vimeo
            $Url = $connect->_rows[$i][4] ;
        }


        echo '<div class="col-md-4 col-xs-6 col-sm-6">
                <div class="card hoverable animated flipInX " style="border: 1px #ECF0F5;min-height: 190px;max-height: 190px;">
                    <div class="card-image small">
                        <div class="img-contenedor" style="cursor: pointer" onclick="$(\'#videoDemo\').attr(\'src\',\''.$Url.'\');$(\'#mdlTituloVideo\').text(\''.$connect->_rows[$i][2].'\');fngnSeleccionarVideo();">
                            <img src="'.$Imagen.'" style="max-height: 100px;" class="img-responsive">
                        </div>
                        <a style="margin: 5px !important;margin-top: -20px !important;" class="btn-floating waves-effect waves-light bg-blue" onclick="$(\'#videoDemo\').attr(\'src\',\''.$Url.'\');$(\'#mdlTituloVideo\').text(\''.$connect->_rows[$i][2].'\');fngnSeleccionarVideo();" ><i class="fa fa-eye"></i></a>
                    </div>
                    <div class="card-content small" style="word-wrap: break-word;">
                        <p class="text-bold">'.$connect->_rows[$i][2].'</p>
                        <p>'.$connect->_rows[$i][3].'</p>
                    </div>
                </div>
            </div>';


    }



}else if($_POST['opc'] == 2){

    if(array_key_exists('txtString',$_POST)){

        $txtString = $_POST['txtString'];
        $connect->_query = "SELECT idVideo,idEmpresa,Titulo,Descripcion,Url,Imagen,TipoImagen FROM BGETablaVideos WHERE NoEstatus = 1 concat_ws(' ',Titulo,Descripcion) LIKE '%$txtString%' order BY FechaAlta DESC";
        $connect->get_result_query();

        for($i=0;$i<count($connect->_rows);$i++){

            $Imagen =  \core\core::ROOT_APP."modules/applications/Adjuntos/videos/imgTemp/" .$connect->_rows[$i][5];

            echo '<div class="col-md-4 col-xs-6 col-sm-6">
                <div class="card hoverable animated flipInX " style="border: 1px #ECF0F5;min-height: 200px;max-height: 200px;">
                    <div class="card-image small">
                        <div class="img-contenedor" style="cursor: pointer" onclick="$(\'#videoDemo\').attr(\'src\',\''.$connect->_rows[$i][4].'\');$(\'#mdlTituloVideo\').text(\''.$connect->_rows[$i][2].'\');fngnSeleccionarVideo();">
                            <img src="'.$Imagen.'" style="max-height: 100px;" class="img-responsive">
                        </div>
                        <a style="margin: 5px !important;margin-top: -20px !important;" class="btn-floating waves-effect waves-light bg-blue" onclick="$(\'#videoDemo\').attr(\'src\',\''.$connect->_rows[$i][4].'\');$(\'#mdlTituloVideo\').text(\''.$connect->_rows[$i][2].'\');fngnSeleccionarVideo();" ><i class="fa fa-eye"></i></a>
                    </div>
                    <div class="card-content small" style="word-wrap: break-word;">
                        <p class="text-bold">'.$connect->_rows[$i][2].'</p>
                        <p>'.$connect->_rows[$i][3].'</p>
                    </div>
                </div>
            </div>';


        }

    }else{

        echo "noexista";

    }



}