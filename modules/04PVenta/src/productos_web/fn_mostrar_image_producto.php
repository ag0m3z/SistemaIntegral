<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 12/05/2017
 * Time: 12:24 PM
 */


include "../../../../core/sqlconnect.php";


$SqlServer = new \core\sqlconnect();

/*$_REQUEST['tpo'] = '1';
$_REQUEST['id'] = '1';*/


//Nivel Codigo o Serie
$Nivel = $_REQUEST['tpo'];
$idImagen = $_REQUEST['id'];

$SqlServer->_sqlQuery = "SELECT Imagen,TipoImagen FROM SAyT.dbo.INVProdImagen WHERE idImagen = '$idImagen' ";
$SqlServer->get_result_query();

$Imagen = $SqlServer->_sqlRows[0][0];
$Mime = $SqlServer->_sqlRows[0][1];

header("Content-type: $Mime");
echo $Imagen;
