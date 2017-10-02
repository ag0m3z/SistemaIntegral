<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/01/2017
 * Time: 12:01 PM
 */

include "../../../../core/core.php";
include "../../../../core/sesiones.php";
include "../../../../core/seguridad.php";

$seguridad = new \core\seguridad($_SESSION['data_login']['BDDatos']);

$seguridad->valida_session_id($_SESSION['data_login']['NoUsuario']);

//Ultima Actualización de Aparatos
$seguridad->_query = "SELECT MAX(FechaUM)as FechaUM FROM BOPCatalogoProductos";
$seguridad->get_result_query();
$UMAparatos = $seguridad->_rows;

//Ultima Actualización de Oro
$seguridad->_query = "SELECT MAX(FechaUM)as FechaUM FROM BGECotizaciones";
$seguridad->get_result_query();
$UMOroPlata = $seguridad->_rows;

$UMAparatos[0]['FechaUM'] = $seguridad->getFormatFecha($UMAparatos[0]['FechaUM'],2);
$UMOroPlata[0]['FechaUM'] = $seguridad->getFormatFecha($UMOroPlata[0]['FechaUM'],2);


if($_SESSION['data_departamento']['AsignarReportes'] == 'SI'){
    // Tipo de Usuario Tecnico

    // Tickets Por usuario
    $mTicketPendientes = $seguridad->getAlertStats(7,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoUsuario'],null,null,3);
    $mCerradosHoy = $seguridad->getAlertStats(8,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoUsuario'],null,null,3);

    // Tickets por departameneto
    $dTicketPendientes = $seguridad->getAlertStats(11,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoUsuario'],null,null,3);
    $dCerradosHoy = $seguridad->getAlertStats(12,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoUsuario'],null,null,3);
    $total_reportes = $dTicketPendientes;

    // Total de Equipos Asignados
    $dEquiposAsignados = $seguridad->getAlertStats(13,0,0,0,0,3);

    $class_sol = "hidden" ;$class_tec = "show";
}else{
    // Tipo de Usuario Solicitante
    $class_sol = "show" ;$class_tec = "hidden";
    $total_reportes = $seguridad->getAlertStats(1,0,0,0,0,3);

    $seguridad->_query = "SELECT COUNT(idEncuesta)as TotalEncuesta FROM BGEEncuestaProducto WHERE NoSucursal = '".$_SESSION['data_departamento']['NoDepartamento']."'";
//    $tota_encuesta[0][0] = $connect->getFormatFolio($tota_encuesta[0][0],3);
    $seguridad->get_result_query();
    $tota_encuesta = $seguridad->getFormatFolio($seguridad->_rows[0]['TotalEncuesta'],3);

}

// Validar Acceso al a la Mesa de Ayuda
if($_SESSION['menu_opciones'][1][2][1][0]['OpcionV'] == 1 ){
    $btnDisabled = "";
    $hrefDisabled = "";
}else{
    $btnDisabled = "disabled";
    $hrefDisabled = "href-disabled";
}

// Validar Acceso al Catalogo de Aparatos
if($_SESSION['menu_opciones'][5][1][1][0]['OpcionV'] == 1){
    $btnDisabled02 = "";
    $hrefDisabled02 = "";
}else{
    $btnDisabled02 = "disabled";
    $hrefDisabled02 = "href-disabled";
}

// Validar Acceso a Catalogo de Oro  y Plata
if($_SESSION['menu_opciones'][5][1][2][0]['OpcionV'] == 1 ){
    $btnDisabled03 = "";
    $hrefDisabled03 = "";
}else{
    $btnDisabled03 = "disabled";
    $hrefDisabled03 = "href-disabled";
}


// Validar Acceso a Asignación de Equipos
if($_SESSION['menu_opciones'][1][2][2][0]['OpcionV'] == 1){
    $btnDisabled04 = "";
    $hrefDisabled04 = "";
}else{
    $btnDisabled04 = "disabled"; // para botones de Asignación de Equipos
    $hrefDisabled04 = "href-disabled"; // para <a href> de Asignación de Equipos
}

//echo "<span class='label label-success'>Datos del Usuario</span> <br>";
//var_dump($_SESSION['data_login']);
//echo "<br><br>";

/*
echo "<span class='label label-success'>Datos del Departamento</span> <br>";
//var_dump($_SESSION['data_departamento']);
echo "<br><br>";

echo "<span class='label label-success'>Menu Principal</span> <br>";
//var_dump($_SESSION['menu_principal']);
echo "<br><br>";


echo "<span class='label label-success'>Menu Opciones</span> <br>";
//var_dump($_SESSION['menu_opciones']);
echo "<br><br>";*/


?>

<div class="row margin-bottom-none ">
    <div class="col-md-3 padding-x3">

        <div class="box box-primary">
            <div class="box-title padding-x3">
                <i class="fa fa-whatsapp"></i> Mesa de ayuda
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12 col-lg-6 col-sm-6"><button <?=$btnDisabled?> onclick="fnsdMenu(2,2)" class="btn btn-primary btn-sm btn-block"><i class="fa fa-file"></i> Nuevo Ticket</button> </div>
                    <div class="col-md-12 col-lg-6 col-sm-6"><button <?=$btnDisabled?> onclick="fnsdMenu(3,3)" class="btn btn-primary btn-sm btn-block"><i class="fa fa-list"></i> Lista de Tickets</button> </div>
                </div>

                <br>

                <!-- Box informacion de Tickets para Tecnicos -->
                <div id="tkts_tecnicos" class="<?=$class_tec?>">

                    <!-- Titulo de la caja  -->
                    <div class="box-title"><i class="fa fa-user"></i> Mis tickets</div>

                    <!-- Mis Tickets Pendientes -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- small box -->
                            <a href="javascript:void(0)" class="<?=$hrefDisabled?>" onclick="fnsdMenu(3,'dat=1')">
                                <div class="small-box waves-effect bg-red-gradient">
                                    <div class="inner">
                                        <h3><?=$mTicketPendientes?></h3>

                                        <p>Pendientes</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-list-alt"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div><!-- END Mis Tickets Pendientes -->

                    <!-- Mis Tickets Cerrados al dia de hoy -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- small box -->
                            <a href="javascript:void(0)" class="<?=$hrefDisabled?>" onclick="fnsdMenu(3,'dat=2')">
                                <div class="small-box waves-effect bg-green-gradient">
                                    <div class="inner">
                                        <h3><?=$mCerradosHoy?></h3>

                                        <p>Cerrados</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check-square-o"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div><!-- END Mis Tickets Cerrados al dia de hoy -->


                    <!-- Tickets del Departamento -->
                    <div class="box-title"><i class="fa fa-users"></i> Departamento</div>

                    <!-- Tickets Pendientes del DEpartamento -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- small box -->
                            <a href="javascript:void(0)" class="<?=$hrefDisabled?>" onclick="fnsdMenu(3,'dat=5')">
                                <div class="small-box waves-effect bg-red-gradient">
                                    <div class="inner">
                                        <h3><?=$dTicketPendientes?></h3>

                                        <p>Pendientes</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-list-alt"></i>
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div><!-- END Tickets Pendientes del Departamento-->

                    <!-- Tickets Cerrados el dia de hoy del DEpartamento -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- small box -->
                            <a href="javascript:void(0)" class="<?=$hrefDisabled?>" onclick="fnsdMenu(3,'dat=6')">
                                <div class="small-box waves-effect bg-green-gradient">
                                    <div class="inner">
                                        <h3><?=$dCerradosHoy?></h3>

                                        <p>Cerrados</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check-square-o"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div><!-- END Tickets Cerrados el dia de hoy del Departamento-->
                </div><!-- END Box informacion de Tickets para Tecnicos   -->

                <!-- Box para informacion de Tickets para solicitantes-->
                <div id="tkts_solicitantes" class="<?=$class_sol?> scroll-auto" style="height: 68vh" >

                    <?php
                    $seguridad->_query = "SELECT NoDepartamento,Descripcion FROM SINTEGRALPRD.BGECatalogoDepartamentos WHERE AsignarReportes = 'SI' AND NoEstado = 1 ORDER BY Descripcion ASC;";
                    $seguridad->get_result_query();
                    $Departamentos_Tecnicos = $seguridad->_rows;

                    $bg_color = array(
                        2=>'bg-aqua-gradient',
                        3=>'bg-green-gradient',
                        1=>'bg-light-blue-gradient'
                    );

                    for($i =0; $i < count($Departamentos_Tecnicos); $i++){

                        $id = $id+ 1;

                        echo '<div class="row">
                        <div class="col-md-12">
                            <!-- small box -->
                            <a href="javascript:void(0)" class="'.$hrefDisabled.'" onclick="fnsdMenu(3,\'dat=13&nameDpto='.$Departamentos_Tecnicos[$i][1].'&dpto='.$Departamentos_Tecnicos[$i][0].'\');">
                                <div class="small-box waves-effect '.$bg_color[$id].'">
                                    <div class="inner">
                                        <h3>'.$seguridad->getAlertStats(23,$_SESSION['data_departamento']['NoDepartamento'],$_SESSION['data_login']['NoUsuario'],null,null,3,$Departamentos_Tecnicos[$i][0]).'</h3>
                                        <p>'.$Departamentos_Tecnicos[$i][1].'</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-windows"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>';
                        if($id >= 3){$id = 0;}


                    }

                    ?>



                </div>

            </div>
        </div>
    </div>

    <div class="col-md-9 padding-x3">
        <!-- Inicio de Box -->
        <div class="box box-primary">
            <div class="box-title padding-x3">
                <i class="fa fa-dashboard"></i> Dashboard
            </div>
            <div class="box-body margin" style="height: 24vh">
                <div class="row ">
                    <div class="col-lg-3 col-xs-6 padding-x5">
                        <!-- small box -->
                        <div class="small-box  bg-aqua">
                            <div class="inner">
                                <h3><?=$total_reportes?></h3>

                                <p>Mesa de ayuda</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-whatsapp"></i>
                            </div>
                            <a href="javascript:void(0)" onclick="fnsdMenu(3)" class="small-box-footer <?=$hrefDisabled?> waves-effect">Total de reportes <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <!-- ./col -->
                    <div class="col-lg-3 col-xs-6 padding-x5">
                        <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3><?=$UMAparatos[0]['FechaUM']?></h3>

                                <p>Catalogo de productos</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-list-alt"></i>
                            </div>
                            <a href="javascript:void(0)" onclick="fnsdMenu(15,15)" class="small-box-footer <?=$hrefDisabled02?> waves-effect">Ultima Actualización <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-xs-6 padding-x5">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3><?=$UMOroPlata[0]['FechaUM']?></h3>

                                <p>Oro y Plata</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-diamond"></i>
                            </div>
                            <a href="javascript:void(0)" onclick="fnsdMenu(14,14)" class="small-box-footer <?=$hrefDisabled03?> waves-effect">Ultima Actualización <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-xs-6 padding-x5">
                        <!-- small box -->
                        <?php

                        if($_SESSION['data_departamento']['NoTipo'] == 'S' ){
                            echo '<div class="small-box bg-red <?=$class_sol?>">
                                   <div class="inner">
                                       <h3>'.$tota_encuesta.'</h3>

                                       <p>Encuestas</p>
                                   </div>
                                   <div class="icon">
                                       <i class="fa fa-clipboard"></i>
                                   </div>
                                   <div class="small-box-footer">Total de Encuestas <i class="fa fa-arrow-circle-right"></i></div>
                               </div>';
                        }

                        ?>
                        <div class="small-box bg-red <?=$class_tec?>">
                            <div class="inner">
                                <h3><?=$dEquiposAsignados?></h3>

                                <p>Equipos</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-laptop"></i>
                            </div>
                            <a href="javascript:void(0)" onclick="fnsdMenu(8,8)" class="small-box-footer <?=$hrefDisabled04?>">Equipos Asignados <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
            </div>
        </div> <!-- End Box Dashboard -->

        <div class="box box-primary">
            <div class="box-title padding-x3"><i class="fa fa-list-alt"></i> Ultimos <span>10</span> Productos Agregados</div>
            <div class="box-body" >
                <div class="scroll-auto" style="height: 38.5vh" >
                    <ul class="products-list product-list-in-box">
                        <?php
                        $seguridad->_query ="SELECT a.CodigoProducto,b.Descripcion,a.Descripcion as Des,a.FechaAlta
                                FROM BOPCatalogoProductos as a
                                LEFT JOIN BGECatalogoGeneral as b
                                ON a.Clasificacion02 = b.OpcCatalogo AND  b.CodCatalogo = 5
                                ORDER BY a.FechaAlta DESC LIMIT 0,10" ;

                        $seguridad->get_result_query();

                        if(count($seguridad->_rows) > 0){

                            for($i = 0; $i < count($seguridad->_rows); $i++){

                                echo '<li class="item padding-x3">
                                               <div class="product-img">
                                                   <img src="site_design/img/pages/sinfoto.jpg" alt="Product Image">
                                               </div>
                                               <div class="product-info">
                                                   <a href="javascript:void(0)" class="product-title">'.$seguridad->_rows[$i]['CodigoProducto'].'
                                                       <span class="label label-default pull-right">'.$seguridad->getFormatFecha($seguridad->_rows[$i]['FechaAlta'],2).'</span></a>
                                                    <span class="product-description">
                                                      '.$seguridad->_rows[$i]['Des'].'
                                                    </span>
                                               </div>
                                           </li>';

                            }

                        }

                        ?>
                    </ul><!-- /.item -->
                </div>


            </div>
            <div class="box-footer text-center">
                <button onclick="fnsdMenu(15,15)" class="btn btn-xs btn-link uppercase">Ver todos los productos</button>
            </div>
        </div>

    </div>

</div>