<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 09/08/2017
 * Time: 01:56 PM
 */
/**
 * Incluir las Librerias Principales del Sistema
 * En el Siguiente Orden ruta de libreias: @@/SistemaIntegral/core/
 *
 * 1.- core.php;
 * 2.- sesiones.php
 * 3.- seguridad.php o modelo ( ej: model_aparatos.php)
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 *
 * Ejemplo:
 * Si se requiere cambiar de servidor de base de datos
 * $data_server = array(
 *   'bdHost'=>'192.168.2.5',
 *   'bdUser'=>'sa',
 *   'bdPass'=>'pasword',
 *   'port'=>'3306',
 *   'bdData'=>'dataBase'
 *);
 *
 * Si no es requerdio se puede dejar en null
 *
 * con @data_server
 * @@$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos'],$data_server);
 *
 * Sin @data_server
 * @@$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 *
 * @@$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

/**@@ Vacriar Variable el cual contiene los datos de la ultima exportacion de
 **   Cualquier reporte
 **/
unset($_SESSION['EXPORT']);
?>
<script>
    setOpenModal("mdlNuevoVideo");
    $(".select2").select2();
</script>
<div class="modal fade" id="mdlNuevoVideo" data-keyboard="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><i class="fa fa-file"></i> Nuevo Video</div>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    Compañias
                    <select multiple class=" form-control input-sm select2" style="width: 100%;border-radius:5px; ">
                        <option value="1">Prestamo Express</option>
                        <option value="1">Bienes Express</option>
                        <option value="1">Productora Express</option>

                    </select>
                </div>

                <div class="form-group">
                    Tipo Departamento
                    <select multiple class=" form-control input-sm select2" style="width: 100%;border-radius:5px; ">
                        <option value="1">Sucursales</option>
                        <option value="2">Departamentos</option>
                    </select>
                </div>

                <div class="form-group">
                    Titulo
                    <input class="form-control input-sm" placeholder="Titulo del video">
                </div>

                <div class="form-group">
                    Descripcion
                    <textarea class="form-control input-sm" placeholder="Descripción del video"></textarea>
                </div>

                <div class="form-group">
                    Url o Archivo
                    <input  class="form-control input-sm" placeholder="Url o Archivo">
                </div>

            </div>
            <div class="modal-footer">

                <button class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-default btn-sm"><i class="fa fa-paperclip"></i> Adjuntar</button>
                <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Salir</button>

            </div>
        </div>
    </div>
</div>
