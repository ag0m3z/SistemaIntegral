<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 12/05/2017
 * Time: 02:19 PM
 */

unset($_SESSION['EXPORT']);
$idCodigo = $_POST['idcodigo'];
$idSerie = $_POST['idserie'];
$idImagen = $_POST['idimagen'];

?>
<script>setOpenModal("mdl_picture");</script>
<div class="modal animated flipInY" id="mdl_picture">
    <div class="modal-dialog" >
        <div class="modal-content profile">
            <div class="modal-body no-padding">
                <div class="profile">
                    <img src="modules/04PVenta/src/productos_web/fn_mostrar_image_producto.php?tpo=1&id=<?=$idImagen?>" class="img-responsive" />
                </div>
            </div>
        </div>
    </div>
</div>

