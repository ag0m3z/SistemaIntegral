<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 02/05/2017
 * Time: 05:46 PM
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


if(!array_key_exists('idcodigo',$_POST) && !array_key_exists('valor_caracteristica',$_POST) && !array_key_exists('idcaracteristica',$_POST) && !array_key_exists('idcategoria',$_POST) && !array_key_exists('idserie',$_POST)){
    \core\core::MyAlert("no se encontro el codigo o la serie para registrar <br>la caracteristica","alert");
}
$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$connect->valida_session_id();
$SqlConnect = new \core\sqlconnect();
unset($_SESSION['EXPORT']);

$idCategoria = $_POST['idcategoria'];
$idSerie = (int) $_POST['idserie'];
$idCodigo = $_POST['idcodigo'];
$idCaracteristica = (int) $_POST['idcaracteristica'];
$Caracteristica = $connect->get_sanatiza($_POST['valor_caracteristica']);


if($_POST['opc'] == 8){
    //Agregar caracteristica a Nivel codigo
    $idSerie = " ";
    //validar que no exista la caracteristica
    $SqlConnect->_sqlQuery = "
                         SELECT 
                            idCaracteristica
                        FROM 
                            SAyT.dbo.INVProdCarDet  
                        WHERE idSerie = '' AND idCodigo ='$idCodigo' AND idCaracteristica = '$_POST[idcaracteristica]' ";
    $SqlConnect->get_result_query();

    if(count($SqlConnect->_sqlRows) > 0){
        \core\core::MyAlert("Error la caracteristica ya existe","alert");

        if($_POST['opcion'] == 3){
            echo "<script>fn_registrar_caracteristica(9,'$idCategoria','$idCodigo','$idSerie')</script>";
            echo "<script>fn_registrar_caracteristica(10,'$idCategoria','$idCodigo','$idSerie')</script>";
        }else{
            echo "<script>fn_registrar_caracteristica(9,'$idCategoria','$idCodigo','$idSerie')</script>";
        }

    }else{
        //Registrar caracteristica
        $SqlConnect->_sqlQuery =
            "
        INSERT INTO SAyT.dbo.INVProdCarDet (
        idSerie,idCodigo,idCaracteristica,ValorCaracteristica
        ) VALUES (
        '$idSerie',
        '$_POST[idcodigo]',
        $idCaracteristica,
        '$_POST[valor_caracteristica]'
        )";
        $SqlConnect->execute_query();
        if($_POST['opcion'] == 3){
            echo "<script>fn_registrar_caracteristica(9,'$idCategoria','$idCodigo','$idSerie')</script>";
            echo "<script>fn_registrar_caracteristica(10,'$idCategoria','$idCodigo','$idSerie')</script>";
        }else{
            echo "<script>fn_registrar_caracteristica(9,'$idCategoria','$idCodigo','$idSerie')</script>";

        }
    }
}else {
    // Anivel Serie
    //validar que no exista la caracteristica
    $SqlConnect->_sqlQuery = "
                         SELECT 
                            idCaracteristica
                        FROM 
                            SAyT.dbo.INVProdCarDet  
                        WHERE idSerie = '$_POST[idserie]' AND idCaracteristica = '$_POST[idcaracteristica]' ";
    $SqlConnect->get_result_query();

    if(count($SqlConnect->_sqlRows) > 0){
        \core\core::MyAlert("Error la caracteristica ya existe","alert");
        echo "<script>fn_registrar_caracteristica(5,'$idCategoria','$idCodigo','$idSerie')</script>";
    }else{
        //Registrar caracteristica
        echo $SqlConnect->_sqlQuery =
            "
        INSERT INTO SAyT.dbo.INVProdCarDet (
        idSerie,idCodigo,idCaracteristica,ValorCaracteristica
        ) VALUES (
        $idSerie,
        '$_POST[idcodigo]',
        $idCaracteristica,
        '$_POST[valor_caracteristica]'
        )";
        $SqlConnect->execute_query();
        echo "<script>fn_registrar_caracteristica(5,'$idCategoria','$idCodigo','$idSerie')</script>";
    }
}

