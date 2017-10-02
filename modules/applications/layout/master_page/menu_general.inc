

<ul class="sidebar-menu">
    <li class="active treeview">
        <a href="#" class="waves-effect">
            <i class="fa fa-home"></i> <span>Inicio</span>
        </a>
        <ul class="treeview-menu">
            <li><a href="#" onclick="location.reload()" ><i class="fa fa-newspaper-o"></i> Pagina Principal</a></li>
            <li><a href="#" onclick="fnsdMenu(17,17)"><i class="fa fa-book"></i> Directorio</a></li>
            <li class="hidden"><a href="#" onclick="fnsdMenu(39,39)" ><i class="fa fa-envelope"></i> Correo Interno</a></li>
            <li><a href="#" onclick="fnMostrarPerfil(2,<?=$_SESSION['data_login']['idEmpleado']?>)"><i class="fa fa-user"></i> Mi Perfil</a></li>
            <li><a href="#" onclick="fnsdMenu(19,19)" ><i class="fa fa-cloud-download"></i> Aplicaciones</a></li>
            <li class="hidden"><a href="#"><i class="fa fa-book"></i> e Learning</a></li>
            <li class="hidden"><a href="#"><i class="fa fa-list-alt"></i> Formas y Solicitudes</a></li>
            <li class="hidden"><a href="#"><i class="fa fa-whatsapp"></i> Chat online</a></li>
            <li class="hidden"><a href="modules/00console/console.sys.agb.php"><i class="fa fa-dropbox"></i> Utilerias</a></li>
            <li class="hidden"><a href="#"><i class="fa fa-gears"></i> Herramientas</a></li>
            <li><a href="#" onclick="fnsdMenu(40,40)"><i class="fa fa-file-movie-o"></i> Videos</a></li>
            <li class="hidden"><a href="#"><i class="fa fa-list-alt"></i> Manuales</a></li>
            <li class="hidden"><a href="#"><i class="fa fa-list-alt"></i> Correo</a></li>
            <li class="hidden"><a href="#"><i class="fa fa-list-alt"></i> Catalogos</a></li>
            <li class="hidden"><a href="#"><i class="fa fa-list-alt"></i> Grupos</a></li>
            <li class="hidden"><a href="#"><i class="fa fa-list-alt"></i> Seguridad</a></li>
            <li class="hidden"><a href="#"><i class="fa fa-list-alt"></i> Ayuda</a></li>
            <li><a href="#" onclick="getMessageNotify(
            'Informaci&oacute;n del Sistema',
            'Sistema para integrar y centralizar la gestión de la información dentro de la organización<br><br><address><strong>Nombre: </strong>Sistema Integral<br><b>Copyright ©:</b> 2016-2017<br><b>Organizaci&oacute;n: </b>Prestamo Express<br><b>Versi&oacute;n: </b>2.3.2<br><b>Actualizaci&oacute;n: </b>20/10/2016 </address>',
            'info'
            )" >
                    <i class="fa fa-cubes"></i> Acerca de
                </a>
            </li>
        </ul>
    </li>
    <?php

    // mostrar los modulos de cargados
    for($i = 0 ; $i < count($_SESSION['menu_principal']['modulos']); $i++ ){

        $fa_icon = $_SESSION['menu_principal']['modulos'][$i]['icon'];
        $nombreModulo = $_SESSION['menu_principal']['modulos'][$i]['nombre'];

        echo "
        <li class='treeview'>
            <a href='#mnu=1' class='waves-effect'>
                <i class='fa ".$fa_icon."'></i>
                <span>".$nombreModulo."</span>
            </a>";

        echo '<ul class="treeview-menu">';

        for($s = 0;$s < count($_SESSION['menu_principal']['secciones']);$s++){

            if($_SESSION['menu_principal']['modulos'][$i]['idModulo'] == $_SESSION['menu_principal']['secciones'][$s]['idModulo'] ){

                $nombreModulo = $_SESSION['menu_principal']['secciones'][$s]['nombre'];

                echo '<li class="waves-effect">
                        <a href="#" class="waves-effect"><i class="fa fa-folder"></i>'.$nombreModulo.' </a>';
                echo '<ul class="treeview-menu">';

                for($op = 0; $op < count($_SESSION['menu_principal']['aplication']) ;$op++){

                    if($_SESSION['menu_principal']['secciones'][$s]['idModulo'] == $_SESSION['menu_principal']['aplication'][$op]['idModulo'] ){

                        if($_SESSION['menu_principal']['secciones'][$s]['idSeccion'] == $_SESSION['menu_principal']['aplication'][$op]['idSeccion'] ){

                            $fa_icon = $_SESSION['menu_principal']['aplication'][$op]['icon'];
                            $nombreModulo = $_SESSION['menu_principal']['aplication'][$op]['nombre'];
                            $Click = $_SESSION['menu_principal']['aplication'][$op]['eventclick'];

                            echo '<li class="waves-effect"><a href="javascript:void(0)" onclick="fnsdMenu('.$Click.',0);" ><i class="fa '.$fa_icon.'"></i>'.$nombreModulo.'</a></li>';
                        }
                    }
                }

                echo '</ul>';
                echo '</li>';
            }
        }

        echo '</ul>';
        echo "</li>";
    }


    ?>

    <li class="treeview">
        <a href="#close=yes" onclick="jsgn_salir()" class="waves-effect">
            <i class="fa fa-sign-out"></i> <span>Salir</span>
        </a>
    </li>
</ul>
<div class="hidden" style="color: #000 !important;margin: 3px;position: absolute;
right:    0;
bottom:   0;">
    <a href="#" class="" onclick="$(body).removeClass();$(body).addClass('hold-transition skin-vino fixed sidebar-minin')" style="color: #000 !important;">Tema Vino</a><br>
    <a href="#" class="" onclick="$(body).removeClass();$(body).addClass('hold-transition skin-green fixed sidebar-minin')" style="color: #000 !important;">Tema Green</a><br>
    <a href="#" class="" onclick="$(body).removeClass();$(body).addClass('hold-transition skin-blue fixed sidebar-minin')" style="color: #000 !important;">Tema Blue</a><br>
    <a href="#" class="" onclick="$(body).removeClass();$(body).addClass('hold-transition skin-red fixed sidebar-minin')" style="color: #000 !important;">Tema Red</a><br>
    <a href="#" class="" onclick="$(body).removeClass();$(body).addClass('hold-transition skin-black fixed sidebar-minin')" style="color: #000 !important;">Tema Black</a><br>
    <a href="#HomeWrapper" class="btn hidden btn-floating bg-light-blue-gradient waves-effect waves-light"><i class="fa fa-arrow-up"></i></a>
</div>
<br>
