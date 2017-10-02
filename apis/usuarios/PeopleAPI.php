<?php

class PeopleAPI {

    public function API(){

        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {

            case 'GET'://consulta
                   $this->getPeoples();
                break;

            case 'POST'://inserta
                echo 'POST';
                break;

            case 'PUT'://actualiza
                echo 'PUT';
                break;

            case 'DELETE'://elimina
                echo 'DELETE';
                break;

            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

    function response($code=200, $status="", $message="") {
        http_response_code($code);
        if( !empty($status) && !empty($message) ){
            $response = array("status" => $status ,"message"=>$message);
            echo json_encode($response,JSON_PRETTY_PRINT);
        }
    }

    function getPeoples(){
        $db = new PeopleDB();

        if($_GET['type'] == '1'){


            if(isset($_GET['id'])){
                //muestra 1 solo registro si es que existiera ID
                $response = $db->getPeople($_GET['id']);
                echo json_encode($response,JSON_PRETTY_PRINT);
             }else{
                //muestra todos los registros

                $response = $db->getPeoples();
                 echo json_encode($response,JSON_PRETTY_PRINT);
             }
     }else{
            $response = array("title"=>"Sistema Integral","modules"=>"apis.sintegal.srv","status" => '400' ,"message"=>'error, metodo no soportado');
            echo json_encode($response,JSON_PRETTY_PRINT);
     }
 }

}//end class