<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 24/01/2017
 * Time: 12:20 PM
 */

namespace core;


class views
{
    private $_module ;
    private $_view ;
    private $_nameView ;


    public function call_view($data_view = array()){

        $this->_module = $data_view[0] ;
        $this->_view = $data_view[1] ;
        $this->_nameView = $data_view[2].".php" ;

        if(views::isValid()){

            views::load();
        }

    }

    public function load(){

        include "modules/$this->_module/views/$this->_view/$this->_nameView";

    }

    public function isValid(){

        $valid=false;

        if(file_exists($file =  "modules/$this->_module/views/$this->_view/$this->_nameView")){
            $valid = true;
        }else{
            views::Error("Error la vista solicitada no existe");
        }

        return $valid;

    }

    public function Error($message){
        print  $message ;
    }

}