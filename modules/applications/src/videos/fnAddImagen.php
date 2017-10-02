<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/08/2017
 * Time: 12:51 PM
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

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
unset($_SESSION['IMAGENES']);

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

$NombreImagen = date("YmdHis");
$_SESSION['IMAGENES']['nombre'] =$NombreImagen;
$_SESSION['IMAGENES']['mime'] = $tipo_archivo;


if(in_array($extension2, $extension_permitidas)) {

    //Validar que el Archivo no Este Dañado o Corrupto
    if ($_FILES['archivo']['error'] > 0) {
        echo "Error al Subir el Archivo " . $_FILES['archivo']['error'];
    } else {

         $RutaRoot = \core\core::ROOT_APP."modules/applications/Adjuntos/videos/imgTemp/";
        $NombreImagen = $_SESSION['data_login']['NoUsuario'].$NombreImagen."." . $extension;

        if (!move_uploaded_file($tmp_archivo, "../../Adjuntos/videos/imgTemp/".$NombreImagen)) {
            $return = Array('ok' => FALSE, 'msg' => "Ocurrio un error al subir el archivo. No pudo guardarse." . $archivador, 'status' => 'error');
            echo json_encode($return);
        } else {

            $_SESSION['IMAGENES']['nombre'] = $NombreImagen;

        }
    }
}
