<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 11/05/2017
 * Time: 12:19 PM
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

sleep(1);

// Variables para sacar la Informacion del Archivo a Subir.
$nombre_archivo = $_FILES['archivo']['name'];
$tipo_archivo = $_FILES['archivo']['type'];
$tamano_archivo = $_FILES['archivo']['size'];
$tmp_archivo = $_FILES['archivo']['tmp_name'];
$Archivo = $_FILES['archivo'];
$partes_nombre = explode(".",$nombre_archivo);
$extencion = end( $partes_nombre );
$permitidos = array("image/jpg","image/jpeg", "image/gif", "image/png");
$limite_kb = 750;
$idCodigo = $_POST['idcodigo'];
$idSerie = $_POST['idserie'];

// Comprobamos si ha ocurrido un error.
if (!isset($_FILES["archivo"]) || $_FILES["archivo"]["error"] > 0)
{
    \core\core::MyAlert("Ha ocurrido un error.","error");

}else{

     // Verificamos si el tipo de archivo es un tipo de imagen permitido.
    if (in_array($_FILES['archivo']['type'], $permitidos) && $_FILES['archivo']['size'] <= $limite_kb * 1024){

        //echo json_encode($_FILES);
        include "../../../../core/sqlconnect.php";
        $SqlServer = new \core\sqlconnect();

        //Validar que tipo de Imagen es, A Nivel Codigo o Nivel Serie
        switch ($_POST['opc']){
            case 1:
                // Leemos el contenido del archivo temporal en binario.
                $fp = fopen($tmp_archivo, 'r+b');
                $data = fread($fp, filesize($tmp_archivo));
                fclose($data);

                $fileStream = $data;
                $idSerie = ' ';
                $idCodigo = $_POST['idcodigo'];

                //Traer el Siguiente id Imagen
                $SqlServer->_sqlQuery = "SELECT ISNULL(MAX(idImagen)+1,1) FROM SAyT.dbo.INVProdImagen ";
                $SqlServer->get_result_query();

                $idImagen = $SqlServer->_sqlRows[0][0];
                $Orden = '1';
                $UsuarioAlta = $_SESSION['data_login']['NoUsuario'];
                $FechaAlta = date("Ymd");
                $HoraAlta = date("H:i:s");

                $sql = "INSERT INTO SAyT.dbo.INVProdImagen (idSerie,idCodigo,idImagen,Imagen,NombreImagen,TamanoImagen,Extencion,TipoImagen,Orden,UsuarioAlta,FechaAlta,HoraAlta)
                VALUES ('$idSerie','$idCodigo','$idImagen',?,'$nombre_archivo','$tamano_archivo','$extencion','$tipo_archivo','$Orden','$UsuarioAlta','$FechaAlta','$HoraAlta')";

                $params =
                    array(
                        array(&$fileStream,
                            SQLSRV_PARAM_IN,
                            SQLSRV_PHPTYPE_STREAM(SQLSRV_ENC_BINARY),
                            SQLSRV_SQLTYPE_VARBINARY('max'))
                    );

                $SqlServer->execute_msql_upload_file($sql,$params);

                echo "<script>getMessageNotify('','Imagen guardada correctamente','info',1000)</script>";
                echo "<script>fn_listar_imagenes_producto_web(1,'$idCodigo','$idSerie')</script>";


                break;
            case 2:
                break;
            default:
                \core\core::MyAlert("Opcion no encontrada","error");
                echo "<script>fn_listar_imagenes_producto_web(1,'$idCodigo','$idSerie')</script>";
                break;
        }


    }else{
        echo "<script>fn_listar_imagenes_producto_web(1,'$idCodigo','$idSerie')</script>";

        $tamano_archivo = round($tamano_archivo * 0.0009765625,2);

        \core\core::MyAlert("Formato de archivo no permitido o excede el tamaño límite.<br><b>Archivo: </b> $tamano_archivo Kbytes <br><b>Limite: </b> $limite_kb Kbytes.","error");


    }

}
