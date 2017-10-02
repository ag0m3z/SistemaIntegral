<?php
include "../../../../controller/genContenido.class.php";

$connect = new \controller\Contenido();
//Validar que el usuario este logueado
if(!$connect->ValidaAcceso()){$connect->returnHomePage();}

//validar tiempo de actividad
$connect->ValidaSession_id();

?>

<?php
if($_POST['noservicio']== 3){
    $Consulta = $connect->Consulta("SELECT OpcCatalogo,Descripcion FROM BSISHELPDESK.BGECatalogoGeneral where CodCatalogo = 20 AND Texto1 = '".$_POST['noservicio']."' ORDER BY Descripcion ASC");
    if($connect->num_rows($Consulta)>0){
        echo "<span>Tipo Servicio:</span>";

    }else{
        echo "<span style='display: none'>Tipo Servicios: </span>";
    }
}elseif($_POST['noservicio'] <> 0){
    $Consulta = $connect->Consulta("SELECT OpcCatalogo,Descripcion FROM BSISHELPDESK.BGECatalogoGeneral where CodCatalogo = 9 ORDER BY Descripcion ASC ");
    if($connect->num_rows($Consulta)>0){
        echo "<span>No Categoria:</span>";

    }else{
        echo "<span style='display: none'>No Categoria: </span>";
    }
}

?>