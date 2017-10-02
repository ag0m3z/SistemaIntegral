/**
 * Created by alejandro.gomez on 26/05/2017.
 */





//Funcion para editar Modelos
function fn07EditarModelo(opc,idmodelo) {

    switch (opc){
        // Opcion para mostrar el formulario para editar la ubicacion
        case 1:

            SendAjax(
                "modules/07Mantenimiento/views/catalogo_modelos/",
                "frm_editar_modelo.php",
                null,
                "idgeneral",
                "post",
                null,
                {
                    opc:opc,
                    idmodelo:idmodelo
                }
            );
            break;
        //Funcion para guardar los cambios del proveedor
        case 2:

            // Validar Campos
            var nombre = $("#nombre").val(),
                descripcion =$("#descripcion").val(),
                idestatus = $("#idestatus").val(),
                idmarca = $("#idmarca").val();

            if(idmodelo == ""){

                MyAlert("El Modelo seleccionada no existe","error");

            }else if($.trim(nombre) == ""){
                MyAlert("Ingrese el nombre del modelo","alert");
            }else{

                SendAjax(
                    "modules/07Mantenimiento/src/catalogo_modelos/",
                    "fn_editar_modelo.php",
                    null,
                    "result_modal",
                    "post",
                    null,
                    {
                        opc:opc,
                        idmodelo:idmodelo,
                        nombre:nombre,
                        descripcion:descripcion,
                        idestatus:idestatus,
                        idmarca:idmarca
                    }
                );

            }



            break;
    }


}

//Funcion listar Modelos
function fn07ListarModelo(opc,idestatus) {

    var textSearch = $("#txtSearch").val();
    SendAjax(
        "modules/07Mantenimiento/src/catalogo_modelos/",
        "fn_listar_modelos.php",
        null,
        "listaMarcas",
        "post",
        null,
        {
            opc:opc,
            idestatus:idestatus,
            textSearch:textSearch
        }
    );
}

//Funcion para dar de alta Nuevo Modelos
function fn07NuevoModelo(opc) {

    switch (opc){

        //Mostrar Formulario para nueva ubicacion
        case 1:

            SendAjax(
                "modules/07Mantenimiento/views/catalogo_modelos/",
                "frm_nuevo_modelo.php",
                null,
                "idgeneral",
                "post",
                null,
                {
                    opc:opc
                }
            );

            break;
        //funcion para guardar el nuevo proveedor
        case 2:

            //Validar Campos

            var nombre = $("#nombre").val(),
                descripcion = $("#descripcion").val(),
                idmarca = $("#idmarca").val();


            if(idmarca == 0){
                MyAlert("Seleccione una marca","alert");
            }else if($.trim(nombre) == ""){
                MyAlert("Ingrese el nombre del modelo","alert");
            }else{

                SendAjax(
                    "modules/07Mantenimiento/src/catalogo_modelos/",
                    "fn_registra_nuevo_modelo.php",
                    null,
                    "result_modal",
                    "post",
                    null,
                    {
                        opc:opc,
                        nombre:nombre,
                        descripcion:descripcion,
                        idmarca:idmarca
                    }
                );
            }

            break;
        default:
            MyAlert("La opción solicitada no existe","error");


    }

}



/**
 * CAtalogo de Marcas
 * @param opc
 * @param idmarca
 */


//Funcion para editar las marcas
function fn07EditarMarca(opc,idmarca) {

    switch (opc){
        // Opcion para mostrar el formulario para editar la ubicacion
        case 1:

            SendAjax(
                "modules/07Mantenimiento/views/catalogo_marcas/",
                "frm_editar_marca.php",
                null,
                "idgeneral",
                "post",
                null,
                {
                    opc:opc,
                    idmarca:idmarca
                }
            );
            break;
        //Funcion para guardar los cambios del proveedor
        case 2:

            // Validar Campos
            var nombre = $("#nombre").val(),
                descripcion =$("#descripcion").val(),
                idestatus = $("#idestatus").val();

            if(idmarca == ""){

                MyAlert("La marca seleccionada no existe","error");

            }else if($.trim(nombre) == ""){
                MyAlert("Ingrese el nombre de la marca","alert");
            }else{

                SendAjax(
                    "modules/07Mantenimiento/src/catalogo_marcas/",
                    "fn_editar_marca.php",
                    null,
                    "result_modal",
                    "post",
                    null,
                    {
                        opc:opc,
                        idmarca:idmarca,
                        nombre:nombre,
                        descripcion:descripcion,
                        idestatus:idestatus
                    }
                );

            }



            break;
    }


}

//Funcion listar marcas
function fn07ListarMarca(opc,idestatus) {

    var textSearch = $("#txtSearch").val();
    SendAjax(
        "modules/07Mantenimiento/src/catalogo_marcas/",
        "fn_listar_marcas.php",
        null,
        "listaMarcas",
        "post",
        null,
        {
            opc:opc,
            idestatus:idestatus,
            textSearch:textSearch
        }
    );
}

//Funcion para dar de alta Nueva marca
function fn07NuevaMarca(opc) {

    switch (opc){

        //Mostrar Formulario para nueva ubicacion
        case 1:

            SendAjax(
                "modules/07Mantenimiento/views/catalogo_marcas/",
                "frm_nueva_marca.php",
                null,
                "idgeneral",
                "post",
                null,
                {
                    opc:opc
                }
            );

            break;
        //funcion para guardar el nuevo proveedor
        case 2:

            //Validar Campos

            var nombre = $("#nombre").val(),
                descripcion = $("#descripcion").val();


            if($.trim(nombre) == ""){
                MyAlert("Ingrese el nombre del la marca","alert");
            }else{

                SendAjax(
                    "modules/07Mantenimiento/src/catalogo_marcas/",
                    "fn_registra_nueva_marca.php",
                    null,
                    "result_modal",
                    "post",
                    null,
                    {
                        opc:opc,
                        nombre:nombre,
                        descripcion:descripcion
                    }
                );
            }

            break;
        default:
            MyAlert("La opción solicitada no existe","error");


    }

}

/**
 * Catalogo de Tipos de Equipos
 * @param opc
 * @param idtipo_equipo
 */

//Funcion para editar los tipos de equipos
function fn07EditarTipoEquipo(opc,idtipo_equipo) {

    switch (opc){
        // Opcion para mostrar el formulario para editar la ubicacion
        case 1:

            SendAjax(
                "modules/07Mantenimiento/views/catalogo_tipo_equipos/",
                "frm_editar_tipo_equipo.php",
                null,
                "idgeneral",
                "post",
                null,
                {
                    opc:opc,
                    idtipo_equipo:idtipo_equipo
                }
            );
            break;
        //Funcion para guardar los cambios del proveedor
        case 2:

            // Validar Campos
            var nombre = $("#nombre").val(),
                descripcion =$("#descripcion").val(),
                idestatus = $("#idestatus").val();

            if(idtipo_equipo == ""){

                MyAlert("el Tipo de Equipo seleccionada no existe","error");

            }else if($.trim(nombre) == ""){
                MyAlert("Ingrese el nombre del tipo de equipo","alert");
            }else{

                SendAjax(
                    "modules/07Mantenimiento/src/catalogo_tipo_equipos/",
                    "fn_editar_tipo_equipo.php",
                    null,
                    "result_modal",
                    "post",
                    null,
                    {
                        opc:opc,
                        idtipo_equipo:idtipo_equipo,
                        nombre:nombre,
                        descripcion:descripcion,
                        idestatus:idestatus
                    }
                );

            }



            break;
    }


}

//Funcion listar los tipos de equipos
function fn07ListarTipoEquipos(opc,idestatus) {

    var textSearch = $("#txtSearch").val();
    SendAjax(
        "modules/07Mantenimiento/src/catalogo_tipo_equipos/",
        "fn_listar_tipo_equipos.php",
        null,
        "listaTiposEquipos",
        "post",
        null,
        {
            opc:opc,
            idestatus:idestatus,
            textSearch:textSearch
        }
    );
}

//Funcion para dar de alta los tipos de equipos
function fn07NuevaTipoEquipo(opc) {

    switch (opc){

        //Mostrar Formulario para nueva ubicacion
        case 1:

            SendAjax(
                "modules/07Mantenimiento/views/catalogo_tipo_equipos/",
                "frm_nuevo_tipo_equipo.php",
                null,
                "idgeneral",
                "post",
                null,
                {
                    opc:opc
                }
            );

            break;
        //funcion para guardar el nuevo proveedor
        case 2:

            //Validar Campos

            var nombre = $("#nombre").val(),
                descripcion = $("#descripcion").val();


            if($.trim(nombre) == ""){
                MyAlert("Ingrese el nombre del tipo de equipo","alert");
            }else{

                SendAjax(
                    "modules/07Mantenimiento/src/catalogo_tipo_equipos/",
                    "fn_registra_nuevo_tipo_equipo.php",
                    null,
                    "result_modal",
                    "post",
                    null,
                    {
                        opc:opc,
                        nombre:nombre,
                        descripcion:descripcion
                    }
                );
            }

            break;
        default:
            MyAlert("La opción solicitada no existe","error");


    }

}

/**
 * Catalogo de Refacciones
 * @param opc
 * @param idrefaccion
 */

//Funcion para editar las Refacciones
function fn07EditarRefaccion(opc,idrefaccion) {

    switch (opc){
        // Opcion para mostrar el formulario para editar la ubicacion
        case 1:

            SendAjax(
                "modules/07Mantenimiento/views/catalogo_refacciones/",
                "frm_editar_refaccion.php",
                null,
                "idgeneral",
                "post",
                null,
                {
                    opc:opc,
                    idrefaccion:idrefaccion
                }
            );
            break;
        //Funcion para guardar los cambios del proveedor
        case 2:

            // Validar Campos
            var nombre = $("#nombre").val(),
                idequipo = $("#idequipo").val(),
                descripcion =$("#descripcion").val(),
                idestatus = $("#idestatus").val();

            if(idrefaccion == ""){

                MyAlert("La refacción seleccionada no existe","error");

            }else if($.trim(nombre) == ""){
                MyAlert("Ingrese el nombre de la refacción","alert");
            }else{

               /* SendAjax(
                    "modules/07Mantenimiento/src/catalogo_ubicaciones/",
                    "fn_editar_ubicacion.php",
                    null,
                    "result_modal",
                    "post",
                    null,
                    {
                        opc:opc,
                        idrefaccion:idrefaccion,
                        nombre:nombre,
                        descripcion:descripcion,
                        idestatus:idestatus
                    }
                );*/

            }



            break;
    }


}

//Funcion listar Refacciones
function fn07ListarRefacciones(opc,idestatus) {

    var textSearch = $("#txtSearch").val();
    SendAjax(
        "modules/07Mantenimiento/src/catalogo_refacciones/",
        "fn_listar_refacciones.php",
        null,
        "listaRefacciones",
        "post",
        null,
        {
            opc:opc,
            idestatus:idestatus,
            textSearch:textSearch
        }
    );
}

//Funcion para dar de alta Nueva Refaccion
function fn07NuevaRefaccion(opc) {

    switch (opc){

        //Mostrar Formulario para nueva ubicacion
        case 1:

            SendAjax(
                "modules/07Mantenimiento/views/catalogo_refacciones/",
                "frm_nueva_refaccion.php",
                null,
                "idgeneral",
                "post",
                null,
                {
                    opc:opc
                }
            );

            break;
        //funcion para guardar el nuevo proveedor
        case 2:

            //Validar Campos

            var nombre = $("#nombre").val(),
                descripcion = $("#descripcion").val(),
                idequipo = $("#idequipo").val();


            if(idequipo == 0){
                MyAlert("Seleccione un equipo a la que pertencera la refacción","alert");
            }else if($.trim(nombre) == ""){
                MyAlert("Ingrese el nombre del la refacción","alert");
            }else{

                SendAjax(
                    "modules/07Mantenimiento/src/catalogo_refacciones/",
                    "fn_registra_nueva_refaccion.php",
                    null,
                    "result_modal",
                    "post",
                    null,
                    {
                        opc:opc,
                        idequipo:idequipo,
                        nombre:nombre,
                        descripcion:descripcion
                    }
                );
            }

            break;
        default:
            MyAlert("La opción solicitada no existe","error");


    }

}

/**
 * Catalogo de Ubicaciones
 * @param opc
 * @param idubicacion
 */

//Funcion para editar las ubicaciones
function fn07EditarUbicacion(opc,idubicacion) {

    switch (opc){
        // Opcion para mostrar el formulario para editar la ubicacion
        case 1:

            SendAjax(
                "modules/07Mantenimiento/views/catalogo_ubicaciones/",
                "frm_editar_ubicacion.php",
                null,
                "idgeneral",
                "post",
                null,
                {
                    opc:opc,
                    idubicacion:idubicacion
                }
            );
            break;
        //Funcion para guardar los cambios del proveedor
        case 2:

            // Validar Campos
            var nombre = $("#nombre").val(),
                descripcion =$("#descripcion").val(),
                idestatus = $("#idestatus").val();

            if(idubicacion == ""){

                MyAlert("La ubicación seleccionada no existe","error");

            }else if($.trim(nombre) == ""){
                MyAlert("Ingrese el nombre de la ubicación","alert");
            }else{

                SendAjax(
                    "modules/07Mantenimiento/src/catalogo_ubicaciones/",
                    "fn_editar_ubicacion.php",
                    null,
                    "result_modal",
                    "post",
                    null,
                    {
                        opc:opc,
                        idubicacion:idubicacion,
                        nombre:nombre,
                        descripcion:descripcion,
                        idestatus:idestatus
                    }
                );

            }



            break;
    }


}


//Funcion listar Ubicaciones
function fn07ListarUbicaciones(opc,idestatus) {

    var textSearch = $("#txtSearch").val();
    SendAjax(
        "modules/07Mantenimiento/src/catalogo_ubicaciones/",
        "fn_listar_ubicaciones.php",
        null,
        "listaUbicaciones",
        "post",
        null,
        {
            opc:opc,
            idestatus:idestatus,
            textSearch:textSearch
        }
    );
}

//Funcion para dar de alta Nueva Ubicacion
function fn07NuevaUbicacion(opc) {

    switch (opc){

        //Mostrar Formulario para nueva ubicacion
        case 1:

            SendAjax(
                "modules/07Mantenimiento/views/catalogo_ubicaciones/",
                "frm_nueva_ubicacion.php",
                null,
                "idgeneral",
                "post",
                null,
                {
                    opc:opc
                }
            );

            break;
        //funcion para guardar el nuevo proveedor
        case 2:

            //Validar Campos

            var nombre = $("#nombre").val(),
                descripcion = $("#descripcion").val();


            if($.trim(nombre) == ""){
                MyAlert("Ingrese el nombre del la ubicación","alert");
            }else{

                SendAjax(
                    "modules/07Mantenimiento/src/catalogo_ubicaciones/",
                    "fn_registra_nueva_ubicacion.php",
                    null,
                    "result_modal",
                    "post",
                    null,
                    {
                        opc:opc,
                        nombre:nombre,
                        descripcion:descripcion
                    }
                );
            }

            break;
        default:
            MyAlert("La opción solicitada no existe","error");


    }

}


/**
 * Catalogo de Provreedores
 * @param opc
 * @param idestatus
 */

// Listar Proveedores
function fn07ListarProveedores(opc,idestatus) {

    var textSearch = $("#txtSearch").val();
    SendAjax(
        "modules/07Mantenimiento/src/catalogo_proveedores/",
        "fn_listar_proveedores.php",
        null,
        "listaProveedores",
        "post",
        null,
        {
            opc:opc,
            idestatus:idestatus,
            textSearch:textSearch
        }
    );
}

//Funcion para editar proveedor
function fn07EditarProveedor(opc,idproveedor) {

    switch (opc){
        // Opcion para mostrar el formulario para editar el proveedor
        case 1:

            SendAjax(
              "modules/07Mantenimiento/views/catalogo_proveedores/",
              "frm_editar_proveedor.php",
               null,
                "idgeneral",
                "post",
                null,
                {
                    opc:opc,
                    idproveedor:idproveedor
                }
            );
            break;
        //Funcion para guardar los cambios del proveedor
        case 2:

            // Validar Campos
            var nombre = $("#nombre").val(),
                contacto = $("#contacto").val(),
                descripcion =$("#descripcion").val(),
                telefono01 =$("#telefono01").val(),
                telefono02 =$("#telefono02").val(),
                celular =$("#celular").val(),
                ext =$("#ext").val(),
                correo =$("#correo").val(),
                callenumero =$("#callenumero").val(),
                colonia =$("#colonia").val(),
                idestatus = $("#idestatus").val();

            if(idproveedor == ""){

                MyAlert("El proveedor seleccionado no existe","error");

            }else if($.trim(nombre) == ""){
                MyAlert("Ingrese el nombre del proveedor","alert");
            }else if($.trim(contacto) == ""){
                MyAlert("Ingrese el nombre del contacto","alert");
            }else if($.trim(telefono01) == "" && $.trim(telefono02) == ""){
                MyAlert("Ingrese almenos un telefono de contacto","alert");
            }else{

                var isSend = true;

                if(isSend){
                    SendAjax(
                        "modules/07Mantenimiento/src/catalogo_proveedores/",
                        "fn_editar_proveedor.php",
                        null,
                        "result_modal",
                        "post",
                        null,
                        {
                            opc:opc,
                            idproveedor:idproveedor,
                            nombre:nombre,
                            contacto:contacto,
                            descripcion:descripcion,
                            telefono01:telefono01,
                            telefono02:telefono02,
                            celular:celular,
                            ext:ext,
                            correo:correo,
                            callenumero:callenumero,
                            colonia:colonia,
                            idestatus:idestatus
                        }
                    );
                }


            }



            break;
    }


}

//Funcion para nuevo proveedor
function fn07NuevoProveedor(opc) {

    var isSend = false;

    switch (opc){

        //Mostrar Formulario para nuevo Proveedor
        case 1:

            isSend = true;
            if(isSend){

                SendAjax(
                    "modules/07Mantenimiento/views/catalogo_proveedores/",
                    "frm_nuevo_proveedor.php",
                    null,
                    "idgeneral",
                    "post",
                    null,
                    {
                        opc:opc
                    }
                );

            }
            break;
        //funcion para guardar el nuevo proveedor
        case 2:

            //Validar Campos

            var nombre = $("#nombre").val(),
                contacto = $("#contacto").val(),
                descripcion =$("#descripcion").val(),
                telefono01 =$("#telefono01").val(),
                telefono02 =$("#telefono02").val(),
                celular =$("#celular").val(),
                ext =$("#ext").val(),
                correo =$("#correo").val(),
                callenumero =$("#callenumero").val(),
                colonia =$("#colonia").val();


            if($.trim(nombre) == ""){
                MyAlert("Ingrese el nombre del proveedor","alert");
            }else if($.trim(contacto) == ""){
                MyAlert("Ingrese el nombre del contacto","alert");
            }else if($.trim(telefono01) == "" && $.trim(telefono02) == ""){
                MyAlert("Ingrese almenos un telefono de contacto","alert");
            }else{

                isSend = true;

                if(isSend){
                    SendAjax(
                        "modules/07Mantenimiento/src/catalogo_proveedores/",
                        "fn_registra_nuevo_proveedor.php",
                        null,
                        "result_modal",
                        "post",
                        null,
                        {
                            opc:opc,
                            nombre:nombre,
                            contacto:contacto,
                            descripcion:descripcion,
                            telefono01:telefono01,
                            telefono02:telefono02,
                            celular:celular,
                            ext:ext,
                            correo:correo,
                            callenumero:callenumero,
                            colonia:colonia
                        }
                    );
                }


            }


            break;
        default:
            MyAlert("La opción solicitada no existe","error");


    }





}

