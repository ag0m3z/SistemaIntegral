<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 11/02/2017
 * Time: 01:14 PM
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
include "../../../../core/PHPUploads.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$upload = new \core\PHPUploads($_SESSION['data_login']['BDDatos']);
$upload->valida_session_id($_SESSION['data_login']['NoUsuario']);

$FechaActual = date("Ymd");
$HoraActual = date("H:i:s");

if(!isset($_FILES['archivo'])){
    //Si el no existe o biene vacio el Archivo

    \core\core::MyAlert('Seleccione un archivo, antes de guardar','info');

}else{

    $upload->_query = "SELECT NoSucursal FROM  BSHReportes WHERE Folio = ".$_REQUEST['pr01']." AND anio =".$_REQUEST['pr02']."  AND NoDepartamento = '".$_REQUEST['pr03']."' ";
    $upload->get_result_query();

    $dataRow = $upload->_rows[0];


    switch($_REQUEST['doc']){
        case 1:
            //Adjuntar Imagenes en Ticket

            //Adjuntar Documentos en Ticket
            $upload->NameFile = $_FILES['archivo']['name'];
            $upload->NamFileTemp = $_FILES['archivo']['tmp_name'];
            $upload->File = $_FILES['archivo'];
            $upload->FileType = $_FILES['archivo']['type'];
            $upload->FileSize = $_FILES['archivo']['size'];
            $upload->FileExte = pathinfo($upload->File['name'],PATHINFO_EXTENSION);
            $upload->FolderName = "../../Adjuntos/pictures/";
            $upload->PregMatch = '/^image\/(pjpeg|jpeg|gif|png)$/';
            $upload->Extencion = array(
                'jpg','jpeg','gif','png',
                'JPG','JPEG','GIF','PNG'
            );
            if(!$upload->UploadDocument($_REQUEST['doc'],$_REQUEST['pr01'],$_REQUEST['pr03'],$_REQUEST['pr02'],$dataRow[0])){
                echo "<script language='JavaScript'>CloseModalAndReload();</script>";
            }
            break;
        case 2:
            //Adjuntar Documentos en Ticket
            $upload->NameFile = $_FILES['archivo']['name'];
            $upload->NamFileTemp = $_FILES['archivo']['tmp_name'];
            $upload->File = $_FILES['archivo'];
            $upload->FileType = $_FILES['archivo']['type'];
            $upload->FileSize = $_FILES['archivo']['size'];
            $upload->FileExte = pathinfo($upload->File['name'],PATHINFO_EXTENSION);
            $upload->FolderName = "../../Adjuntos/documents/";
            $upload->PregMatch = '/^application\/(msword|vnd.ms-excel|nd.ms-powerpoint|pdf)$/';
            $upload->Extencion = array(
                'xls','xlsx','docx','doc','pptx','ppt',
                'pdf','zip','xlsm','msg','txt','TXT',
                'MSG','XLS','XLSX','DOCX','DOC','PPTX','PPT',
                'PDF','ZIP','XLSM'
            );
            if(!$upload->UploadDocument($_REQUEST['doc'],$_REQUEST['pr01'],$_REQUEST['pr03'],$_REQUEST['pr02'],$dataRow[0])){
                echo "<script language='JavaScript'>CloseModalAndReload();</script>";
            }else{
                echo "<script language='JavaScript'>MyAlert('Error al realizar la carga del archivo','error');</script>";
            }
            break;
        case 3:
            $RutaArchivo = "../../Adjuntos/equipos/documents/";

            //Adjuntar Documentos de Equipos
            $upload->NameFile = $_FILES['archivo']['name'];
            $upload->NamFileTemp = $_FILES['archivo']['tmp_name'];
            $upload->File = $_FILES['archivo'];
            $upload->FileType = $_FILES['archivo']['type'];
            $upload->FileSize = $_FILES['archivo']['size'];
            $upload->FileExte = pathinfo($upload->File['name'],PATHINFO_EXTENSION);
            $upload->FolderName = $RutaArchivo;
            $upload->PregMatch = '/^application\/(msword|vnd.ms-excel|nd.ms-powerpoint|pdf)$/';
            $upload->Extencion = array(
                'xls','xlsx','docx','doc','pptx','ppt',
                'pdf','zip','xlsm','msg','txt','TXT',
                'MSG','XLS','XLSX','DOCX','DOC','PPTX','PPT',
                'PDF','ZIP','XLSM'
            );

            $res = $upload->UploadEquipos($_REQUEST['doc'],$_REQUEST['pr01']);
            if($res){
                $Folio = $_REQUEST['pr01'];
                $TipoDoc = ($_REQUEST['doc'] - 2);
                $UsuarioRecibe = $_SESSION['data_login']['NoUsuario'];

                $ip = "127.0.0.1";
                $ip2 = "127.0.0.1";

                $upload->_query = "CALL sp_agregar_adjuntos_equipos('$Folio','$TipoDoc','$res','$RutaArchivo','$FechaActual','$HoraActual','$UsuarioRecibe','$ip','$ip2','1')";
                if($upload->execute_query()){
                    echo 1;
                }else{

                    echo "<script language='JavaScript'>fnsdImprimeyActualizaEquipo(".$Folio.",null,false);</script>";

                }
            }

            break;
        case 4:
            //Adjuntar Imagenes de Equipos
            $RutaArchivo = "../../Adjuntos/equipos/pictures/";

            //Adjuntar Documentos de Equipos
            $upload->NameFile = $_FILES['archivo']['name'];
            $upload->NamFileTemp = $_FILES['archivo']['tmp_name'];
            $upload->File = $_FILES['archivo'];
            $upload->FileType = $_FILES['archivo']['type'];
            $upload->FileSize = $_FILES['archivo']['size'];
            $upload->FileExte = pathinfo($upload->File['name'],PATHINFO_EXTENSION);
            $upload->FolderName = $RutaArchivo;
            $upload->PregMatch = '/^image\/(pjpeg|jpg|jpeg|gif|png)$/';
            $upload->Extencion = array(
                'jpg','jpeg','gif','png',
                'JPG','JPEG','GIF','PNG'
            );

            $res = $upload->UploadEquipos($_REQUEST['doc'],$_REQUEST['pr01']);
            if($res){
                $Folio = $_REQUEST['pr01'];
                $TipoDoc = ($_REQUEST['doc'] - 2);
                $UsuarioRecibe = $_SESSION['data_login']['NoUsuario'];
                $ip = "127.0.0.1";
                $ip2 = "127.0.0.1";

                $upload->_query = "CALL sp_agregar_adjuntos_equipos('$Folio','$TipoDoc','$res','$RutaArchivo','$FechaActual','$HoraActual','$UsuarioRecibe','$ip','$ip2','1')";

                if($upload->execute_query()){
                    echo 1;
                }else{

                    echo "<script language='JavaScript'>fnsdImprimeyActualizaEquipo(".$Folio.",null,false);</script>";

                }
            }

            break;
    }



}