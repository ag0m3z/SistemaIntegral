<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 01/02/2017
 * Time: 11:27 AM
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

//validar sesion del usuario
$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);

// Declaracion de Variables para localizar las Carpetas
$upload_folder =  \core\core::ROOT_APP ."site_design/img/faces/fullscreen/";
$upload_folder2 =  "faces/thumbnail/";
$CarpetaFullScreen = "../../../../site_design/img/faces/fullscreen/";

// Variables para sacar la Informacion del Archivo a Subir.
$nombre_archivo = $_FILES['archivo']['name'];
$tipo_archivo = $_FILES['archivo']['type'];
$tamano_archivo = $_FILES['archivo']['size'];
$tmp_archivo = $_FILES['archivo']['tmp_name'];
$Archivo = $_FILES['archivo'];

//Sacar la Extencion del Archivo a Subir.
$extension = pathinfo($Archivo['name'], PATHINFO_EXTENSION);

//Extenciones Permitidas
$extension_permitidas = array('jpg','gif','png','JPG','GIF','PNG');

$partes_nombre = explode(".",$nombre_archivo);
$extension2 = end( $partes_nombre );

$ext_correcta = in_array($extension2, $extension_permitidas);

//expresion para sacar el filtro.
$tipo_correcto = preg_match('/^image\/(pjpeg|jpeg|gif|png)$/', $tipo);

if(in_array($extension2, $extension_permitidas)){

    //Validar que el Archivo no Este Dañado o Corrupto
    if($_FILES['archivo']['error']>0){
        echo "Error al Subir el Archivo ".$_FILES['archivo']['error'];
    }else{

        $archivador = $upload_folder . "idUser".$_SESSION['data_login']['NoUsuario'].".".$extension;

        if(!move_uploaded_file($tmp_archivo,$archivador)){
            $return = Array('ok' => FALSE, 'msg' => "Ocurrio un error al subir el archivo. No pudo guardarse.".$nombre_archivo, 'status' => 'error');
            echo json_encode($return);
        }else{

            $nombreFoto = $upload_folder2."idUser".$_SESSION['data_login']['NoUsuario'].".".$extension;

            // Cambiar Tamano a la Imagen
            switch(strtolower($extension)){
                case "jpg":
                    $rsr_org = imagecreatefromjpeg($archivador);

                    $rsr_scl = imagescale($rsr_org, 215, 215,  IMG_BICUBIC_FIXED);
                    imagejpeg($rsr_scl, "../../../../site_design/img/faces/thumbnail/idUser".$_SESSION['data_login']['NoUsuario'].".".$extension);
                    imagedestroy($rsr_org);
                    imagedestroy($rsr_scl);
                    break;
                case "gif":
                    $rsr_org = imagecreatefromgif($archivador);

                    $rsr_scl = imagescale($rsr_org, 215, 215,  IMG_BICUBIC_FIXED);
                    imagegif($rsr_scl, "../../../../site_design/img/faces/thumbnail/idUser".$_SESSION['data_login']['NoUsuario'].".".$extension);
                    imagedestroy($rsr_org);
                    imagedestroy($rsr_scl);
                    break;
                case "png":
                    $rsr_org = imagecreatefrompng($archivador);

                    $rsr_scl = imagescale($rsr_org, 215, 215,  IMG_BICUBIC_FIXED);
                    imagepng($rsr_scl, "../../../../site_design/img/faces/thumbnail/idUser".$_SESSION['data_login']['NoUsuario'].".".$extension);
                    imagedestroy($rsr_org);
                    imagedestroy($rsr_scl);
                    break;
                default:
                    $return = Array('ok' => FALSE, 'msg' => "Ocurrio un error convertir la imagen. No pudo guardarse.", 'status' => 'error');
                    echo json_encode($return);
                    break;

            }


            $seguridad->_query = "
            UPDATE SINTEGRALGNL.BGEEmpleados
                SET idPhoto = '$nombreFoto'
            WHERE idEmpleado = ".$_SESSION['data_login']['idEmpleado']."
            ";

            $seguridad->execute_query();

            unset($_SESSION['data_login']['imagen_profile']);

            $_SESSION['data_login']['imagen_profile'] = $nombreFoto;



        }


    }

}else{
    \core\core::MyAlert("Tipo de documento invalido, Int&eacute;ntelo nuevamente","alert");
}