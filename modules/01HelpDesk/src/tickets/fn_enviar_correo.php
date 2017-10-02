<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 14/02/2017
 * Time: 11:52 AM
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
require_once  "../../../../core/model_mails.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$Tickets = new \core\model_tickets($_SESSION['data_login']['BDDatos']);

$Tickets->valida_session_id($_SESSION['data_login']['NoUsuario']);

$EnviarCorreo = false; //Variable Boleana para Enviar o no Correo

$NoTicket = $_POST['fl'];
$AnioTicket = $_POST['an'];
$NoDepartamento = $_POST['nodpto'];;

$Asunto = $Tickets->get_sanatiza($_POST['subject']);
$Mensaje = $Tickets->get_sanatiza($_POST['msg']);

//Query para sacar la informacion del Reporte
$Tickets->_query = "SELECT 
  a.Folio,
  a.Anio,
  a.NoDepartamento,
  d.Descripcion,
  a.NoSucursal,
  e.Correo,
  b.Descripcion,
  a.idEmpleado,
  a.NoUsuarioAsignado,
  c.NombreDePila,
  a.SolucionCierre,
  a.DescripcionReporte,
  a.Reporte,
  a.Fecha,
  a.HoraInicioReporte,
  f.NombreDePila as UsuarioRecibe,
  CONCAT_WS(' ',g.Nombre,g.ApPaterno,g.ApMaterno) as NombreSolicitante
FROM BSHReportes as a
LEFT JOIN BGECatalogoDepartamentos as b
ON a.NoSucursal = b.NoDepartamento
LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c
ON a.NoUsuarioAsignado = c.NoUsuario
LEFT JOIN BGECatalogoDepartamentos as d
ON a.NoDepartamento = d.NoDepartamento
LEFT JOIN SINTEGRALGNL.BGEEmpleados as e
ON c.idEmpleado = e.idEmpleado
LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as f 
ON a.NoUsuarioRecibe = f.NoUsuario
LEFT JOIN SINTEGRALGNL.BGEEmpleados as g 
ON a.idEmpleado = g.idEmpleado
where a.Anio = $AnioTicket And a.NoDepartamento = '$NoDepartamento' AND a.Folio = $NoTicket";

$Tickets->get_result_query();
$dataSegmento = $Tickets->_rows[0];

//Validar que el correo sea Correcto
if(filter_var($dataSegmento[5],FILTER_VALIDATE_EMAIL)){

    $AsuntoMail = $Asunto . " - Ticket: ".$Tickets->getFormatFolio($dataSegmento[0],4) ; // Este es el titulo del email.


    $data_mensaje = array(
        $Mensaje,
        $Tickets->getFormatFolio($dataSegmento[0],4)
    );

    $Plantilla = \core\model_mails::set_plantilla(
        $data_mensaje,
        $dataSegmento[16],
        $dataSegmento[9],
        $dataSegmento[15],
        $dataSegmento[3],
        $dataSegmento[11],
        $dataSegmento[12],
        $dataSegmento[13],
        $dataSegmento[14]
    );

    \core\model_mails::EnviarMensaje(
        $dataSegmento[5],
        $dataSegmento[9],
        $AsuntoMail,
        $Plantilla,
        NULL,
        NULL
    );

    try{

        //Enviar correo


        //Registrar Seguimiento
        $Tickets->seguimiento_ticket($AnioTicket,$NoTicket,$NoDepartamento,2,'Envío de correo: '.$Asunto,$_SESSION['data_login']['NoUsuario']);

        echo "<script>$('#modalbtnclose').click()</script>";

    }catch (Exception $e){

        echo $e->getMessage();
    }

}else{
    //Mostrar error de Correo Incorrecto
    echo "<img src='site_design/img/icons/error.png' width='19' /> La direcion de correo de ".$dataSegmento[9]." : </strong>" .$dataSegmento[5] ."<strong>, No Existe o No es Valida No es valida";
}