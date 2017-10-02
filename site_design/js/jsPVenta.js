/**
 * Created by alejandro.gomez on 30/09/2016.
 */

/**
 * Funciones para Cotizador de Call Center
 */

roundTo10 = function(x,factor){ return x - (x%factor) + (x%factor>0 && factor);}



function fnBuscarCotizacion(opc){

    var Folio = $("#folio").val(),
        MedioContacto = $("#mcontacto").val(),
        TipoCotizacion = $("#tcotizacion").val(),
        Categoria = $("#idcategoria").val(),
        Tipo = $("#id_tpoproducto").val(),
        UsuarioRegistra = $("#usuarioregistra").val(),
        NoEstatus = $("#noestatus").val(),
        Fecha1 = $("#fecha1").val(),
        Fecha2 = $("#fecha2").val();

    if($.trim(Folio)==""){
        Folio = 0;
    }
    if($.trim(Fecha1)==""){
        Fecha1 = 0;
    }
    if($.trim(Fecha2)==""){
        Fecha2 = 0;
    }

    SendAjax(
      "modules/04PVenta/src/cotizador/",
      "fnBuscarCotizacion.php",
      null,
      "myGrid",
      "post",
      null,
        {opc:opc,Folio:Folio,MedioContacto:MedioContacto,TipoCotizacion:TipoCotizacion,Categoria:Categoria,Tipo:Tipo,UsuarioRegistra:UsuarioRegistra,NoEstatus:NoEstatus,Fecha1:Fecha1,Fecha2:Fecha2}
    );


}
function fn04CalculaCotizador(opc) {

    var txtPrecio = setFormatoMoneda(1,$("#txtPrecio").val());
    var txtPrecioPEX =setFormatoMoneda(1,$("#txtPrecioPEX").val());
    var ClaseAE = $("#txtClaseAE");
    var ClaseBE = $("#txtClaseBE");
    var ClaseCE = $("#txtClaseCE");
    var ClaseAC = $("#txtClaseAC");
    var ClaseBC = $("#txtClaseBC");
    var ClaseCC = $("#txtClaseCC");

    txtPrecioPEX = txtPrecio * opc ;
    //txtPrecioPEX = txtPrecioPEX.roundTo(10);

    $.ajax({
       url:"modules/04PVenta/src/cotizador/fnCalculaCotizador.php",
       type:"post",
       dataType:"json",
       data:{
           demanda:opc,
           precio:txtPrecio,
           precioPEX:txtPrecioPEX,
           claseae:0.80,claseac:0.83,
           clasebe:0.74,clasebc:0.77,
           clasece:0.57,clasecc:0.57,
       }
    }).done(function(response){

        console.log(response);

        $("#txtPrecioPEX").val(response.data.precioPex);
        $("#txtClaseAE").val(response.data.claseae);
        $("#txtClaseBE").val(response.data.clasebe);
        $("#txtClaseCE").val(response.data.clasece);
        $("#txtClaseAC").val(response.data.claseac);
        $("#txtClaseBC").val(response.data.clasebc);
        $("#txtClaseCC").val(response.data.clasecc);

        $('.currency').numeric({prefix:'$ ', cents: true});

    });




}

function fn04EditarCotizacion(opc,idCotizacion,Serie){

    switch (opc){
        case 1:
            SendAjax(
                "modules/04PVenta/views/cotizador/",
                "FrmEditarCotizacion.php",
                null,
                "HomeContent",
                "post",
                null,
                {opc:opc,FolioCotizacion:idCotizacion,Serie:Serie}
            );
            break;
        case 2:
            var txtNombre = $("#txtNombreCliente").val(),
                txtMedioContacto = $("#idmediocontacto").val(),
                txtTipoCotizacion = $("#idtipocotizacion").val(),
                txtCategoria = $("#idcategoria").val(),
                txtTipoProducto = $("#id_tpoproducto").val(),
                txtMontoSolicitado = $("#txtMontoSolicitado").val(),
                txtMontoAutorizado = $("#txtMontoAutorizado").val(),
                txtDescripcion = $("#txtDescripcion").val(),
                txtObservaciones = $("#txtObservaciones").val(),
                FolioCotizacion = $("#txtFolio").val(),
                SerieCotizacion = $("#txtSerie").val(),

                txtMontoSolicitado = setFormatoMoneda(1,txtMontoSolicitado);
                txtMontoAutorizado = setFormatoMoneda(1,txtMontoAutorizado);

            if($.trim(txtNombre) == ""){
                MyAlert("Ingrese el nombre del cliente","alerterror","#txtNombreCliente");
            }else if(txtMedioContacto == 0){
                MyAlert("Seleccione el medio de contacto","alerterror","#idmediocontacto");
            }else if(txtTipoCotizacion == 0){
                MyAlert("Seleccione el tipo cotización","alerterror","#idtipocotizacion");
            }else if(txtCategoria == 0){
                MyAlert("Seleccione la categoría","alerterror","#idcategoria");
            }else if(txtTipoProducto == 0){
                MyAlert("Seleccione el tipo de producto","alerterror","#id_tpoproducto");
            }else if(txtMontoAutorizado <= 0){
                MyAlert("Ingrese el Monto Autorizado","alerterror","#txtMontoAutorizado");
            }else{

                $.ajax({
                    url:"modules/04PVenta/src/cotizador/fnEditarCotizacion.php",
                    type:"post",
                    dataType:"json",
                    data:{
                        Serie:SerieCotizacion,
                        FolioCotizacion:FolioCotizacion,
                        txtNombre:txtNombre,txtMedioContacto:txtMedioContacto,txtTipoCotizacion:txtTipoCotizacion,txtCategoria:txtCategoria,txtTipoProducto:txtTipoProducto,
                        txtMontoSolicitado:txtMontoSolicitado,txtMontoAutorizado:txtMontoAutorizado,txtDescripcion:txtDescripcion,txtObservaciones:txtObservaciones
                    },
                    beforeSend:function () {
                        fnloadSpinner(1);
                    }
                }).done(function(response){
                    fnloadSpinner(2);

                    if(response.result){
                        fnsdMenu(41,41);

                        //MyAlert("","ok");
                    }else{
                        MyAlert(response.message,"error");
                    }

                }).fail(function(jqHR,textStatus,errno){
                    fnloadSpinner(2);
                    if(console && console.log){

                        if(textStatus == 'timeout'){

                            MyAlert("Tiempo de espera agotado, para esta solicitud","error");

                        }else{


                            MyAlert("Error al cargar la vista {" +  textStatus + " =>  "+ errno +"<br> "+jqHR+ "<br>" +"}","error");

                        }

                    }


                });


            }

            break;
        default:
            MyAlert("Error la opcion no existe","error");
            break;
    }

}

function fn04ListarCotizaciones(opc,estatus){
    $.ajax({
        type: "POST",
        url: "modules/04PVenta/src/cotizador/fnListarCotizacionesJson.php",
        data:{opc:opc,estatus:estatus},
        dataType: 'json',
        success: function(json) {

            var grid;

            var columns = [
                {id: "id", name: "Folio", field: "id",width:65,cssClass: "text-center  btn-link", formatter: formatterGrid},
                {id: "cliente", name: "Cliente", field: "cliente",width:210},
                {id: "descripcion", name: "Descripcion", field: "descripcion",width:280},
                {id: "montoautorizado", name: "Cotización",cssClass:"text-right", field: "montoautorizado",width:85,formatter:formatterGrid,formatter:Slick.Formatters.CurrencyFormatter},
                {id: "montoprestamo", name: "Prestamo",cssClass:"text-right", field: "montoprestamo",width:85,formatter:formatterGrid,formatter:Slick.Formatters.CurrencyFormatter},

                {id: "boletaprestamo", name: "Boleta", field: "boletaprestamo",width:80},
                {id: "estatus", name: "Estatus", field: "estatus",width:75,cssClass:"text-center"},
                {id: "usuarioa", name: "Usuario Registro", field: "usuarioa",width:135},
                {id: "fechainicial", name: "Fch. Inicial", field: "fechainicial",width:110,formatter:formatterGrid},
                {id: "fechavigencia", name: "Fch. Vigencia", field: "fechavigencia",width:110,formatter:formatterGrid},
                {id: "fecharegistro", name: "Fch. Registro", field: "fecharegistro",width:110,formatter:formatterGrid}
            ];

            var options = {
                enableCellNavigation: true,
                enableColumnReorder: false,
                multiColumnSort: true,
                editable: true,
                enableAddRow: true
            };

            var data = json;
            //console.log(data);
            $("#idtotal").text(json[0].TotalRow);

            grid = new Slick.Grid("#myGrid", data, columns, options);

            grid;
        }
    });
}


function fn04GuardarCotizacion(HoraInicial){

    var txtNombre = $("#txtNombreCliente").val(),
        txtMedioContacto = $("#idmediocontacto").val(),
        txtTipoCotizacion = $("#idtipocotizacion").val(),
        txtCategoria = $("#idcategoria").val(),
        txtTipoProducto = $("#id_tpoproducto").val(),
        txtMontoSolicitado = $("#txtMontoSolicitado").val(),
        txtMontoAutorizado = $("#txtMontoAutorizado").val(),
        txtDescripcion = $("#txtDescripcion").val(),
        txtObservaciones = $("#txtObservaciones").val(),

    txtMontoSolicitado = setFormatoMoneda(1,txtMontoSolicitado);
    txtMontoAutorizado = setFormatoMoneda(1,txtMontoAutorizado);

    if($.trim(txtNombre) == ""){
        MyAlert("Ingrese el nombre del cliente","alerterror","#txtNombreCliente");
    }else if(txtMedioContacto == 0){
        MyAlert("Seleccione el medio de contacto","alerterror","#idmediocontacto");
    }else if(txtTipoCotizacion == 0){
        MyAlert("Seleccione el tipo cotización","alerterror","#idtipocotizacion");
    }else if(txtCategoria == 0){
        MyAlert("Seleccione la categoría","alerterror","#idcategoria");
    }else if(txtTipoProducto == 0){
        MyAlert("Seleccione el tipo de producto","alerterror","#id_tpoproducto");
    }else if(txtMontoAutorizado <= 0){
        MyAlert("Ingrese el Monto Autorizado","alerterror","#txtMontoAutorizado");
    }else{

        $.ajax({
            url:"modules/04PVenta/src/cotizador/fnRegistraCotizacion.php",
            type:"post",
            dataType:"json",
            data:{txtNombre:txtNombre,txtMedioContacto:txtMedioContacto,txtTipoCotizacion:txtTipoCotizacion,txtCategoria:txtCategoria,txtTipoProducto:txtTipoProducto,
            txtMontoSolicitado:txtMontoSolicitado,txtMontoAutorizado:txtMontoAutorizado,txtDescripcion:txtDescripcion,HoraInicial:HoraInicial,txtObservaciones:txtObservaciones
            },
            beforeSend:function () {
                fnloadSpinner(1);
            }
        }).done(function(response){
            fnloadSpinner(2);

            if(response.result){
                fnsdMenu(41,41);
                MyAlert("Nombre Cliente: "+ response.data.NombreCliente +"<br>Monto Autorizado: "+response.data.MontoAutorizado+"<br>Folio: "+response.data.Folio,"ok");
            }else{
                MyAlert(response.message,"error");
            }

        }).fail(function(jqHR,textStatus,errno){
            fnloadSpinner(2);
            if(console && console.log){

                if(textStatus == 'timeout'){

                    MyAlert("Tiempo de espera agotado, para esta solicitud","error");

                }else{


                    MyAlert("Error al cargar la vista {" +  textStatus + " =>  "+ errno +"<br> "+jqHR+ "<br>" +"}","error");

                }

            }


        });


    }



}

function showReloj(Fecha,HoraInicial) {

    var hoy=new Date(Fecha+" "+HoraInicial);
    var h=hoy.getHours(); var m=hoy.getMinutes(); var s=hoy.getSeconds();

    s++;

    if (s == 60) {
        s = 0;
        m++;
        if (m == 60) {
            m = 0;
            h++;
            if (h == 24) {
                h = 0;
            }
        }
    }

    s = actualizarHora(s);
    m = actualizarHora(m);
    h = actualizarHora(h);

    var Hora = h + ":" + m + ":" + s;

    document.getElementById("liveclock").innerHTML =  Hora;
    setTimeout(function () {
        showReloj(Fecha,Hora);
    },1000)
}
function actualizarHora(i) {

    if (i<10) {i = "0" + i};  // Añadir el cero en números menores de 10

    return i;

}

function fn04MenuCotizaciones(opc){

    switch (opc){
        case 1:
            SendAjax(
                "modules/04PVenta/views/cotizador/",
                "FrmNuevaCotizacion.php",
                null,
                "HomeContent",
                "post",
                null,
                {opc:opc}
            );
            break;
        case 2:
            break;
        default:
            MyAlert("Opcion no valida","error");
            break;
    }

}

/***
 * Funciones para la administracion de PRODUCTOS WEB
 */

function fn_imagen_producto_web(opc,idcategoria,idcodigo,idserie,idimagen){

    var archivo = document.getElementById('btn_upload');
    var file = archivo.files[0];
    var data = new FormData();
    if (archivo.files && archivo.files[0]) {

        data.append('archivo',file);
        data.append('opc',opc);
        data.append('idcategoria',idcategoria);
        data.append('idcodigo',idcodigo);
        data.append('idserie',idserie);
        data.append('idimagen',idimagen);

        $.ajax({
            url:"modules/04PVenta/src/productos_web/fn_imagenes_productos_web.php",
            type:"post",
            contentType:false,
            data:data,
            processData:false,
            cache:false,
            beforeSend:function(){
                fnloadSpinner(1);
            },
            success:function(data){
                $("#listar_imagenes").html(data);
                fnloadSpinner(2);
            }
        });

    }else{

        MyAlert("No se encontro una imagen valida, seleccione una","error");

    }



}

function fn_ve_imagen_producto_web(opc,idcodigo,idserie,idimagen) {
    SendAjax("modules/04PVenta/src/productos_web/","fn_ver_imagenes_producto_web.php",null,"idgeneral","post",null,{opc:opc,idserie:idserie,idcodigo:idcodigo,idimagen:idimagen});
}

function fn_eliminar_imagen_producto_web(opc,idcodigo,idserie,idimagen){

    SendAjax("modules/04PVenta/src/productos_web/","fn_eliminar_imagenes_producto_web.php",null,"listar_imagenes","post",null,{opc:opc,idserie:idserie,idcodigo:idcodigo,idimagen:idimagen});

    //alert(opc + " " + idserie + " " + idcodigo + " " + idimagen);
}

function fn_listar_imagenes_producto_web(opc,idcodigo,idserie) {

    SendAjax("modules/04PVenta/src/productos_web/","fn_listar_imagenes_producto_web.php",null,"listar_imagenes","post",null,{opc:opc,idserie:idserie,idcodigo:idcodigo});


}

function fn_agregar_imagenes(opc,idcategoria,idcodigo,idserie,idimagen){

    switch (opc){
        //Mostrar Modal para subir imagenes a Nivel Codigo
        case 1:
            SendAjax("modules/04PVenta/views/productos_web/","frm_subir_imagen_nivel_codigo.php",null,"modal_result","post",null,{opc:opc,idcategoria:idcategoria,idserie:idserie,idcodigo:idcodigo,idimagen:idimagen});

            break;
        //Mostrar modal para cargar las imagenes a nivel Serie
        case 2:
            SendAjax("modules/04PVenta/views/productos_web/","frm_subir_imagen_nivel_serie.php",null,"modal_result","post",null,{opc:opc,idcategoria:idcategoria,idserie:idserie,idcodigo:idcodigo,idimagen:idimagen});
            break;
        //Registrar las imagenes del nivel codigo a nivel serie
        case 3:
            SendAjax("modules/04PVenta/src/productos_web/","fn_agregar_imagenes_codigo_a_serie.php",null,"result","post",null,{opc:1,idcategoria:idcategoria,idserie:idserie,idcodigo:idcodigo,idimagen:idimagen});
            break;
        default:
            MyAlert("la opcion solicita no existe","alert");

            break;
    }

}

function  fn_registrar_caracteristica(opc,idcategoria,idcodigo,idserie,idcaracteristica = 0) {


    switch (opc){
        //Mostrar modal para agregar caracteristicas a Nivel Serie
        case 1:

            SendAjax("modules/04PVenta/views/productos_web/","frm_agregar_caracteristica_producto_web.php",null,"modal_result","post",null,{opc:opc,idcategoria:idcategoria,idserie:idserie,idcodigo:idcodigo});
            break;
        //funcion para registrar las caracteristicas a Nivel Serie
        case 2:
            //funcion para registrar las caracteristicas
            if($("#idcaracteristica").val() == 0){
                MyAlert("Seleccione una caracteristica","alert");

            }else if($.trim($("#valor_caracteristica").val()) == ""){
                MyAlert("Ingrese el valor de la caracteristica","alert");
            }else{
                SendAjax("modules/04PVenta/src/productos_web/","fn_agregar_caracteristica_producto_web.php",null,"tblista","post",null,
                    {
                        opc:opc,
                        idcategoria:idcategoria,
                        idserie:idserie,
                        idcodigo:idcodigo,
                        valor_caracteristica:$("#valor_caracteristica").val(),
                        idcaracteristica:$("#idcaracteristica").val()
                    }
                );

            }

            break;
        //Funcion para Agregar Caracterisitcas a Nivel Codigo
        case 3:

            if($("#idcaracteristica").val() == 0){
                MyAlert("Seleccione una caracteristica","alert");

            }else if($.trim($("#valor_caracteristica").val()) == ""){
                MyAlert("Ingrese el valor de la caracteristica","alert");
            }else{
                SendAjax("modules/04PVenta/src/productos_web/","fn_agregar_caracteristica_producto_web.php",null,"tblista","post",null,
                    {
                        opc:8,
                        opcion:opc,
                        idcategoria:idcategoria,
                        idserie:idserie,
                        idcodigo:idcodigo,
                        valor_caracteristica:$("#valor_caracteristica").val(),
                        idcaracteristica:$("#idcaracteristica").val()
                    }
                );

            }
            break;
        //Editar Caracteristica a Nivel Serie
        case 4:
            //funcion para editar la caracteristica
            if($("#idcaracteristica").val() == 0){
                MyAlert("Seleccione una caracteristica","alert");

            }else if($.trim($("#valor_caracteristica").val()) == ""){
                MyAlert("Ingrese el valor de la caracteristica","alert");
            }else{

                $.ajax({
                    data:{opc:opc,idcategoria:idcategoria,idserie:idserie,idcodigo:idcodigo,idcaracteristica:$("#idcaracteristica").val(),valor_caracteristica:$("#valor_caracteristica").val()},
                    type:"POST",
                    beforeSend:function(){
                        fnloadSpinner(1);
                    },
                    dataType:"JSON",
                    url:"modules/04PVenta/src/productos_web/fn_mostrar_caracteristicas.php"
                }).done( function(data) {

                    fnloadSpinner(2);
                    console.log(data);
                    if(data.confirm == "ok"){

                        getMessageNotify("Mensaje",data.mensaje,"info","1000");
                        fn_registrar_caracteristica(5,idcategoria,idcodigo,idserie,0);
                        $("#btnagregar").show();
                        $("#btncambiar").hide();
                        $("#idcaracteristica").attr('disabled',false);
                        $('#idcaracteristica option[value='+0+' ]').prop('selected', true).change();
                        $("#valor_caracteristica").val('');
                        $("#valor_caracteristica").focus();

                    }else{

                        MyAlert(data.mensaje,"alert");

                    }

                }).fail(function(jqXHR,textStatus,errorThrown){
                    //fnloadSpinner(2);
                    console.log(textStatus + errorThrown);

                });

            }

            break;
        //Listar Caracterisitcas a Nivel Serie
        case 5:
            SendAjax("modules/04PVenta/src/productos_web/","fn_mostrar_caracteristicas.php",null,"tblista","post",null,{opc:opc,idcategoria:idcategoria,idserie:idserie,idcodigo:idcodigo});
            break;
        // ELiminar Caracteristica a Nivel Serie
        case 6:
            $.ajax({
                data:{opc:opc,idcategoria:idcategoria,idserie:idserie,idcodigo:idcodigo,idcaracteristica:idcaracteristica},
                type:"POST",
                beforeSend:function(){
                    fnloadSpinner(1);
                },
                dataType:"JSON",
                url:"modules/04PVenta/src/productos_web/fn_mostrar_caracteristicas.php"
            }).done( function(data) {

                fnloadSpinner(2);
                console.log(data);
                if(data.confirm == "ok"){

                    fn_registrar_caracteristica(5,idcategoria,idcodigo,idserie,0);

                }else{

                    MyAlert(data.mensaje,"alert");

                }

            }).fail(function(jqXHR,textStatus,errorThrown){
                //fnloadSpinner(2);
                console.log(textStatus + errorThrown);

            });

            break;
        case 7:

            $.ajax({
                data:{opc:opc,idcategoria:idcategoria,idserie:idserie,idcodigo:idcodigo,idcaracteristica:idcaracteristica},
                type:"POST",
                beforeSend:function(){
                    fnloadSpinner(1);
                },
                dataType:"JSON",
                url:"modules/04PVenta/src/productos_web/fn_mostrar_caracteristicas.php"
            }).done( function(data) {

                fnloadSpinner(2);
                console.log(data);
                if(data.confirm == "ok"){

                    $("#btnagregar").hide();
                    $("#btncambiar").show();
                    $("#idcaracteristica").attr('disabled','disabled');
                    $('#idcaracteristica option[value='+idcaracteristica+' ]').prop('selected', true).change();
                    $("#valor_caracteristica").val(data.descripcion);
                    $("#valor_caracteristica").focus();

                }else{

                    MyAlert(data.mensaje,"alert");

                }

            }).fail(function(jqXHR,textStatus,errorThrown){
                //fnloadSpinner(2);
                console.log(textStatus + errorThrown);

            });


            break;
        case 8:
            // Agregar Caracteristica a Nivel Codigo
            if($("#idcaracteristica").val() == 0){
                MyAlert("Seleccione una caracteristica","alert");

            }else if($.trim($("#valor_caracteristica").val()) == ""){
                MyAlert("Ingrese el valor de la caracteristica","alert");
            }else{
                SendAjax("modules/04PVenta/src/productos_web/","fn_agregar_caracteristica_producto_web.php",null,"carac_codigo","post",null,
                    {
                        opc:opc,
                        idcategoria:idcategoria,
                        idserie:idserie,
                        idcodigo:idcodigo,
                        valor_caracteristica:$("#valor_caracteristica").val(),
                        idcaracteristica:$("#idcaracteristica").val()
                    }
                );

            }

            break;
        // Listar Caracteristicas a nivel Codigo lectura
        case 9:

            SendAjax("modules/04PVenta/src/productos_web/","fn_mostrar_caracteristicas.php",null,"carac_codigo","post",null,{opc:opc,idcategoria:idcategoria,idserie:idserie,idcodigo:idcodigo});

            break;
        // Listar Caracteristicas a nivel Codigo lectura y escritura
        case 10:

            SendAjax("modules/04PVenta/src/productos_web/","fn_mostrar_caracteristicas.php",null,"tblista","post",null,{opc:opc,idcategoria:idcategoria,idserie:idserie,idcodigo:idcodigo});

            break;
        //Guardar Cambios de Caracteristica a Nivel Codigo
        case 11:

            //funcion para editar la caracteristica
            if($("#idcaracteristica").val() == 0){
                MyAlert("Seleccione una caracteristica","alert");

            }else if($.trim($("#valor_caracteristica").val()) == ""){
                MyAlert("Ingrese el valor de la caracteristica","alert");
            }else {

                $.ajax({
                    data: {
                        opc: 11,
                        opcion: opc,
                        idcategoria: idcategoria,
                        idserie: idserie,
                        idcodigo: idcodigo,
                        idcaracteristica: $("#idcaracteristica").val(),
                        valor_caracteristica: $("#valor_caracteristica").val()
                    },
                    type: "POST",
                    beforeSend: function () {
                        fnloadSpinner(1);
                    },
                    dataType: "JSON",
                    url: "modules/04PVenta/src/productos_web/fn_mostrar_caracteristicas.php"
                }).done(function (data) {

                    fnloadSpinner(2);
                    console.log(data);
                    if (data.confirm == "ok") {

                        getMessageNotify("Mensaje", data.mensaje, "info", "1000");
                        fn_registrar_caracteristica(10, idcategoria, idcodigo, idserie, 0);
                        $("#btnagregar").show();
                        $("#btncambiar").hide();
                        $("#idcaracteristica").attr('disabled', false);
                        $('#idcaracteristica option[value=' + 0 + ' ]').prop('selected', true).change();
                        $("#valor_caracteristica").val('');
                        $("#valor_caracteristica").focus();

                    } else {

                        MyAlert(data.mensaje, "alert");

                    }

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    //fnloadSpinner(2);
                    console.log(textStatus + errorThrown);

                });
            }

                break;
            //Opcion para agregar caracteristicas a nivel Codigo
        case 12:
            SendAjax("modules/04PVenta/views/productos_web/","frm_agregar_caracteristica_producto_por_codigo_web.php",null,"modal_result","post",null,{opc:opc,idcategoria:idcategoria,idserie:idserie,idcodigo:idcodigo});

            break;
        //Eliminar Caracteristica a Nivel Codigo
        case 13:
            $.ajax({
                data:{opc:6,idcategoria:idcategoria,idserie:idserie,idcodigo:idcodigo,idcaracteristica:idcaracteristica},
                type:"POST",
                beforeSend:function(){
                    fnloadSpinner(1);
                },
                dataType:"JSON",
                url:"modules/04PVenta/src/productos_web/fn_mostrar_caracteristicas.php"
            }).done( function(data) {

                fnloadSpinner(2);
                console.log(data);
                if(data.confirm == "ok"){

                    fn_registrar_caracteristica(9,idcategoria,idcodigo,idserie,0);
                    fn_registrar_caracteristica(10,idcategoria,idcodigo,idserie,0);

                }else{

                    MyAlert(data.mensaje,"alert");

                }

            }).fail(function(jqXHR,textStatus,errorThrown){
                //fnloadSpinner(2);
                console.log(textStatus + errorThrown);

            });
            break;
        //Argegar caracteristica de nivel codigo a nivel Serie
        case 14:

            SendAjax("modules/04PVenta/src/productos_web/","fn_agregar_caracteristicas_codigo_a_serie.php",null,"result_modal","post",null,{
                opc:opc,opcion:1,idcategoria:idcategoria,idserie:idserie,idcodigo:idcodigo,idcaracteristica:idcaracteristica
            });

            break;
        //Argegar todas las caracteristica de nivel codigo a nivel Serie
        case 15:
            break;
        default:
            MyAlert("la opcion solicita no existe","alert");
            break;
    }

}

function fn_editar_producto_web(opc,idcodigo,idcategoria){


    SendAjax("modules/04PVenta/views/productos_web/","frm_editar_producto_web.php",null,"lListarTabla","post",null,{opc:opc,idcodigo:idcodigo,idcategoria:idcategoria});

}

function fn_productos_web_nuevo(opc){

    var urlPhp,nameView,idDiv,isType,strData,idBoton = null,send = false;

    switch (opc){
        case 1:
            //Opcion para mostrar el formulario
            urlPhp = "modules/04PVenta/views/productos/";
            nameView = "frm_nuevo_producto_web.php";
            idDiv = "lListarTabla";
            isType = "POST";
            strData = {opcion:opc};
            send = true;
            break;
        case 2:
            //Opcion para guardar el nuevo Producto
        default:
            MyAlert("No se encontro la Opción solicitada","error");
            break;
    }

    if(send){
        SendAjax(
            urlPhp,nameView,null,idDiv,isType,idBoton,strData
        )
    }

}

function fn_productos_web_listar(opc) {

    var confirm = false,strData,idDiv,isType,urlPhp,nameView ;

    switch (opc){
        case 1:
            //Listar todos los productos
            idDiv = "lListarTabla";
            isType = "POST";
            urlPhp = "modules/04PVenta/src/productos/";
            nameView = "json_listar_productos_web.php";
            strData = {opc:opc};

            confirm = true;

            break;
        default:
            MyAlert("No se encontro la opción solicitada","error");
            break;
    }

    if(confirm){
        SendAjax(
            urlPhp,nameView,null,idDiv,isType,null,strData
        );
    }
}

function fn_caracteristicas_producto_web(opc,idcaracteristica){

    var urlPhp,nameView,idDiv,isType,strData,idBoton = null,send = false;


    switch (opc){
        case 1:
            //Mostrar modal para el alta de la nueva caracteristica
            urlPhp = "modules/04PVenta/views/productos_web/";
            nameView = "frm_nueva_caracteristica_producto_web.php";
            idDiv = "idgeneral";
            isType = "POST";
            strData = {opcion:1};
            send = true;

            if(send){
                SendAjax(
                    urlPhp,nameView,null,idDiv,isType,idBoton,strData
                )
            }
            break;
        case 2:
            //Guardar la nueva categoria
            //Mostrar modal para el alta de la nueva caracteristica
            urlPhp = "modules/04PVenta/src/productos_web/";
            nameView = "fn_nueva_caracteristica_producto_web.php";
            idDiv = "result_modal";
            isType = "POST";

            var nombre_caracteristica = $("#NombreCategoria").val(),
                NoCategoria = $("#NoCategoria").val(),
                Orden = $("#Orden").val(),
                Estatus = $("#Estatus").val();

            if($.trim(nombre_caracteristica) == ""){
                MyAlert("Ingrese el nombre de la categoría","alert");
            }else if(NoCategoria == 0){
                MyAlert("Seleccione una categoría","alert");
            }else{
                send = true;
                strData = {
                    nombre_caracteristica:nombre_caracteristica,
                    NoCategoria:NoCategoria,
                    Orden:Orden,
                    Estatus:Estatus,
                    opcion:opc
                };

            }

            if(send){
                SendAjax(
                    urlPhp,nameView,null,idDiv,isType,idBoton,strData
                )
            }
            break;
        case 3:
            //Mostrar modal para editar la categoria
            //Mostrar modal para el alta de la nueva caracteristica
            urlPhp = "modules/04PVenta/views/productos_web/";
            nameView = "frm_editar_caracteristica_producto_web.php";
            idDiv = "idgeneral";
            isType = "POST";
            strData = {opcion:opc,idcaracteristica:idcaracteristica};
            send = true;

            if(send){
                SendAjax(
                    urlPhp,nameView,null,idDiv,isType,idBoton,strData
                )
            }
            break;
        case 4:
            // Opcion para guardar los cambios en la caracteristica
            //Guardar la nueva categoria
            //Mostrar modal para el alta de la nueva caracteristica
            urlPhp = "modules/04PVenta/src/productos_web/";
            nameView = "fn_nueva_caracteristica_producto_web.php";
            idDiv = "result_modal";
            isType = "POST";

            var nombre_caracteristica = $("#NombreCategoria").val(),
                NoCategoria = $("#NoCategoria").val(),
                Orden = $("#Orden").val(),
                Estatus = $("#Estatus").val();

            if($.trim(nombre_caracteristica) == ""){
                MyAlert("Ingrese el nombre de la categoría","alert");
            }else if(NoCategoria == 0){
                MyAlert("Seleccione una categoría","alert");
            }else{
                send = true;
                strData = {
                    nombre_caracteristica:nombre_caracteristica,
                    NoCategoria:NoCategoria,
                    Orden:Orden,
                    Estatus:Estatus,
                    opcion:opc,
                    idcaracteristica:idcaracteristica
                };

            }

            if(send){
                SendAjax(
                    urlPhp,nameView,null,idDiv,isType,idBoton,strData
                )
            }

            break;
        default:
            MyAlert("La opcion no existe","alert");
            break;
    }

}

function fn_caracteristicas_productos_web_listar(opc){
    var confirm = false,strData,idDiv,isType,urlPhp,nameView ;

    switch (opc){
        case 1:
            //Listar todos los productos
            idDiv = "lListarTabla";
            isType = "POST";
            urlPhp = "modules/04PVenta/src/productos/";
            nameView = "json_listar_caracteristicas_productos_web.php";
            strData = {opc:opc};

            confirm = true;

            break;
        default:
            MyAlert("No se encontro la opción solicitada","error");
            break;
    }

    if(confirm){
        SendAjax(
            urlPhp,nameView,null,idDiv,isType,null,strData
        );
    }
}

/***
 * Funciones para la administracion de encuestas de producto
 */

function DatosEncuesta(idEncuesta){

    SendAjax(
       "modules/04PVenta/views/encuestas/",
        "frm_ver_encuesta_producto.php",
        null,
        "modalDataEncuesta",
        "post",
        null,
        {idEnc:idEncuesta}
    );
}

function fn_pv_exportar(opcion){

    if($("#total_encuestas").val() == 0){
        bootbox.confirm({
            title:"Exportar Encuesta",
            message: ptabla +"Se exportara todas las encuestas y puede demorar un poco, Esta seguro de continuar",
            size:"small",
            callback:function(result){
                if(result){

                    window.open( "modules/04PVenta/reportes/encuestas/rpt_encuestas_productos.php");


                }
            }
        });
    }else{
        window.open( "modules/04PVenta/reportes/rpt_encuestas_productos.php");
    }

}

function fn_pv_cargar_marcas(TipoProducto,idcategoria){
    $.ajax({
        url:"modules/04PVenta/src/productos/fn_loadMarcas.php",
        type:"POST",
        data:{idproducto:TipoProducto,idcategoria:idcategoria},
        beforeSend:function(){
        },
        success:function(data){
            $("#id_marca").html(data);
        }
    });
}
function fn_pv_cargar_usuarios(NoDepartamento){
    $.ajax({
        url:"modules/04PVenta/src/encuestas/fnload_cargar_usuarios.php",
        type:"POST",
        data:{nosuc:NoDepartamento},
        beforeSend:function(){
        },
        success:function(data){
            $("#loadUsersSucursal").html(data);
        }
    });
}

function fn_pv_BuscarEncuesta(tipoBusqueda){

    var suc = $("#id_suc").val();
    var usr = $("#filUser").val();
    var sup = $("#id_super").val();
    var zon = $("#id_zona").val();
    var fci = $("#fch_ini").val();
    var fcf = $("#fch_fin").val();
    var prd = $("#id_prod").val();
    var mar = $("#id_marca").val();
    var cls = $("#idClass").val(),
        dCategoria = $("#idNoCategoria").val(),
        dCompetidor = $("#idCompetidor").val(),
        dTpoServicio = $("#idTpoServicio").val();

    if(tipoBusqueda == 1){
        $.ajax({
            url:"modules/04PVenta/src/encuestas/jsonLoadEncuestaProducto.php",
            type:"POST",
            data:{opc:tipoBusqueda,nosuc:suc,nousr:usr,nosup:sup,nozon:zon,fchaini:fci,fchafin:fcf,nopro:prd,nomar:mar,clasificacion:cls,
                Nocategoria:dCategoria,NoCompetidor:dCompetidor,TpoServicio:dTpoServicio},
            beforeSend:function(){
                fnloadSpinner(1,"fa-search","btn-Buscar");
            },
            success:function(data){
                $("#lListTable").html(data);
                $("#closemodal").click();
            }
        });
    }else{

        $.ajax({
            url:"modules/04PVenta/src/encuestas/jsonLoadEncuestaProducto.php",
            type:"POST",
            data:{opc:tipoBusqueda,nosuc:suc,nousr:usr,nosup:sup,nozon:zon,fchaini:fci,fchafin:fcf,nopro:prd,nomar:mar,clasificacion:cls,
                Nocategoria:dCategoria,NoCompetidor:dCompetidor,TpoServicio:dTpoServicio},
            beforeSend:function(){
                fnloadSpinner(1,"fa-search","btn-Buscar");
            },
            success:function(data){
                fnloadSpinner(2,"fa-search","btn-Buscar");
                $("#lListTable").html(data);
                $("#closemodal").click();
            }
        });

    }

}
function fnpv_exportar_resultado(TipoDocumento,md5){

    var condicion = $("#whereConsulta").text(),
        md5 = "v34s12w23",
        ajaxSend = false;

    if(condicion == ""){
        bootbox.confirm({
            size: 'small',
            message: ptabla + "Se exportara toda el catalogo de productos. esto puede demorar un poco, esta seguro de continuar.",
            title:"Exportar Productos",
            callback: function(result){ if(result){

                window.open("modules/04PVenta/reportes/productos/rpt.productos.php?ref=1&dat="+md5+"&cond="+condicion+"&opc=1");

            }}
        })
    }else{
        window.open("modules/04PVenta/reportes/productos/rpt.productos.php?ref=1&dat="+md5+"&cond="+condicion+"&opc=1");
    }




}

function loadCalidadMetal(Nocategoria){
    var tpoServicio = $("#frmServicioEncuesta").val();
    var NoAtendido = $("#frmNoAtedidos").val();

    loadCalidadMetalTxt(Nocategoria,tpoServicio);
    if(Nocategoria == 5 && NoAtendido != 4){
        cargaCombosEncuesta(1);
    }else{
        cargaCombosEncuesta(4);
    }

    $.ajax({
        url:"modules/04PVenta/src/encuestas/fn_loadCalidadMetal.php",
        type:"POST",
        data:{categoria:Nocategoria,tposerv:tpoServicio},
        success:function(data){
            $("#loadCalidadMetal").html(data);
        }
    });
}
function loadCalidadMetalTxt(Nocategoria,tpoServicio){
    $.ajax({
        url:"modules/04PVenta/src/encuestas/fn_loadCalidadMetaltxt.php",
        type:"POST",
        data:{categoria:Nocategoria,tposerv:tpoServicio},
        success:function(data){
            $("#loadCalidadMetalTxt").html(data);
        }
    });
}

function loadCategoriaServicioText(servicios){

    $.ajax({
        url:"modules/04PVenta/src/encuestas/fn_loadServiciosTxt.php",
        type:"POST",
        data:{noservicio:servicios},
        success:function(data){
            $("#loadServiciostxt").html(data);
        }
    });
}

function loadCategoriaServicio(servicio){
    var tpoarticulo = $("#tpo_articulo"),
        nomarca = $("#id_marca"),
        nombreprod = $("#nombreprod");
    var ctenoaten = $("#frmNoAtedidos").val();
    loadCategoriaServicioText(servicio);


    if(servicio == 3){
        tpoarticulo.attr('disabled','disabled');
        nomarca.attr('disabled','disabled');
        nombreprod.attr('disabled','disabled');
        $(".formDataProducto").Frmreset();
    }else{
        tpoarticulo.removeAttr('disabled');
        nomarca.removeAttr('disabled');
        nombreprod.removeAttr('disabled');
    }
    cargaCombosEncuesta(ctenoaten);

    $.ajax({
        url:"modules/04PVenta/src/encuestas/fn_loadServicios.php",
        type:"POST",
        data:{noservicio:servicio},
        success:function(data){
            $("#loadServicios").html(data);
        }
    });
}
function cargaCombosEncuesta(valor){
    $(".formDataProducto").Frmreset();
    var tpoarticulo = $("#tpo_articulo"),
        nomarca = $("#id_marca"),
        nombreprod = $("#nombreprod");
    var Nocategoria = $("#CategoriaServicio").val();
    var tpoServicio = $("#frmServicioEncuesta").val();

    if(valor == 4 ){
        $("#DescripcionProducto").removeAttr('disabled');
        $(".formDataProducto").Frmreset();
        tpoarticulo.attr('disabled','disabled');
        nomarca.attr('disabled','disabled');
        nombreprod.attr('disabled','disabled');
    }else{

        if(tpoServicio != 3){
            if(Nocategoria != 5){
                tpoarticulo.attr('disabled','disabled');
                nomarca.attr('disabled','disabled');
                nombreprod.attr('disabled','disabled');
            }else{
                $("#DescripcionProducto").attr('disabled','disabled');
                tpoarticulo.removeAttr('disabled');
                nomarca.removeAttr('disabled');
                nombreprod.removeAttr('disabled');
            }
        }
    }

}

function fnpv_show_modal_encuestas(codProducto,opc){
    $.ajax({
        url:"modules/04PVenta/views/encuestas/frm_encuesta_producto.php",
        type:"POST",
        data:{CodProd:codProducto},
        success:function(data){
            $(".ModalAjax").html(data);
        }
    });

}
/***
 * Funciones para la administracion
 * de productos
 */

function fnpv_guardar_encuesta_producto(CodProdcuto){

    var OpcNoAtendidos = $("#frmNoAtedidos").val();
    var ServicioEncuesta = $("#frmServicioEncuesta").val();
    var CondicionesEncuesta = $("#frmCondicionesEncuesta").val();
    var MontoSolicita = $("#frmMontoSolicitaEncuesta").val();
    var CompetidorEncuesta = $("#frmCompetidorEncuesta").val();
    var MontoCompetidor = $("#frmMontoCompetidorEncuesta").val();

    var observa = $("#observaciones").val(),
        descripprod = $("#DescripcionProducto").val(),
        dCalidadMetal = $("#dcalidadMetal").val(),
        catservicio = $("#CategoriaServicio").val(),
        dtpoArticulo = $("#tpo_articulo").val(),
        dNoMarca = $("#id_marca").val(),
        dNombreProducto = $("#nombreprod").val();

    if(OpcNoAtendidos == 0){
        MyAlert('Seleccione una opción de clientes no atendidos',"alert");
    }else if(ServicioEncuesta == 0){
        MyAlert('Seleccione el tipo de servicio',"alert");
        $("#frmServicioEncuesta").focus();
    }else if(ServicioEncuesta == 0){
        MyAlert("Seleccione el tipo de encuesta","alert");
    }else if(catservicio == 0){
        MyAlert("Seleccione una categoria","alert");
    }else if(OpcNoAtendidos != 4 && catservicio == 5  && dtpoArticulo == 0 && ServicioEncuesta  != 3 ){
        MyAlert('Seleccione el tipo del producto',"alert");
    }else if(OpcNoAtendidos != 4 && catservicio == 5 && dNoMarca == 0 && ServicioEncuesta  != 3){
        MyAlert('Seleccione el tipo de marca',"alert");
    }else if(OpcNoAtendidos != 4 && catservicio == 5 &&dNombreProducto == 0 && ServicioEncuesta  != 3){
        MyAlert('Seleccione el nombre del producto',"alert");
    }else if(ServicioEncuesta !=3 && CondicionesEncuesta == 0){
        MyAlert('Seleccione las condiciones del producto',"alert");
        $("#frmCondicionesEncuesta").focus();
    }else if(OpcNoAtendidos == 4 && descripprod == "" ){
        MyAlert('Agrege la Descripción del Producto ',"alert");
    }else if(ServicioEncuesta == 3 && observa == ""){
        MyAlert('Agrege una observación por la cual no se dio el servicio ',"alert");
    }else if(observa == "" && OpcNoAtendidos == 5 ){
        MyAlert("Agrege una observación por la cual no se dio el servicio","alert");
    }else{
        //alert(dNombreProducto);
        bootbox.confirm({
            title:"Guardar Encuesta",
            message: ptabla +"Esta seguro de guardar la encuesta ",
            size:"small",
            callback:function(result){
                if(result){
                    $.ajax({
                        url:'modules/04PVenta/src/encuestas/fn_GuardaEncuestaProducto.php',
                        type:'POST',
                        data:{calidadmetal:dCalidadMetal,catServ:catservicio,descprod:descripprod,obsrv:observa,codprod:dNombreProducto,noatendida:OpcNoAtendidos,tpoServicio:ServicioEncuesta,condiciones:CondicionesEncuesta,
                            mtosolicita:MontoSolicita,competencia:CompetidorEncuesta,mtocompetidor:MontoCompetidor},
                        success:function(data){
                            $("#divresult").html(data);
                        }
                    });
                }
            }
        });
    }
}
function loadProductos(nomarca,idcategoria){
    var tpoarticulo = $("#tpo_articulo").val();

    $.ajax({
        url:"modules/04PVenta/src/encuestas/fn_loadProductos.php",
        type:"POST",
        data:{marca:nomarca,tpoart:tpoarticulo,idcategoria:idcategoria},
        success:function(data){
            $("#loadproductos").html(data);
        }
    });
}

function fnpv_load_productos(categoria){

    $.ajax({
        url:"modules/04PVenta/src/productos/fn_load_productos.php",
        type:"POST",
        data:{categoria:categoria},
        success:function(data){
            $("#id_tpoproducto").html(data);
        }
    });

}

//cargar marcas por producto y categoria
function fnpv_loadMarcas(idproducto,idcategoria){


    $.ajax({
        url: "modules/04PVenta/src/productos/fn_loadMarcas.php",
        type:"POST",
        data:{idproducto:idproducto,idcategoria:idcategoria},
        success:function(data){
            $("#id_marca").html(data);
        }
    });

}

//cargar marcas por categoria
function fnpv_load_marcas(categoria){

    $.ajax({
        url:"modules/04PVenta/src/productos/fn_load_marcas.php",
        type:"POST",
        data:{categoria:categoria},
        success:function(data){
            $("#id_marca").html(data);
        }
    });


}

function fnpv_load_categorias_clasificacion(opc,categoria){

    fnpv_load_productos(categoria);
    fnpv_load_marcas(categoria);

    if(categoria == 3){
        $("#precios_capturados").toggleClass('hidden');
    }else{
        $("#precios_capturados").addClass('hidden');
    }

    $.ajax({
        url:"modules/04PVenta/src/productos/fn_cargar_categorias.php",
        type:"POST",
        data:{categoria:categoria,opc:opc},
        success:function(data){
            $("#clasificacion").html(data);
        }
    });

}

//funcion para realizar la busqueda de proiductos
function fnpv_buscar_producto(){

    var prod = $("#id_producto2");
    var tpoprod = $("#id_tpoproducto").val();
    var nomarca = $("#id_marca").val();
    var clasifi = $("#clasificacion").val();
    var fchAlta_ini = $("#fchalta_ini").val();
    var fchAlta_fin = $("#fchalta_fin").val();
    var fchUm_ini = $("#fchum_ini").val();
    var fchUm_fin = $("#fchum_fin").val();
    var nousuario = $("#nousuario").val();
    var nousuariou = $("#nousuarioum").val();
    var txtDescrip = $("#txtDescripcionProd").val();
    var categoria_producto = $("#id_categoria_producto").val();

    if(tpoprod == 0 && nomarca == 0 && categoria_producto == 0 && clasifi == 0 && fchAlta_ini == "" && fchAlta_fin == "" && fchUm_ini == "" && fchUm_fin == "" && nousuario == 0 && nousuariou == 0 && txtDescrip == "" && $.trim(prod.val()) == "" ){
        MyAlert("Seleccione al menos un filtro o ingrese el codigo de producto","alert");
        prod.text("");
        prod.focus();

    }else if (!/^([0-9])*$/.test(prod.val())){

        MyAlert("El valor " + prod.val() + " no es un número","alert");
    }else{
        fnloadSpinner(1,"fa-search","btn-search-product");

        $.ajax({
            url:"modules/04PVenta/src/productos/fn_buscar_producto.php",
            type:"POST",
            data:{categoria_producto:categoria_producto,txtDescripProd:txtDescrip,producto:prod.val(),tpoproducto:tpoprod,marca:nomarca,clasificacion:clasifi,fchalIni:fchAlta_ini,fchaFin:fchAlta_fin,fchumini:fchUm_ini,fchumfin:fchUm_fin,nouser:nousuario,nouseru:nousuariou},
            beforeSend:function(){
                fnloadSpinner(1,"fa-search","btn-search-product");
            }
        }).done(function(data){

            fnloadSpinner(2,"fa-search","btn-search-product");

            $("#lListarTabla").html(data);
            $("#closemodal").click();
            $("#btnExport").removeAttr('disabled');
            $("#btn3").hide();
            $("#btn5").hide();

        }).fail(function(jqXHR,textStatus,errorThrown){
            fnloadSpinner(2,"fa-search","btn-search-product");

            if ( console && console.log ) {
                MyAlert( "La solicitud a fallado:<br>"+jqXHR+" <br>" +  textStatus + "<br>" +errorThrown,'error');
            }

        });
    }
}

//funcion para calcular precio
function fnpv_calcula_precio(){

    var importe = $("#calc_import"),
        idcategoria = $('#idcategoria').val();

    if(idcategoria == 0){
        MyAlert("Seleccione una categoría","alert");
    }else{
        $.ajax({
            url:"modules/04PVenta/src/productos/fn_calcula_precio.php",
            type:"POST",
            data:{importe:importe.val(),idcategoria:idcategoria},
            success:function(data){
                $("#result_calc").html(data);
            }
        });
    }

}

//Funcion para editar el producto seleccionado
function fnpv_guardar_cambios(){

    var noarticulo = $("#no_producto").val();
    var nocategoria = $("#nocategoria").val();
    var namearticulo = $("#name_producto").val();
    var tpoarticulio = $("#tpo_articulo").val();
    var nomarca = $("#id_marca").val();
    var fchalta = $("#fch_alta").val();
    var importevta = $("#importe_venta").val();
    var clasifica = $("#clasificacion").val();
    var fchum = $("#fch_um").val();
    var namefga = $("#rta_fga").val();
    var estatus = $("#estatus").val();

    var precio_compra = $("#precio_compra").val();
    var precio_empeno = $("#precio_empeno").val();
    var sendConfirm = false;


    if(namearticulo == ""){
        $("#name_producto").addClass("has-error");
        MyAlert("El nombre del producto no debe de estar vacio","alert","#name_producto");
    }else if(importevta == ""){
        MyAlert("El importe no debe de estar vacio","alert");
    }else if(fchalta == ""){
        MyAlert("La fecha alta no debe estar vacia","alert");
    }else if(clasifica == 0){
    MyAlert("Ingrese la clasificación del producto","alert");
    }else if(namefga == ""){
        MyAlert("Ingrese el nombre de la fotografia","alert");
    }else{

        sendConfirm = true;

        if(nocategoria == 3){

            if(precio_compra == "$ 0.00"){

                MyAlert("Ingrese el precio de venta","alert");
                exit;

            }else if(precio_empeno == "$ 0.00"){
                MyAlert("Ingrese el precio de empeño","alert");
                exit;

            }else{
                sendConfirm = true;
            }

        }

        var strData = {noart:noarticulo,nocate:nocategoria,namrart:namearticulo,tpoart:tpoarticulio,nomarc:nomarca,fchalt:fchalta,importe:importevta,clasif:clasifica,
            fchu:fchum,namefoto:namefga,esta:estatus,precio_compra:precio_compra,
            precio_empeno:precio_empeno};

        if(sendConfirm){

            bootbox.confirm({
                size: 'small',
                message:  ptabla +"Esta seguro de guardar los cambios en el producto",
                title:"Confirmación",
                callback: function(result){ if(result){

                    SendAjax(
                        "modules/04PVenta/src/productos/",
                        "fn_editar_articulo.php",
                        null,
                        "lListarTabla",
                        "POST",
                        null,
                        strData
                    );

                }}
            })

        }

    }

}

//funcion para registrar el producto nuevo
function fnpv_registra_articulo(){

    var nocategoria = $("#nocategoria").val();
    var namearticulo = $("#name_producto").val();
    var tpoarticulio = $("#id_tpoproducto").val();
    var nomarca = $("#id_marca").val();
    var fchalta = $("#fch_alta").val();
    var importevta = $("#importe_venta").val();
    var clasifica = $("#clasificacion").val();
    var fchum = $("#fch_um").val();
    var namefga = $("#rta_fga").val();
    var estatus = $("#estatus").val();
    var noproducto =$("#no_producto").val();

    var precio_compra = $("#precio_compra").val();
    var precio_empeno = $("#precio_empeno").val();
    var sendConfirm = false;

    var strData = {
            noprod:noproducto,
            nocate:nocategoria,
            namrart:namearticulo,
            tpoart:tpoarticulio,
            nomarc:nomarca,
            fchalt:fchalta,
            importe:importevta,
            clasif:clasifica,
            fchu:fchum,
            namefoto:namefga,
            precio_compra:precio_compra,
            precio_empeno:precio_empeno,
            esta:estatus}
        ;

    if(namearticulo == ""){
        MyAlert("Por favor ingrese el nombre del producto","alert");
    }else if(tpoarticulio == 0){
        MyAlert("Seleccione el tipo de producto","alert");
    }else if(nomarca == 0) {
        MyAlert("Seleccione un marca para el producto", "alert");

    }else if(clasifica == 0){
        MyAlert("Ingrese la clasificación del producto","alert");
    }else{

        sendConfirm = true;

        if(nocategoria == 3){

            if(precio_compra == "$ 0.00"){

                MyAlert("Ingrese el precio compra","alert");
                exit;

            }else if(precio_empeno == "$ 0.00"){
                MyAlert("Ingrese el precio del empeño","alert");
                exit;

            }else{
                sendConfirm = true;
            }

        }

        if(sendConfirm){
            bootbox.confirm({
                size: 'small',
                message: ptabla + "Esta seguro de guardar el producto",
                title:"Confirmación - Guardar Producto",
                callback: function(result){ if(result){

                    SendAjax(
                        "modules/04PVenta/src/productos/",
                        "fn_registra_producto.php",
                        null,
                        "idgeneral",
                        "POST",
                        null,
                        strData
                    );
                }}
            });
        }
    }
}

//Funcion nueva marca
function fn_nueva_marca(opc,pantalla,idcategoria){

    var send_ajax = false;

    switch (opc){
        //Mostrar el modal para el nuevo tipo de producto
        case 1:

            //que pantalla pide el modal,
            if(pantalla == 1){ // pantalla 1 es desde el formulario de nuevo producto

                if(idcategoria == 0){
                    MyAlert("Seleccione una categoría","alert");
                }else{
                    send_ajax = true;
                }
            }

            if(send_ajax){
                SendAjax(
                    "modules/04PVenta/views/productos/",
                    "frm_modal_nueva_marca.php",
                    null,
                    "idgeneral",
                    "post",
                    null,
                    {opc:opc,pantalla:pantalla,idcategoria:idcategoria}
                );
            }

            break;
        //Funcion para guardar la nueva tipo de producto
        case 2:

            var nocategoria = $("#nocategoria").val(),
                descripcion = $("#descripcion").val(),
                abreviacion = $("#abreviacion").val();
            if(nocategoria == 0){
                MyAlert("Seleccione una categoría","alert");
            }else if($.trim(descripcion) == ""){
                MyAlert("Escriba el nombre de la marca","alert");
            }else{

                SendAjax(
                    "modules/04PVenta/src/productos/",
                    "fn_guardar_nueva_marca.php",
                    null,
                    "modal_result",
                    "post",
                    null,
                    {
                        opc:opc,
                        pantalla:pantalla,
                        nocategoria:nocategoria,
                        descripcion:descripcion,
                        abreviacion:abreviacion
                    }
                );

            }


            break;
        default:
            MyAlert("La opcion solicitada no existe","error");
            break;
    }

}
//funcion nuevo tipo de producto
function fn_nuevo_tipo_producto(opc,pantalla,idcategoria){

    //Opcion 1: Mostrar modal para nuevo tipo de producto

    var send_ajax = false;

    switch (opc){
        //Mostrar el modal para el nuevo tipo de producto
        case 1:

            //que pantalla pide el modal,
            if(pantalla == 1){ // pantalla 1 es desde el formulario de nuevo producto

                if(idcategoria == 0){
                    MyAlert("Seleccione una categoría","alert");
                }else{
                    send_ajax = true;
                }
            }

            if(send_ajax){
                SendAjax(
                    "modules/04PVenta/views/productos/",
                    "frm_modal_nuevo_tipo_producto.php",
                    null,
                    "idgeneral",
                    "post",
                    null,
                    {opc:opc,pantalla:pantalla,idcategoria:idcategoria}
                );
            }

            break;
        //Funcion para guardar la nueva tipo de producto
        case 2:

            var nocategoria = $("#nocategoria").val(),
                tipo_aparato = $("#tipo_aparato").val(),
                descripcion = $("#descripcion").val(),
                abreviacion = $("#abreviacion").val();
            if(nocategoria == 0){
                MyAlert("Seleccione una categoría","alert");
            }else if(tipo_aparato == 0){
                MyAlert("Seleccione el tipo de aparato","alert");
            }else if($.trim(descripcion) == ""){
                MyAlert("Escriba el nombre del nuevo tipo de producto","alert");
            }else{

                SendAjax(
                    "modules/04PVenta/src/productos/",
                    "fn_guardar_nuevo_tipo_producto.php",
                    null,
                    "modal_result",
                    "post",
                    null,
                    {
                        opc:opc,
                        pantalla:pantalla,
                        nocategoria:nocategoria,
                        tipo_aparato:tipo_aparato,
                        descripcion:descripcion,
                        abreviacion:abreviacion
                    }
                );

            }


            break;
        default:
            MyAlert("La opcion solicitada no existe","error");
            break;
    }



}

//Funcion para mostrar el formulario para el nuevo producto
function fnpv_nuevo_producto(){

    var strData = {opc:1};

    SendAjax(
        "modules/04PVenta/views/productos/",
        "frm_nuevo_producto.php",
        null,
        "lListarTabla",
        "POST",
        null,
        strData
    );
}

//funcion para ver la informacion del producto
function fnpv_ver_producto(idProducto, idCategoria){

    var strData = {nprod:idProducto,categoria:idCategoria}

    SendAjax(
        "modules/04PVenta/views/productos/",
        "frm_editar_producto.php",
        null,
        "lListarTabla",
        "POST",
        null,
        strData
    );

}

//listar productos en slickGrid
function fnpv_listar_productos(opc,opc2){

    var strData = {opc:opc,opc2:opc2};

    $("#btn3").hide();
    $("#btn5").hide();
    $("#btn2").show();
    $("#btn4").show();
    $("#btnList").hide();

    SendAjax(
        "modules/04PVenta/src/productos/",
        "json_listar_productos.php",
        null,
        "lListarTabla",
        "POST",
        null,
        strData

    );



}

/**
 * Funciones para el DashBorad e Indicadores
 */
function fn_cargar_indicadores(indicador){

    var indicador = parseInt(indicador),strData ;
    var fecha_inicial = $('#fch_inicial').val(),
        fecha_final = $('#fch_final').val(),
        zona = $('#zona').val(),
        sucursales = $('#sucursales').val();

    switch (indicador){
        case 1:

            strData = {indicador:indicador,fecha_inicial:fecha_inicial,fecha_final:fecha_final,zona:zona,sucursales:sucursales}
            SendAjax(
              'modules/04PVenta/src/indicadores/',
                'fn_no_atendidos.php',
                null,
                'dashboard','post',null,strData
            );
            break;
        case 2:
            strData = {indicador:indicador,fecha_inicial:fecha_inicial,fecha_final:fecha_final,zona:zona,sucursales:sucursales}
            SendAjax(
                'modules/04PVenta/src/indicadores/',
                'fn_no_acepta_cotizacion.php',
                null,
                'dashboard','post',null,strData
            );
            break;
        case 3:
            strData = {indicador:indicador,fecha_inicial:fecha_inicial,fecha_final:fecha_final,zona:zona,sucursales:sucursales}
            SendAjax(
                'modules/04PVenta/src/indicadores/',
                'fn_por_competencia.php',
                null,
                'dashboard','post',null,strData
            );
            break;
        case 4:
            strData = {indicador:indicador,fecha_inicial:fecha_inicial,fecha_final:fecha_final,zona:zona,sucursales:sucursales}
            SendAjax(
                'modules/04PVenta/src/indicadores/',
                'fn_por_clasificacion.php',
                null,
                'dashboard','post',null,strData
            );
            break;
        case 5:
            strData = {indicador:indicador,fecha_inicial:fecha_inicial,fecha_final:fecha_final,zona:zona,sucursales:sucursales}
            SendAjax(
                'modules/04PVenta/views/indicadores/',
                'FrmHistorialTiposDeCambio.php',
                null,
                'dashboard','post',null,strData
            );
            break;
    }


}