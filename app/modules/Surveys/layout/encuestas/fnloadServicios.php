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
        echo '<select class="formInput" onchange="loadCalidadMetal(this.value)" id="CategoriaServicio"><option value="0">-- --</option>';
        while($result = mysqli_fetch_array($Consulta)){

            echo "<option value='".$result[0]."'>".$result[1]."</option>";

        }
        echo '</select>';
    }else{
        echo '<select class="formInput"  style="display: none;" id="CategoriaServicio">';
        echo "<option value='0'>-- --</option>";
        echo '</select>';
    }
}elseif($_POST['noservicio'] <> 0){
    $Consulta = $connect->Consulta("SELECT OpcCatalogo,Descripcion FROM BSISHELPDESK.BGECatalogoGeneral where CodCatalogo = 9 ORDER BY Descripcion ASC");
    if($connect->num_rows($Consulta)>0){
        echo '<select class="formInput" onchange="loadCalidadMetal(this.value)" id="CategoriaServicio"><option value="0">-- --</option>';
        while($result = mysqli_fetch_array($Consulta)){

            echo "<option value='".$result[0]."'>".$result[1]."</option>";

        }
        echo '</select>';
    }else{
        echo '<select class="formInput" style="display: none;" id="CategoriaServicio">';
        echo "<option value='0'>-- --</option>";
        echo '</select>';
    }
}
?>