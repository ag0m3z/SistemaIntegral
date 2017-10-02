<?php
include "../../../../controller/genContenido.class.php";

$connect = new \controller\Contenido();
//Validar que el usuario este logueado
if(!$connect->ValidaAcceso()){$connect->returnHomePage();}

//validar tiempo de actividad
$connect->ValidaSession_id();

if($_POST['tposerv']<> 3){
    if($_POST['categoria'] == 1 || $_POST['categoria'] == 7 ){
        $Sql = $connect->Consulta("SELECT OpcCatalogo,Descripcion FROM BSISHELPDESK.BGECatalogoGeneral WHERE Numero1 = ".$_POST['categoria']." AND CodCatalogo = 21");
        echo "<select id='dcalidadMetal' class='formInput'><option value='0'>-- --</option>";
        while($result = mysqli_fetch_array($Sql)){
            echo "<option value='".$result[0]."'>".$result[1]."</option>";
        }
        echo "</select>";

    }
}