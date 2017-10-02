<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 12/04/2017
 * Time: 05:16 PM
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
include "../../../../core/model_categorias_sd.php";

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

$categorias = new \core\model_categorias_sd($_SESSION['data_login']['BDDatos']);
$categorias->valida_session_id();

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


if(array_key_exists('nocategoria',$_POST) || array_key_exists('nombre_categoria',$_POST) || array_key_exists('NoArea',$_POST) || array_key_exists('NoDepartamento',$_POST)){

    $NoCategoria = $categorias->get_sanatiza($_POST['nocategoria']);
    $NombreCategoria = $categorias->get_sanatiza($_POST['nombre_categoria']);
    $NoArea = $categorias->get_sanatiza($_POST['NoArea']);
    $NoDepartamento = $categorias->get_sanatiza($_POST['NoDepartamento']);
    $NoEstatus = $categorias->get_sanatiza($_POST['NoEstado']);


    $categorias->edit(array(
        'nocategoria'=>$NoCategoria,
        'nombre_categoria'=>$NombreCategoria,
        'NoArea'=>$NoArea,
        'NoDepartamento'=>$NoDepartamento,
        'NoEstatus'=>$NoEstatus
    ));

    if($categorias->_confirm){

        // $categorias->_query;
       echo "<script>fn_catalogo_categorias_lista(1);$('#modalbtnclose').click();</script>";

    }else{
        \core\core::MyAlert("Error al actualizar la categoría","error");
    }


}else{
    \core\core::MyAlert("No existe la categoría solicitada","error");
}