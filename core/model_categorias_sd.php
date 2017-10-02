<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 11/04/2017
 * Time: 12:59 PM
 */

namespace core;
include "seguridad.php";

class model_categorias_sd extends seguridad
{
    protected $descripcion;
    protected $nocategoria;
    protected $NoDepartamento;
    protected $NombreDepartamento;
    protected $NoArea;
    protected $NombreArea;
    protected $NoEstatus;

    public function getNombreCategoria(){
        return $this->descripcion;
    }
    public function getNoCategoria(){
        return $this->nocategoria;
    }
    public function getNoDepartamento(){
        return $this->NoDepartamento;
    }
    public function getNombreDepartamento(){
        return $this->NombreDepartamento;
    }
    public function getNoArea(){
        return $this->NoArea;
    }
    public function getNombreArea(){
        return $this->NombreArea;
    }

    public function getNoEstatus(){
        return $this->NoEstatus;
    }

    public function get_list($opcion,$filtro,$NoDepartamento){

        switch ($opcion){
            case 1:
                //Solo Activos
                $where1 = " a.NoEstatus = 1 ORDER BY c.Descripcion,b.Descripcion,a.Descripcion DESC ";
                break;
            case 2:
                //Solo Inactivos
                $where1 = " a.NoEstatus = 0 ORDER BY c.Descripcion,b.Descripcion,a.Descripcion DESC ";
                break;
            case 3:
                //Todos los estados
                $where1 = "a.NoEstatus >= 0 ORDER BY c.Descripcion,b.Descripcion,a.Descripcion DESC ";
                break;
            case 4:
                //ultimos 10 registrados
                $where1 = " a.FechaAlta = '".date("Ymd")."' ORDER BY c.Descripcion,b.Descripcion,a.Descripcion DESC ";
                break;
            case 5:
                //ultimos 10 actualizados
                $where1 = " a.FechaUM = '".date("Ymd")."' ORDER BY c.Descripcion,b.Descripcion,a.Descripcion DESC ";
                break;
        }

        if($filtro == 1){
            $Where2 = " ";
        }else{
            $Where2 = " a.NoDepartamento = '$NoDepartamento' AND";
        }

        $this->_query = "SELECT 
                            a.nocategoria,a.descripcion,a.NoArea,b.Descripcion,a.NoDepartamento,c.Descripcion,a.fechaalta,a.fechaum,
                            a.NoUsuarioAlta,a.NoUsuarioUM,a.HoraAlta,a.HoraUM,a.NoEstatus  
                            FROM BSHCatalogoCategoria as a 
                            LEFT JOIN BSHCatalogoAreas as b
                            ON a.NoArea = b.NoArea AND b.NoDepartamento = a.NoDepartamento
                            LEFT JOIN BGECatalogoDepartamentos as c 
                            ON a.NoDepartamento = c.NoDepartamento 
                          WHERE ".$Where2.$where1." ";



        $this->get_result_query();



    }

    public function set($data_categoria = array()){

        // funcion para registrar las areas

        if(array_key_exists('nombre_categoria',$data_categoria) ||array_key_exists('NoDepartamento',$data_categoria)){
            //validar que no exista ya el area
            unset($this->_rows) ;

            $this->_query = "SELECT descripcion FROM BSHCatalogoCategoria WHERE descripcion = '$data_categoria[nombre_categoria]' AND NoArea = '$data_categoria[NoArea]' AND NoDepartamento = '$data_categoria[NoDepartamento]' ";

            $this->get_result_query();


            if(count($this->_rows) >= 1){

                $this->_confirm = false ;
                $this->_message = "La categoría ya existe";

            }else{
                // si el puesto no existe se procede a dar de alta
                unset($this->_rows) ;
                $FechaAlta = date("Ymd");
                $HoraAlta = date("H:i:s");

                $this->_query = "SELECT count(nocategoria)+1 as total FROM BSHCatalogoCategoria WHERE  NoDepartamento = '$data_categoria[NoDepartamento]' ";

                $this->get_result_query();

                $NoCategoria = $this->_rows[0]['total'];

                unset($this->_rows) ;
                $NoUsuario = $_SESSION['data_login']['NoUsuario'];


                $this->_query = "
                    INSERT INTO BSHCatalogoCategoria (
                    nocategoria,
                    NoDepartamento,
                    descripcion,
                    fechaalta,
                    fechaum,
                    NoArea,
                    NoUsuarioAlta,
                    NoUsuarioUM,
                    HoraAlta,
                    HoraUM,
                    NoEstatus
                    ) 
                    VALUES (
                    '$NoCategoria',
                    '$data_categoria[NoDepartamento]',
                    '$data_categoria[nombre_categoria]',
                    '$FechaAlta',
                    '$FechaAlta',
                    '$data_categoria[NoArea]',
                    '$NoUsuario',
                    '$NoUsuario',
                    '$HoraAlta',
                    '$HoraAlta',
                    '1'
                    )";

                $this->execute_query();
                $this->_confirm = true ;
                $this->_message = "se registro la categoría correctamente";

            }


        }else{
            $this->_message = "No se encontro el campo de Nombre de la categoría";
            $this->_confirm = false;
        }

    }

    public function edit($data_categoria = array()){

        // funcion para editar las categorias

        $FechaUM = date("Ymd");
        $HoraUM = date("H:i:s");

        if(array_key_exists('nombre_categoria', $data_categoria) || array_key_exists('nocategoria',$_POST) ||array_key_exists('NoDepartamento',$_POST) || array_key_exists('NoArea',$data_categoria)){

            // validar que no exista ya el nuevo nombre del área
            unset($this->_rows) ;

            $this->_query = "SELECT descripcion FROM BSHCatalogoCategoria WHERE descripcion = '$data_categoria[nombre_categoria]' AND nocategoria != '$data_categoria[nocategoria]' AND NoArea = '$data_categoria[NoArea]' AND NoDepartamento != '$data_categoria[NoDepartamento]' ";
            $this->get_result_query();

            $NoUsuario = $_SESSION['data_login']['NoUsuario'];


            if(count($this->_rows) >= 1){

                $this->_confirm = false ;
                $this->_message = "La categoría ya existe";

            }else{
                unset($this->_rows) ;

                $this->_query = "UPDATE BSHCatalogoCategoria 
                                  SET descripcion = '$data_categoria[nombre_categoria]',
                                      NoDepartamento = '$data_categoria[NoDepartamento]',
                                      NoArea = '$data_categoria[NoArea]',
                                      NoEstatus = '$data_categoria[NoEstatus]',
                                      FechaUM = '$FechaUM',
                                      HoraUM = '$HoraUM',
                                      NoUsuarioUM = '$NoUsuario'
                                  WHERE nocategoria = '$data_categoria[nocategoria]' AND NoArea = '$data_categoria[NoArea]' AND NoDepartamento = '$data_categoria[NoDepartamento]' ";



                $this->execute_query();
                $this->_confirm = true ;
                $this->_message = "categoría editado correctamente";
            }

        }else{
            $this->_confirm = false;
            $this->_message = "Error";
        }
    }

    public function get($idCategoria,$NoArea,$NoDepartamento = null){
        //funcion para  traer la informacion de las categorías;

        if($idCategoria != '' || $NoArea = ''){

            if($NoDepartamento == NULL){
                $NoDepartamento = $_SESSION['data_departamento']['NoDepartamento'];
            }

            $this->_query = "SELECT 
                                a.nocategoria,
                                a.descripcion,
                                a.NoDepartamento,
                                a.NoArea,
                                c.Descripcion as NombreArea,
                                b.Descripcion as NombreDepartamento,
                                a.fechaalta,
                                a.fechaum,
                                a.NoUsuarioAlta,
                                a.NoUsuarioUM,
                                a.HoraAlta,
                                a.HoraUM,
                                a.NoEstatus
                            FROM
                                BSHCatalogoCategoria as a 
                                LEFT JOIN BGECatalogoDepartamentos as b 
                                ON a.NoDepartamento = b.NoDepartamento 
                                LEFT JOIN BSHCatalogoAreas as c 
                                ON a.NoArea = c.NoArea AND a.NoDepartamento = c.NoDepartamento 
                            WHERE
                                a.nocategoria = '$idCategoria' AND a.NoArea = '$NoArea'
                                    AND a.NoDepartamento = '$NoDepartamento' ORDER BY a.descripcion ASC ";

            $this->get_result_query();

        }

        if(count($this->_rows) == 1){

            foreach ($this->_rows[0] as $campo => $valor){
                $this->$campo = $valor ;
            }

            $this->_confirm = true ;
            $this->_message = "Se encontro la categoría";

        }else{
            $this->_confirm = false ;
            $this->_message = "No se encontro la categorías" ;
        }
    }

}