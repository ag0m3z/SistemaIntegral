<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 04/04/2017
 * Time: 05:02 PM
 */

include "../../../../core/core.php";

?>
<script src="<?=\core\core::ROOT_APP?>site_design/js/jsServiceDesk.js" type="text/javascript"></script>

<a class="btn btn-default btn-app" onclick="sdMenuCatalogos(2)" data-toggle="tooltip" data-placement="top" title="Catálogo de áreas">
    <i class="fa fa-list"></i>Áreas
</a>

<a class="btn btn-default btn-app" onclick="sdMenuCatalogos(1)" data-toggle="tooltip" data-placement="top" title="Catálogo de categorías">
    <i class="fa fa-list-ul"></i>Categorias
</a>

<a class="btn btn-default btn-app" onclick="sdMenuCatalogos(3)" data-toggle="tooltip" data-placement="top" title="Medio de Contacto">
    <i class="fa fa-phone"></i>Medio de Contacto
</a>

<a class="btn hidden btn-default btn-app" onclick="sdMenuCatalogos(4)" data-toggle="tooltip" data-placement="top" title="Tipo de Atencion">
    <i class="fa fa-bullhorn"></i>Tipo Atencion
</a>

