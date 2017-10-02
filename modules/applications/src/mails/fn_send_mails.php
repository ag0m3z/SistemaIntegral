<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 09/02/2017
 * Time: 02:49 PM
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
include "../../../../core/model_mails.php";
include "../../../../core/seguridad.php";

if( $_SESSION['data_login']['BDDatos'] != 'SINTEGRALQAS')
{
    /**
     * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
     * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
     * Ejemplo:
     * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
     * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
     */
    $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);

    $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);



## Proceso para enviar correo de Alta de Ticket
    $opcion = $_POST['opcion'];
    $HoraActual = date("H:i:s");
    $NoTicket = $_POST['fl'];
    $AnioActual = $_POST['anio'];
    $NoDepartamento = $_POST['dpto'];
    $Folio =  $seguridad->getFormatFolio($NoTicket,4);



    switch ($opcion){

        case 1:
            //Enviar correo al registrar los tickets

            $seguridad->_query = "
                    SELECT 
                        a.Fecha as FechaAlta,
                        a.Reporte as reporte,
                        a.DescripcionReporte as descripcion_reporte,
                        e.NombreDePila as UsuarioRecibe,
                        f.NombreDePila as UsuarioAsignado,
                        c.Descripcion as NombreSucursal,
                        c.Correo as CorreoSucursal,
                        concat_ws(' ',d.Nombre,d.ApPaterno,d.ApMaterno) as NombreSolicitante,
                        d.Correo as CorreoSolicitante,
                        b.Descripcion as NombreDepartamento,
                        b.Correo as CorreoDepartamento,
                        concat_ws(' ',g.Nombre,g.ApPaterno,g.ApMaterno) as NombreEncargado,
                        g.Correo as CorreoEncargadoDepartamento,
                        b.NoTipo
                    FROM BSHReportes as a 
                        LEFT JOIN BGECatalogoDepartamentos as b ON a.NoDepartamento = b.NoDepartamento
                        LEFT JOIN BGECatalogoDepartamentos as c ON a.NoSucursal = c.NoDepartamento 
                        LEFT JOIN SINTEGRALGNL.BGEEmpleados as d ON a.idEmpleado = d.idEmpleado
                        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as e ON a.NoUsuarioRecibe = e.NoUsuario 
                        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as f ON a.NoUsuarioAsignado = f.NoUsuario 
                        LEFT JOIN SINTEGRALGNL.BGEEmpleados as g ON b.Encargado = g.idEmpleado
                    WHERE 
                        a.Folio = '$NoTicket' AND 
                        a.Anio = '$AnioActual' AND 
                        a.NoDepartamento = '$NoDepartamento'
            ";

            $seguridad->get_result_query();

            $data_mails = $seguridad->_rows;

            //## 1.- Enviar correo al Gerente del departamento (sistemas,Mantenimiento)
            //## 2.- Enviar correo al Solicitante;
            $data_mensaje = array(
                "Se registro el ticket: $Folio",
                $Folio
            );

            $FechaTicket = $seguridad->getFormatFecha($data_mails[0]['FechaAlta'],2);
            $NombreDepartamento = $data_mails[0]['NombreDepartamento'];


            $mensaje = \core\model_mails::set_plantilla(
                $data_mensaje,
                $data_mails[0]['NombreSolicitante'],
                $data_mails[0]['UsuarioAsignado'],
                $data_mails[0]['UsuarioRecibe'],
                $data_mails[0]['NombreSucursal'],
                $data_mails[0]['descripcion_reporte'],
                $data_mails[0]['reporte'],
                $FechaTicket,
                $HoraActual
            );

            $TituloAsuto = "Departamento de ".$NombreDepartamento." - Registro de Ticket: ".$Folio." ";

            \core\model_mails::EnviarMensaje(
                $data_mails[0]['CorreoSolicitante'],
                $data_mails[0]['NombreSolicitante'],
                $TituloAsuto,
                $mensaje,
                $data_mails[0]['CorreoEncargadoDepartamento'],
                $data_mails[0]['NombreEncargado']
            );

            //## < END >fin del proceso para enviar correo

            break;
        case 2:

            //Enviar correo al cerrar Tickets

            $seguridad->_query =
                "
             SELECT 
                        a.Fecha as FechaAlta,
                        a.HoraInicioReporte as HoraReporte,
                        a.Reporte as reporte,
                        a.DescripcionReporte as descripcion_reporte,
                        e.NombreDePila as UsuarioCierre,
                        c.Descripcion as NombreSucursal,
                        c.Correo as CorreoSucursal,
                        concat_ws(' ',d.Nombre,d.ApPaterno,d.ApMaterno) as NombreSolicitante,
                        d.Correo as CorreoSolicitante,
                        b.Descripcion as NombreDepartamento,
                        b.Correo as CorreoDepartamento,
                        concat_ws(' ',g.Nombre,g.ApPaterno,g.ApMaterno) as NombreEncargado,
                        g.Correo as CorreoEncargadoDepartamento,
                        b.NoTipo,
                        a.FechaCierre,
                        a.HoraCierre,
                        a.SolucionCierre
                    FROM BSHReportes as a 
                        LEFT JOIN BGECatalogoDepartamentos as b ON a.NoDepartamento = b.NoDepartamento
                        LEFT JOIN BGECatalogoDepartamentos as c ON a.NoSucursal = c.NoDepartamento 
                        LEFT JOIN SINTEGRALGNL.BGEEmpleados as d ON a.idEmpleado = d.idEmpleado
                        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as e ON a.NoUsuarioCierre = e.NoUsuario
                        LEFT JOIN SINTEGRALGNL.BGEEmpleados as g ON b.Encargado = g.idEmpleado 
                    WHERE 
                        a.Folio = '$NoTicket' AND 
                        a.Anio = '$AnioActual' AND 
                        a.NoDepartamento = '$NoDepartamento'
            ";
            $seguridad->get_result_query();

            $data_mails = $seguridad->_rows[0];

            //Parametros para la Liga de Encuesta
                $dataEcnript = base64_encode($AnioActual)."_".base64_encode($NoTicket)."_".base64_encode($NoDepartamento);
                $Parametros = "?enus=".md5("ok")."&dpto=".base64_encode($NoDepartamento)."&opt=".md5('agomez.barron@gmail.com')."&ref=".base64_encode($dataEcnript);

            //liga para realizar la encuestas
            $ligaEncuesta = "http://192.168.2.55:4514/SistemaIntegral/modules/Surveys/".$Parametros;

            //Nombre del Departamento
            $NombreDepartamento = $data_mails['NombreDepartamento'];
            $FechaRegistro = $seguridad->getFormatFecha($data_mails['FechaAlta'],2);
            $HoraRegistro = $data_mails['HoraReporte'];

            $Solicitante = $data_mails['NombreSolicitante'];
            $DescripcionTicket = $data_mails['descripcion_reporte'];
            $FechaCierre = $seguridad->getFormatFecha($data_mails['FechaCierre'],2);
            $HoraCierre = $data_mails['HoraCierre'];

            $AgenteCierre = $data_mails['UsuarioCierre'];
            $Solucion = $data_mails['SolucionCierre'];

            $DeptoSolicita = $data_mails['NombreSucursal'];


            $mensaje = \core\model_mails::plantilla_cierre(
                $NoTicket,
                $NoDepartamento,
                $NombreDepartamento,
                $FechaRegistro,
                $HoraRegistro,
                $Solicitante,
                $DescripcionTicket,
                $FechaCierre,
                $HoraCierre,
                $AgenteCierre,
                $Solucion,
                $ligaEncuesta,
                $DeptoSolicita
            );

            $TituloAsuto = "Departamento de ".$NombreDepartamento." - Cierre de Ticket: ".$Folio." ";


            \core\model_mails::EnviarMensaje(
                $data_mails['CorreoSolicitante'],
                $data_mails['NombreSolicitante'],
                $TituloAsuto,
                $mensaje,
                $data_mails['CorreoEncargadoDepartamento'],
                $data_mails['NombreEncargado']
            );

            break;
        case 3:
            //Enviar correo en el seguimiento

            $seguridad->_query =
                "
             SELECT 
                        a.Fecha as FechaAlta,
                        a.HoraInicioReporte as HoraReporte,
                        a.Reporte as reporte,
                        a.DescripcionReporte as descripcion_reporte,
                        e.NombreDePila as UsuarioSeguimiento,
                        c.Descripcion as NombreSucursal,
                        c.Correo as CorreoSucursal,
                        concat_ws(' ',d.Nombre,d.ApPaterno,d.ApMaterno) as NombreSolicitante,
                        d.Correo as CorreoSolicitante,
                        b.Descripcion as NombreDepartamento,
                        b.Correo as CorreoDepartamento,
                        concat_ws(' ',g.Nombre,g.ApPaterno,g.ApMaterno) as NombreEncargado,
                        g.Correo as CorreoEncargadoDepartamento,
                        b.NoTipo,
                        h.Seguimiento,
                        h.FolioSeguimiento,
                        h.FechaSeguimiento,
                        h.HoraInicioSeguimiento,
                        h.NoUsuarioSeguimiento
                    FROM BSHReportes as a 
                        LEFT JOIN BGECatalogoDepartamentos as b ON a.NoDepartamento = b.NoDepartamento
                        LEFT JOIN BGECatalogoDepartamentos as c ON a.NoSucursal = c.NoDepartamento 
                        LEFT JOIN SINTEGRALGNL.BGEEmpleados as d ON a.idEmpleado = d.idEmpleado
                        LEFT JOIN SINTEGRALGNL.BGEEmpleados as g ON b.Encargado = g.idEmpleado 
                        LEFT JOIN BSHSeguimientoReportes as h ON a.Anio = h.Anio AND a.Folio = h.Folio AND a.NoDepartamento = h.NoDepartamento 
                        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as e ON h.NoUsuarioSeguimiento = e.NoUsuario
                    WHERE 
                        a.Folio = '$NoTicket' AND 
                        a.Anio = '$AnioActual' AND 
                        a.NoDepartamento = '$NoDepartamento' 
                        ORDER BY h.FolioSeguimiento DESC LIMIT 0,1
            ";
            $seguridad->get_result_query();

            $data_mails = $seguridad->_rows[0];

            //Nombre del Departamento
            $NombreDepartamento = $data_mails['NombreDepartamento'];
            $FechaRegistro = $seguridad->getFormatFecha($data_mails['FechaAlta'],2);
            $HoraRegistro = $data_mails['HoraReporte'];

            $Solicitante = $data_mails['NombreSolicitante'];
            $DescripcionTicket = $data_mails['descripcion_reporte'];

            $FechaSeguimiento = $seguridad->getFormatFecha($data_mails['FechaSeguimiento'],2);
            $HoraSeguimiento = $data_mails['HoraInicioSeguimiento'];
            $AgenteSeguimiento = $data_mails['UsuarioSeguimiento'];
            $Seguimiento = $data_mails['Seguimiento'];

            $DeptoSolicita = $data_mails['NombreSucursal'];


            $mensaje = \core\model_mails::plantilla_seguimiento(
                $NoTicket,
                $NoDepartamento,
                $NombreDepartamento,
                $FechaRegistro,
                $HoraRegistro,
                $Solicitante,
                $DescripcionTicket,
                $FechaSeguimiento,
                $HoraSeguimiento,
                $AgenteSeguimiento,
                $Seguimiento,
                $DeptoSolicita
            );

            $TituloAsuto = "Departamento de ".$NombreDepartamento." - Seguimiento de Ticket: ".$Folio." ";


            \core\model_mails::EnviarMensaje(
                $data_mails['CorreoSolicitante'],
                $data_mails['NombreSolicitante'],
                $TituloAsuto,
                $mensaje,
                $data_mails['CorreoEncargadoDepartamento'],
                $data_mails['NombreEncargado']
            );


            break;
        default:
            exit();
            break;
    }

}

