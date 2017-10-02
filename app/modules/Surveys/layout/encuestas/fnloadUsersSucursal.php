<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 20/10/15
 * Time: 06:14 PM
 */
include "../../../../controller/genContenido.class.php";

$connect = new \controller\Contenido();
//Validar que el usuario este logueado
if(!$connect->ValidaAcceso()){$connect->returnHomePage();}

//validar tiempo de actividad
$connect->ValidaSession_id();

$ListUsers = $connect->Consulta("SELECT NoUsuario,NombreDePila FROM BGECatalogoUsuarios WHERE NoDepartamento = ".$_POST['nosuc']." ORDER BY NombreDePila ASC");
?>
<select id="filUser" class="formInput">
    <option value="0">Todos</option>
    <?php
    if($_POST['nosuc'] != 0){
        while($row = mysqli_fetch_array($ListUsers)){
            echo "<option value='".$row[0]."'>".$row[1]."</option>";
        }
    }
    ?>
</select>
