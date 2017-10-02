<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 08/02/2017
 * Time: 06:31 PM
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

$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);

$NoArea = $_POST['NoArea'];
$NoDepartamento = $_POST['NoDepartamento'];

$seguridad->_query = "
                      SELECT nocategoria,descripcion,NoArea 
                        FROM BSHCatalogoCategoria 
                      where 
                        NoArea=" . $NoArea . " AND 
                        NoDepartamento='".$NoDepartamento."' 
                      ORDER BY Descripcion ASC
                      ";

$seguridad->get_result_query();

$data_categorias = $seguridad->_rows;

for($i=0; $i < count($data_categorias); $i++ ){

    echo "<option value='".$data_categorias[$i]['nocategoria']."' >".$data_categorias[$i]['descripcion']."</option>";

}