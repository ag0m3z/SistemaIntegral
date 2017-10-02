<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 22/08/2017
 * Time: 09:00 AM
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

$idVideo = $_POST['idVideo'];

switch ($_POST['opc']){

    case 2: //Editar informacion del video

        header("Content-Type:application/json");

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
            $FechaActual = date("Y-m-d H:i:s");
            $NoUsuario = $_SESSION['data_login']['NoUsuario'];
            $OpcionVideo = $_POST['TipoVideo'];

            $imagen = $_SESSION['IMAGENES']['nombre'];
            $type = $_SESSION['IMAGENES']['mime'];

            $Video = $_SESSION['VIDEOVIMEO']['nombre'];

            $connect->_query = "SELECT Url,UrlLocal,Imagen FROM BGETablaVideos WHERE idVideo = $idVideo";
            $connect->get_result_query();

            if(empty($imagen)){
                //Vacia
                $imagen = $connect->_rows[0][2];

            }
            if(empty($Video)){
                //Vacia
                $Video = $connect->_rows[0][1];

            }

            $connect->_query = "
                UPDATE BGETablaVideos 
                  SET 
                    idEmpresa = $idEmpresa,
                    Titulo = '$Titulo',
                    Descripcion = '$Descripcion',
                    OpcionVideo = '$OpcionVideo',
                    Url= '$Url',
                    UrlLocal = '$Video',
                    Imagen = '$imagen',
                    NoUsuarioUM = '$NoUsuario',
                    FechaUM = '$FechaActual'
                WHERE idVideo = $idVideo";

            $connect->execute_query();

            echo json_encode(
                array(
                    "success"=>true,
                    "message"=>"Video editado correctamente",
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



        break;
    case 3: //Desactivar el video
        header("Content-Type:application/json");

        if(!array_key_exists('idVideo',$_POST) && !empty($_POST['idVideo'])){
            echo json_encode(array(
                "result"=>false,
                "message"=>"Error no se encontro el id del video",
                "data"=>$_POST
            ));
        }else{

            $connect->_query = "UPDATE BGETablaVideos SET NoEstatus = 0 WHERE idVideo = $idVideo";
            $connect->execute_query();
            echo json_encode(array(
                "result"=>true,
                "message"=>"Video desactivado correctamente",
                "data"=>array()
            ));

        }
        break;
    case 4: //Eliminar el video
        header("Content-Type:application/json");

        if(!array_key_exists('idVideo',$_POST) && !empty($_POST['idVideo'])){
            echo json_encode(array(
                "result"=>false,
                "message"=>"Error no se encontro el id del video",
                "data"=>$_POST
            ));
        }else{

            $connect->_query = "DELETE FROM BGETablaVideos WHERE idVideo = $idVideo";
            $connect->execute_query();
            echo json_encode(array(
                "result"=>true,
                "message"=>"Video eliminado correctamente",
                "data"=>array()
            ));

        }
        break;
    case 5://Activar Video
        header("Content-Type:application/json");

        if(!array_key_exists('idVideo',$_POST) && !empty($_POST['idVideo'])){
            echo json_encode(array(
                "result"=>false,
                "message"=>"Error no se encontro el id del video",
                "data"=>$_POST
            ));
        }else{

            $connect->_query = "UPDATE BGETablaVideos SET NoEstatus = 1 WHERE idVideo = $idVideo";
            $connect->execute_query();
            echo json_encode(array(
                "result"=>true,
                "message"=>"Video Activado correctamente",
                "data"=>array()
            ));

        }
        break;
    default:
        break;
}