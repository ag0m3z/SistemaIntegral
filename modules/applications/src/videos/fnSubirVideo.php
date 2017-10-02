<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 16/08/2017
 * Time: 05:39 PM
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

header('Content-Type: application/JSON');

$NoUsuario = $_SESSION['data_login']['NoUsuario'];
$FechaAlta = date("Y-m-d H:i:s");

if($_SERVER['REQUEST_METHOD'] == "POST"){

    if($_POST['opc'] == 1){
        //Alta de Video

        //Validar datos
        if(
            array_key_exists('txtEmpresa',$_POST) &&
            !empty($_POST['txtEmpresa']) &&
            array_key_exists('txtTitulo',$_POST) &&
            !empty($_POST['txtTitulo']) &&
            array_key_exists('txtUrl',$_POST) &&
            !empty($_POST['txtUrl'])
        ){

            //Sanatizar Datos
            $_POST = $connect->get_sanatiza($_POST);
            $Titulo = $_POST['txtTitulo'];
            $idEmpresa = $_POST['txtEmpresa'];
            $Descripcion = $_POST['txtDescripcion'];
            $Url = $_POST['txtUrl'];

            if(empty($_SESSION['IMAGENES'])){
                echo json_encode(
                    array(
                        "success"=>false,
                        "message"=>"Error no se encontro la imagen",
                        "data"=>array('sesion'=>$_SESSION['IMAGENES'])
                    )
                );
                exit;
            }

            $imagen = $_SESSION['IMAGENES']['nombre'];
            $type = $_SESSION['IMAGENES']['mime'];

            $videoLocal = $_SESSION['VIDEOVIMEO']['nombre'];

            $OpcioVideo = $_POST['TipoVideo'];


            $connect->_query = "
            INSERT INTO BGETablaVideos (idVideo,idEmpresa,Titulo,Descripcion,OpcionVideo,Url,UrlLocal,Imagen,TipoImagen,NoEstatus,NoUsuarioAlta,NoUsuarioUM,FechaAlta,FechaUM) VALUES (
            null,'$idEmpresa','$Titulo','$Descripcion','$OpcioVideo','$Url','$videoLocal','$imagen','$type','1','$NoUsuario','$NoUsuario','$FechaAlta','$FechaAlta'
            )";

            $connect->execute_query();

            echo json_encode(
                array(
                    "success"=>true,
                    "message"=>"Video guardado correctamente",
                    "data"=>array('image'=>$imagen,'sesion'=>$_SESSION['IMAGENES'])
                )
            );

        }else{
            echo json_encode(
                array(
                    "success"=>false,
                    "message"=>"Parametros incorrectos",
                    "data"=>array()
                )
            );
        }
    }

}else{
    echo json_encode(
      array(
          "success"=>false,
          "message"=>"El metodo no es soportado",
          "data"=>array()
      )
    );
}