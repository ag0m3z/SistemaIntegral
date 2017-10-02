<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 09/02/2017
 * Time: 11:38 AM
 */

namespace core;

require_once('class.phpmailer.php');
include("class.smtp.php");


class model_mails
{
    static function EnviarMensaje($CorreoRemitente,$NombreRemitente,$Asunto,$Message,$CorreoBCC = null,$NombreBCC =NULL){

        $mail = new \PHPMailer();

        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Username = "mesadeayuda@prestamoexpress.com.mx";
        $mail->Password = "meay#123";

        $mail->From = "mesadeayuda@prestamoexpress.com.mx";
        $mail->FromName = "Sistema Integral";


        $mail->addAddress($CorreoRemitente,$NombreRemitente);

        $mail->addBCC($CorreoBCC,$NombreBCC);

        $mail->isHTML(true);

        $mail->Subject = $Asunto;

        $mail->Body =  utf8_decode($Message);

        if($mail->Send()){
            return true;
        }
    }

    static function plantilla_cierre($NoFolio,$NoDepartamento,$NombreDepartamento,$FechaRegistro,$HoraRegistro,$Solicitante,$DescripcionTicket,$FechaCierre,$HoraCierre,$AgenteCierre,$Solucion,$ligaEncuesta,$DeptoSolicita){
        if($NoDepartamento == '0109'){
            $Contacto = ", si quieres mayor informaci&oacute;n sobre el contenido de este mensaje, contactar a Sistemas en la Ext. 711 o al Correo: sistemas@prestamoexpress.com.mx.";
        }else{
            $Contacto = ".";
        }
        $message = '<html><head><META http-equiv="Content-Type" content="text/html; charset=utf-8" /><style type="text/css"><!--
                     body{font-family: Calibri, Helvetica, Arial;}
                     #titulo{font-family: "Times New Roman";color: #620012;}
                     .tableGeneral{width: 750px;}
                     #caja01{background: #CFE0F1;padding: 15px;font-size: 16px;height: 25px;color: #4a7197;}
                     #caja02{background: #59A041;padding: 15px;font-size: 16px;height: 25px;color: #fff;}
                     #caja03{background: #CFE0F1;color: #4a7197;padding: 15px;font-size: 16px;height: 25px;}
                     #rowstyle01{background: #e3edf7;padding: 3px;font-size: 15px;font-family: Calibri, Helvetica, Arial;}
                     #bold{font-weight: bold;}
                     --></style></head><body><div>
                     <table  class="tableGeneral">
                     <tr>
                        <td style="width: 100px;"><img src="http://192.168.2.55:4514/web/prd/SistemaIntegral/app/images/logos/img1.jpg" width="150"> </td>
                        <td id="titulo"><h3 style="margin-left: 160px;"> Mesa de Ayuda</h3></td></tr>
                     </table>
    <table class="tableGeneral" >
        <tr>
            <td id="caja01" colspan="2">
                <p>Estimado(a), <span id="bold">'.$Solicitante.'</span> <br><br>
                En relación a su solicitud de servicio, queremos informarle que su Ticket ha pasado a Estado de Resuelto.
                </p>
            </td>
        </tr>
        <tr style="height: 10px">
            <td></td>
        </tr>
        <tr>
            <td>
                <div style="font-size: 16px;" id="subtitulo">
                    Detalles
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="tableGeneral">
                    <tr id="rowstyle01">
                        <td id="bold" colspan="2" style="padding: 3px;">Ticket: '.$NoFolio.' </td>
                    </tr>
                    <tr>
                        <td style="padding: 3px;width: 300px;" width="300">Fecha: '.$FechaRegistro.' </td>
                        <td width="300" >Hora: '.$HoraRegistro.'</td>
                    </tr>
                    <tr>
                        <td colspan="2">Solicitante: '.$Solicitante.'</td>
                    </tr>
                    <tr>
                        <td colspan="2">Departamento: '.$DeptoSolicita.'</td>
                    </tr>
                    <tr>
                        <td colspan="2">Descripcion: '.$DescripcionTicket.'</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="tableGeneral">
                    <tr id="rowstyle01">
                        <td colspan="2" id="bold" style="padding: 3px;">Cierre </td>
                    </tr>
                    <tr>
                        <td style="padding: 3px;width: 300px;" width="300">Fecha: '.$FechaCierre.'</td>
                        <td width="300" >Hora: '.$HoraCierre.'</td>
                    </tr>
                    <tr>
                        <td colspan="2">Agente Cierre: '.$AgenteCierre.'</td>
                    </tr>
                    <tr>
                        <td colspan="2">Soluci&oacute;n: '.$Solucion.'</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>

            </td>
        </tr>
        <tr>
            <td id="caja02" colspan="2">
                <p id="bold">
                    Encuesta de Satisfacción
                </p>
                <p style="text-align: justify;">
                    Si por alg&uacute;n motivo el servicio no se solucion&oacute;, favor de comunicarse a Sistemas Ext.711 proporcionando el numero de ticket Para el seguimiento y re apertura del ticket
                   , Ay&uacute;danos a Mejorar Contestando la Siguiente <a href="'.$ligaEncuesta.'">Encuesta de Servicio</a>, Estamos mejorando para ofrecerte un mejor servicio.
        </p>
        </td></tr>
        <tr style="height: 15px"><td></td></tr>
        <tr><td id="caja03" colspan="2"><div><p style="text-align: justify;">
        <span id="bold">Este Correo es informativo</span>, Favor de no responder a esta direcci&oacute;n de correo<br> ya que no se encuentra habilitada para recibir mensajes'.$Contacto.'
        </p></div></td></tr></table></div></body></html>';

        return $message;
    }

    static function set_plantilla($Mensaje,$NombreSolicitante,$AgenteAsignado,$AgenteRegistra,$NombreDepartamento,$Descripcion,$Reporte,$FechaReporte,$HoraReporte){

        if(empty($Mensaje)){$Mensaje = "Favor de darle seguimiento al siguiente reporte.";}

        $message = '<html><head><META http-equiv="Content-Type" content="text/html; charset=utf-8" /><style type="text/css"><!--
                     body{font-family: Calibri, Helvetica, Arial;}
                     #titulo{font-family: "Times New Roman";color: #620012;}
                     .tableGeneral{width: 750px;}
                     #caja01{background: #CFE0F1;padding: 15px;font-size: 16px;height: 25px;color: #4a7197;}
                     #caja02{background: #59A041;padding: 15px;font-size: 16px;height: 25px;color: #fff;}
                     #caja03{background: #CFE0F1;padding: 15px;font-size: 16px;height: 25px;}
                     #rowstyle01{background: #e3edf7;padding: 3px;font-size: 15px;font-family: Calibri, Helvetica, Arial;}
                     #bold{font-weight: bold;}
                     --></style></head><body><div>
                     <table  class="tableGeneral">
                     <tr>
                        <td style="width: 100px;"><img src="http://192.168.2.55:4514/web/prd/SistemaIntegral/app/images/logos/img1.jpg" width="150"> </td>
                        <td id="titulo"><h3 style="margin-left: 160px;"> Mesa de Ayuda</h3></td></tr>
                     </table>
                    <table class="tableGeneral" >
                        <tr>
                            <td id="caja01" colspan="2">
                                '.$Mensaje[0].'
                                </p>
                            </td>
                        </tr><tr style="height: 10px"><td></td>
                        </tr><tr><td><div style="font-size: 15px;" id="titulo">Detalles</div></td></tr>
                        <tr>
                            <td colspan="2">
                            <table class="tableGeneral">
                                <tr id="rowstyle01">
                                    <td id="bold" style="padding: 3px;">
                                        Ticket: '.$Mensaje[1].'
                                    </td>
                                    <td>
                                        <span id="bold">Fecha: '.$FechaReporte.'&nbsp; Hora: '.$HoraReporte.'  </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Departamento: '.$NombreDepartamento.'
                                    </td>
                                    <td>
                                        Registro: '.$AgenteRegistra.'
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Solicita: '.$NombreSolicitante.'
                                    </td>
                                    <td>
                                        Agente: '.$AgenteAsignado.'
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Descripci&oacute;n: '.$Descripcion.'
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Reporte: '.$Reporte.'
                                    </td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                        <tr><td></td></tr><tr><td id="caja02" colspan="2"><div><p>Este Correo es informativo, Favor de no responder a esta direcci&oacute;n de correo<br> ya que no se encuentra habilitada para recibir mensajes, si quieres mayor informaci&oacute;n sobre el contenido de este mensaje, contactar a Sistemas en la Ext. 711 o al Correo: sistemas@prestamoexpress.com.mx.</p></div></td></tr>
                    </table>
                </div>
                </body>
                </html>';
        return $message;
    }

    static function plantilla_seguimiento($NoFolio,$NoDepartamento,$NombreDepartamento,$FechaRegistro,$HoraRegistro,$Solicitante,$DescripcionTicket,$FechaSeguimiento,$HoraSeguimiento,$AgenteSeguimiento,$Seguimiento,$DeptoSolicita){
        if($NoDepartamento == '0109'){
            $Contacto = ", si quieres mayor informaci&oacute;n sobre el contenido de este mensaje, contactar a Sistemas en la Ext. 711 o al Correo: sistemas@prestamoexpress.com.mx.";
        }else{
            $Contacto = ".";
        }
        $message = '<html><head><META http-equiv="Content-Type" content="text/html; charset=utf-8" /><style type="text/css"><!--
                     body{font-family: Calibri, Helvetica, Arial;}
                     #titulo{font-family: "Times New Roman";color: #620012;}
                     .tableGeneral{width: 750px;}
                     #caja01{background: #CFE0F1;padding: 15px;font-size: 16px;height: 25px;color: #4a7197;}
                     #caja02{background: #59A041;padding: 15px;font-size: 16px;height: 25px;color: #fff;}
                     #caja03{background: #CFE0F1;color: #4a7197;padding: 15px;font-size: 16px;height: 25px;}
                     #rowstyle01{background: #e3edf7;padding: 3px;font-size: 15px;font-family: Calibri, Helvetica, Arial;}
                     #bold{font-weight: bold;}
                     --></style></head><body><div>
                     <table  class="tableGeneral">
                     <tr>
                        <td style="width: 100px;"><img src="http://192.168.2.55:4514/web/prd/SistemaIntegral/app/images/logos/img1.jpg" width="150"> </td>
                        <td id="titulo"><h3 style="margin-left: 160px;"> Mesa de Ayuda</h3></td></tr>
                     </table>
    <table class="tableGeneral" >
        <tr>
            <td id="caja01" colspan="2">
                <p>Estimado(a), <span id="bold">'.$Solicitante.'</span> <br><br>
                En relación a su solicitud de servicio, queremos informarle que está siendo atendido.
                </p>
            </td>
        </tr>
        <tr style="height: 10px">
            <td></td>
        </tr>
        <tr>
            <td>
                <div style="font-size: 16px;" id="subtitulo">
                    Detalles
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="tableGeneral">
                    <tr id="rowstyle01">
                        <td  colspan="2" id="bold" style="padding: 3px;">Ticket: '.$NoFolio.' </td>
                    </tr>
                    <tr>
                        <td style="padding: 3px;width: 300px;" width="300" >Fecha: '.$FechaRegistro.' </td>
                        <td width="300">Hora: '.$HoraRegistro.'</td>
                    </tr>
                    <tr>
                        <td colspan="2">Solicitante: '.$Solicitante.'</td>
                    </tr>
                    <tr>
                        <td colspan="2">Departamento: '.$DeptoSolicita.'</td>
                    </tr>
                    <tr>
                        <td colspan="2">Descripcion: '.$DescripcionTicket.'</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="tableGeneral">
                    <tr id="rowstyle01">
                        <td colspan="2" id="bold" style="padding: 3px;" >Seguimiento </td>
                    </tr>
                    <tr>
                        <td style="padding: 3px;width: 300px;" width="300">Fecha: '.$FechaSeguimiento.'</td>
                        <td width="300">Hora: '.$HoraSeguimiento.'</td>
                    </tr>
                    <tr>
                        <td colspan="2">Agente: '.$AgenteSeguimiento.'</td>
                    </tr>
                    <tr>
                        <td colspan="2">Seguimiento: '.$Seguimiento.'</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>

            </td>
        </tr>
        
        <tr style="height: 15px"><td></td></tr>
        <tr><td id="caja03" colspan="2"><div><p style="text-align: justify;">
        <span id="bold">Este Correo es informativo</span>, Favor de no responder a esta direcci&oacute;n de correo<br> ya que no se encuentra habilitada para recibir mensajes'.$Contacto.'
        </p></div></td></tr></table></div></body></html>';

        return $message;
    }

}
/*
echo model_mails::plantilla_cierre(1,1,1,$Fecha,1,'aladsldaslka adslakdlalsdlasd adds','adsasdaksdajkdsjdkjfkdsjkfdsfdsfdsajfakdsf jadsjadskajksdkadjadskjdskaskajdkasdjasdkjjakj',1,1,1,'ajsdjadsfkjdhfsdajfhdaskjfdshjfdashfhadksjfadshkjfa adshfkhdsjfkdsfjdsakfadksjjkfhdsafjkhdskjfjkdsahkfjdasfkhasdjfkadsfhadjshfajdksfhahjkfdhdakjsfhadskfadsjhj',1,1);*/