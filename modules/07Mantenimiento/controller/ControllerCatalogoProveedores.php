<?php

/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 26/05/2017
 * Time: 03:32 PM
 */

include "../../../../core/seguridad.php";

class ControllerCatalogoProveedores extends \core\seguridad
{

    protected $idproveedor ;
    protected $NombreProveedor;
    protected $NombreContacto;
    protected $Descripcion;
    protected $Telefono01;
    protected $Telefono02;
    protected $Celular;
    protected $Ext ;
    protected $CalleNumero;
    protected $Colonia;
    protected $Correo;
    protected $idestatus ;
    protected $NoUsuarioAlta;
    protected $UsuarioAlta;
    protected $FechaAlta;
    protected $NoUsuarioUM;
    protected $UsuarioUM ;
    protected $FechaUM;

    public function getIDProveedor(){ return $this->idproveedor ; }
    public function getNombreProveedor(){return $this->NombreProveedor ;}
    public function getNombreContacto(){return $this->NombreContacto ;}
    public function getDescripcion(){return $this->Descripcion ;}
    public function getTelefono01(){return $this->Telefono01 ;}
    public function getTelefono02(){return $this->Telefono02 ;}
    public function getCelular(){return $this->Celular ;}
    public function getExt(){return $this->Ext  ;}
    public function getCalleNumero(){ return $this->CalleNumero;}
    public function getColonia(){ return $this->Colonia ;}
    public function getIDEstatus(){ return $this->idestatus ;}
    public function getNoUsuarioAlta(){return $this->NoUsuarioAlta;}
    public function getUsuarioAlta(){return $this->UsuarioAlta;}
    public function getFechaAlta(){return $this->FechaAlta;}
    public function getNoUsuarioUM(){return $this->NoUsuarioUM;}
    public function getUsuarioUM(){return $this->UsuarioUM;}
    public function getFechaUM(){return $this->FechaUM;}
    public function getCorreo(){ return $this->Correo;}


    public function get_list_proveedores($idEstatus = 1,$opcion =1, $Filtros = array()){


        switch ($opcion){
            case 1:
                //Estatus Activos
                $this->_query = "SELECT 
                            a.idProveedor,a.NombreProveedor,a.NombreContacto,a.Descripcion,a.Telefono01,a.Telefono02,a.Celular,a.Ext,a.CalleNumero,a.Colonia,a.idestatus,b.NombreDePila,a.FechaAlta,c.NombreDePila,a.FechaUM,a.Correo 
                          FROM 
                            07MTOCatalogoProveedores as a 
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                            ON a.NoUsuarioAlta = b.NoUsuario
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                            ON a.NoUsuarioUm = c.NoUsuario
                          WHERE 
                            idestatus = 1 ORDER BY FechaAlta DESC";
                break;
            case 2:
                //Estatus Inactivos
                $this->_query = "SELECT 
                            a.idProveedor,a.NombreProveedor,a.NombreContacto,a.Descripcion,a.Telefono01,a.Telefono02,a.Celular,a.Ext,a.CalleNumero,a.Colonia,a.idestatus,b.NombreDePila,a.FechaAlta,c.NombreDePila,a.FechaUM,a.Correo 
                          FROM 
                            07MTOCatalogoProveedores as a 
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                            ON a.NoUsuarioAlta = b.NoUsuario
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                            ON a.NoUsuarioUm = c.NoUsuario
                          WHERE 
                            idestatus = 0 ORDER BY FechaAlta DESC";
                break;
            case 3:
                //Todos los registros
                $this->_query = "SELECT 
                            a.idProveedor,a.NombreProveedor,a.NombreContacto,a.Descripcion,a.Telefono01,a.Telefono02,a.Celular,a.Ext,a.CalleNumero,a.Colonia,a.idestatus,b.NombreDePila,a.FechaAlta,c.NombreDePila,a.FechaUM,a.Correo 
                          FROM 
                            07MTOCatalogoProveedores as a 
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                            ON a.NoUsuarioAlta = b.NoUsuario
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                            ON a.NoUsuarioUm = c.NoUsuario
                          ORDER BY FechaAlta DESC";

                break;
            case 4:
                //Registrados actualmente
                $this->_query = "SELECT 
                           a.idProveedor,a.NombreProveedor,a.NombreContacto,a.Descripcion,a.Telefono01,a.Telefono02,a.Celular,a.Ext,a.CalleNumero,a.Colonia,a.idestatus,b.NombreDePila,a.FechaAlta,c.NombreDePila,a.FechaUM,a.Correo 
                          FROM 
                            07MTOCatalogoProveedores as a 
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                            ON a.NoUsuarioAlta = b.NoUsuario
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                            ON a.NoUsuarioUm = c.NoUsuario
                          WHERE 
                            date(a.FechaAlta) = date(now())  ORDER BY a.FechaAlta ASC";
                break;
            case 5:
                //Registros actualizados
                $this->_query = "SELECT 
                           a.idProveedor,a.NombreProveedor,a.NombreContacto,a.Descripcion,a.Telefono01,a.Telefono02,a.Celular,a.Ext,a.CalleNumero,a.Colonia,a.idestatus,b.NombreDePila,a.FechaAlta,c.NombreDePila,a.FechaUM,a.Correo 
                          FROM 
                            07MTOCatalogoProveedores as a 
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                            ON a.NoUsuarioAlta = b.NoUsuario
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                            ON a.NoUsuarioUm = c.NoUsuario
                          WHERE 
                            date(a.FechaUM) = date(now())  ORDER BY a.FechaUM ASC";
                break;
            case 6:
                //Busqueda por nombre proveedor, nombre contacto , correo
                $this->_query = "SELECT 
                           a.idProveedor,a.NombreProveedor,a.NombreContacto,a.Descripcion,a.Telefono01,a.Telefono02,a.Celular,a.Ext,a.CalleNumero,a.Colonia,a.idestatus,b.NombreDePila,a.FechaAlta,c.NombreDePila,a.FechaUM,a.Correo 
                          FROM 
                            07MTOCatalogoProveedores as a 
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                            ON a.NoUsuarioAlta = b.NoUsuario
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                            ON a.NoUsuarioUm = c.NoUsuario
                          WHERE 
                            a.NombreProveedor LIKE '%$Filtros%' OR a.NombreContacto LIKE '%$Filtros%' OR a.Correo LIKE '%$Filtros%' ORDER BY a.NombreProveedor ASC";
                break;
            default:

                $this->_query = "SELECT 
                            a.idProveedor,a.NombreProveedor,a.NombreContacto,a.Descripcion,a.Telefono01,a.Telefono02,a.Celular,a.Ext,a.CalleNumero,a.Colonia,a.idestatus,b.NombreDePila,a.FechaAlta,c.NombreDePila,a.FechaUM,a.Correo 
                          FROM 
                            07MTOCatalogoProveedores as a 
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                            ON a.NoUsuarioAlta = b.NoUsuario
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                            ON a.NoUsuarioUm = c.NoUsuario
                          ORDER BY FechaAlta DESC";
                break;
        }

        $this->get_result_query();

        return $this->_rows;


    }

    public function set_proveedores($data_array = array()){


        if(
            array_key_exists('nombre',$data_array) &&
            array_key_exists('contacto',$data_array) &&
            array_key_exists('telefono01',$data_array) ||
            array_key_exists('telefono01',$data_array)

        ){

            //Validar que no exista ya el proveedor

            $this->_query = "SELECT NombreProveedor FROM 07MTOCatalogoProveedores WHERE NombreProveedor = '$data_array[nombre]' ";
            $this->get_result_query();

            if(count($this->_rows) > 0 ){
                //Ya se encuentra el nombre del proveedor registrado

                $this->_confirm = false;
                $this->_message =  "El nombre del proveedor ya existe";

            }else{

                $this->_query = "call sp_07_abc_proveedores(
                '1',
                '0',
                '$data_array[nombre]',
                '$data_array[contacto]',
                '$data_array[descripcion]',
                '$data_array[telefono01]',
                '$data_array[telefono02]',
                '$data_array[celular]',
                '$data_array[ext]',
                '$data_array[correo]',
                '$data_array[callenumero]',
                '$data_array[colonia]',
                '1',
                '$data_array[NoUsuarioAlta]',
                '$data_array[FechaAlta]'
                )";
                $this->execute_query();

                $this->_confirm = true;


            }

        }else{
            $this->_confirm = false;
            $this->_message =  "No se encontraron las llaves para el registro del nuevo proveedor";

        }



    }


    public function edit_proveedor($data_array = array()){

        //Validar que vengan los datos principales
        if(
            array_key_exists('idproveedor',$data_array) &&
            array_key_exists('nombre',$data_array) &&
            array_key_exists('contacto',$data_array) &&
            array_key_exists('telefono01',$data_array) ||
            array_key_exists('telefono01',$data_array)

        ){
            //Validar que no exista ya el Nombre del Proveedor
            $this->_query = "SELECT NombreProveedor FROM 07MTOCatalogoProveedores WHERE NombreProveedor = '$data_array[nombre]' AND idProveedor <> '$data_array[idproveedor]' ";
            $this->get_result_query();

            if(count($this->_rows) > 0 ){
                //Ya se encuentra el nombre del proveedor registrado

                $this->_confirm = false;
                $this->_message =  "El nombre del proveedor ya existe";

            }else{

                $this->_query = "call sp_07_abc_proveedores(
                '2',
                '$data_array[idproveedor]',
                '$data_array[nombre]',
                '$data_array[contacto]',
                '$data_array[descripcion]',
                '$data_array[telefono01]',
                '$data_array[telefono02]',
                '$data_array[celular]',
                '$data_array[ext]',
                '$data_array[correo]',
                '$data_array[callenumero]',
                '$data_array[colonia]',
                '$data_array[idestatus]',
                '$data_array[NoUsuarioAlta]',
                '$data_array[FechaAlta]'
                )";
                $this->execute_query();

                $this->_confirm = true;


            }


        }else{
            $this->_confirm = false;
            $this->_message =  "No se encontraron las llaves para el registro del nuevo proveedor";
        }





    }


    public function get_proveedor($idProveedor = ""){

        if($idProveedor != ""){

            $this->_query = "SELECT 
                            a.idProveedor,
                            a.NombreProveedor,
                            a.NombreContacto,
                            a.Descripcion,
                            a.Telefono01,
                            a.Telefono02,
                            a.Celular,
                            a.Ext,
                            a.Correo,
                            a.CalleNumero,
                            a.Colonia,
                            a.idestatus,
                            a.NoUsuarioAlta,
                            b.NombreDePila as UsuarioAlta,
                            a.FechaAlta,
                            a.NoUsuarioUM,
                            c.NombreDePila as UsuarioUM,
                            a.FechaUM 
                          FROM 
                            07MTOCatalogoProveedores as a 
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                            ON a.NoUsuarioAlta = b.NoUsuario
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                            ON a.NoUsuarioUm = c.NoUsuario
                          WHERE 
                            a.idProveedor = $idProveedor ORDER BY a.FechaAlta DESC";

            $this->get_result_query();

            if(count($this->_rows) == 1){

                foreach ($this->_rows[0] as $campo => $valor){
                    $this->$campo = $valor ;
                }

                $this->_confirm = true ;
                $this->_message = "Se encontro el proveedor";

            }else{
                $this->_confirm = false ;
                $this->_message = "No se encontro el proveedor" ;
            }


        }else{

            \core\core::MyAlert("Error no se encotro el proveedor seleccionado","error");
            $this->_confirm = false;
            $this->_message = "Error no se encotro el proveedor seleccionado";

        }


    }

}