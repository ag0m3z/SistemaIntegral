<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 02/03/2017
 * Time: 09:27 AM
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
include "../../../../core/model_empleados.php";

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

$empleado = new \core\model_empleados($_SESSION['data_login']['BDDatos']);

$empleado->valida_session_id($_SESSION['data_login']['NoUsuario']);

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);


// Sanatizar Campos
$idEmpleado = $empleado->get_sanatiza($_POST['idEmpleado']);

$idEmpresa = $empleado->get_sanatiza($_POST['idEmpresa']);
$tpoEmpleado = $empleado->get_sanatiza($_POST['tpoEmpleado']) ;
$NoEmpleado = $empleado->get_sanatiza($_POST['noempleado']) ;
$NoDepartamento = $empleado->get_sanatiza($_POST['nodpto']) ;

$Nombre = $empleado->get_sanatiza($_POST['nombre']) ;
$ApPaterno = $empleado->get_sanatiza($_POST['appaterno']) ;
$ApMaterno = $empleado->get_sanatiza($_POST['apmaterno']) ;

$Correo = $empleado->get_sanatiza($_POST['correo']) ;
$Direccion = $empleado->get_sanatiza($_POST['direccion']) ;
$Tele01 = $empleado->get_sanatiza($_POST['tel01']) ;
$Tele02 = $empleado->get_sanatiza($_POST['tel02']) ;
$Tele03 = $empleado->get_sanatiza($_POST['tel03']) ;
$Telp01 = $empleado->get_sanatiza($_POST['tel04']) ;
$Telp02 = $empleado->get_sanatiza($_POST['tel05']);
$idPhoto = '';
$Estatus = $_POST['estatus'];
$NoPuesto = $_POST['idpuesto'];

if(trim($Nombre) == ""){
    echo "<script>MyAlert('El nombre no debe estar vacio','alerta')</script>";
}elseif(trim($ApPaterno) == ""){
    echo "<script>MyAlert('EL apellido paterno esta vacio','alerta')</script>";
}else{

    if(trim($Correo)== ""){
        $Correo = "none@no-reply.com";
    }


    if(!filter_var($Correo,FILTER_VALIDATE_EMAIL)){

        echo "<script>MyAlert('Correo Invalido','error')</script>";

    }else{

        $empleado->editar_empleado(
            array(
                'idEmpleado'=>$idEmpleado,
                'idEmpresa'=>$idEmpresa,
                'TipoEmpleado'=>$tpoEmpleado,
                'NoDepartamento'=>$NoDepartamento,
                'NoEmpleado'=>$NoEmpleado,
                'NombreEmpleado'=>$Nombre,
                'aPaterno'=>$ApPaterno,
                'aMaterno'=>$ApMaterno,
                'Correo'=>$Correo,
                'Telefono01'=>$Tele01,
                'Telefono02'=>$Tele02,
                'Telefono03'=>$Tele03,
                'Direccion'=>$Direccion,
                'Telefono04'=>$Telp01,
                'Telefono05'=>$Telp02,
                'idFoto'=>$idPhoto,
                'NoEstatus'=>$Estatus,
                'NoPuesto'=>$NoPuesto
            )
        );

        if($empleado->_confirm){


            switch ($_POST['opcion2']){
                case 1:

                    echo '<script language="javascript">$("#myModal_edit").modal("toggle");$("#frm_editar_empleado").Frmreset();setTimeout(function() {fnCatListarEmpleados(7);}, 500);</script>';

                    break;
            }



        }


    }
}