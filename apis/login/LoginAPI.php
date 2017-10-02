<?php

class LoginAPI {

    public function API(){

        header('Content-Type: application/JSON; charset-UTF8');
        header("Access-Control-Allow-Origin: *");

        $method = $_SERVER['REQUEST_METHOD'];
        $posData = $_POST;

        switch ($method) {

            case 'GET'://consulta
                   $this->getPeoples($posData);
                break;
            case 'POST':
                $this->getPeoples($posData);
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

    function getPeoples($data){
        $db = new LoginBD();


        if(isset($data)){

            $request = json_decode($data);

            $Usuario = $request->username;
            $Password = $request->password;

            if($Usuario != "" && $Password != ""){

                $response = $db->getLogin($Usuario,$Password);

                if(count($response) > 0){

                    echo json_encode($response);

                }else{
                    header('HTTP/1.0 401 Unauthorized');
                }


            }else{
                header('HTTP/1.0 401 Unauthorized');
            }



        }else{
            header('HTTP/1.0 401 Unauthorized');
        }
 }

}//end class