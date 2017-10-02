<?php

/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 26/04/2017
 * Time: 04:26 PM
 */
include "../../../../core/sqlconnect.php";

class ClassController extends \core\sqlconnect
{
    protected $idCaracteristica;
    protected $idCategoria;
    protected $NombreCategoria;
    protected $Descripcion;
    protected $Orden;
    protected $Estatus;

    public function getIDCaracteristica(){return $this->idCaracteristica;}
    public function getIDCategoria(){return $this->idCategoria;}
    public function getNombreCategoria(){return $this->NombreCategoria;}
    public function getNombreCaracteristica(){return $this->Descripcion;}
    public function getIDOrden(){return $this->Orden;}
    public function getIDEstatus(){return $this->Estatus;}

    public function set_caracteristicas ($data_array){

        if(array_key_exists('nombre_caracteristica',$_POST) || array_key_exists('NoCategoria',$_POST)){

            //Validar que no exista ya la caracteristica
            $this->_sqlQuery = " SELECT Descripcion FROM SAyT.dbo.INVProdCaracteristica WHERE idCategoria = '$data_array[NoCategoria]' AND Descripcion = '$data_array[nombre_caracteristica]' ";
            $this->get_result_query();

            if(count($this->_sqlRows) >= 1){
                //Ya se encuentra una caracteristica con este nombre
                $this->_sqlConfirm = false;
                $this->_sqlMensaje = "Ya se encuentra una caracteristica con este nombre";

            }else{
                $this->_sqlQuery = " SELECT MAX(idCaracteristica)+1 FROM SAyT.dbo.INVProdCaracteristica ";
                $this->get_result_query();

                $idCaracteristica = $this->_sqlRows[0][0];

                if($idCaracteristica > 0){

                    $this->_sqlQuery = "
                    INSERT INTO SAyT.dbo.INVProdCaracteristica 
                    VALUES ('$idCaracteristica','$data_array[NoCategoria]','$data_array[nombre_caracteristica]','$data_array[Orden]','$data_array[Estatus]')
                    ";

                    $this->execute_query();
                    $this->_sqlConfirm = true;
                    $this->_sqlMensaje = "caracteristica registrada correctamente";

                }else{
                    $this->_sqlConfirm = false;
                    $this->_sqlMensaje = "Ocurrio un error al extraer el ultimo id";

                }

            }


        }else{
            $this->_sqlConfirm = false;
            $this->_sqlMensaje = "Error no se contraron los datos para procesar";
        }


    }

    public function get_caracteristicas($idCaracteristica){

        if($idCaracteristica != ''){


            $this->_sqlQuery = "SELECT a.idCaracteristica,a.idCategoria,b.NombreCategoria,a.Descripcion,a.Orden,a.Estatus 
            FROM SAyT.dbo.INVProdCaracteristica as a 
            LEFT JOIN BDSPSAYT.dbo.BPFCatalogoCategoriasPrestamo as b 
            ON a.idCategoria = b.NoCategoria 
            WHERE a.idCaracteristica = '$idCaracteristica' ";

            $this->get_result_query();

        }

        if(count($this->_sqlRows) == 1){

            foreach ($this->_sqlRows[0] as $campo => $valor){
                $this->$campo = $valor ;
            }

            $this->_sqlConfirm = true ;
            $this->_sqlMensaje = "Se encontro el área";

        }else{
            $this->_sqlConfirm = false ;
            $this->_sqlMensaje = "No se encontro el área" ;
        }


    }

    public function edit_caracteristicas($data_array){

        if(array_key_exists('idcaracteristica', $data_array) || array_key_exists('NoCategoria',$data_array) || array_key_exists('nombre_caracteristica',$data_array)){

            // validar que no exista ya el nuevo nombre del área
            unset($this->_sqlRows) ;

            $this->_sqlQuery = "SELECT Descripcion FROM SAyT.dbo.INVProdCaracteristica WHERE Descripcion = '$data_array[nombre_caracteristica]' AND idCaracteristica != '$data_array[idcaracteristica]' ";
            $this->get_result_query();


            if(count($this->_rows) >= 1){

                $this->_sqlConfirm = false ;
                $this->_sqlMensaje = "La caracteristica ya existe";

            }else{
                unset($this->_rows) ;

                $this->_sqlQuery =
                    "
                    UPDATE SAyT.dbo.INVProdCaracteristica 
                      SET 
                        Descripcion = '$data_array[nombre_caracteristica]',
                        idCategoria = '$data_array[NoCategoria]',
                        Orden = '$data_array[Orden]',
                        Estatus = '$data_array[Estatus]' 
                    WHERE idCaracteristica = '$data_array[idcaracteristica]' ";

                $this->execute_query();
                $this->_sqlConfirm = true ;
                $this->_sqlMensaje = "Caracteristica editado correctamente";
            }

        }else{
            $this->_sqlConfirm = false;
            $this->_sqlMensaje = "Error";
        }

    }

    public function get_list($opcion){

        switch ($opcion){
            case 1:
                //Solo Activos
                $where1 = " WHERE a.Estatus = 1 ORDER BY b.NombreCategoria,a.Descripcion ASC ";
                break;
            case 2:
                //Solo Inactivos
                $where1 = "WHERE a.Estatus = 0 ORDER BY b.NombreCategoria,a.Descripcion ASC";
                break;
        }

        $this->_sqlQuery = "SELECT a.idCaracteristica,a.idCategoria,b.NombreCategoria,a.Descripcion,a.Orden,a.Estatus 
        FROM SAyT.dbo.INVProdCaracteristica as a 
        LEFT JOIN BDSPSAYT.dbo.BPFCatalogoCategoriasPrestamo as b ON a.idCategoria = b.NoCategoria 
          ".$where1." ";
        $this->get_result_query();

    }

}