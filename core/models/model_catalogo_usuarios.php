<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 24/01/2017
 * Time: 09:44 AM
 */

namespace core\models;

use core\contenido;

include 'core\contenido.php';

class model_catalogo_usuarios extends contenido
{

    public function saludo($msg){
        echo $msg;
    }

}