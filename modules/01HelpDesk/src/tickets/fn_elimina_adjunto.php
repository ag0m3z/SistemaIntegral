<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 14/02/2017
 * Time: 12:47 PM
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
include "../../../../core/model_tickets.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$Tickets = new \core\model_tickets($_SESSION['data_login']['BDDatos']);

$Tickets->valida_session_id($_SESSION['data_login']['NoUsuario']);

$FechaActual = date("Ymd");
$HoraActual = date("H:i:s");

//Variables recibidas por Ajax
$ID = $_POST['idadd'];
$Folio = $_POST['fl'];
$Anio = $_POST['an'];
$NoDepartamento = $_POST['nodpto'];
$TipoArchivo = $_POST['opt'];

//Ruta si Son Documentos
$RutaAdjuntos = "../../Adjuntos/documents/";
//Ruta si Son Imagenes
if($TipoArchivo == 1){ $RutaAdjuntos = "../../Adjuntos/pictures/"; }

$Tickets->_query =  "SELECT Anio,Folio,NoDepartamento,ID,NombreAdjunto
                                FROM BSHAdjuntos
                                WHERE TipoArchivo=$TipoArchivo AND Anio= $Anio
                                    AND NoDepartamento= '$NoDepartamento' AND Folio= $Folio
                                    AND ID = $ID LIMIT 1";

$Tickets->get_result_query();

if(count($Tickets->_rows) > 0){
    $NombreAdjunto = $Tickets->_rows[0]['NombreAdjunto'];
    $dir = $RutaAdjuntos.$NombreAdjunto;
    //Se valida que el Archivo Exista en la Carpeta
    if(file_exists($dir)){
        if(unlink($dir)){
            //----> Consulta EliminaAdjunto();
            echo $Tickets->eliminar_adjunto($TipoArchivo,$Anio,$NoDepartamento,$Folio,$ID);

            if($TipoArchivo == 1){
                $Tickets->mostrar_imagenes_adjuntas($Folio,$NoDepartamento,$Anio,$TipoArchivo);
            }else{
                echo '<table class="tableHistory table-hover">
                        <thead>
                            <tr>
                                <th width="25">Id</th>
                                <th>Nombre del Adjunto</th>
                                <th width="105">Fecha</th>
                                <th width="105">Hora</th>
                                <th width="155">Usuario</th>
                                <th width="205">Funciones</th>
                            </tr>
                        </thead>';
                $Tickets->mostrar_adjuntos($Folio,$Anio,$NoDepartamento,$TipoArchivo);
            }
        }else{
            echo "Error al Eliminar el Adjunto<br>";
        }
    }else{
        echo "El Archivo no existe";
    }
}



