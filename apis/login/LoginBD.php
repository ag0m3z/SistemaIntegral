<?php

/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 14/03/2017
 * Time: 05:55 PM
 */
class LoginBD
{

    protected $mysqli;

    const LOCALHOST = '192.168.2.55';
    const USER = 'pexpress';
    const PASSWORD = 'Masterkey.88';
    const DATABASE = 'SINTEGRALGNL';

    /**
     * Constructor de clase
     */
    public function __construct() {
        try{
            //conexión a base de datos
            $this->mysqli = new mysqli(self::LOCALHOST, self::USER, self::PASSWORD, self::DATABASE);
            $this->mysqli->query("SET NAMES 'utf8'");
        }catch (mysqli_sql_exception $e){
            //Si no se puede realizar la conexión
            http_response_code(500);
            exit;
        }
    }

    /**
     * obtiene un solo registro dado su ID
     * @param int $id identificador unico de registro
     * @return Array array con los registros obtenidos de la base de datos
     */
    public function getLogin($usuario,$clave){
        $stmt = $this->mysqli->prepare("SELECT NoUsuario,NombreDePila FROM BGECatalogoUsuarios WHERE UsuarioLogin= '$usuario' and PassLogin = '".md5($clave)."' ; ");
        $stmt->execute();
        $result = $stmt->get_result();
        $peoples = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $peoples;
    }



}