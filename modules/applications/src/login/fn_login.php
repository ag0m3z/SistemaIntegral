<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 27/01/2017
 * Time: 12:55 PM
 */

include "../../../../core/core.php";
include '../../../../core/seguridad.php';
include "../../../../core/sesiones.php";


$seguridad = new \core\seguridad();
$sesiones = new \core\sesiones();


//Sanatizar datos
sleep(1);
$Usuario = $seguridad->get_sanatiza($_POST['user']);
$Contrasena = md5( $seguridad->get_sanatiza($_POST['pass']) );
$FechaActual = date("Y-m-d H:i:s");

if(trim($Usuario) != "" || trim($Contrasena) != ""){

    $seguridad->loginIn($Usuario,$Contrasena);

    if($seguridad->_confirm){

        $data_login = $seguridad->_rows;

        //validar que el usuario tengo un empleado asignado existente y activo
        if(trim($data_login[0]['idEmpleado']) == ""){

            \core\core::MyAlert("El usuario no cuenta con un empleado asignado. comuníquese a sistemas al (81)1946-3600 Ext. 711","alert");

        }else if(trim($data_login[0]['NoEmpleado']) == ""){

            \core\core::MyAlert("El empleado no cuenta con NoEmpleado. comuníquese a sistemas al (81)1946-3600 Ext. 711","alert");


        }else if ( $data_login[0]['NoEstatus'] == 0 ) {

            // el usuario se encuetra desactivado
            \core\core::MyAlert("EL usuario: <b>".$Usuario."</b>, se encuentra Desactivado. comuníquese  a sistemas al (81)1946-3600 Ext. 711","alert");

        }else if( $data_login[0]['Intentos'] >= 3){

            // el usuario se encuentra bloqueado
            \core\core::MyAlert("EL usuario: <b>".$Usuario."</b>, se encuentra Bloqueado. comuníquese  a sistemas al (81)1946-3600 Ext. 711","alert");

        }else if( $Contrasena != $data_login[0]['PassLogin'] ) {
            // contrasena incorrecta

           $seguridad->_query = "UPDATE BGECatalogoUsuarios SET Intentos = (Intentos + 1) WHERE NoUsuario =  ".$data_login[0]['NoUsuario']." ";
            $seguridad->execute_query();
            \core\core::MyAlert("La contraseña es incorrecta, inténtelo nuevamente","alert");

        }else if($data_login[0]['Estatus'] == 'C' || $data_login[0]['REQUEST_TIME'] == session_id() ){

            // el usuario ya se encuentra conectado
            \core\core::MyAlert('<p class=\"text-left padding-x5\" > EL usuario: <b>'.$Usuario.'</b>, ya cuenta con una sesión  abierta<br><br><b>Fecha conexión :</b> '.$data_login[0]['FechaConexion'].'<br><b>ipaddress01:</b> '.$data_login[0]['Noip'].'<br><b>hostname:</b> '.$data_login[0]['HostName'].'<br><b>user_agent:</b> '.$data_login[0]['User_Agent'] .'<br><br>comuníquese  a sistemas al (81)1946-3600 Ext. 711 </p>',"error");

        }else if($data_login[0]['BDDatos'] == ""){

            // validar que el usuario cuente con un servidor de datos
            \core\core::MyAlert("El usuario no cuenta con un sevidor de datos. comuníquese  a sistemas al (81)1946-3600 Ext. 711","error");

        }else{

            $NoUsuario  = $data_login[0]['NoUsuario'];
            $NoDepartamento = $data_login[0]['NoDepartamento'];
            $is_ipaddress = $seguridad->get_obtener_ip();
            $nombre_host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $User_agent = $_SERVER['HTTP_USER_AGENT'];
            $Request_time = session_id();
            $Fecha =  date("Y-m-d H:i:s");

           $seguridad->_query = "CALL sp_actualiza_conexion('1','$NoUsuario','$is_ipaddress','$is_ipaddress','$nombre_host','$User_agent','$Request_time','$Fecha') ";
           $seguridad->execute_query();

            /**
             *
             * Inicializacion de variables SUPER GLOBALES
             *  ========================================
             */

            // datos de usuario
            $sesiones->set('data_login',array(
               'NoUsuario'=>$NoUsuario,
                'Usuario'=>$data_login[0]['UsuarioLogin'],
                'Nombre'=>$data_login[0]['Nombre'],
                'NombreDePila'=>$data_login[0]['NombreDePila'],
                'NombreCompleto'=>$data_login[0]['NombreCompleto'],
                'ApPaterno'=>$data_login[0]['ApPaterno'],
                'ApMaterno'=>$data_login[0]['ApMaterno'],
                'NoPerfil'=>$data_login[0]['NoPerfil'],
                'idEmpleado'=>$data_login[0]['idEmpleado'],
                'NoEmpleado'=>$data_login[0]['NoEmpleado'],
                'correo_usuario'=>$data_login[0]['correo'],
                'imagen_profile'=>$data_login[0]['idphoto'],
                'init_time'=>date("Y-m-d H:i:s"),
                'BDDatos'=>$data_login[0]['BDDatos'],
                'NombreConexion'=>$data_login[0]['NombreConexion'],
                'REQUEST_TIME'=>$Request_time
            ));

            // Datos del Departamento del usuario

            $DataBaseUser = $data_login[0]['BDDatos'];

            $Departamentos = new \core\seguridad($data_login[0]['BDDatos']);

            $Departamentos->_query = "SELECT NoDepartamento,Descripcion,idEmpresa,NoTipo,AsignarReportes,NoSucursal,Correo,NoZona,NoSupervisor FROM $DataBaseUser.BGECatalogoDepartamentos WHERE NoDepartamento = '$NoDepartamento' ";
            $Departamentos->get_result_query();
            $data_departamento = $Departamentos->_rows;

            $sesiones->set('data_departamento',array(
                'NoDepartamento'=>$data_departamento[0]['NoDepartamento'],
                'NombreDepartamento'=>$data_departamento[0]['Descripcion'],
                'idEmpresa'=>$data_departamento[0]['idEmpresa'],
                'NoTipo'=>$data_departamento[0]['NoTipo'],
                'AsignarReportes'=>$data_departamento[0]['AsignarReportes'],
                'NoSucursal'=>$data_departamento[0]['NoSucursal'],
                'Correo'=>$data_departamento[0]['Correo'],
                'NoZona'=>$data_departamento[0]['NoZona'],
                'NoSupervisor'=>$data_departamento[0]['NoSupervisor']
            ));

            //Eliminar Permisos para Cargarlos Nuevamente
            unset($_SESSION['menu_principal']) ;

            $Departamentos->_query = "call sp_cargar_accesos($NoUsuario)";

            $Departamentos->get_result_multi_query();

            $Menu = $Departamentos->_rows;

            /**
             *  Armado de Menu y Carga de Accesos
             * el sp retorna un resultado de 4 tablas
             *
             * Tabla(array 1) = Lista de Modulos
             * Tabla(array 2) = Lista de Secciones
             * Tabla(array 3) = Lista de Aplicaciones
             * Tabla(array 4) = Carga de Accesos
             *
             */

            // Cargar Modulos en array
            for($i =0 ; $i < count($Menu[0]); $i++){

                $_SESSION['menu_principal']['modulos'][] = array(
                    "idModulo"=>$Menu[0][$i][0],
                    "nombre"=>$Menu[0][$i][1],
                    "icon"=>$Menu[0][$i][2]
                );
            }

            // Cargar Secciones en array
            for($i =0 ; $i < count($Menu[1]); $i++){

                $_SESSION['menu_principal']['secciones'][] = array(
                    "idModulo"=>$Menu[1][$i][0],
                    "idSeccion"=>$Menu[1][$i][1],
                    "nombre"=>$Menu[1][$i][2],
                    "icon"=>$Menu[1][$i][3]
                );
            }

            // Cargar Secciones en array
            for($i =0 ; $i < count($Menu[2]); $i++){

                $_SESSION['menu_principal']['aplication'][] = array(
                    "idModulo"=>$Menu[2][$i][0],
                    "idSeccion"=>$Menu[2][$i][1],
                    "idOpcion"=>$Menu[2][$i][2],
                    "nombre"=>$Menu[2][$i][3],
                    "icon"=>$Menu[2][$i][4],
                    "eventclick"=>$Menu[2][$i][5],
                    "eventdblclick"=>$Menu[2][$i][6]
                );
            }

            for($i = 0; $i < count($Menu[3]);$i++){
                $_SESSION['menu_opciones'][$Menu[3][$i][0]][$Menu[3][$i][1]][$Menu[3][$i][2]][] = array(
                    "OpcionA"=>$Menu[3][$i][3],
                    "OpcionB"=>$Menu[3][$i][4],
                    "OpcionC"=>$Menu[3][$i][5],
                    "OpcionV"=>$Menu[3][$i][6],
                    "OpcionR"=>$Menu[3][$i][7]
                );
            }

            //redireccionar a la HomePage
            \core\core::returnHome();


        }


    }else{

        \core\core::MyAlert($seguridad->_message,'alert');


    }

}else{

    \core\core::MyAlert("No se encontraron los datos para la consulta","alert");

}

