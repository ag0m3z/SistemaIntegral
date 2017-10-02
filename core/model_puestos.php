<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 04/04/2017
 * Time: 11:54 AM
 */

namespace core;
include "seguridad.php";

class model_puestos extends seguridad
{
    protected $OpcCatalogo;
    protected $Descripcion;
    protected $Texto1 ;


    public function getIdPuesto(){
        return $this->OpcCatalogo;
    }
    public function getNombrePuesto(){

        return $this->Descripcion;
    }
    public function getDescripcionPuesto(){
        return $this->Texto1;
    }


    public function set($datos_puesto = array()){
        // funcion para registrar nuevos puestos

        if(array_key_exists('nombre_puesto',$datos_puesto)){
            //validar que no exista ya el puesto
            unset($this->_rows) ;

            $this->_query = "SELECT Descripcion FROM BGECatalogoGeneral WHERE Descripcion = '$datos_puesto[nombre_puesto]' AND CodCatalogo = 28 ";

            $this->get_result_query();


            if(count($this->_rows) >= 1){

                $this->_confirm = false ;
                $this->_message = "El Puesto ya existe";

            }else{
                // si el puesto no existe se procede a dar de alta
                unset($this->_rows) ;
                $FechaAlta = date("Ymd");
                $HoraAlta = date("H:i:s");

                $this->_query = "SELECT count(OpcCatalogo)+1 as total FROM BGECatalogoGeneral WHERE CodCatalogo = 28";

                $this->get_result_query();

                $Total_puesto = $this->_rows[0]['total'];

                unset($this->_rows) ;
                $NoUsuario = $_SESSION['data_login']['NoUsuario'];


                $this->_query = "
                    INSERT INTO BGECatalogoGeneral (
                    CodCatalogo,
                    OpcCatalogo,
                    Descripcion,
                    Texto1,
                    Numero1,
                    Numero2,
                    NoEstatus,
                    NoUsuarioAlta,
                    NoUsuarioUM,
                    FechaAlta,
                    HoraAlta,
                    FechaUM,
                    HoraUM
                    ) 
                    VALUES (
                    28,
                    '$Total_puesto',
                    '$datos_puesto[nombre_puesto]',
                    '$datos_puesto[descripcion_puesto]',
                    0,
                    0,
                    1,
                    '$NoUsuario',
                    '$NoUsuario',
                    '$FechaAlta',
                    '$HoraAlta',
                    '$FechaAlta',
                    '$HoraAlta'
                    )";

                $this->execute_query();
                $this->_confirm = true ;
                $this->_message = "se registro el puesto correctamente";



            }


        }else{
            $this->_message = "No se encontro el campo de Nombre del pueso";
            $this->_confirm = false;
        }
    }

    public function edit($datos_puesto = array()){
        // funcion para editar los puestos

        $FechaUM = date("Ymd");
        $HoraUM = date("H:i:s");

        if(array_key_exists('nombre_puesto', $datos_puesto) || array_key_exists('idpuesto',$datos_puesto)){

            // validar que no exista ya el nuevo nombre del puesto
            unset($this->_rows) ;

            $this->_query = "SELECT Descripcion FROM BGECatalogoGeneral WHERE Descripcion = '$datos_puesto[nombre_puesto]' AND OpcCatalogo != '$datos_puesto[idpuesto]' AND CodCatalogo = 28 ";

            $this->get_result_query();

            $NoUsuario = $_SESSION['data_login']['NoUsuario'];


            if(count($this->_rows) >= 1){

                $this->_confirm = false ;
                $this->_message = "El Puesto ya existe";

            }else{
                unset($this->_rows) ;

                $this->_query = "UPDATE BGECatalogoGeneral 
                                  SET Descripcion = '$datos_puesto[nombre_puesto]',
                                      Texto1 = '$datos_puesto[descripcion_puesto]',
                                      FechaUM = '$FechaUM',
                                      HoraUM = '$HoraUM',
                                      NoUsuarioUM = '$NoUsuario'
                                  WHERE CodCatalogo = 28 AND OpcCatalogo = '$datos_puesto[idpuesto]' AND Numero1 = 0 AND Numero2 = 0 ";



                $this->execute_query();
                $this->_confirm = true ;
                $this->_message = "Puesto editado correctamente";
            }

        }else{
            $this->_confirm = false;
            $this->_message = "Error";
        }
    }

    public function get($idPuesto = ''){
        //funcion para  traer la informacion de los puestos;

        if($idPuesto != ''){

            $this->_query = "SELECT OpcCatalogo,Descripcion,Texto1 FROM BGECatalogoGeneral WHERE CodCatalogo = 28 AND OpcCatalogo = '$idPuesto' AND NoEstatus = 1 ";

            $this->get_result_query();

        }

        if(count($this->_rows) == 1){

            foreach ($this->_rows[0] as $campo => $valor){
                $this->$campo = $valor ;
            }

            $this->_confirm = true ;
            $this->_message = "Se encontro el Puesto";

        }else{
            $this->_confirm = false ;
            $this->_message = "No se encontro el Puesto" ;
        }

    }

    //Listar Los Puestos
    public function get_list($id_estatus = 1){

        unset($this->_rows);
        $this->_query = "SELECT 
                            a.OpcCatalogo,a.Descripcion,a.Texto1,a.NoEstatus,b.NombreDePila as NoUsuarioAlta,
                            c.NombreDePila as NoUsuarioUM,a.FechaAlta,a.HoraAlta,a.FechaUM,a.HoraUM 
                          FROM BGECatalogoGeneral as a 
                          LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b 
                          ON a.NoUsuarioAlta = b.NoUsuario 
                          LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c 
                          ON a.NoUsuarioUM = c.NoUsuario
                          WHERE a.CodCatalogo = 28 AND a.NoEstatus = '$id_estatus' ";

        $this->get_result_query();


    }

}