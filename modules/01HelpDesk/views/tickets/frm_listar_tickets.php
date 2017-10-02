<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 01/02/2017
 * Time: 01:00 PM
 */
/**
 * Incluir las Librerias Principales del Sistema
 * En el Siguiente Orden
 * ruta de libreias: @@/SistemaIntegral/core/
 *
 * 1.- core.php;
 * 2.- sesiones.php
 * 3.- seguridad.php
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

/**
 * 1.- Instanciar la Clase seguridad y pasar como valor la BD del Usuario
 * 2.- Llamar al metodo @@valida_session_id($NoUsuario), para validar que el usuario este conectado y con una sesión valida
 * Ejemplo:
 * @@ $seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);
 * @@ $seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
 */

$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);

//validar session del usuario
$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);
$MyDepartamento = $_SESSION['data_departamento']['NoDepartamento'];
$OpenModal = "";
if($_REQUEST['dat'] < 1){$_REQUEST['dat']=0;} // Parametros para Mostrar el Filtro

if($_REQUEST['state'] == 4){
    echo "<script language='JavaScript'>fnsdAbrirModalTicket(5,'".$_REQUEST['fl']."','".$_REQUEST['anio']."','".$_REQUEST['dpto']."');</script>";
}

/**
 *
 * if($_SESSION['MenuOpciones'][1][2][1][0]['OpcionA'] == 1){
 * $connect->CargarBotones("alta");
 * }
 * if($_SESSION['MenuOpciones'][1][2][1][0]['OpcionB'] == 1){
 * $connect->CargarBotones("baja");
 * }
 * if($_SESSION['MenuOpciones'][1][2][1][0]['OpcionC'] == 1){
 * $connect->CargarBotones("cambio");
 * }
 * if($_SESSION['MenuOpciones'][1][2][1][0]['OpcionV'] == 1){
 * $connect->CargarBotones("vista");
 * }
 *
 */

?>
<!-- Inicio de Scripts -->
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsServiceDesk.js"></script>
<script language="javascript">
    $(document).ready(function(){

        init_botons();
        fnsdListarTickets(<?=$_REQUEST['dat']?>,'<?=$_REQUEST['dpto']?>','<?=$_REQUEST['nameDpto']?>');

    });
</script>
<!-- END Scripts -->
<div id="ModalTicket"></div>

<!-- Inicio de panel principal -->
<div class="panel panel-info margin-bottom-none">
    <div class="panel-heading padding-x3">
        <i class="fa fa-list-alt"></i> Registro de Tickets
        <span id="txt_nombre_departamento" class="pull-right text-info"><?=$_SESSION['data_departamento']['NombreDepartamento']?></span>
    </div>
    <div class="toolbars">
        <button class="btn btn-primary btn-xs waves-effect" id="btnReload" onclick="fnsdMenu(3,0);"><span class="fa fa-refresh"></span></button>
        <div class="btn-group" data-opcion="vista">
            <button class="btn btn-primary btn-xs waves-effect dropdown" data-toggle="dropdown"><i class="fa fa-filter"></i> Filtrar Tickets Por <i class="fa fa-caret-down"></i></button>
            <ul class="dropdown-menu" role="menu">
                <li role="presentation" class="dropdown-header bg-gray">Mis Tickets</li>
                <?php
                if($_SESSION['data_departamento']['AsignarReportes'] == 'NO'){
                    // Botones para Perfil Solicitante

                    $seguridad->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE AsignarReportes = 'SI' ORDER BY Descripcion ASC";
                    $seguridad->get_result_query();
                    $departamentos = $seguridad->_rows;
                    for($i=0;$i < count($departamentos);$i++){
                        echo  "<li><a href='#dat=1' onclick='fnsdMenu(3,\"dat=13&nameDpto=".$departamentos[$i][1]."&dpto=".$departamentos[$i][0]."\")'>".$departamentos[$i][1]."</a></li>";
                    }


                    ?>

                    <?php
                }else{
                    // Botones para Perfil Tecnico
                    ?>
                    <li><a href="#dat=1" onclick="fnsdMenu(3,'dat=1')"> Tickets Pendientes</a></li>
                    <li><a href="#dat=2" onclick="fnsdMenu(3,'dat=2')"> Tickets Cerrados</a></li>
                    <li><a href="#dat=3" onclick="fnsdMenu(3,'dat=3')"> Tickets Cancelados</a></li>
                    <li role="presentation" class="dropdown-header bg-gray">Tickets por Grupo</li>
                    <li><a href="#dat=4" onclick="fnsdMenu(3,'dat=4')"> Tickets Sin Asignar</a></li>
                    <li><a href="#dat=5" onclick="fnsdMenu(3,'dat=5')"> Tickets Pendientes</a></li>
                    <li><a href="#dat=6" onclick="fnsdMenu(3,'dat=6')"> Tickets Cerrados</a></li>
                    <li><a href="#dat=7" onclick="fnsdMenu(3,'dat=7')"> Tickets Cancelados</a></li>
                    <li role="presentation" class="dropdown-header bg-gray">Mesa de ayuda</li>
                    <?php
                    $seguridad->_query = "SELECT NoDepartamento,Descripcion FROM BGECatalogoDepartamentos WHERE AsignarReportes = 'SI' AND NoDepartamento != $MyDepartamento ORDER BY Descripcion ASC";
                    $seguridad->get_result_query();
                    $departamentos = $seguridad->_rows;
                    for($i=0;$i < count($departamentos);$i++){
                        echo  "<li><a href='#dat=1' onclick='fnsdMenu(3,\"dat=13&nameDpto=".$departamentos[$i][1]."&dpto=".$departamentos[$i][0]."\")'>".$departamentos[$i][1]."</a></li>";
                    }
                }
                ?>
            </ul>
        </div>
        <button class="btn btn-primary btn-xs" data-opcion="alta" data-toggle="tooltip" data-placement="top" title="Nuevo Ticket" onclick="fnsdMenu(2,0)"><i class="fa fa-file"></i> Nuevo</button>
        <span id="lblTipoFiltro">Tickets Pendientes</span>

        <span class="pull-right"><span id="num" class="label label-success badge">0</span></span>
    </div>
    <div class="panel-body no-padding">

        <div id="lListarTabla">
            <div id="myGrid" style="height: 72vh;font-size: 12.55px;"></div>
        </div>
    </div>
</div>
<?php
if(!empty($_REQUEST['fl'])){

    echo "<script language='JavaScript'>send_mail(1,'".$_REQUEST['fl']."','".$_REQUEST['anio']."','".$_REQUEST['dpto']."');</script>";

}
?>

<!-- END Panel Pricipal

