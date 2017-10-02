<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 29/09/2017
 * Time: 08:40 AM
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

$connect = new \core\seguridad($_SESSION['data_login']['BDDatos']);
$NoUsuario = $_SESSION['data_login']['NoUsuario'];

header("ContentType:application/json");

if($_SERVER['REQUEST_METHOD'] == "GET"){

    if($_GET['nameModal'] == $_GET['Nombre']){

        $FechaUltimoMensaje = $_GET['timestamp'];
        $NoUsuarioRecibe = $_GET['NoUsuarioRecibe'];
        $Mensaje = array();

        $connect->_query = "SELECT MAX(Fecha) FROM SINTEGRALPRD.BGEMensajes WHERE NoUsuarioEnvia = '$NoUsuarioRecibe' ";
        $connect->get_result_query();

        $FechaNuevaUltimoMensaje = $connect->_rows[0][0];

        if($FechaUltimoMensaje =="" || $FechaUltimoMensaje =="undefined"){
            echo json_encode(array("result"=>true,"message"=>"No hay ultima fecha","data"=>array("ultimo"=>$FechaUltimoMensaje,"nuevo"=>$FechaNuevaUltimoMensaje,"NoUsuarioRecibe"=>$NoUsuarioRecibe,"mensaje"=>$Mensaje)));
            $FechaUltimoMensaje = $FechaNuevaUltimoMensaje;
        }else{

            if($FechaNuevaUltimoMensaje > $FechaUltimoMensaje){

                $connect->_query = "UPDATE SINTEGRALPRD.BGENotificacionMensaje SET NoEstatus = 0 WHERE NoUsuarioEnvia = $NoUsuarioRecibe AND idNotificacion > 0";
                $connect->execute_query();


                $connect->_query = "
            SELECT 
              b.NombreDePila as UsuarioEnvia,
              e.idphoto as ImagenEnvia,
              a.Mensaje,
              DATE_FORMAT(date(a.Fecha),'%d/%m/%Y')as Fecha,
              DATE_FORMAT(time(a.Fecha),  '%h:%i%p')as Hora,
              a.Fecha
                      FROM 
             SINTEGRALPRD.BGEMensajes as a 
            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
            ON a.NoUsuarioEnvia = b.NoUsuario 
            LEFT JOIN SINTEGRALGNL.BGEEmpleados as e 
            ON b.idEmpleado = e.idEmpleado 
            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
            ON a.NoUsuarioRecive = c.NoUsuario 
            LEFT JOIN SINTEGRALPRD.BGECatalogoDepartamentos as d 
            ON c.NoDepartamento = d.NoDepartamento 
            WHERE a.NoUsuarioRecive = $NoUsuario AND a.Fecha = '$FechaNuevaUltimoMensaje' ORDER BY a.Fecha ASC
            ";
                $connect->get_result_query(true);
                $Mensaje = $connect->_rows;

                if(count($Mensaje)>0){
                    echo json_encode(array("result"=>true,"message"=>"nuevo mensaje encontrado","data"=>array("ultimo"=>$FechaUltimoMensaje,"nuevo"=>$FechaNuevaUltimoMensaje,"NoUsuarioRecibe"=>$NoUsuarioRecibe,"mensaje"=>$Mensaje)));

                }else{
                    echo json_encode(array("result"=>true,"message"=>"No hay nuevo mensaje","data"=>array("ultimo"=>$FechaUltimoMensaje,"nuevo"=>$FechaNuevaUltimoMensaje,"NoUsuarioRecibe"=>$NoUsuarioRecibe,"mensaje"=>$Mensaje)));
                }

            }else{
                echo json_encode(array("result"=>true,"message"=>"No hay nuevo mensaje","data"=>array("ultimo"=>$FechaUltimoMensaje,"nuevo"=>$FechaNuevaUltimoMensaje,"NoUsuarioRecibe"=>$NoUsuarioRecibe,"mensaje"=>$Mensaje)));
            }

        }




    }else{
        echo json_encode(array("result"=>false,"message"=>"Ventana del chat cerrada"));

    }




}else{
    echo json_encode(array("result"=>false,"message"=>"Metodo no soportado"));
}