<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 27/09/2017
 * Time: 03:18 PM
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/mensajeria.php";

$connect = new \core\mensajeria($_SESSION['data_lagin']['BDDatos']);
header("ContentType:application/json");


if($_SERVER['REQUEST_METHOD']=="POST"){


    if(array_key_exists('NoUsuario',$_POST)){

        $_POST = $connect->get_sanatiza($_POST);

        $idConversacion = date("YmdHis");

        $NoUsuario = $_SESSION['data_login']['NoUsuario'];
        $NoUsuarioRecive = $_POST['NoUsuario'];
        $Limit = "";


        if($_POST['opc'] == 1){
            $Limit = "LIMIT 0,1";
        }

        $connect->_query = "UPDATE SINTEGRALPRD.BGENotificacionMensaje SET NoEstatus = 0 WHERE NoUsuarioEnvia = $NoUsuarioRecive AND idNotificacion > 0";
        $connect->execute_query();

        $connect->_query = "
        SELECT 
          a.idMensaje,
          a.NoUsuarioEnvia,
          b.NombreDePila as UsuarioEnvia,
          e.idphoto as ImagenEnvia,
          a.NoUsuarioRecive,
          c.NombreDePila as UsuarioRecive,
          d.Descripcion as NombreDepartamento,
          a.Mensaje,
          DATE_FORMAT(date(a.Fecha),'%d/%m/%Y')as Fecha,
          DATE_FORMAT(time(a.Fecha),  '%h:%i%p')as Hora,
          a.RutaImagen,a.NombreImagen,a.NombreUnico,
          a.TipoMensaje
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
        WHERE a.NoUsuarioRecive = $NoUsuario AND a.NoUsuarioEnvia = $NoUsuarioRecive OR  a.NoUsuarioRecive = $NoUsuarioRecive AND a.NoUsuarioEnvia = $NoUsuario ORDER BY a.Fecha ASC
        ";

        $connect->get_result_query(true);

        if(count($connect->_rows)>0){



            echo json_encode(array("result"=>true,"message"=>"Todo correcto","data"=>$connect->_rows,"NoUsuario"=>$NoUsuario,"keyconversacion"=>$idConversacion));


        }else{
            echo json_encode(array("result"=>true,"message"=>"Todo correcto","data"=>$connect->_rows,"NoUsuario"=>$NoUsuario,"keyconversacion"=>$idConversacion));
        }

    }else{
        echo json_encode(array("result"=>false,"message"=>"No se encontro el contacto"));
    }


}else{
    echo json_encode(array("result"=>false,"message"=>"Metodo no soportado"));
}

