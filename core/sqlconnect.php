<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 21/02/2017
 * Time: 04:35 PM
 */

namespace core;


class sqlconnect
{

    //private $_hostname  = '192.168.2.183\\SQLVIRTUAL';
    private $_hostname = '192.168.2.8';


    private $_mssConexion;
    public $_sqlConfirm = false;
    public $_sqlQuery = '';
    public $_sqlRows = array();
    public $_sqlMensaje = '';

    private $_data_conexion = array(
        "Database" => "BDSPAPARATOS",
        "Uid" => "sa",
        "PWD" => "masterkey",
        "CharacterSet" =>"UTF-8"
    );

    public function __construct()
    {



        $this->_mssConexion = sqlsrv_connect($this->_hostname,$this->_data_conexion);

        if( !$this->_mssConexion ) {            //error en la conexion con la base de datos

            $this->_sqlConfirm = false;
            core::MyAlert("Error en la conexion con: " . $this->_data_conexion['Database']);
            exit;

        }else{
            // conexion exitosa a la base de datos

            $this->_sqlConfirm = true;

        }

    }


    public function execute_msql_upload_file($query,$parametros ){

        $uploadPic = sqlsrv_prepare($this->_mssConexion, $query, $parametros);
        $cons = sqlsrv_execute($uploadPic);

        if( !$cons ) {
            die( print_r( sqlsrv_errors(), true));
        }


    }

    public function execute_query(){

        sqlsrv_query($this->_mssConexion,$this->_sqlQuery);

    }

    public function query(){

        if(!$getResults = sqlsrv_query($this->_mssConexion,$this->_sqlQuery)){

            exit;

        }else{

            return $getResults;
        }
    }

    public function get_result_query(){
        unset($this->_sqlRows);

        $getResults = sqlsrv_query($this->_mssConexion,$this->_sqlQuery);

        if( $getResults === false) {
            die( print_r( sqlsrv_errors(), true) );
        }

        while( $this->_sqlRows[] = sqlsrv_fetch_array( $getResults));
        sqlsrv_free_stmt( $getResults);
        array_pop($this->_sqlRows) ;

    }




    public function __destruct()
    {
        // TODO: Implement __destruct() method.

        sqlsrv_close($this->_mssConexion);

    }

}