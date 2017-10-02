<?php
/**
 * Created by PhpStorm.
 * User: kronus
 * Date: 04/04/2016
 * Time: 01:41 AM
 */

include "../../../controller/Encuestas.class.php";

$connect = new \controller\Encuestas();

$NoEncuesta = $_POST['no'];

if(!empty($NoEncuesta)){
    $connect->ActivarEncuesta($NoEncuesta);
}
?>