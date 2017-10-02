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


    if(array_key_exists('contacto',$_POST)){

        $_POST = $connect->get_sanatiza($_POST);

        $Contacto = $_POST['contacto'];
        $NoUsuario = $_SESSION['data_login']['NoUsuario'];


        switch ($_POST['opc']){

            case 1:
                $connect->_query = "
                SELECT * FROM (SELECT a.NoUsuario,a.NombreDePila,c.Descripcion as NombreDepartamento,b.idphoto as Imagen,a.NoEstado 
                FROM SINTEGRALGNL.BGECatalogoUsuarios as a 
                JOIN SINTEGRALGNL.BGEEmpleados as b 
                ON a.idEmpleado = b.idEmpleado 
                JOIN SINTEGRALPRD.BGECatalogoDepartamentos as c 
                ON a.NoDepartamento = c.NoDepartamento 
                WHERE (a.NoEstado = 1 AND a.NoUsuario <> $NoUsuario AND a.NombreDePila like '%$Contacto%') or c.Descripcion LIKE '%$Contacto%'  ORDER BY a.NombreDePila ASC )as Tabla WHERE Tabla.NoEstado = 1 
                ";
                break;
            case 2: // Mensajes Sin Leer
                $connect->_query = "
                SELECT a.NoUsuarioEnvia as NoUsuario,b.NombreDePila, d.Descripcion as NombreDepartamento,c.idphoto as Imagen, b.NoEstado 
                FROM 
                    SINTEGRALPRD.BGENotificacionMensaje as a
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                ON a.NoUsuarioEnvia = b.NoUsuario 
                JOIN SINTEGRALGNL.BGEEmpleados as c 
                        ON b.idEmpleado = c.idEmpleado 
                        JOIN SINTEGRALPRD.BGECatalogoDepartamentos as d 
                        ON b.NoDepartamento = d.NoDepartamento 
                WHERE a.NoEstatus = 1 AND a.NoUsuarioRecibe = $NoUsuario GROUP BY NoUsuarioRecibe;
                ";
                break;
            case 3: //Mensajes Recientes
                $connect->_query = "
                SELECT a.NoUsuarioRecive as NoUsuario,b.NombreDePila,c.Descripcion as NombreDepartamento,d.idphoto as Imagen,b.NoEstado 
				FROM SINTEGRALPRD.BGEMensajes as a 
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b
                ON a.NoUsuarioRecive = b.NoUsuario 
                JOIN SINTEGRALPRD.BGECatalogoDepartamentos as c 
                ON b.NoDepartamento = c.NoDepartamento 
                JOIN SINTEGRALGNL.BGEEmpleados as d 
                ON b.idEmpleado = d.idEmpleado 
                WHERE a.NoUsuarioEnvia = $NoUsuario GROUP BY a.NoUsuarioRecive DESC LIMIT 0,6";
                break;


        }

        $connect->get_result_query(true);

        if(count($connect->_rows)>0){


            echo json_encode(array("result"=>true,"message"=>"Todo correcto","data"=>$connect->_rows,"MyImg"=>$_SESSION['data_login']['imagen_profile']));


        }else{
            echo json_encode(array("result"=>false,"message"=>"No se encontro el contacto"));

        }

    }else{
        echo json_encode(array("result"=>false,"message"=>"No se encontro el contacto"));
    }


}else{
    echo json_encode(array("result"=>false,"message"=>"Metodo no soportado"));
}

