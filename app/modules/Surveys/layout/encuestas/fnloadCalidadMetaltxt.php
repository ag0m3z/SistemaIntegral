<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 2/11/15
 * Time: 11:46 AM
 */

if($_POST['tposerv']<> 3){

    if($_POST['categoria'] == 1){
        echo "Calidad Oro:";
    }elseif($_POST['categoria'] == 7 ){
        echo "Calidad Plata: ";
    }
}
