/**
 * Created by alejandro.gomez on 21/09/2016.
 */
//Variable Publica para crear la tabla de los bootbox.confirm

var confirm_close = false;
var array_data ;
var ptabla = "" +
    "<table style='margin-top: 15px;'>" +
    "   <tr>" +
    "       <td rowspan='2' valign='middle' style='padding-left: 6px;' >" +
    "           <img src='site_design/img/icons/help.png' style='margin-right: 8px;' width='32'>" +
    "       </td>" +
    "   </tr>" +
    "   <tr>" +
    "       <td valign='middle' style='text-align: justify'>";


/**
 *
 * Apartado para la mensajeria
 * @param row
 * @param cell
 * @param value
 * @param columnDef
 * @param dataContext
 * @returns {*}
 */
function getlongPollingChat(timestamp)
{
    var polling = {};
    if(typeof timestamp != "undefined")
    {
        polling.timestamp = timestamp;
    }
    $.ajax({
        type: "get",
        url: "modules/applications/src/mensajes/getNotificacionMensajes.php",
        data: polling,
        dataType:"json",
        async:true,
        cache:false,
        success: function(res)
        {
            getlongPollingChat(res.timestamp);

            $(".bellMensajes").text(res.notificaciones);
            if(res.notificaciones > 0){
               if( $("#icon_bellMensajes").hasClass("infiner")){

               }else{
                   $("#icon_bellMensajes").addClass("infinite");
               }
            }else{
                $("#icon_bellMensajes").removeClass("infinite");
            }
        }
    })
}
function fngnFrmMensajeria(opc){
    
    //Mostrar Formulario de Mesajeria
    SendAjax(
        "modules/applications/views/mensajes/",
        "FrmMensajes.php",
        null,
        "chat",
        "post",
        null,
        {opc:opc}
    );

}

/**
 * Fin de la Mensajeria
 */

//funcion para Formatear las Celdas
function formatterGrid(row, cell, value, columnDef, dataContext) {
    return value;
}

function fngnEditarVideos(opc,idVideo) {

    switch (opc){
        case 1:
            //Mostrar Formulario de editar
            SendAjax(
              "modules/applications/views/videos/",
                "FrmEditarVideo.php",
                null,
                "listaVideos",
                "post",
                null,
                {opc:opc,idVideo:idVideo}
            );
            break;
        case 2:
            //Editgar la informacion del video

            var txtTitulo = $("#etxtTitulo").val(),
                txtEmpresa = $("#etxtEmpresa").val(),
                txtDescripcion = $("#etxtDescripcion").val(),
                txtUrl = $("#etxtUrl").val();

            //extraer la informacion delinput file
            var archivo = document.getElementById('eimagenVideo');

            if(txtEmpresa == 0){
                MyAlert("Seleccione la empresa a la que pertence el video","alerterror","#etxtEmpresa");
            }else if($.trim(txtTitulo) == ""){
                MyAlert("Ingrese el titulo de video","alerterror","#etxtTitulo");
            }else if($.trim(txtUrl) == ""){
                MyAlert("Ingrese la url del video","alerterror","#etxtUrl");
            }else if($('#etxtImagen').val() == ""){

                MyAlert("Selecciona la imagen del video","alerterror","#etxtImagen");

            }else{

                if($('#etypeVideo').val() == 1 && $('#ebtnvideoLocal').val() == ""){

                    MyAlert("Seleccione un vido para subir al servidor",'alert');

                }else{

                    $.ajax({
                        url:"modules/applications/src/videos/fnEditarVideo.php",
                        type:"post",
                        dataType:"json",
                        data:{txtTitulo:txtTitulo,TipoVideo:$("#etypeVideo").val(),txtEmpresa:txtEmpresa,txtDescripcion:txtDescripcion,txtUrl:txtUrl,opc:opc,idVideo:idVideo},
                        beforeSend:function(){
                            fnloadSpinner(1);
                        }
                    }).done(function(response){

                        fnloadSpinner(2);

                        console.log(response);

                        if(response.success){

                            //Todo Bien
                            getMessageNotify(null,response.message,'success',1500);
                            fngnAdministrarVideos(1);

                        }else{
                            MyAlert(response.message,"error");
                        }

                    }).fail(function (jqh,textStatus,errno) {

                        fnloadSpinner(2);
                        if(console && console.log){

                            if(textEstatus == 'timeout')
                            {
                                MyAlert('EL tiempo de la solicitud a sido agotado','alert');
                                //do something. Try again perhaps?

                            }else{

                                MyAlert(
                                    "Error al realizar la carga de la vista","alert"
                                );
                            }

                        }

                    });


                }

            }


            break;
        case 3:// Desactivar el Video

            bootbox.confirm({

                title:"Desactivar video",
                message:"Esta seguro en desactivar el video ?",
                size:"small",
                callback:function (result) {

                    if(result){

                        $.ajax({
                            url:"modules/applications/src/videos/fnEditarVideo.php",
                            type:"POST",
                            dataType:"JSON",
                            data:{opc:opc,idVideo:idVideo},
                            beforeSend:function () {
                                fnloadSpinner(1);
                            }
                        }).done(function (response) {

                            fnloadSpinner(2);

                            console.log(response);

                            if(response.result){

                                getMessageNotify('Videos',response.message,"success",1500);
                                fngnAdministrarVideos(1);

                            }else{
                                MyAlert(response.message,"error");
                            }

                        }).fail(function (jqH,textStatus,errno) {

                            fnloadSpinner(2);
                            console.log(textStatus + errno);

                            if(console && console.log){

                                if(textStatus == 'timeout'){

                                    MyAlert("Tiempo de espera agotado para la solicitud","error");

                                }else{
                                    MyAlert("Error al ejecutar la app" + errno,"error");
                                }
                            }
                        });

                    }

                }

            })

            break;
        case 4://Eliminar el Video

            bootbox.confirm({

                title:"Eliminar video",
                message:"Esta seguro en eliminar el video ?",
                size:"small",
                callback:function (result) {

                    if(result){

                        $.ajax({
                            url:"modules/applications/src/videos/fnEditarVideo.php",
                            type:"POST",
                            dataType:"JSON",
                            data:{opc:opc,idVideo:idVideo},
                            beforeSend:function () {
                                fnloadSpinner(1);
                            }
                        }).done(function (response) {

                            fnloadSpinner(2);

                            console.log(response);

                            if(response.result){

                                getMessageNotify('Videos',response.message,"success",1500);
                                fngnAdministrarVideos(1);

                            }else{
                                MyAlert(response.message,"error");
                            }

                        }).fail(function (jqH,textStatus,errno) {

                            fnloadSpinner(2);
                            console.log(textStatus + errno);

                            if(console && console.log){

                                if(textStatus == 'timeout'){

                                    MyAlert("Tiempo de espera agotado para la solicitud","error");

                                }else{
                                    MyAlert("Error al ejecutar la app" + errno,"error");
                                }
                            }
                        });

                    }

                }

            });

            break;
        case 5: //Activar el video

            bootbox.confirm({

                title:"Activar video",
                message:"Esta seguro de Activar el video ?",
                size:"small",
                callback:function (result) {

                    if(result){

                        $.ajax({
                            url:"modules/applications/src/videos/fnEditarVideo.php",
                            type:"POST",
                            dataType:"JSON",
                            data:{opc:opc,idVideo:idVideo},
                            beforeSend:function () {
                                fnloadSpinner(1);
                            }
                        }).done(function (response) {

                            fnloadSpinner(2);

                            console.log(response);

                            if(response.result){

                                getMessageNotify('Videos',response.message,"success",1500);
                                fngnAdministrarVideos(1);

                            }else{
                                MyAlert(response.message,"error");
                            }

                        }).fail(function (jqH,textStatus,errno) {

                            fnloadSpinner(2);
                            console.log(textStatus + errno);

                            if(console && console.log){

                                if(textStatus == 'timeout'){

                                    MyAlert("Tiempo de espera agotado para la solicitud","error");

                                }else{
                                    MyAlert("Error al ejecutar la app" + errno,"error");
                                }
                            }
                        });

                    }

                }

            });

            break;
        default:
            MyAlert("La opcion no es valida","error");
            break;
    }


}

function fngnAdministrarVideos(opc) {

    switch (opc){

        case 1:
            $("#listaVideos").removeClass('hidden');
            $("#panelNuevoVideo").addClass('hidden');
            SendAjax(
                "modules/applications/views/videos/",
                "FrmAdministrarVideos.php",
                null,
                "listaVideos",
                "post",
                "null",
                {opc:opc}
            );
            break;
        case 2:

            $.ajax({
                type: "POST",
                url: "modules/applications/src/videos/fnListarVideosJson.php",
                dataType: 'json',
                success: function(json) {

                    console.log(json);

                    var grid;

                    var columns = [
                        {id: "id", name: "Id", field: "id",width:55,cssClass: "text-center  btn-link", formatter: formatterGrid},
                        {id: "empresa", name: "Empresa", field: "empresa",width:140},
                        {id: "name", name: "Titulo", field: "name",width:180},
                        {id: "descrip", name: "Descripción", field: "descrip",width:250},
                        {id: "noestado", name: "No Estatus", field: "noestado",width:120,formatter:formatterGrid},
                        {id: "config", name: "Acciones", field: "config",width:80,formatter:formatterGrid},
                        {id: "usuarioa", name: "Usuario Alta", field: "usuarioa",width:150},
                        {id: "fechaalta", name: "Fecha Alta", field: "fechaalta",width:100}

                    ];

                    var options = {
                        enableCellNavigation: true,
                        enableColumnReorder: false,
                        multiColumnSort: true,
                        editable: true,
                        enableAddRow: true
                    };

                    var data = json;

                    grid = new Slick.Grid("#myGrid", data, columns, options);

                    grid;
                }
            });


            break;
        default:
            MyAlert("La Opcion no existe","error");
            break;
    }


}

function fngnListarVideos(opc) {

    $("#listaVideos").removeClass('hidden');
    $("#panelNuevoVideo").addClass('hidden');

    switch (opc){
        case 1: //Los mas recientes

            SendAjax(
              "modules/applications/src/videos/",
              "fnListarVideos.php",
              null,
              "listaVideos",
              "post",
              null,
                {opc:1}
            );
            break;
        case 2: //Busqueda por String
            var txtTexto = $("#txtString").val();
            SendAjax(
                "modules/applications/src/videos/",
                "fnListarVideos.php",
                null,
                "listaVideos",
                "post",
                null,
                {opc:opc,txtString:txtTexto}
            );
            console.log(txtTexto);
            break;
        default:
            MyAlert("La opción solicitada no existe","error");
            break

    }


}
//Seleccionar Video

function fngnSeleccionarVideo(){


    $('#videoDemo').attr('preload','auto');

    setTimeout(function () {
        document.getElementById('videoDemo').play();
        setOpenModal('mdlVideoDemostracion');
    },500);



}
//Abrir Modal para Nuevo Video
function fngnNuevoVideo(opc){

    switch (opc){

        case 1:

            var txtTitulo = $("#txtTitulo").val(),
                txtEmpresa = $("#txtEmpresa").val(),
                txtDescripcion = $("#txtDescripcion").val(),
                txtUrl = $("#txtUrl").val();

            //extraer la informacion delinput file
            var archivo = document.getElementById('imagenVideo');

            if(txtEmpresa == 0){
                MyAlert("Seleccione la empresa a la que pertence el video","alerterror","#txtEmpresa");
            }else if($.trim(txtTitulo) == ""){
                MyAlert("Ingrese el titulo de video","alerterror","#txtTitulo");
            }else if($.trim(txtUrl) == ""){
                MyAlert("Ingrese la url del video","alerterror","#txtUrl");
            }else if($('#txtImagen').val() == ""){

                MyAlert("Selecciona la imagen del video","alerterror","#txtImagen");

            }else{

                if (archivo.files && archivo.files[0]) {

                    $.ajax({
                        url:"modules/applications/src/videos/fnSubirVideo.php",
                        type:"post",
                        dataType:"json",
                        data:{txtTitulo:txtTitulo,TipoVideo:$("#typeVideo").val(),txtEmpresa:txtEmpresa,txtDescripcion:txtDescripcion,txtUrl:txtUrl,opc:1},
                        beforeSend:function(){
                            fnloadSpinner(1);
                        }
                    }).done(function(response){

                        fnloadSpinner(2);

                        if(response.success){

                            //Todo Bien
                            getMessageNotify(null,response.message,'success',1500);
                            fngnListarVideos(1);

                        }else{
                            MyAlert(response.message,"error");
                        }

                    }).fail(function (jqh,textStatus,errno) {

                        fnloadSpinner(2);
                        if(console && console.log){

                            if(textEstatus == 'timeout')
                            {
                                MyAlert('EL tiempo de la solicitud a sido agotado','alert');
                                //do something. Try again perhaps?

                            }else{

                                MyAlert(
                                    "Error al realizar la carga de la vista","alert"
                                );
                            }

                        }

                    });

                }else{
                    MyAlert("Selecciona la imagen del video","alerterror","#txtImagen");
                }

            }


            break;
        case 2:
            break;
        default:
            MyAlert("Error la opción no existe","error");

    }


}

// Funcion para realizar la Busqueda de Empleado Rapida
function jsgnBuscarEmpleado(opc){

    var cadena = $("#textSearch");

    if($.trim(cadena.val()) == ""){
        MyAlert("El Campo no debe estar vacio, Intentelo nuevamente.","alert");
        cadena.val('');
        cadena.focus();
    }else{

        var strData = {opcion:opc,cadena:cadena.val()};


        SendAjax(
            "modules/applications/src/empleados/",
            "fn_buscar_empleado.php",
            null,
            "myGrid",
            "POST",
            null,
            strData
        );
    }
}
//funcion para enviar correos
function send_mail(opcion,folio,anio,departamento){

    $.ajax({
        url:"modules/applications/src/mails/fn_send_mails.php",
        type:"POST",
        data:{
            opcion:opcion,
            fl:folio,
            anio:anio,
            dpto:departamento
        }
    })

}

// Funcion para dar de alta el Empleado
function fngnAltaEmpleado(opc){

    var nEmpresa = $("#idempresa").val(),
        tpoEmpleado = $("#idtpoempleado").val(),
        noEmpleado = $('#noempleado').val(),
        idpuesto = $("#idpuesto").val(),
        nodpto = $("#nodpto").val(),
        nombre = $("#nombreempleado").val(),
        appaterno = $("#appaterno").val(),
        apmaterno = $("#apmaterno").val(),
        correo = $("#correo").val(),
        tel01 = $("#tel01").val(),
        tel02 = $("#tel02").val(),
        tel03 = $("#tel03").val(),
        direccion = $("#direccion").val(),
        tel04 = $("#tel04").val(),
        tel05 = $("#tel05").val(),
        estatus = $("#estatus").val();

    if(nEmpresa == 0){
        MyAlert("Seleccione una Empresa","alerta");
    }else if(tpoEmpleado == 0 ){
        MyAlert("Seleccione el tipo de empleado","alerta");
    }else if(tpoEmpleado == 1 && $.trim(noEmpleado) == ""){
        MyAlert("Ingrese el Numero de Nomina","alerta");
    }else if (nodpto == 0){
        MyAlert("Seleccione el departamento","alerta");
    }else if($.trim(nombre) == ""){
        MyAlert("Ingrese el nombre del empleado","alerta");
    }else if($.trim(appaterno) == ""){
        MyAlert("Ingrese el apellido del empleado","alerta");
    }else{

        var strData = {
            opcion:opc,
            noempleado:noEmpleado,
            nodpto:nodpto,
            idpuesto:idpuesto,
            nombre:nombre,
            appaterno:appaterno,
            apmaterno:apmaterno,
            correo:correo,
            tel01:tel01,
            tel02:tel02,
            tel03:tel03,
            direccion:direccion,
            tel04:tel04,
            tel05:tel05,
            estatus:estatus,
            idEmpresa:nEmpresa,
            tpoEmpleado: tpoEmpleado
        };

        SendAjax(
            "modules/applications/src/empleados/",
            "fn_registra_empleado.php",
            null,
            "resultrge",
            "POST",
            null,
            strData

        );

    }
}

function fngn_buscar_empleado(opc){
    $('.btn-success').hide();

    var urlPhp,nameView,idDiv,ajaSend=false,txtString;

    if(opc == 1){
        //Abrir el modal para realizar la busqueda del empleado
        urlPhp = "modules/applications/views/empleados/",
            nameView = "FrmBuscarEmpleado.php",
            idDiv = "#ShowModal";
        ajaSend = true;


    }else if(opc == 2){
        //Mostrar la lista del resultado de la busqueda de empleado

        var nombre = $('#txtNombre'),
            nodpto = $('#txtNoDepartamento'),
            noestado = $('#txtNoEstado'),
            useralta = $('#txtNoUsuarioA'),
            userum = $('#txtNoUsuarioU'),
            fechaalta = $('#txtFechaA'),
            fechaum = $('#txtFechaU');

        opc = 99;
        urlPhp = "modules/applications/src/empleados/";
        nameView = "fnListEmployed.php";
        idDiv = "#lListTable";

        ajaSend = true;

    }

    if(ajaSend){

        $.ajax({
            url:urlPhp+nameView,
            type:"POST",
            data:{opcion:opc,txtData:txtString,cadena:nombre.val(),enodpto:nodpto.val(),enoestado:noestado.val(),euseralta:useralta.val(),euserum:userum.val(),efalta:fechaalta.val(),efum:fechaum.val()}
        }).done(function(data){

            fnloadSpinner(2,'fa-search','btnSearch');
            $(idDiv).html(data);

        }).fail(function(jqHR,textSatatus,errno){
            fnloadSpinner(2,'fa-search','btnSearch');
            if(console && console.log){

                MyAlert(
                    "Error al realizar la carga de la vista: <br>{<br> view => "+nameView +", <br>"+textSatatus+ " =>  "+errno +"<br> "+jqHR+ "<br>}","error"
                );
            }

        })

    }

}
function fngnEditarEmpleado(opc,idEmpleado,opcion2,opcion3){

    var mUrlPhp,mNamveView,midDiv,MyData,ajaxSend = false ;

    switch (opc){

        case 1:
            // mostrar modal para edirar
            mUrlPhp = "modules/applications/views/empleados/";
            mNamveView = "frm_editar_empleado.php";
            midDiv = "edit_empleado";
            MyData = {idEmpleado:idEmpleado,opcion2:opcion2,opcion3:opcion3};
            ajaxSend = true;
            break;
        case 2:
            // funcion para editar el empleado
            var nEmpresa = $("#edit_idempresa").val(),
                tpoEmpleado = $("#edit_idtpoempleado").val(),
                noEmpleado = $('#edit_noempleado').val(),
                nodpto = $("#edit_nodpto").val(),
                idpuesto = $("#edit_idpuesto").val(),
                nombre = $("#edit_nombreempleado").val(),
                appaterno = $("#edit_appaterno").val(),
                apmaterno = $("#edit_apmaterno").val(),
                correo = $("#edit_correo").val(),
                tel01 = $("#edit_tel01").val(),
                tel02 = $("#edit_tel02").val(),
                tel03 = $("#edit_tel03").val(),
                direccion = $("#edit_direccion1").val(),
                tel04 = $("#edit_tel04").val(),
                tel05 = $("#edit_tel05").val(),
                estatus = $("#edit_estatus").val();

            if(nEmpresa == 0){
                MyAlert("Seleccione una Empresa","alerta");
            }else if(tpoEmpleado == 0 ){
                MyAlert("Seleccione el tipo de empleado","alerta");
            }else if(tpoEmpleado == 1 && $.trim(noEmpleado) == ""){
                MyAlert("Ingrese el Numero de Nomina","alerta");
            }else if($.trim(nombre) == ""){
                MyAlert("Ingrese el nombre del empleado","alerta");
            }else if($.trim(appaterno) == ""){
                MyAlert("Ingrese el apellido del empleado","alerta");
            }else{
                mUrlPhp ="modules/applications/src/empleados/";
                mNamveView = "fnEditarEmpleado.php";
                midDiv = "resultrge";
                MyData = {idEmpleado:idEmpleado,
                    opcion2:opcion2,opcion3:opcion3,
                    noempleado:noEmpleado,
                    nodpto:nodpto,
                    idpuesto:idpuesto,
                    nombre:nombre,
                    appaterno:appaterno,
                    apmaterno:apmaterno,
                    correo:correo,
                    tel01:tel01,
                    tel02:tel02,
                    tel03:tel03,
                    direccion:direccion,
                    tel04:tel04,
                    tel05:tel05,
                    estatus:estatus,
                    idEmpresa:nEmpresa,
                    tpoEmpleado: tpoEmpleado};
                ajaxSend = true;

            }
            break

    }

    if(ajaxSend){
        $.ajax({
            url:mUrlPhp + mNamveView,
            type:"post",
            data:MyData
        }).done(function(data){

            $("#" + midDiv).html(data);

        }).fail(function(jqHR,textEstatus,errno){
            if(console && console.log){
                MyAlert(
                    "Error al realizar la carga de la vista: <br>{<br> view => "+mNamveView +", <br>"+textEstatus+ " =>  "+errno +"<br> "+jqHR+ "<br>}","error"
                );
            }
        });
    }
}
function fnCatEditarEmpleado(idempleado,opc,opcion2){

    var urlPhp,nameView,idDiv,ajaxSend,nEmpresa = $("#idempresa").val(),
        tpoEmpleado = $("#idtpoempleado").val(),
        noEmpleado = $('#noempleado').val(),
        nodpto = $("#nodpto").val(),
        nombre = $("#nombreempleado").val(),
        appaterno = $("#appaterno").val(),
        apmaterno = $("#apmaterno").val(),
        correo = $("#correo").val(),
        tel01 = $("#tel01").val(),
        tel02 = $("#tel02").val(),
        tel03 = $("#tel03").val(),
        direccion = $("#direccion1").val(),
        tel04 = $("#tel04").val(),
        tel05 = $("#tel05").val(),
        estatus = $("#estatus").val();

    if(opc == 1){
        // Mostrar el Modal para editar el empleado
        urlPhp ="modules/applications/views/empleados/",
            nameView = "frm_editar_empleado.php",idDiv = "#edit_empleado";

        ajaxSend = true;
    }else if(opc == 2){
        // Guardar los cambios del empleado
        urlPhp ="modules/00consola/layout/empleados/",
            nameView = "fnEditarEmpleado.php",idDiv = "#resultrge";

        if(nEmpresa == 0){
            MyAlert("Seleccione una Empresa","alerta");
        }else if(tpoEmpleado == 0 ){
            MyAlert("Seleccione el tipo de empleado","alerta");
        }else if(tpoEmpleado == 1 && $.trim(noEmpleado) == ""){
            MyAlert("Ingrese el Numero de Nomina","alerta");
        }else if (nodpto == 0){
            MyAlert("Seleccione el departamento","alerta");
        }else if($.trim(nombre) == ""){
            MyAlert("Ingrese el nombre del empleado","alerta");
        }else if($.trim(appaterno) == ""){
            MyAlert("Ingrese el apellido del empleado","alerta");
        }else{
            ajaxSend = true;
        }
    }

    if(ajaxSend){
        $.ajax({
            url:urlPhp+nameView,
            type:"POST",
            data:{idEmpleado:idempleado,
                noempleado:noEmpleado,
                nodpto:nodpto,
                nombre:nombre,
                appaterno:appaterno,
                apmaterno:apmaterno,
                correo:correo,
                tel01:tel01,
                tel02:tel02,
                tel03:tel03,
                direccion:direccion,
                tel04:tel04,
                tel05:tel05,
                estatus:estatus,
                idEmpresa:nEmpresa,
                tpoEmpleado: tpoEmpleado}
        }).done(function(data){
            $(idDiv).html(data);
        }).fail(function(jqHR,textEstatus,errno){
            if(console && console.log){
                MyAlert(
                    "Error al realizar la carga de la vista: <br>{<br> view => "+nameView +", <br>"+textEstatus+ " =>  "+errno +"<br> "+jqHR+ "<br>}","error"
                );
            }
        });
    }
}
function loadMunicipios(id_estado){

    $.ajax({
        url:"modules/applications/src/departamentos/fn_load_municipios.php",
        type:"POST",
        data:{id_estado:id_estado}
    }).done(function (data){

        $("#idMunicipio").html(data);

    }).fail(function(jqHR,textEstatus,error){
        if(console && console.log){
            MyAlert("Error al realizar la carga de la vista:{ views: "+textEstatus +" - "+error+ " - "+nameView+" }","error");
        }
    });

}
//Nuevo Departamentos
function fnCatFrmNuevoDepartamento(opcion,nodpto,listar){

    var phpUrl="",nameView="",divResult="",lSendAjax=false,idBoton,faIcon;
    switch (opcion){
        case 1:
            // Mostrar el Formulario para el Alta
            nameView = "FrmNuevoDepartamento.php";
            phpUrl = "modules/applications/views/departamentos/";
            divResult = "#ShowModal";
            stringData = {
                opc:opcion
            };
            lSendAjax = true;
            break;
        case 2:
            // Llamar a la Funcion para realizar el Alta
            nameView = "fnNuevoDepartamento.php";
            phpUrl = "modules/applications/src/departamentos/";
            divResult = "#imgLoad";
            idBoton = "btnSave";
            faIcon = "fa-save";

            // Validar Campos Vacios
            if($.trim($("#NoDepartamento").val()) == ""){
                MyAlert("Ingrese el numero del departamento","error");
            }else if($("#idEmpresa").val() == 0){
                MyAlert("Seleccione la empresa del departamento","error");
            }else if($("#NoTipo").val() == 0){
                MyAlert("Seleccione el tipo del departamento","error");
            }else if($.trim($("#NombreDpto").val()) == ""){
                MyAlert("Escriba el nombre del departamento ","error");
            }else if( $("#idEncargadoDpto").val() == 0 ){

                MyAlert("Seleccione el encargado del departamento ","error");
            }else if($('#idEstado').val() == 0){

                MyAlert("Seleccione el estado del departamento ","error");

            } else if($('#idMunicipio').val() == 0){

                MyAlert("Seleccione el municipio del departamento ","error");

            }else{
                stringData = {
                    opc:opcion,
                    nodepartamento:$("#NoDepartamento").val(),
                    idempresa:$("#idEmpresa").val(),
                    notipo:$("#NoTipo").val(),
                    asignareportes:$("#AsignarReportes").val(),
                    nosucursal:$("#NoSucursal").val(),
                    nombre:$("#NombreDpto").val(),
                    domicilio:$("#direccion").val(),
                    idestado:$("#idEstado").val(),
                    idmunicipio:$("#idMunicipio").val(),
                    telefono01:$('#tel01').val(),
                    telefono02:$('#tel02').val(),
                    telefono03:$('#tel03').val(),
                    telefono04:$('#tel04').val(),
                    correo:$("#correo").val(),
                    nozona:$("#idZona").val(),
                    nosupervisor:$("#idSupervisor").val(),
                    idencargado:$("#idEncargadoDpto").val(),
                    estatus:$("#NoEstatus").val()
                };
                lSendAjax = true ;

            }

            break;
        case 3:
            // Actualizar Departamento
            nameView = "fnEditarDepartamento.php";
            phpUrl = "modules/applications/src/departamentos/";
            divResult = "#imgLoad2";
            idBoton = "btnSave";
            faIcon = "fa-save";

            // Validar Campos Vacios
            if($.trim($("#NoDepartamento").val()) == ""){
                MyAlert("Ingrese el numero del departamento","error");
            }else if($("#idEmpresa").val() == 0){
                MyAlert("Seleccione la empresa del departamento","error");
            }else if($("#NoTipo").val() == 0){
                MyAlert("Seleccione el tipo del departamento","error");
            }else if($.trim($("#NombreDpto").val()) == ""){
                MyAlert("Escriba el nombre del departamento ","error");
            }else if( $("#idEncargadoDpto").val() == 0 ){

                MyAlert("Seleccione el encargado del departamento ","error");
            }else if($('#idEstado').val() == 0){

                MyAlert("Seleccione el estado del departamento ","error");

            } else if($('#idMunicipio').val() == 0){

                MyAlert("Seleccione el municipio del departamento ","error");

            }else {
                stringData = {
                    opc:opcion,
                    nodepartamento:$("#NoDepartamento").val(),
                    idempresa:$("#idEmpresa").val(),
                    notipo:$("#NoTipo").val(),
                    asignareportes:$("#AsignarReportes").val(),
                    nosucursal:$("#NoSucursal").val(),
                    nombre:$("#NombreDpto").val(),
                    domicilio:$("#direccion").val(),
                    idestado:$("#idEstado").val(),
                    idmunicipio:$("#idMunicipio").val(),
                    telefono01:$('#tel01').val(),
                    telefono02:$('#tel02').val(),
                    telefono03:$('#tel03').val(),
                    telefono04:$('#tel04').val(),
                    correo:$("#correo").val(),
                    nozona:$("#idZona").val(),
                    nosupervisor:$("#idSupervisor").val(),
                    idencargado:$("#idEncargadoDpto").val(),
                    estatus:$("#NoEstatus").val(),
                    listar:listar
                };
                lSendAjax = true ;

            }
            break;
        case 4:

            // Mostrar el Formulario para Actualizar los datos del departamento
            nameView = "FrmEditarDepartamento.php";
            phpUrl = "modules/applications/views/departamentos/";
            divResult = "#idgeneral";
            stringData = {
                opc:opcion,
                dpto:nodpto,
                listar:listar
            };
            lSendAjax = true;

            break;
        default :
            MyAlert("Opcion no valida o no existe ");
            break;
    }

    if(lSendAjax){
        $.ajax({
            url:phpUrl+nameView,
            type:"POST",
            data:stringData,
            beforeSend:function(){
                fnloadSpinner(1,faIcon,idBoton);

            }
        }).done(function(data){
            fnloadSpinner(2,faIcon,idBoton);

            $(divResult).html(data);

        }).fail(function(jqHR,textEstatus,errno){

            fnloadSpinner(2,faIcon,idBoton);
            if(console && console.log){
                MyAlert(
                    "Error al realizar la carga de la vista: <br>" +
                    "{<br> view => "+nameView +", " +
                    "<br>"+textEstatus+ " =>  "+errno +"<br>" +
                    " "+jqHR+ "<br>" +
                    "}","error"
                );
            }
        });
    }

}

//Funcion para cerrar los modales con confirmacion
function fnsdcerrarModal(idModal,confirm){



   if(confirm){
       bootbox.confirm({
           title:"Cerrar ventana",
           message: ptabla + "Se perderan los datos no guadados, esta seguro de continuar ?",
           size:"small",
           callback:function(result){
               if(result){
                   $("#alert").modal('toggle');
                   $("#"+idModal+"").modal('toggle');
               }
           }
       });
   }else{
       $("#"+idModal+"").modal('toggle');
   }

}

//funcion para buscar los contactos
function jsgnSearchContact(){

    var NombreEmpleado = $("#scNombreEmpleado"),Limite = $("#searchLimit").val(),stringData;

    if($.trim(NombreEmpleado.val()) == ""){
        MyAlert("Campo vacio intentelo nuevemante","alert");
    }else{

        //urlPhp,nameView,params_url_get,cache = false,idDiv,is_type,idBoton,stringData

        stringData = {sData: NombreEmpleado.val(),limit:Limite}

        SendAjax(
            "modules/applications/src/contacts/",
            "fn_buscar_contact.php",
            null,
            "searchContent",
            "POST",
            null,
            stringData
        );

    }
}
//Funcion Mostrar Perfil
function fnMostrarPerfil(opc,idEmpleado){

    switch (opc){
        case 1:
            // ver perfil del usuario seleccionado, de Directorio Telefonico
            $.ajax({
                url:"modules/applications/views/profile/frm_profile.php",
                type:"POST",
                data:{idEmpleado:idEmpleado,opc:opc}
            }).done(function(data){

                $("#searchContent").html(data);
                $("#btn_return").removeClass('hidden');

            });

            break;
        case 2:

            // mostrar perfil del usuario actual
            $.ajax({
                url:"modules/applications/views/profile/frm_profile.php",
                type:"POST",
                data:{idEmpleado:idEmpleado,opc:opc}
            }).done(function(data){

                $("#HomeContent").html(data);

            });


            break;
    }



}
//Guardar Cambios en la Informacion del Usuario
function gnActualizaPerfil(opc,user){

    var nosuc = $("#e-nodepartamento").val();
    var nombresuc = $("#e-nombresucursal").val();
    var direc = $("#e-direccion").val();

    var correo = $("#e-correo").val();
    var tel1 = $("#e-tel1").val();
    var tel2 = $("#e-tel2").val();
    var cell = $("#e-celular").val();
    var nomenc = $("#e-encargada").val(),
        dap01 = $("#e-ap01").val(),
        dap02 = $("#e-ap02").val();
    var nomencorto = $("#e-encargada2").val();
    var new_pass  = $('#new_pass'),
        last_pass = $('#last_pass').val();

    if($.trim(nomenc) == ""){
        MyAlert("Ingrese su nombre",'alert');
    }else if($.trim(dap01) == ""){
        MyAlert("Ingrese su apellido paterno",'alert');
    }else if($.trim(dap02)==""){
        MyAlert("Ingrese su apellido materno",'alert');
    }else if($.trim(nomencorto) == ""){
        MyAlert("Ingrese el nombre para mostrar","alert");
    }else{

        bootbox.confirm({
            title:"Actualizar Perfil",
            message: ptabla + "Esta seguro de guardar los Cambios ?",
            size:"small",
            callback:function(result){
                if(result){
                    $.ajax({
                        url:'modules/applications/src/profile/fn_guardar_perfil.php',
                        type:'POST',
                        data:{pass01:new_pass.val(),pass02:last_pass,param2:$("#changeemployed").val(),idEmpleado:$("#e-idEmpleado").val(),nousers:$("#e-nousuario").val(),ap01:dap01,ap02:dap02,nameshort:nomencorto,nouser:user,param:opc,nosucursal:nosuc,namesuc:nombresuc,direcion:direc,email:correo,telefono1:tel1,telefono2:tel2,cel:cell,nameenca:nomenc},
                        beforeSend:function(){
                            fnloadSpinner(1,"fa-save","btnSave");
                        },
                        success:function(data){
                            fnloadSpinner(2,"fa-save","btnSave");
                            $("#resultsave").html(data);
                        }
                    });
                }
            }
        });
    }
}
// funcion para cambiar la imagen de perfil
function fnreadURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#photo').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
    var filename = document.getElementById('selectedFile');
    var file = filename.files[0];
    var data = new FormData();

    data.append('archivo',file);
    $.ajax({
        url:"modules/applications/src/profile/fn_upload_image.php",
        type:"post",
        contentType:false,
        data:data,
        processData:false,
        cache:false,
        success:function(data){

            console.log(data);

        }
    });
}

//funcion para cargar la pagina principal
function jsgn_cargar_tablero(){

    $.ajax({
        data:{opc:1 },
        type:"POST",
        cache:false,
        beforeSend:function(){
            fnloadSpinner(1);
        },
        url:"modules/applications/views/index/dashboard_principal.php"
    }).done( function(data) {

        $("#HomeContent").html(data);
        fnloadSpinner(2,null);

    }).fail(function(jqXHR,textStatus,errorThrown){
        if ( console && console.log ) {
            fnloadSpinner(2,null);
            MyAlert( "La solicitud a fallado: "+ textStatus  + errorThrown+ ".","alert");
        }
    });

}

//Desconectar una session de usuario
function fngnDesconectar_sesion(Usuario,opcion){



    var nouser = $("#edit_user_nousuario").val();

    if(opcion == 2)
    {
        nouser = Usuario;
    }

    $.ajax({
        data:{NoUsuario:nouser,opc:2 },
        type:"POST",
        dataType:"JSON",
        url:"modules/applications/src/login/desconectar_session.php"
    }).done( function(data) {

        if(data.dexists == "ok"){
            MyAlert("Usuario desconectado correctamente","alert");
        }

    }).fail(function(jqXHR,textStatus,errorThrown){
        if ( console && console.log ) {
            MyAlert( "La solicitud a fallado: "+ textStatus  + errorThrown+ ".","alert");
        }
    });
}

//funcion para salir del sistema
function jsgn_salir(){

    if(confirm_close){
        bootbox.confirm({
            title:"Actualizaci&oacute;n de Datos",
            message: ptabla + " Se perderá la Información no guardada, está seguro de continuar ?",
            size:"small",
            callback:function(valid){

                if (valid) {
                    location.href = "modules/applications/src/login/fn_logout.php";
                }

            }
        });
    }else{
        location.href = "modules/applications/src/login/fn_logout.php";
    }

}

function jsgn_close_system(NoUsuario) {

    $.ajax({
        data:{NoUsuario:NoUsuario,opc:1 },
        type:"POST",
        cache:false,
        dataType:"JSON",
        url:"modules/applications/src/login/desconectar_session.php"
    }).done( function(data) {


    }).fail(function(jqXHR,textStatus,errorThrown){
        if ( console && console.log ) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            //MyAlert( "La solicitud 23 a fallado: "+ textStatus  + errorThrown+ ".","alert");
        }
    });

}

// funcion para mostrar notificaciones
function getMessageNotify (pTitle,messages,typemsg,timer){

    if(timer<1){
        timer = 3500;
    }

    switch (typemsg){

        case "notice":
            PNotify.prototype.options.delay = timer;
            new PNotify({
                title: pTitle,
                text: messages,
                type: typemsg
            });
            break;
        case "info":
            PNotify.prototype.options.styling = "jqueryui";
            PNotify.prototype.options.delay = timer;
            new PNotify({
                title: pTitle,
                text: messages
            });
            break;
        case "success":
            PNotify.prototype.options.delay = timer;
            new PNotify({
                title: pTitle,
                text: messages,
                type: typemsg
            });
            break;
        case "error":
            PNotify.prototype.options.delay = timer;
            new PNotify({
                title: pTitle,
                text: messages,
                type: typemsg
            });
            break;
        case null:
            PNotify.prototype.options.delay = timer;
            PNotify.prototype.options.styling = "jqueryui";
            new PNotify(messages);
            break;
        default :
            PNotify.prototype.options.delay = timer;
            PNotify.prototype.options.styling = "jqueryui";
            new PNotify(messages);
            break;
    }
}

// funcion para realizar el preloader
function fnloadSpinner(opc,idboton){

    switch (opc){
        // mostrar fa-Spinner
        case 1:
            $('#'+idboton).prop('disabled',true);
            jsShowWindowLoad(" espere un momento . .");
            break;
        case 2:
            // Ocultar fa-Spinner
            $('#'+idboton).prop('disabled',false);
            jsRemoveWindowLoad();
            break;
        default :
            MyAlert("error no se encontro la opci&oacute;n solicitada","error");
            break;
    }
}
function jsRemoveWindowLoad() {
    // eliminamos el div que bloquea pantalla
    $("#WindowLoad").remove();

}
function jsShowWindowLoad(mensaje) {
    //eliminamos si existe un div ya bloqueando
    jsRemoveWindowLoad();

    confirm_close = false;

    //si no enviamos mensaje se pondra este por defecto
    if (mensaje === undefined) mensaje = mensaje;

    //centrar imagen gif
    height = 20;//El div del titulo, para que se vea mas arriba (H)
    var ancho = 0;
    var alto = 0;

    //obtenemos el ancho y alto de la ventana de nuestro navegador, compatible con todos los navegadores
    if (window.innerWidth == undefined) ancho = window.screen.width;
    else ancho = window.innerWidth;
    if (window.innerHeight == undefined) alto = window.screen.height;
    else alto = window.innerHeight;

    //operación necesaria para centrar el div que muestra el mensaje
    var heightdivsito = ((alto/2) - (parseInt(height)) /2 ) - 95;//Se utiliza en el margen superior, para centrar

    //imagen que aparece mientras nuestro div es mostrado y da apariencia de cargando
    imgCentro = "<div style='text-align:center;height:" + alto + "px;'><div class='text-white'  style='margin-top:" + heightdivsito + "px; font-size:18px;'><span class='fa fa-2x fa-spinner fa-spin '></span><br><small>"+mensaje+"</small></div></div>";

    //creamos el div que bloquea grande------------------------------------------
    div = document.createElement("div");
    div.id = "WindowLoad"
    div.style.width = ancho + "px";
    div.style.height = alto + "px";
    $("body").append(div);

    //creamos un input text para que el foco se plasme en este y el usuario no pueda escribir en nada de atras
    input = document.createElement("input");
    input.id = "focusInput";
    input.type = "text"

    //asignamos el div que bloquea
    $("#WindowLoad").append(input);

    //asignamos el foco y ocultamos el input text
    $("#focusInput").focus();
    $("#focusInput").hide();

    //centramos el div del texto
    $("#WindowLoad").html(imgCentro);

}


//Funcion para agregar option y dejar como seleccionado

function AddOptionSelect(selector,valor,texto){

    $(selector).append($("<option value='"+valor+"' selected='selected'></option>").text(texto));

}

//Funcion para enviar las alertas
function MyAlert(message,tpo_message_error_alert_question_info_advert_ok,idFoco) {

    var faimg = "<span class='fa fa-ban text-info'></span>", msgTitle, rta_img,has = false;

    if (tpo_message_error_alert_question_info_advert_ok == "") {
        rta_img = "site_design/img/icons/alerta.ico";
    }


    switch (tpo_message_error_alert_question_info_advert_ok.toLowerCase()) {
        case 'question':
            rta_img = "site_design/img/icons/help.png";
            msgTitle = " Ayuda";
            break;
        case 'error':
            rta_img = "site_design/img/icons/error.png";
            msgTitle = " Error";
            break;
        case 'info':
            rta_img = "site_design/img/icons/info.png";
            msgTitle = " Informacion";
            break;
        case 'warning':
            rta_img = "site_design/img/icons/warning.png";
            msgTitle = " Warning";
            break;
        case 'ok':
            rta_img = "site_design/img/icons/good.png";
            msgTitle = " Alerta";
            break;
        case 'success':
            rta_img = "site_design/img/icons/good.png";
            msgTitle = " Alerta";
            break;
        case 'alerterror':
            rta_img = "site_design/img/icons/alerta.ico";
            msgTitle = " Alerta";
            has = true;
            break;
        case 'encuesta':
            rta_img = "/SistemaIntegral/site_design/img/icons/alerta.ico";
            msgTitle = " Alerta";
            has = true;
            break;
        default :
            rta_img = "site_design/img/icons/alerta.ico";
            msgTitle = " Alerta";
            break;
    }

    var dtabla = "" +
        "<table style='margin-top: 15px;'>" +
        "   <tr>" +
        "       <td rowspan='2' valign='middle'>" +
        "           <img src='" + rta_img + "' style='margin-right: 8px;' width='32'>" +
        "       </td>" +
        "   </tr>" +
        "   <tr>" +
        "       <td valign='middle' style='text-align: justify;padding-left: 6px;'>" +
        "       " + message + "" +
        "       </td>" +
        "   </tr>" +
        "</table>";

    bootbox.alert({
        title:faimg + msgTitle,
        message:dtabla,
        size:"small",
        buttons: {
            ok: {
                label: "Aceptar",
                className: "btn-primary btn-sm"
            }
        },
        callback: function () {

            if(has){
                $(idFoco).addClass('has-error');

                setTimeout(function () {
                    $(idFoco).focus().select();
                },180);
            }

        }
    });

}



//funcion para envair solicitud por medio de ajax
function SendAjax(urlPhp,nameView,params_url_get,idDiv,is_type,idBoton,stringData){

    if(params_url_get == null){
        params_url_get = "";
    }else{
        params_url_get = "?" + params_url_get ;
    }

    switch (is_type){
        case "post":
            $.ajax({
                url:urlPhp + nameView,
                cache:false,
                data:stringData,
                beforeSend:function(){
                    fnloadSpinner(1,idBoton);
                },
                type:is_type
            }).done(function(data){


                $("#"+idDiv).html(data);
                fnloadSpinner(2,idBoton);

            }).fail(function(jqHR,textEstatus,errno){
                fnloadSpinner(2,idBoton);

                if(console && console.log){

                    if(textEstatus == 'timeout')
                    {
                        MyAlert('EL tiempo de la solicitud a sido agotado','alert');
                        //do something. Try again perhaps?

                    }else{

                        MyAlert(
                            "Error al realizar la carga de la vista: <br>" +
                            "{<br> url=>"+urlPhp +"  <br> " +
                            "view => "+nameView +", <br>"+
                            textEstatus+ " =>  "+
                            errno +"<br> "+
                            jqHR+ "<br>" +
                            "}","alert"
                        );
                    }

                }


            });
            break;
        case "json":
            $.ajax({
                url:urlPhp + nameView,
                cache:false,
                data:stringData,
                beforeSend:function(){
                    fnloadSpinner(1,idBoton);
                },
                type:'post',
                dataType:'json'
            }).done(function(response){
                fnloadSpinner(2,idBoton);

                array_data = response;

            }).fail(function(jqHR,textEstatus,errno){
                fnloadSpinner(2,idBoton);

                if(console && console.log){

                    if(textEstatus == 'timeout')
                    {
                        MyAlert('EL tiempo de la solicitud a sido agotado','alert');
                        //do something. Try again perhaps?

                    }else{

                        MyAlert(
                            "Error al realizar la carga de la vista: <br>" +
                            "{<br> url=>"+urlPhp +"  <br> " +
                            "view => "+nameView +", <br>"+
                            textEstatus+ " =>  "+
                            errno +"<br> "+
                            jqHR+ "<br>" +
                            "}","alert"
                        );
                    }

                }


            });
            break;
        default:
            $.ajax({
                url:urlPhp + nameView,
                cache:false,
                data:stringData,
                beforeSend:function(){
                    fnloadSpinner(1,idBoton);
                },
                type:is_type
            }).done(function(data){


                $("#"+idDiv).html(data);
                fnloadSpinner(2,idBoton);

            }).fail(function(jqHR,textEstatus,errno){
                fnloadSpinner(2,idBoton);

                if(console && console.log){

                    if(textEstatus == 'timeout')
                    {
                        MyAlert('EL tiempo de la solicitud a sido agotado','alert');
                        //do something. Try again perhaps?

                    }else{

                        MyAlert(
                            "Error al realizar la carga de la vista: <br>" +
                            "{<br> url=>"+urlPhp +"  <br> " +
                            "view => "+nameView +", <br>"+
                            textEstatus+ " =>  "+
                            errno +"<br> "+
                            jqHR+ "<br>" +
                            "}","alert"
                        );
                    }

                }


            });
            break;
    }
}

//Funcion para abrir un modal
function setOpenModal(idmodal){

    $('#'+idmodal).modal('show');

    setTimeout(function() { $('.modal-body').find('input:text').first().focus(); }, 700);

    $("#"+idmodal).draggable({
        handle: ".modal-header"
    });

}
function setFormatoMoneda(opc,valor) {

    switch (opc){
        case 1:
            //Desconvertir formato moneda
            var cadena = valor.replace('$', "");
            cadena = cadena.replace(',',"");
            //console.log(cadena);
            return cadena;
            break;
    }

    alert(cadena);

    return valor;

}
//funcion para cargar la opcion solicitada desde el menu principal
function fnsdMenu(mnuOpcion,parametro){

    var urlDirect,dat,HomeContent = "HomeContent",sender = false;
    if(parametro == null){
        dat = "";
    }else{
        dat = "?"+parametro;
    }

    confirm_close = false;

    switch (mnuOpcion){
        case 0:
            MyAlert("La opcion solicitada no existe, intentelo nuevamente o reporte a sistemas","error");
            break;
        case 1:
            //Graficas personales
            urlDirect = "modules/01HelpDesk/views/indicadores/frm_dashboard.php?str="+mnuOpcion;
            $("#TituloStats").html("&nbsp; Graficas <small> - ServiceDesk </small>");
            sender = true;
            break;
        case 2:
            // Formulario para Nuevo Ticket
            urlDirect = "modules/01HelpDesk/views/tickets/frm_nuevo_ticket.php"+dat;
            $("#TituloStats").html("&nbsp; Nuevo Ticket<small> - ServiceDesk</small>");
            sender = true;
            break;
        case 3:
            // Formulario para Listar Tickets
            urlDirect = "modules/01HelpDesk/views/tickets/frm_listar_tickets.php"+dat;
            $("#TituloStats").html("&nbsp; Lista de Tickets<small> - ServiceDesk</small>");
            sender = true;
            break;
        case 4:
            //Formulario para Sacar Reportes de Tickets
            urlDirect = "modules/01HelpDesk/views/tickets/frm_reportes_tickets.php"+dat;
            $("#TituloStats").html("&nbsp; Reportes de HelpDesk <small> - ServiceDesk </small>");
            sender = true;
            break;
        case 5:
            // Formulario para Sacar Reportes de las Encuestas
            urlDirect = "modules/01HelpDesk/views/encuestas/frm_reporte_encuestas_servicio.php"+dat;
            $("#TituloStats").html("&nbsp; Reportes de Encuesta <small> - ServiceDesk</small>");
            sender = true;
            break;
        case 6:
            // Formulario para Buscar Tickets
            urlDirect = "modules/01HelpDesk/views/FrmBuscarTicket.php"+dat;
            $("#TituloStats").html("&nbsp; Buscar Ticket");
            sender = true;
            break;
        case 7:
            // Formulario para Mostrar los Catalogos
            urlDirect = "modules/01HelpDesk/views/catalogos/frm_catalogos.php"+dat;
            $("#TituloStats").html("&nbsp; Cat&aacute;logos <small> - ServiceDesk</small>");
            sender = true;

            break;
        case 8:
            // formulario para el Control de Equipos Internos
            urlDirect = "modules/01HelpDesk/views/equipos/frm_equipos_internos.php"+dat;
            sender = true;
            $("#TituloStats").html("&nbsp; Equipos Internos <small> - ServiceDesk </small>");

            break;
        case 9:
            // Formulario para Administrar las Encuestas
            urlDirect = "modules/01HelpDesk/views/FrmadminEncuestas.php"+dat;
            sender = true;
            $("#TituloStats").html("&nbsp; Administrar Encuestas");

            break;
        case 10:
            //none
            // Ordenes de trabajo para Mantenimienro
            urlDirect = "modules/01HelpDesk/views/FrmWorkOrders.php"+dat;
            sender = true;
            $("#TituloStats").html("&nbsp; Ordenes de Trabajo");
            break;
        case 11:
            //Redireccionar a ver Tickets
            urlDirect =  "modules/01HelpDesk/views/tickets/frm_ver_ticket.php"+dat;
            sender = true;
            break;
        case 12:
            // Alta de Nuevo Equipo para uso interno
            urlDirect = "modules/01HelpDesk/views/equipos/frm_nuevo_equipo.php";
            sender = true;
            HomeContent = "listTable";
            break;
        case 13:
            // Edicion de Equipo Asignado
            urlDirect = "modules/01HelpDesk/views/equipos/frm_editar_equipo_asignado.php"+parametro;
            sender = true;
            HomeContent = "listTable";
            break;
        case 14:
            //Menu para ir al Modulo PVENTA catalogo de cotizaciones
            urlDirect = "modules/04PVenta/views/metales/frm_listaCotizaciones.php";
            sender = true;
            $("#TituloStats").html("&nbsp; Lista de cotizaciones <small> - Punto de Venta</small>");
            break;
        case 15:
            //Menu para ir al Modulo PVENTA catalogo de cotizaciones
            urlDirect = "modules/04PVenta/views/productos/frm_lista_productos.php";
            sender = true;
            $("#TituloStats").html("&nbsp; Lista de Productos <small> - Punto de Venta</small>");
            break;
        case 16:
            //Menu para ir a los catalogos generales del sistema (Seccion Herramientas)
            urlDirect = "modules/applications/views/catalogos_generales/frm_catalogos.php"+dat;
            sender = true;
            $("#TituloStats").html("&nbsp; Catalogos Generales <small> - Administraci&oacute;n</small>");
            break;
        case 17:
            // Menu para ir al modulo de Contactos
            urlDirect = "modules/applications/views/contacts/frm_contacts.php"+dat;
            sender = true;
            $("#TituloStats").html("&nbsp; Contactos <small> - Sistema Integral</small>");
            break;
        case 18:
            // Menu para ir: Modulo=>Proyectos=>Transaccion=>Control de Proyectos;
            urlDirect = "modules/02Proyectos/views/proyectos/02FrmProyectos.php"+dat;
            sender = true;
            //$("#TituloStats").html("&nbsp; Contactos <small> - Sistema Integral</small>");
            break;
        case 19:
            // Menu para ir: Mostrar la lista de aplicaciones del Storad
            urlDirect = "modules/applications/views/icloud/frm_applicaciones.php"+dat;
            sender = true;
            //$("#TituloStats").html("&nbsp; Contactos <small> - Sistema Integral</small>");
            break;
        case 20:
            // Menu: Punto de Venta/Consultas y Reportes/Reportes de Encuestas
            urlDirect = "modules/04PVenta/views/encuestas/frm_reporte_encuesta.php"+dat;
            sender = true;
            break;
        case 21:
            // Menu Seccion Catalogos de Proyectos
            urlDirect = "modules/02Proyectos/views/catalogos/FrmCatalogos.php"+dat;
            sender = true;
            break;
        case 22:
            // Menu: Reporte de Equipos, Modulo: Mesa de Ayuda, Seccion: Consultas y Reportes
            urlDirect = "modules/01HelpDesk/views/equipos/frm_reportes.php"+dat;
            sender = true;
            break;
        case 23:
            //Menu para Ver el catalogo de Productos para servidor de triara
            urlDirect = "modules/04PVenta/views/productos/frm_productos_web.php"+dat;
            sender = true;
            break;
        case 24:
            //Graficas personales
            urlDirect = "modules/01HelpDesk/views/indicadores/frm_indicadores.php?str="+mnuOpcion;
            $("#TituloStats").html("&nbsp; Graficas <small> - ServiceDesk </small>");
            sender = true;
            break;
        case 25:
            //DashBoard del Punto de venta
            urlDirect = "modules/04PVenta/views/indicadores/frm_dashboard.php"+dat;
            sender = true;
            break;
        case 26:
            //Indicadores del Punto de venta
            urlDirect = "modules/04PVenta/views/indicadores/frm_indicadores.php"+dat;
            sender = true;
            break;
        case 27:
            //Indicadores del Punto de venta
            urlDirect = "modules/04PVenta/views/productos/frm_caracteristicas_producto_web.php"+dat;
            sender = true;
            break;
            //07MANTENIMIENTO CATALOGO PROVEEDORES
        case 28:
            urlDirect = "modules/07Mantenimiento/views/catalogo_proveedores/frm_catalogo_proveedores.php"+dat;
            sender = true;
            break;
            //07MANTENIMIENTO CATALOGO UBICACIONES
        case 29:
            urlDirect = "modules/07Mantenimiento/views/catalogo_ubicaciones/frm_catalogo_ubicaciones.php"+dat;
            sender = true;
            break;
         // 07MANTENIMIENTO CATALOGO REFACCIONES
        case 30:
            urlDirect = "modules/07Mantenimiento/views/catalogo_refacciones/frm_catalogo_refacciones.php"+dat;
            sender = true;
            break;
        // 07MANTENIMIENTO CATALOGO DE EQUIPOS
        case 31:
            urlDirect = "modules/07Mantenimiento/views/catalogo_equipos/frm_catalogo_equipos.php"+dat;
            sender = true;
            break;
        // 07MANTENIMIENTO CATALOGO TIPOS DE EQUIPOS
        case 32:
            urlDirect = "modules/07Mantenimiento/views/catalogo_tipo_equipos/frm_catalogo_tipo_equipos.php"+dat;
            sender = true;
            break;
        case 33:
            urlDirect = "modules/07Mantenimiento/views/catalogo_marcas/frm_marcas.php"+dat;
            sender = true;
            break;
        case 34:
            urlDirect = "modules/07Mantenimiento/views/catalogo_modelos/frm_modelos.php"+dat;
            sender = true;
            break;
        case 35:
            urlDirect = "modules/07Mantenimiento/views/garantias/frm_garantias.php"+dat;
            sender = true;
            break;
        //07MANTENIMIENTO PLAN MANTENIMIENTO
        case 36:
            urlDirect = "modules/07Mantenimiento/views/plan_matenimiento/frm_plan_mantenimiento.php"+dat;
            sender = true;
            break;
        case 37://CATALOGO DE ESTATUS
            urlDirect = "modules/07Mantenimiento/views/catalogo_estatus/frm_estatus.php"+dat;
            sender = true;
            break;
        case 38://CATALOGO DE CARACTERISTICAS
            urlDirect = "modules/07Mantenimiento/views/catalogo_caracteristicas/FrmCaracteristicas.php"+dat;
            sender = true;
            break;
        case 39: //Correo Interno
            urlDirect = "modules/applications/views/correo/FrmMailBox.php"+dat;
            sender = true;
            break
        case 40: //Correo Interno
            urlDirect = "modules/applications/views/videos/FrmVideos.php"+dat;
            sender = true;
            break
        case 41: //Cotizador CallCenter 04PVenta
            urlDirect = "modules/04PVenta/views/cotizador/FrmCotizador.php"+dat;
            sender = true;
            break
        default :
            MyAlert("La opcion solicitada no existe, intentelo nuevamente o reporte a sistemas");
            break;
    }

    window.history.pushState({page: 1}, "title 1", "?page="+mnuOpcion);


    if(sender){
        $.ajax({
            url: urlDirect,
            type: "post",
            data: {opt: mnuOpcion},
            beforeSend:function(){
                fnloadSpinner(1,null);
            }
        }).done(function(data){

            $("#"+HomeContent+"").html(data);
            fnloadSpinner(2,null);
        }).fail(function(jqHR,textSatatus,errno){

            fnloadSpinner(2,null);

            if(console && console.log){

                MyAlert(
                    "Error al realizar la carga de la vista: <br>{<br> view => "+urlDirect +", <br>"+textSatatus+ " =>  "+errno +"<br> "+jqHR+ "<br>}","error"
                );
            }

        });
    }
}

function init_botons(){

    $('button').addClass('btn waves-effect');
    $('a').addClass('waves-effect');

}
//Funcion para la pantalla completa
function requestFullScreen() {

    var el = document.body;

    // Supports most browsers and their versions.
    var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen
        || el.mozRequestFullScreen || el.msRequestFullScreen;

    if (requestMethod) {

        // Native full screen.
        requestMethod.call(el);

    } else if (typeof window.ActiveXObject !== "undefined") {

        // Older IE.
        var wscript = new ActiveXObject("WScript.Shell");

        if (wscript !== null) {
            wscript.SendKeys("{F11}");
        }
    }
}