<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 14/02/2017
 * Time: 03:58 PM
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
include "../../../../core/model_empleados.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$Empleado = new \core\model_empleados($_SESSION['data_login']['BDDatos']);

$Empleado->valida_session_id($_SESSION['data_login']['NoUsuario']);

// Validar campos vacios
$idEmpleado = NULL;
$idEmpresa = $_POST['idEmpresa'];
$tpoEmpleado = $_POST['tpoEmpleado'];
$NoDepartamento = $_POST['nodpto'];

$Nombre = $Empleado->get_sanatiza($_POST['nombre']);
$ApPaterno = $Empleado->get_sanatiza($_POST['appaterno']);
$ApMaterno = $Empleado->get_sanatiza($_POST['apmaterno']);
$Correo = $_POST['correo'];
$Direccion = $Empleado->get_sanatiza($_POST['direccion']);
$Tele01 = $Empleado->get_sanatiza($_POST['tel01']);
$Tele02 = $Empleado->get_sanatiza($_POST['tel02']);
$Tele03 = $Empleado->get_sanatiza($_POST['tel03']);
$Telp01 = $Empleado->get_sanatiza($_POST['tel04']);
$Telp02 = $Empleado->get_result_query($_POST['tel05']);
$idPhoto = 'default.png';
$Estatus = $_POST['estatus'];
$NoPuesto =  $_POST['idpuesto'];

/* Generar Numero de Empleado
    $NoEmpleado = $_POST['noempleado'];
*/
if($tpoEmpleado == 1){
    // Empleado Interno
    $NoEmpleado = $Empleado->getFormatFolio($idEmpresa,2).$tpoEmpleado.$Empleado->getFormatFolio($_POST['noempleado'],6);
}elseif($tpoEmpleado == 2){
    //Empleado Externo
    $NoEmpleado = $Empleado->getFormatFolio($idEmpresa,2).$tpoEmpleado.$Empleado->set_genera_NoEmpleado();
}


if(trim($Nombre) == ""){
    echo "<script>MyAlert('El nombre no debe estar vacio','alerta')</script>";
}elseif(trim($ApPaterno) == ""){
    echo "<script>MyAlert('EL apellido paterno esta vacio','alerta')</script>";
}elseif(strlen($ApPaterno) > 20){
    \core\core::MyAlert("El apellido paterno es demasiado largo","alerta");
}elseif(strlen($ApPaterno) > 20){
    \core\core::MyAlert("El apellido materno es demasiado largo","alerta");
}
elseif($NoDepartamento == 0 ){
    echo "<script>MyAlert('Seleccione un Departametno','alerta')</script>";
}else{

    if(trim($Correo)== ""){
        // Si el correo biene vacio, se le agrega uno falso
        $Correo = "none@no-reply.com";
    }

    if(!filter_var($Correo,FILTER_VALIDATE_EMAIL)){
        //validar que el correo no este vacio
        echo "<script>MyAlert('Correo Invalido','error')</script>";
    }else{
        //Registrar Empleado

        $data_empleado = array(
            "NoDepartamento"=>$NoDepartamento,
            "NoEmpleado"=>$NoEmpleado,
            "nombre_empleado"=>$Nombre,
            "appaterno"=>$ApPaterno,
            "apmaterno"=>$ApMaterno,
            "correo_empleado"=>$Correo,
            "direccion_empleado"=>$Direccion,
            "telefono01"=>$Tele01,
            "telefono02"=>$Tele02,
            "telefono03"=>$Tele03,
            "telefono04"=>$Telp01,
            "telefono05"=>$Telp02,
            "foto_empleado"=>$idPhoto,
            "puesto_empleado"=>$NoPuesto,
            "estatus"=>$Estatus,
            "idEmpleado"=>1
        );

        $Empleado->set_empleado($data_empleado);

        if($Empleado->_confirm){

            //Empleado Registrado correctamente
            $id = trim($Empleado->_rows[0][0]);

            switch ($_POST['opcion']){
                case 1:
                    //Esta opcion proviene del Fomulario de Nuevo Tickets

                    //Extraer Nombre del Departamento
                    $Empleado->_query = "SELECT Descripcion FROM BGECatalogoDepartamentos WHERe NoDepartamento = '$NoDepartamento' ";
                    $Empleado->get_result_query();

                    $NombreDepartamento = $Empleado->_rows[0][0];


                    //Cargar los Usuarios del Departamento y dejar el Departamento del Usuario Seleccionado en el formulario de nuevo ticket
                    echo "<script language='javascript'>$('#frm_alta_empleado').Frmreset();jsSdSeleccionarEmpleado('".$NoDepartamento."','".$NombreDepartamento."','".$id."','".$Nombre.' '.$ApPaterno.' '.$ApMaterno."','modalbtnclose2','".$NoEmpleado."')</script>";


                    break;
                case 2:
                    //Cargar los Usuarios del Departamento y dejar el Departamento del Usuario Seleccionado en el formulario de nuevo ticket
                    echo "<script>$('#frm_alta_empleado').Frmreset();fnCatAltaEmpleado(2)</script>";
                    break;

            }



        }else{
            //Empleado no registrado

            echo \core\core::MyAlert($Empleado->_message,"error");

        }



    }
}