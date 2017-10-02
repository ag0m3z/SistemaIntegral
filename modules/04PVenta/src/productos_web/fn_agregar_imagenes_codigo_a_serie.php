<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/05/2017
 * Time: 10:20 AM
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
include "../../../../core/sqlconnect.php";

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

$SqlServer = new \core\sqlconnect();
/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **
**/
unset($_SESSION['EXPORT']);

$idCodigo = $_POST['idcodigo'];
$idCategoria = $_POST['idcategoria'];
$idSerie = $_POST['idserie'];
$idImagen = $_POST['idimagen'];


if($_POST['opc'] == 1){
    //Agregar solo la imagen


    /**
     * Paso 1 Validar que no exista ya la imagen a Nivel Serie
     * Paso 2 Insertar la nueva imagen
     */


    $SqlServer->_sqlQuery =
    "
    SELECT COUNT(idSerie) FROM SAyT.dbo.INVProdImagen WHERE idSerie = '$idSerie' AND idCodigo = '$idCodigo' AND idImagen = '$idImagen'
    ";
    $SqlServer->get_result_query();

        if($SqlServer->_sqlRows[0][0] > 0){

            //Ya existe la imagen

            \core\core::MyAlert("La imagen ya existe a nivel serie","error");
            echo "<script>fn_listar_imagenes_producto_web(2,'$idCodigo','$idSerie');</script>";

        }else{

            //Query para Insertar la Caracterisitca
            $SqlServer->_sqlQuery =
                "
                INSERT INTO SAyT.dbo.INVProdImagen 
                SELECT '$idSerie','$idCodigo','$idImagen',Imagen,NombreImagen,TamanoImagen,Extencion,TipoImagen,Orden,UsuarioAlta,FechaAlta,HoraAlta,null,null,null FROM SAyT.dbo.INVProdImagen 
                      WHERE 
                        idSerie = ' ' AND 
                        idCodigo = '$idCodigo' AND 
                        idImagen = '$idImagen'  
                ";

            $SqlServer->execute_query();
            echo "<script>fn_listar_imagenes_producto_web(2,'$idCodigo','$idSerie')</script>";
            echo "<script>getMessageNotify('','Imagen registrada correctamente','info',1500)</script>";


        }

}else if($_POST['opcion'] == 2){

    //Agregar todas las imagenes

}