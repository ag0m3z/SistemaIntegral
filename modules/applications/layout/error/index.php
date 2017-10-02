<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 24/01/2017
 * Time: 12:12 PM
 */

switch ($_REQUEST['error']){
    case md5(1):
       include "sys_view_error_connect.inc";
        break;
    case md5(2):
        echo "Error 2";
        break;
    case md5(3):
        include "sys_view_time_out.inc";
        break;
}