<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 05/04/2017
 * Time: 11:44 AM
 */

namespace core;

include "seguridad.php";


class model_areas_sd extends seguridad
{
    protected $NoArea;
    protected $Descripcion;
    protected $NoDepartamento;
    protected $NombreDepartamento;
    protected $NoEstatus;


    public function getDescripcion(){
        return $this->Descripcion;
    }
    public function getNoArea(){
        return$this->NoArea;
    }
    public function getNoDepartamento(){
        return $this->NoDepartamento;
    }
    public function getNombreDepartamento(){
        return $this->NombreDepartamento;
    }
    public function getNoEstatus(){
        return $this->NoEstatus;
    }

    public function get_list($opcion,$Filtro,$NoDepartamento){

        switch ($opcion){
            case 1:
                //Solo Activos
                $where1 = " a.NoEstatus = 1  ORDER BY b.Descripcion,a.Descripcion ASC ";
                break;
            case 2:
                //Solo Inactivos
                $where1 = " a.NoEstatus = 0  ORDER BY b.Descripcion,a.Descripcion ASC ";
                break;
            case 3:
                //Todos los estados
                $where1 = "a.NoEstatus >= 0  ORDER BY b.Descripcion,a.Descripcion ASC ";
                break;
            case 4:
                //ultimos 10 registrados
                $where1 = " a.FechaAlta = '".date("Ymd")."'  ORDER BY b.Descripcion,a.Descripcion ASC ";
                break;
            case 5:
                //ultimos 10 actualizados
                $where1 = " a.FechaUM = '".date("Ymd")."'  ORDER BY b.Descripcion,a.Descripcion ASC ";
                break;
        }

        if($Filtro == 1){
            $Where2 = " ";
        }else{
            $Where2 = " a.NoDepartamento = '$NoDepartamento' AND ";
        }




        $this->_query = "SELECT a.NoArea,a.Descripcion,a.NoDepartamento,a.FechaAlta,a.FechaUM,b.Descripcion,c.NoUsuarioAlta,d.NoUsuarioUM FROM BSHCatalogoAreas as a 
                          LEFT JOIN BGECatalogoDepartamentos as b 
                            ON a.NoDepartamento = b.NoDepartamento 
                          LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                            ON a.NoUsuarioAlta = c.NoUsuario 
                          LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as d 
                            ON a.NoUsuarioUM = d.NoUsuario 
                          WHERE ".$Where2.$where1." ";
        $this->get_result_query();

    }

    public function set($data_area = array()){

        // funcion para registrar las areas

        if(array_key_exists('nombre_area',$data_area) ||array_key_exists('NoDepartamento',$data_area)){
            //validar que no exista ya el area
            unset($this->_rows) ;

            $this->_query = "SELECT Descripcion FROM BSHCatalogoAreas WHERE Descripcion = '$data_area[nombre_area]' AND NoDepartamento = '$data_area[NoDepartamento]' ";

            $this->get_result_query();


            if(count($this->_rows) >= 1){

                $this->_confirm = false ;
                $this->_message = "El área ya existe";

            }else{
                // si el puesto no existe se procede a dar de alta
                unset($this->_rows) ;
                $FechaAlta = date("Ymd");
                $HoraAlta = date("H:i:s");

                $this->_query = "SELECT count(NoArea)+1 as total FROM BSHCatalogoAreas WHERE  NoDepartamento = '$data_area[NoDepartamento]' ";

                $this->get_result_query();

                $NoArea = $this->_rows[0]['total'];

                unset($this->_rows) ;
                $NoUsuario = $_SESSION['data_login']['NoUsuario'];


                $this->_query = "
                    INSERT INTO BSHCatalogoAreas (
                    NoArea,
                    Descripcion,
                    NoDepartamento,
                    NoEstatus,
                    NoUsuarioAlta,
                    NoUsuarioUM,
                    FechaAlta,
                    HoraAlta,
                    FechaUM,
                    HoraUM
                    ) 
                    VALUES (
                    '$NoArea',
                    '$data_area[nombre_area]',
                    '$data_area[NoDepartamento]',
                    '1',
                    '$NoUsuario',
                    '$NoUsuario',
                    '$FechaAlta',
                    '$HoraAlta',
                    '$FechaAlta',
                    '$HoraAlta'
                    )";

                $this->execute_query();
                $this->_confirm = true ;
                $this->_message = "se registro el area correctamente";



            }


        }else{
            $this->_message = "No se encontro el campo de Nombre del area";
            $this->_confirm = false;
        }



    }


    public function get($idArea,$NoDepartamento = NULL){
        //funcion para  traer la informacion de las áreas;

        if($idArea != ''){

            if($NoDepartamento == NULL){
                $NoDepartamento = $_SESSION['data_departamento']['NoDepartamento'];
            }

            $this->_query = "SELECT a.NoArea,a.Descripcion,a.NoDepartamento,a.NoEstatus,b.Descripcion as NombreDepartamento 
                              FROM BSHCatalogoAreas as a 
                              LEFT JOIN BGECatalogoDepartamentos as b 
                              ON a.NoDepartamento = b.NoDepartamento
                              WHERE a.NoArea = '$idArea' AND a.NoDepartamento = '$NoDepartamento' ";

            $this->get_result_query();

        }

        if(count($this->_rows) == 1){

            foreach ($this->_rows[0] as $campo => $valor){
                $this->$campo = $valor ;
            }

            $this->_confirm = true ;
            $this->_message = "Se encontro el área";

        }else{
            $this->_confirm = false ;
            $this->_message = "No se encontro el área" ;
        }

    }

    public function edit($data_area = array()){

        // funcion para editar los puestos

        $FechaUM = date("Ymd");
        $HoraUM = date("H:i:s");

        if(array_key_exists('nombre_area', $data_area) || array_key_exists('NoArea',$data_area)){

            // validar que no exista ya el nuevo nombre del área
            unset($this->_rows) ;

            $this->_query = "SELECT Descripcion FROM BSHCatalogoAreas WHERE Descripcion = '$data_area[nombre_area]' AND NoArea != '$data_area[NoArea]' AND NoDepartamento != '$data_area[NoDepartamento]' ";
            $this->get_result_query();

            $NoUsuario = $_SESSION['data_login']['NoUsuario'];


            if(count($this->_rows) >= 1){

                $this->_confirm = false ;
                $this->_message = "El área ya existe";

            }else{
                unset($this->_rows) ;

                $this->_query = "UPDATE BSHCatalogoAreas 
                                  SET Descripcion = '$data_area[nombre_area]',
                                      NoDepartamento = '$data_area[NoDepartamento]',
                                      NoEstatus = '$data_area[NoEstatus]',
                                      FechaUM = '$FechaUM',
                                      HoraUM = '$HoraUM',
                                      NoUsuarioUM = '$NoUsuario'
                                  WHERE NoArea = '$data_area[NoArea]' AND NoDepartamento = '$data_area[NoDepartamento]' ";



                $this->execute_query();
                $this->_confirm = true ;
                $this->_message = "Puesto editado correctamente";
            }

        }else{
            $this->_confirm = false;
            $this->_message = "Error";
        }


    }

}