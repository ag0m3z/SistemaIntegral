<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 08/02/2017
 * Time: 11:37 AM
 */
/**
 * Incluir las Librerias Principales del Sistema
 * En el Siguiente Orden
 * ruta de libreias: @@/SistemaIntegral/core/
 *
 * 1.- core.php;
 * 2.- sesiones.php
 * 3.- seguridad.php
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);

// validar sesion iniciada del usuario
$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);

// solo los Usuarios con permisos administradores pueden modificar los datos de la sucursal
$readOnly = ($_SESSION['data_login']['NoPerfil'] == 1 && $_SESSION['data_login']['NoPerfil'] == 2) ? "readonly" : " ";

$seguridad->_query = "SELECT a.NoDepartamento,a.NoSucursal,a.Descripcion,CONCAT_WS(' ',b.Nombre,b.ApPaterno,b.ApMaterno) as NombreEmpleado,a.Correo,a.Telefono01,a.Telefono02,a.Telefono03,a.Telefono04,a.Domicilio,ifnull(b.idEmpleado,0)as idEmpleado
FROM BGECatalogoDepartamentos as a LEFT JOIN SINTEGRALGNL.BGEEmpleados as b
ON a.Encargado = b.idEmpleado WHERE a.NoDepartamento = '".$_POST['nosuc']."'";

$seguridad->get_result_query();

$data_departamento = $seguridad->_rows;

$seguridad->_query =
    "SELECT idEmpleado,CONCAT_WS(' ',Nombre,ApPaterno,ApMaterno) as NombreEmpleado
      FROM SINTEGRALGNL.BGEEmpleados 
    WHERE 
      NoDepartamento = '".$_POST['nosuc']."' AND 
      idEmpleado != ".$data_departamento[0]['idEmpleado'] ." AND 
      NoEstado = 1  ORDER By Nombre ASC ";

$seguridad->get_result_query();

$data_empleados = $seguridad->_rows;

?>

<div class="panel-body">
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span>
            <input type="text" style="font-size: 15px;" class="form-control" id="e-nodepartamento" value="<?=$data_departamento[0]['NoDepartamento']?>" placeholder="No Departamento" disabled />
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-home"></span></span>
            <input type="text"  id="e-nombresucursal" <?=$readOnly?>  class="form-control" value="<?=$data_departamento[0]['Descripcion']?>"  placeholder="Nombre Departamento" />
</div>
</div>
<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
        <select id="e-encargada" class="form-control" style="font-size: 15px">
            <?php
            echo "<option value='".$data_departamento[0]['idEmpleado']."'>".$data_departamento[0]['NombreEmpleado']."</option>";
            for($i=0;$i < count($data_empleados); $i++){
                echo "<option value='".$data_empleados[$i]['idEmpleado']."'>".$data_empleados[$i]['NombreEmpleado']."</option>";
            }
            ?>
        </select>
    </div>
</div>

<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
        <input type="text" style="font-size: 15px;"  id="e-correo"  class="form-control" value="<?=$data_departamento[0]['Correo']?>"placeholder="Correo" />
    </div>
</div>

<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></span>
        <input type="text" style="font-size: 15px;"  id="e-tel1" class="form-control"  value="<?=$data_departamento[0]['Telefono01']?>" placeholder="Telefono 1" />
    </div>
</div>

<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></span>
        <input type="text" style="font-size: 15px;"  id="e-tel2" class="form-control"  value="<?=$data_departamento[0]['Telefono02']?>" placeholder="Telefono 2" />
    </div>
</div>

<div class="form-group" >
    <div class="input-group">
        <span class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></span>
        <input type="text" style="font-size: 15px;"  id="e-celular" class="form-control"  value="<?=$data_departamento[0]['Telefono03']?>"  placeholder="Celular" />
    </div>
</div>

<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><span class="glyphicon glyphicon-book"></span></span>
        <input type="text" style="font-size: 15px;" id="e-direccion" class="form-control"  value="<?=$data_departamento[0]['Domicilio']?>" placeholder="Direcci&oacute;n" />
    </div>
</div>
</div>
<div id="resultsave"></div>