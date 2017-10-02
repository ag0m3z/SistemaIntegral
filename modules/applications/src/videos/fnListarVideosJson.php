<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 18/08/2017
 * Time: 10:33 AM
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

header("Content-Type: application/json");

$connect->_query = "
  SELECT 
    a.idVideo,c.Descripcion,a.Titulo,a.Descripcion,a.Url,a.Imagen,a.TipoImagen,b.NombreDePila,a.FechaAlta,a.NoEstatus,a.OpcionVideo,UrlLocal 
  FROM BGETablaVideos as a 
  LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
  ON a.NoUsuarioAlta = b.NoUsuario 
  JOIN BGEEmpresas as c 
  ON a.idEmpresa = c.idEmpresa
  order BY a.FechaAlta DESC
";
$connect->get_result_query();

$Url = 'modules/applications/Adjuntos/videos/videoTmp/';

for($i=0;$i < count($connect->_rows);$i++){

    if($connect->_rows[$i][9] == 1){
        $opc = 3;
        $etiqueta = "Desactivar";
        $icon = "trash";
    }else{

        $opc = 5;
        $etiqueta = "Activar";
        $icon = "check";

    }

    $NoEstado = $connect->getFormatoEstatus($connect->_rows[$i][9]);

    $data[] = array(
      "id"=>"<a href='#' onclick='fngnEditarVideos(1,\"".$connect->_rows[$i][0]."\")'><span class='text text-primary'>".$connect->getFormatFolio($connect->_rows[$i][0],4)."</span></a>",
      "empresa"=>$connect->_rows[$i][1],
      "name"=>$connect->_rows[$i][2],
      "descrip"=>$connect->_rows[$i][3],
      "noestado"=>$NoEstado,
      "usuarioa"=>$connect->_rows[$i][7],
      "fechaalta"=>$connect->_rows[$i][8],
      "config"=>"<a href='#' onclick='fngnEditarVideos($opc,".$connect->_rows[$i][0].")' class='text-primary btn-sm'><i class='fa fa-$icon'></i></a><a href='#' onclick='fngnEditarVideos(4,".$connect->_rows[$i][0].")' class='text-primary text-right btn-sm'><i class='fa fa-close'></i></a>"
    );
}

echo json_encode($data);