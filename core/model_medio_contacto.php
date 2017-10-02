<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 18/04/2017
 * Time: 10:48 AM
 */

namespace core;
include_once "seguridad.php";


class model_medio_contacto extends seguridad
{

    protected $idDescripcion;
    protected $idCatalogo = 2;
    protected $Opcion1;
    protected $Descripcion;
    protected $NoEstatus;
    protected $UsuarioAlta;
    protected $UsuarioUM;
    protected $FechaAlta;
    protected $FechaUM;


    public function getNombreMedio(){return $this->Descripcion;}
    public function getIDMedio(){return$this->idDescripcion;}
    public function getNoEstatus(){return $this->NoEstatus;}

    public function get_list($opcion,$Filtro,$NoDepartamento){

        switch ($opcion){
            case 1:
                //Solo Activos
                $where1 = " a.NoEstatus = 1 AND idCatalogo = 2 ORDER BY a.Descripcion ASC ";
                break;
            case 2:
                //Solo Inactivos
                $where1 = " a.NoEstatus = 0 AND idCatalogo = 2 ORDER BY a.Descripcion DESC ";
                break;
            case 3:
                //Todos los estados
                $where1 = "a.NoEstatus >= 0 AND idCatalogo = 2 ORDER BY a.Descripcion DESC ";
                break;
            case 4:
                //ultimos 10 registrados
                $where1 = " a.FechaAlta = '".date("Ymd")."' AND idCatalogo = 2 ORDER BY a.HoraAlta DESC ";
                break;
            case 5:
                //ultimos 10 actualizados
                $where1 = " a.FechaUM = '".date("Ymd")."' AND idCatalogo = 2 ORDER BY a.HoraUM DESC ";
                break;
        }




        $this->_query = "SELECT a.idDescripcion,a.Descripcion,a.FechaAlta,a.FechaUM,c.NombreDePila,d.NombreDePila FROM BSHCatalogoCatalogos as a 
                          LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                            ON a.NoUsuarioAlta = c.NoUsuario 
                          LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as d 
                            ON a.NoUsuarioUM = d.NoUsuario 
                          WHERE ".$where1." ";
        $this->get_result_query();

    }

    public function set($data_area = array()){

        // funcion para registrar las areas

        if(array_key_exists('nombre_medio_contacto',$data_area)){
            //validar que no exista ya el area
            unset($this->_rows) ;

            $this->_query = "SELECT Descripcion FROM BSHCatalogoCatalogos WHERE Descripcion = '$data_area[nombre_medio_contacto]' AND idCatalogo = 2 ";
            $this->get_result_query();


            if(count($this->_rows) >= 1){

                $this->_confirm = false ;
                $this->_message = "El medio de contacto ya existe";

            }else{
                // si el puesto no existe se procede a dar de alta
                unset($this->_rows) ;
                $FechaAlta = date("Ymd");
                $HoraAlta = date("H:i:s");

                $this->_query = "SELECT count(idDescripcion)+1 as total FROM BSHCatalogoCatalogos WHERE  idCatalogo = 2 ";
                $this->get_result_query();

                $idMedioContacto = $this->_rows[0]['total'];

                unset($this->_rows) ;
                $NoUsuario = $_SESSION['data_login']['NoUsuario'];


                $this->_query = "
                    INSERT INTO BSHCatalogoCatalogos (
                    idCatalogo,
                    idDescripcion,
                    Descripcion,
                    NoEstatus,
                    NoUsuarioAlta,
                    FechaAlta,
                    HoraAlta
                    ) 
                    VALUES (
                    '2',
                    '$idMedioContacto',
                    '$data_area[nombre_medio_contacto]',
                    '1',
                    '$NoUsuario',
                    '$FechaAlta',
                    '$HoraAlta'
                    )";

                $this->execute_query();
                $this->_confirm = true ;
                $this->_message = "se registro el medio de contacto correctamente";



            }


        }else{
            $this->_message = "No se encontro el campo de Nombre del medio de contacto";
            $this->_confirm = false;
        }



    }

    public function get($idMedioContacto){
        //funcion para  traer la informacion de las áreas;

        if($idMedioContacto != ''){

            $this->_query = "SELECT a.idDescripcion,a.Descripcion,a.FechaAlta,a.FechaUM,c.NombreDePila as UsuarioAlta,d.NombreDePila as UsuarioUM,a.NoEstatus FROM BSHCatalogoCatalogos as a 
                          LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                            ON a.NoUsuarioAlta = c.NoUsuario 
                          LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as d 
                            ON a.NoUsuarioUM = d.NoUsuario 
                          WHERE idCatalogo = 2 AND idDescripcion = '$idMedioContacto' ";

            $this->get_result_query();

        }

        if(count($this->_rows) == 1){

            foreach ($this->_rows[0] as $campo => $valor){
                $this->$campo = $valor ;
            }

            $this->_confirm = true ;
            $this->_message = "Se encontro el medio de contacto";

        }else{
            $this->_confirm = false ;
            $this->_message = "No se encontro el medio de contacto" ;
        }

    }

    public function edit($data_area = array()){

        // funcion para editar los puestos

        $FechaUM = date("Ymd");
        $HoraUM = date("H:i:s");

        if(array_key_exists('nombre_medio_contacto', $data_area) || array_key_exists('idMedioContacto', $data_area) ){

            // validar que no exista ya el nuevo nombre del área
            unset($this->_rows) ;

            $this->_query = "SELECT Descripcion FROM BSHCatalogoCatalogos WHERE Descripcion = '$data_area[nombre_medio_contacto]' AND idCatalogo != '2' ";
            $this->get_result_query();

            $NoUsuario = $_SESSION['data_login']['NoUsuario'];


            if(count($this->_rows) >= 1){

                $this->_confirm = false ;
                $this->_message = "El medio de contacto ya existe";

            }else{
                unset($this->_rows) ;

                $this->_query = "UPDATE BSHCatalogoCatalogos 
                                  SET Descripcion = '$data_area[nombre_medio_contacto]',
                                      NoEstatus = '$data_area[NoEstatus]',
                                      FechaUM = '$FechaUM',
                                      HoraUM = '$HoraUM',
                                      NoUsuarioUM = '$NoUsuario'
                                  WHERE idDescripcion = '$data_area[idMedioContacto]' AND idCatalogo = 2 ";



                $this->execute_query();
                $this->_confirm = true ;
                $this->_message = "medio de contacto editado correctamente";
            }

        }else{
            $this->_confirm = false;
            $this->_message = "Error";
        }


    }

}