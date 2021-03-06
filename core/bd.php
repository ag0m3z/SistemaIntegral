<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 23/01/2017
 * Time: 05:19 PM
 */

namespace core;

abstract class bd
{

    public $INFO = "#00C0EF"; //Color CYAN
    public $PRIMARY = "#3C8DBC"; //Color PRIAMRY
    public $SUCCESS = "#00A65A"; //Color SUCCESS
    public $WARNING = "#F39C12"; //Color WARNING
    public $DANGER = "#DD4B39"; //Color DANGER

    private $conexion ;

    /*private $dataSource = array(
        "diver"=>"Oracle Mysql Server",
        "bdHost"=>"192.168.2.20",
        "bdUser"=>"pexpress",
        "bdPass"=>"Masterkey.88",
        "bdData"=>"SINTEGRALGNL",
        "port"=>"3306"
    );*/


  private $dataSource = array(
        "diver"=>"Oracle Mysql Server",
        "bdHost"=>"192.168.2.55",
        "bdUser"=>"pexpress",
        "bdPass"=>"M@st3rkey",
        "bdData"=>"SINTEGRALGNL",
        "port"=>"3306"
    );

    /*private $dataSource = array(
        "diver"=>"Oracle Mysql Server",
        "bdHost"=>"localhost",
        "bdUser"=>"root",
        "bdPass"=>"",
        "bdData"=>"SINTEGRALGNL",
        "port"=>"3306"
    );*/



    // atributos publicos
    public $_query ;
    public $_rows = array();
    public $_confirm = false ;
    public $_message = "Welcome";

    public function __construct($data_base = 'SINTEGRALGNL',$data_server = array('bdHost'=>'','bdUser'=>'','bdPass'=>'','bdData'=>'','port'=>''))
    {

        $this->dataSource['bdData'] = $data_base;

        $this->conexion = new \mysqli(
            $this->dataSource['bdHost'],
            $this->dataSource['bdUser'],
            $this->dataSource['bdPass'],
            $this->dataSource['bdData'],
            $this->dataSource['port']
        ) ;

        if ($this->conexion->connect_errno) {

            $this->_message = "Connect failed: ". $this->conexion->connect_error ;
            $this->_confirm = false;


            $fecha = date("Ymd");
            $nombre_archivo = "log_connection_".$fecha.".txt";

            file_exists("modules/applications/logs/".$nombre_archivo);

            if($archivo = fopen("modules/applications/logs/".$nombre_archivo, "a"))
            {
                fwrite($archivo, date("Y/m/d H:m:s"). " ". $this->_message. "\n");

                fclose($archivo);
            }

            // error de conexion
            echo "<script> location.href = 'modules/applications/layout/error/?error=".md5(1)." '; </script>";

            exit();

        }

        $this->conexion->query("SET NAMES 'utf8'");

        $this->_message = "conexion exitosa";
    }

    //funciones para sanatizar las consultas
    private function clean_input($input) {

        $search = array(
            '@<script[^>]*?>.*?</script>@si',   // Elimina javascript
            '@<[\/\!]*?[^<>]*?>@si',            // Elimina las etiquetas HTML
            '@<style[^>]*?>.*?</style>@siU',    // Elimina las etiquetas de estilo
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Elimina los comentarios multi-l�nea
        );

        $output = preg_replace($search, '', $input);
        return $output;
    }


    public function get_sanatiza($input) {

        if (is_array($input)) {
            foreach($input as $var=>$val) {
                $output[$var] = $this->get_sanatiza($val);
            }
        }
        else {
            if (get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }
            $input  = $this->clean_input($input);
            $output =   $this->conexion->real_escape_string($input);
        }
        return $output;
    }

    public function get_escape_mysql($data){

        return $this->conexion->real_escape_string($data);

    }


    // funcion para ejecutar solomante el query sin guardar el resultado
    public function execute_query(){


        if (!$this->conexion->query($this->_query)) {

            $this->_message = "Mesaje de error: " . $this->conexion->errno. " " . $this->conexion->error ;
            $this->_confirm = false;

            $fecha = date("Ymd");
            $nombre_archivo = "log_consultas_".$fecha.".txt";

            file_exists("/SistemaIntegral/modules/applications/logs/".$nombre_archivo);

            if($archivo = fopen("/SistemaIntegral/modules/applications/logs/".$nombre_archivo, "a"))
            {
                fwrite($archivo, date("Y/m/d H:m:s"). " ". $this->_message. "\n");

                fclose($archivo);
            }

            //error de consulta
            echo "<script> MyAlert('Error al realizar la Consulta <br>idError:".$this->conexion->errno." <br>message:<br> ". addslashes($this->conexion->error) ." ','alert' ); </script>";
            $this->_confirm = false;
            exit();

        }
    }

    public function get_result_multi_query(){

        unset($this->_rows);

        if (!$this->conexion->multi_query($this->_query)) {
            $this->_message = "Mesaje de error: " . $this->conexion->errno. " " . $this->conexion->error ;
            $this->_confirm = false;

            $fecha = date("Ymd");
            $nombre_archivo = "log_consultas_".$fecha.".txt";

            file_exists("/SistemaIntegral/modules/applications/logs/".$nombre_archivo);

            if($archivo = fopen("/SistemaIntegral/modules/applications/logs/".$nombre_archivo, "a"))
            {
                $fecha_hora =date("Y/m/d H:m:s");
                fwrite($archivo,$fecha_hora . " ". $this->_message. "\n");

                fclose($archivo);
            }
        }

        do {
            if ($resultado = $this->conexion->store_result()) {
                $this->_rows[] = $resultado->fetch_all();
                $resultado->free();

            } else {
                if ($this->conexion->errno) {
                    echo "Store failed: (" . $this->conexion->errno . ") " . $this->conexion->error;
                }
            }
        } while ($this->conexion->more_results() && $this->conexion->next_result());
    }

    public function get_result_query($assoc = false, $json = false){

        unset($this->_rows);

        if(!$result = $this->conexion->query($this->_query)){

            $this->_message = "Mesaje de error: " . $this->conexion->errno. " " . $this->conexion->error ;
            $this->_confirm = false;

            $fecha = date("Ymd");
            $nombre_archivo = "log_consultas_".$fecha.".txt";

            file_exists("/SistemaIntegral/app/modules/applications/logs/".$nombre_archivo);

            if($archivo = fopen("/SistemaIntegral/app/modules/applications/logs/".$nombre_archivo, "a"))
            {
                $fecha_hora =date("Y/m/d H:m:s");
                fwrite($archivo,$fecha_hora . " ". $this->_message. "\n");

                fclose($archivo);
            }

            //error de consulta

            exit();

        }else{


            if($assoc){
                while($this->_rows[] = $result->fetch_assoc()) ;
            }else{
                while($this->_rows[] = $result->fetch_array()) ;
            }


            $this->conexion->next_result();  //Prepara el siguiente juego de resultados de una llamada
            $result->free_result(); //Libera la memoria asociada al resultado.
            array_pop($this->_rows) ;

            if($json){
                for($i=0;$i < count($this->_rows);$i++){

                    $data[] =$this->_rows[$i];

                }

                $this->_rows = json_encode($data);
            }

        }
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.

        $this->conexion->close();
    }

}