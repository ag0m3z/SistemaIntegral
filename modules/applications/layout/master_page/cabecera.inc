<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 30/01/2017
 * Time: 06:00 PM
 */
?>
<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg text-center">Sistema Integral</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">

        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle waves-effect" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" ><i class="fa fa-calendar"></i> <?=\core\core::mostrarFecha('large')?> </a>
                </li>
                <li>
                    <a href="#" onclick="requestFullScreen()"><i class="fa fa-arrows-alt"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar scroll-auto">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="text-center image">
                <img src="<?=\core\core::ROOT_APP?>site_design/img/logos/banner-e.png" onclick="location.reload()" style="cursor: pointer" class="img-circle" alt="User Image">
            </div>
        </div>
        <div style="min-height: 15px;background: #793240;">
            <div class="sidebar-form" >
                <div class="text-center text-white small">
                    <a href="javascript:void(0);" onclick="fnMostrarPerfil(2,<?=$_SESSION['data_login']['idEmpleado']?>)" >
                        <i class="fa fa-user"></i> <?=$_SESSION['data_login']['NombreDePila']?><br>
                        <?=$_SESSION['data_departamento']['NombreDepartamento']?>
                    </a>
                </div>
                <div style="margin: 1px;">
                    <ul class="nav-stats">
                        <!-- Messages: style can be found in dropdown.less-->
                        <li class="tasks-menu">
                            <a href="#" onclick="fngnFrmMensajeria(1)" class="waves-effect">
                                <i id="icon_bellMensajes" class="fa fa-envelope-o animated flash"></i>
                                <span class="label bellMensajes label-success">0</span>
                            </a>
                        </li>
                        <li class="tasks-menu">
                            <a href="#" data-toggle="control-sidebar" onclick="$('#mnu_tab_02').click();" class="waves-effect">
                                <i class="fa fa-bell-o animated flash"></i>
                                <span class="label label-warning">0</span>
                            </a>
                        </li>
                        <li class="tasks-menu ">
                            <a href="#" data-toggle="control-sidebar" onclick="$('#mnu_tab_03').click();" class="waves-effect">
                                <i class="fa fa-flag-o animated flip"></i>
                                <span class="label label-danger">0</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php include "modules/applications/layout/master_page/menu_general.inc";?>
    </section>
    <!-- /.sidebar -->
</aside>
