<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 02/10/2017
 * Time: 05:01 PM
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);

header("ContentType:application/json");

$NombreArchivo = $_FILES['archivo']['name'];
$TipoArchivo = $_FILES['archivo']['type'];
$TamanoArchivo = $_FILES['archivo']['size'];
$TempArchivo = $_FILES['archivo']['tmp_name'];
$Archivo = $_FILES['archivo'];

$NoUsuario = $_SESSION['data_login']['NoUsuario'];
$NoUsuarioRecibe = $_POST['NoUsuarioRecibe'];
//Ruta donde se subira el archivo
$RutaUpload = "../../Adjuntos/mensajes/";
$RutaImagen = "Adjuntos/mensajes/";
//Sacar la Extencion del Archivo a Subir.
$Extension = pathinfo($Archivo['name'], PATHINFO_EXTENSION);


//Extenciones Permitidas
$extension_permitidas = array('jpg','gif','jpeg','png','JPG','JPEG','GIF','PNG',"xls", "xlsx", "doc", "docx", "zip", "rar", "pps", "ppsx","PDF","pdf");

$partes_nombre = explode(".",$NombreArchivo);
$Extension = end( $partes_nombre );

if(in_array($Extension, $extension_permitidas)){
    //Extension Correcta

    //Validar que el Archivo no Este Dañado o Corrupto
    if($_FILES['archivo']['error']>0){
        echo json_encode(array("result"=>false,"message"=>"Error al Subir el Archivo ".$_FILES['archivo']['error'],"data"=>array()));
    }else{

        $NombreUnico = $_SESSION['data_departamento']['NoDepartamento'].$_SESSION['data_login']['NoUsuario'].date("dmYHis").".".$Extension;
        $Ruta = $RutaUpload .$NombreUnico;

        if(!move_uploaded_file($TempArchivo,$Ruta)){

            echo json_encode(array("result"=>false,"message"=>"Ocurrio un error al subir el archivo. No pudo guardarse.".$Ruta,"data"=>array()));

        }else{

            echo json_encode(array("result"=>true,"message"=>"Todo correcto para subir","data"=>array(
                    "TipoMensaje"=>2,
                    "NombreArchivo"=>$NombreArchivo,
                    "NombreUnico"=>$NombreUnico,
                    "NoUsuarioRecibe"=>$NoUsuarioRecibe,
                    "NoUsuario"=>$NoUsuario,
                    "RutaImagen"=>$RutaImagen
                )
            )
            );

        }
    }

}else{
    //Extension incorrecta
    echo json_encode(array("result"=>false,"message"=>"Extension no permitida","data"=>array()));
}