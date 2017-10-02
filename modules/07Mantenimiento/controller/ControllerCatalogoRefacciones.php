<?php

/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 26/05/2017
 * Time: 03:32 PM
 */

include "../../../../core/seguridad.php";

class ControllerCatalogoRefacciones extends \core\seguridad
{

    protected $idTabla ;
    protected $idValor;
    protected $Nombre;
    protected $Descripcion;
    protected $idestatus;

    protected $NoUsuarioAlta;
    protected $UsuarioAlta;
    protected $FechaAlta;

    protected $NoUsuarioUM;
    protected $UsuarioUM ;
    protected $FechaUM;

    public function getIDTabla(){ return $this->idTabla; }
    public function getIDValor(){return $this->idValor ;}
    public function getNombreUbicacion(){return $this->Nombre ;}
    public function getDescripcion(){return $this->Descripcion ;}

    public function getIDEstatus(){ return $this->idestatus ;}
    public function getNoUsuarioAlta(){return $this->NoUsuarioAlta;}
    public function getUsuarioAlta(){return $this->UsuarioAlta;}
    public function getFechaAlta(){return $this->FechaAlta;}
    public function getNoUsuarioUM(){return $this->NoUsuarioUM;}
    public function getUsuarioUM(){return $this->UsuarioUM;}
    public function getFechaUM(){return $this->FechaUM;}


    public function get_list_ubicaciones($idEstatus = 1,$opcion =1, $Filtros = array()){


        switch ($opcion){
            case 1:
                //Estatus Activos
                $this->_query = "
                SELECT 
                    a.idTabla,a.idValor,a.Nombre,a.Descripcion,a.idestatus,a.NoUsuarioAlta,b.NombreDePila,a.FechaAlta,a.NoUsuarioUM,b.NombreDePila,a.FechaUM 
                FROM 07MTOCatalogoGeneral as a 
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                ON a.NoUsuarioAlta = b.NoUsuario
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                ON a.NoUsuarioUM = c.NoUsuario 
                WHERE idestatus = 1 AND idTabla = 1 ORDER BY a.Nombre ASC
                ";
                break;
            case 2:
                //Estatus Inactivos
                $this->_query = "
                SELECT 
                    a.idTabla,a.idValor,a.Nombre,a.Descripcion,a.idestatus,a.NoUsuarioAlta,b.NombreDePila,a.FechaAlta,a.NoUsuarioUM,b.NombreDePila,a.FechaUM 
                FROM 07MTOCatalogoGeneral as a 
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                ON a.NoUsuarioAlta = b.NoUsuario
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                ON a.NoUsuarioUM = c.NoUsuario 
                WHERE idestatus = 0 AND idTabla = 1 ORDER BY a.Nombre ASC";
                break;
            case 3:
                //Todos los registros
                $this->_query = "
                SELECT 
                    a.idTabla,a.idValor,a.Nombre,a.Descripcion,a.idestatus,a.NoUsuarioAlta,b.NombreDePila,a.FechaAlta,a.NoUsuarioUM,b.NombreDePila,a.FechaUM 
                FROM 07MTOCatalogoGeneral as a 
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                ON a.NoUsuarioAlta = b.NoUsuario
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                ON a.NoUsuarioUM = c.NoUsuario 
                WHERE idTabla = 1 ORDER BY a.Nombre ASC
                ";

                break;
            case 4:
                //Registrados actualmente
                $this->_query = "
                SELECT 
                    a.idTabla,a.idValor,a.Nombre,a.Descripcion,a.idestatus,a.NoUsuarioAlta,b.NombreDePila,a.FechaAlta,a.NoUsuarioUM,b.NombreDePila,a.FechaUM 
                FROM 07MTOCatalogoGeneral as a 
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                ON a.NoUsuarioAlta = b.NoUsuario
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                ON a.NoUsuarioUM = c.NoUsuario 
                WHERE date(a.FechaAlta) = date(now()) AND idTabla = 1 ORDER BY a.FechaAlta DESC
                              ";
                break;
            case 5:
                //Registros actualizados
                $this->_query = "
                SELECT 
                    a.idTabla,a.idValor,a.Nombre,a.Descripcion,a.idestatus,a.NoUsuarioAlta,b.NombreDePila,a.FechaAlta,a.NoUsuarioUM,b.NombreDePila,a.FechaUM 
                FROM 07MTOCatalogoGeneral as a 
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                ON a.NoUsuarioAlta = b.NoUsuario
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                ON a.NoUsuarioUM = c.NoUsuario 
                WHERE date(a.FechaUM) = date(now()) AND idTabla = 1 ORDER BY a.FechaUM DESC
                ";
                break;
            case 6:
                //Busqueda por nombre proveedor, nombre contacto , correo

                $this->_query = "
                SELECT 
                    a.idTabla,a.idValor,a.Nombre,a.Descripcion,a.idestatus,a.NoUsuarioAlta,b.NombreDePila,a.FechaAlta,a.NoUsuarioUM,b.NombreDePila,a.FechaUM 
                FROM 07MTOCatalogoGeneral as a 
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                ON a.NoUsuarioAlta = b.NoUsuario
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                ON a.NoUsuarioUM = c.NoUsuario 
                WHERE idTabla = 1 AND  a.Nombre LIKE '%$Filtros%' OR a.Descripcion LIKE '%$Filtros%' ORDER BY a.FechaAlta DESC
                ";
                break;

            default:

                $this->_query = "
                SELECT 
                    a.idTabla,a.idValor,a.Nombre,a.Descripcion,a.idestatus,a.NoUsuarioAlta,b.NombreDePila,a.FechaAlta,a.NoUsuarioUM,b.NombreDePila,a.FechaUM 
                FROM 07MTOCatalogoGeneral as a 
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                ON a.NoUsuarioAlta = b.NoUsuario
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                ON a.NoUsuarioUM = c.NoUsuario 
                WHERE idestatus = 1 AND idTabla = 1 ORDER BY a.Nombre ASC
                ";
                break;
        }

        $this->get_result_query();

        return $this->_rows;


    }

    public function set_refacciones($data_array = array()){


        if(
            array_key_exists('idequipo',$data_array) &&
            array_key_exists('nombre',$data_array) &&
            array_key_exists('idtabla',$data_array)
        ){

            //Validar que no exista ya el proveedor

            $this->_query = "SELECT Nombre FROM 07MTOCatalogoGeneral WHERE Nombre = '$data_array[nombre]' AND idTabla = $data_array[idtabla]  ";
            $this->get_result_query();

            if(count($this->_rows) > 0 ){
                //Ya se encuentra el nombre del proveedor registrado

                $this->_confirm = false;
                $this->_message =  "El nombre de la refacción ya existe";

            }else{

                $this->_query = "call sp_07_abc_catalogos_generales(
                '1',
                '$data_array[idtabla]',
                '0',
                '$data_array[nombre]',
                '$data_array[descripcion]',
                '$data_array[idequipo]',
                '0',
                '0',
                '0',
                '0',
                '1',
                '$data_array[NoUsuarioAlta]',
                '$data_array[FechaAlta]'
                )";
                $this->execute_query();

                $this->_confirm = true;


            }

        }else{
            $this->_confirm = false;
            $this->_message =  "No se encontraron las llaves para el registro de la nueva refacción";

        }



    }

    public function edit_ubicacion($data_array = array()){

        //Validar que vengan los datos principales
        if(
            array_key_exists('idubicacion',$data_array) &&
            array_key_exists('nombre',$data_array)

        ){
            //Validar que no exista ya el Nombre del Proveedor
            $this->_query = "SELECT Nombre FROM 07MTOCatalogoGeneral WHERE Nombre = '$data_array[nombre]' AND idTabla = 2 AND idValor <> '$data_array[idubicacion]' ";
            $this->get_result_query();

            if(count($this->_rows) > 0 ){
                //Ya se encuentra el nombre del proveedor registrado

                $this->_confirm = false;
                $this->_message =  "El nombre de la ubicación ya existe";

            }else{

                $this->_query = "call sp_07_abc_catalogos_generales(
                '2',
                '$data_array[idtabla]',
                '$data_array[idubicacion]',
                '$data_array[nombre]',
                '$data_array[descripcion]',
                '0',
                '0',
                '0',
                '0',
                '0',
                '$data_array[idestatus]',
                '$data_array[NoUsuarioAlta]',
                '$data_array[FechaAlta]'
                )";
                $this->execute_query();
                $this->execute_query();

                $this->_confirm = true;


            }


        }else{
            $this->_confirm = false;
            $this->_message =  "No se encontraron las llaves para el registro del la nueva ubicación";
        }





    }

    public function get_refaccion($idRefaccion = ""){

        if($idRefaccion != ""){

            $this->_query = "SELECT 
                    a.idTabla,a.idValor,a.Nombre,a.Descripcion,a.idestatus,a.NoUsuarioAlta,b.NombreDePila as UsuarioAlta,a.FechaAlta,a.NoUsuarioUM,b.NombreDePila as UsuarioUM,a.FechaUM 
                FROM 07MTOCatalogoGeneral as a 
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                ON a.NoUsuarioAlta = b.NoUsuario
                LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                ON a.NoUsuarioUM = c.NoUsuario 
                WHERE idValor = $idRefaccion AND idTabla = 1 ORDER BY a.Nombre ASC";

            $this->get_result_query();

            if(count($this->_rows) == 1){

                foreach ($this->_rows[0] as $campo => $valor){
                    $this->$campo = $valor ;
                }

                $this->_confirm = true ;
                $this->_message = "Se encontro la ubicación";

            }else{
                $this->_confirm = false ;
                $this->_message = "No se encontro la ubicación" ;
            }


        }else{

            \core\core::MyAlert("Error no se encotro la refacción seleccionado","error");
            $this->_confirm = false;
            $this->_message = "Error no se encotro la refacción seleccionado";

        }


    }

}