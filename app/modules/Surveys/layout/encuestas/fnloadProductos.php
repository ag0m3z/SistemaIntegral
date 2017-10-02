<?php
include "../../../../controller/Aparatos.class.php";

$connect = new \controller\Aparatos();

//Validar que el usuario este logueado
if(!$connect->ValidaAcceso()){$connect->returnHomePage();}

//validar tiempo de actividad
$connect->ValidaSession_id();

$query = $connect->Consulta("SELECT CodigoProducto,Descripcion FROM BOPCatalogoProductos
                            WHERE Clasificacion02 = ".$_POST['tpoart']." AND Clasificacion03 = ".$_POST['marca']." ORDER BY Descripcion ASC");
?>

<select id="nombreprod" class="formInput">
    <option value="0">-- --</option>
    <?php
    while($result = mysqli_fetch_array($query)){
        echo "<option value='".$result[0]."'>".utf8_encode($result[1])."</option>";
    }
    ?>
</select>