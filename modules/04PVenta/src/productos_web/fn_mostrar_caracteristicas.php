<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 03/05/2017
 * Time: 09:07 AM
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
include "../../../../core/sqlconnect.php";

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
$SqlConnect = new \core\sqlconnect();


$idCategoria = $_POST['idcategoria'];
$idCodigo = $_POST['idcodigo'];
$idSerie = $_POST['idserie'];
$idCaracteristica = $_POST['idcaracteristica'];
$valor_caracteristica = $_POST['valor_caracteristica'];

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


switch ($_POST['opc']){
    case 4:
        //Editar la caracteristica
        $SqlConnect->_sqlQuery =
            "
            UPDATE SAyT.dbo.INVProdCarDet 
            SET ValorCaracteristica = '$valor_caracteristica' 
            WHERE idSerie = '$idSerie' AND idCodigo = '$idCodigo' AND idCaracteristica = '$idCaracteristica'
            ";
        $SqlConnect->execute_query();
        header('Content-type: application/json; charset=utf-8');
        echo json_encode(array("confirm"=>"ok","mensaje"=>"Cambios realizados correctamente"));
        break;
    case 5:
        $SqlConnect->_sqlQuery = "
                         SELECT 
                            a.idCaracteristica,
                            b.Descripcion,
                            a.ValorCaracteristica 
                        FROM 
                            SAyT.dbo.INVProdCarDet as a 
                        LEFT JOIN SAyT.dbo.INVProdCaracteristica as b 
                            ON a.idCaracteristica = b.idCaracteristica  
                        WHERE idSerie = '$_POST[idserie]' ";

        $SqlConnect->get_result_query();
        $lista_car = $SqlConnect->_sqlRows;

        for($i=0;$i < count($lista_car);$i++){
            $idCaracteristica = $lista_car[$i][0];
            echo "<tr>
             <td>".$lista_car[$i][1]."</td>
             <td>".$lista_car[$i][2]."</td>
             <td>
                <span class='btn btn-link btn-xs' onclick='fn_registrar_caracteristica(7,\"".$idCategoria."\",\"".$idCodigo."\",\"".$idSerie."\",\"".$idCaracteristica."\")' ><i class='fa fa-edit text-primary'></i></span>
                <span class='btn btn-link btn-xs' onclick='fn_registrar_caracteristica(6,\"".$idCategoria."\",\"".$idCodigo."\",\"".$idSerie."\",\"".$idCaracteristica."\")' ><i class='fa fa-trash text-danger'></i></span>
             </td>
    </tr>";
        }
        break;
    case 6:
        header('Content-type: application/json; charset=utf-8');
        $idCaracteristica = $_POST['idcaracteristica'];
        $SqlConnect->_sqlQuery =
            "
            DELETE FROM SAyT.dbo.INVProdCarDet WHERE idSerie = '$idSerie' AND idCodigo = '$idCodigo' AND idCaracteristica = '$idCaracteristica' 
            ";
        $SqlConnect->execute_query();

        echo json_encode(array("confirm"=>"ok","mensaje"=>$SqlConnect->_sqlQuery));

        break;
    case 7:
        $SqlConnect->_sqlQuery = "
                         SELECT 
                            a.idCaracteristica,
                            b.Descripcion,
                            a.ValorCaracteristica 
                        FROM 
                            SAyT.dbo.INVProdCarDet as a 
                        LEFT JOIN SAyT.dbo.INVProdCaracteristica as b 
                            ON a.idCaracteristica = b.idCaracteristica  
                        WHERE a.idSerie = '$_POST[idserie]' AND  a.idCaracteristica = '$_POST[idcaracteristica]' ";
        $SqlConnect->get_result_query();
        $valor_caracteristica = $SqlConnect->_sqlRows[0][2];

        header('Content-type: application/json; charset=utf-8');
        echo json_encode(array("confirm"=>"ok","mensaje"=>"ok","descripcion"=>$valor_caracteristica));
        break;
    case 9:

        //Mostar caractetisticas a Nivel Codigo Solo Lectura
        $SqlConnect->_sqlQuery = "
                         SELECT 
                            a.idCaracteristica,
                            b.Descripcion,
                            a.ValorCaracteristica 
                        FROM 
                            SAyT.dbo.INVProdCarDet as a 
                        LEFT JOIN SAyT.dbo.INVProdCaracteristica as b 
                            ON a.idCaracteristica = b.idCaracteristica  
                        WHERE idSerie = '' AND idCodigo = '$idCodigo' ";

        $SqlConnect->get_result_query();
        $lista_car = $SqlConnect->_sqlRows;

        $listid= 0;
        for($i=0;$i < count($lista_car);$i++){
            $idCaracteristica = $lista_car[$i][0];
            $listid++;
            echo "<tr>
             <td>$listid</td>
             <td>".$lista_car[$i][1]."</td>
             <td>".$lista_car[$i][2]."</td>
    </tr>";
        }
        break;
    case 10:

        $idSerie= " ";

        //Mostar caractetisticas a Nivel Codigo  Lectura y escritura
        $SqlConnect->_sqlQuery = "
                         SELECT 
                            a.idCaracteristica,
                            b.Descripcion,
                            a.ValorCaracteristica 
                        FROM 
                            SAyT.dbo.INVProdCarDet as a 
                        LEFT JOIN SAyT.dbo.INVProdCaracteristica as b 
                            ON a.idCaracteristica = b.idCaracteristica  
                        WHERE idSerie = '' AND idCodigo = '$idCodigo' ";

        $SqlConnect->get_result_query();
        $lista_car = $SqlConnect->_sqlRows;

        for($i=0;$i < count($lista_car);$i++){
            $idCaracteristica = $lista_car[$i][0];
            echo "<tr>
             <td>".$lista_car[$i][1]."</td>
             <td>".$lista_car[$i][2]."</td>
             <td>
                <span class='btn btn-link btn-xs' onclick='fn_registrar_caracteristica(7,\"".$idCategoria."\",\"".$idCodigo."\",\"".$idSerie."\",\"".$idCaracteristica."\")' ><i class='fa fa-edit text-primary'></i></span>
                <span class='btn btn-link btn-xs' onclick='fn_registrar_caracteristica(13,\"".$idCategoria."\",\"".$idCodigo."\",\"".$idSerie."\",\"".$idCaracteristica."\")' ><i class='fa fa-trash text-danger'></i></span>
             </td>
    </tr>";
        }
        break;
    case 11:
        //Editar la caracteristica
        $SqlConnect->_sqlQuery =
            "
            UPDATE SAyT.dbo.INVProdCarDet 
            SET ValorCaracteristica = '$valor_caracteristica' 
            WHERE idSerie = ' ' AND idCodigo = '$idCodigo' AND idCaracteristica = '$idCaracteristica'
            ";
        $SqlConnect->execute_query();
        header('Content-type: application/json; charset=utf-8');
        echo json_encode(array("confirm"=>"ok","mensaje"=>"Cambios realizados correctamente"));

        break;
    default:
        header('Content-type: application/json; charset=utf-8');
        echo json_encode(array("confirm"=>"false","mensaje"=>"Error opcion no encontrada"));
        break;
}