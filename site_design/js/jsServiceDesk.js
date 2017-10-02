/**
 * Created by alejandro.gomez on 31/10/2016.
 */


/**
 * Firma para los reportes de HelpDesk
 */
function fn01FirmaReportes(folio,anio,nodepartamento){

    var nombre = $("#nombre_firma"),imagenFirma;

    if($.trim(nombre.val()) == ""){
        MyAlert("Ingrese su nombre completo","alerterror",nombre);
    }else if (signaturePad.isEmpty()) {
        MyAlert("Ingrese su firma primeramente","alert");
    }else{

        $.ajax({
            url:"modules/01HelpDesk/src/firmas/fn_guardar_firma_ticket.php",
            cache:false,
            data:{
                folio:folio,
                anio:anio,
                NoDepartamento:nodepartamento,
                nombre_firma:nombre.val(),
                imagen:signaturePad.toDataURL()
            },
            beforeSend:function(){
                fnloadSpinner(1);
            },
            type:"post",
            dataType:"json"
        }).done(function(response){

            fnloadSpinner(2);

            console.log(response);

            switch (response.result){
                case 'error':
                    MyAlert(response.mensaje,"error");
                    break;
                case 'ok':
                    $("#modalbtnclose").click();
                    MyAlert(response.mensaje,"ok");
                    break;
            }


        }).fail(function(jqHR,textEstatus,errno){
            fnloadSpinner(2);

            if(console && console.log){

                if(textEstatus == 'timeout')
                {
                    MyAlert('EL tiempo de la solicitud a sido agotado','alert');
                    //do something. Try again perhaps?

                }else{
                    MyAlert(
                        "Error al realizar la carga de la vista: <br>" +
                        "{<br> url=>modules/01HelpDesk/src/  <br> " +
                        "view =>fn_guardar_firma_ticket, <br>"+
                        textEstatus+ " =>  "+
                        errno +"<br> "+
                        jqHR+ "<br>" +
                        "}","alert"
                    );
                }

            }


        });


    }

}

/**
 *
 * @param nodepartamento
 * @param mesa_deayuda
 */

function cargar_frm_ticket(nodepartamento,mesa_deayuda){

    if(nodepartamento != mesa_deayuda){

        $('#dpto_area').hide();
        $('#MedioDeContacto option:eq(2)').prop('selected', true).change();
        $('#Departamento option[value='+nodepartamento+' ]').prop('selected', true).change();
        $('#Departamento').attr('disabled',true);

        $('#MedioDeContacto').attr('disabled',true);
        $('#frm_data_asign').Frmreset();
        $('#fchalta').attr('disabled',true);
        $('#Prioridad').attr('disabled',true);
        $('#Estatus').attr('disabled',true);
        $('#tipomantenimiento').attr('disabled',true);
        $('#AsignenPerson').attr('disabled',true);
        $('#HistoryReport').hide();

    }else{

        $('#dpto_area').show();
        $('#MedioDeContacto option:eq(2)').prop('selected', false).change();
        $('#MedioDeContacto').attr('disabled',false);
        $('#fchalta').attr('disabled',false);
        $('#Prioridad').attr('disabled',false);
        $('#Estatus').attr('disabled',false);
        $('#tipomantenimiento').attr('disabled',false);
        $('#AsignenPerson').attr('disabled',false);
        $('#HistoryReport').show();
        $('#Departamento').attr('disabled',false);



    }
}

function fn_select_areas(nodepto){
    SendAjax(
        "modules/01HelpDesk/src/areas/",
        "fn_select_areas.php",
        null,
        "id_noarea",
        "post",
        null,
        {NoDepartamento:nodepto}
    );
}

function fn_select_usuarios(nodepto){
    SendAjax(
        "modules/applications/src/usuarios/",
        "fn_select_usuarios.php",
        null,
        "user",
        "post",
        null,
        {NoDepartamento:nodepto}
    );
}
// Menu de Catalogos del ServiceDesk
function sdMenuCatalogos(opc){

    var nameView,urlPhp,idDiv;


    switch (opc){
        case 1:
            // catalogo parametros del categorias
            nameView = "frm_categorias.php";
            urlPhp = "modules/01HelpDesk/views/categorias/";
            idDiv = "#HomeContent";

            break;
        case 2:
            //Catalogo de Areas
            nameView = "FrmAreas.php";
            urlPhp = "modules/01HelpDesk/views/areas/";
            idDiv = "#HomeContent";
            break;
        case 3:
            //Catalogo medios de contacto
            nameView = "frm_medio_contacto.php";
            urlPhp = "modules/01HelpDesk/views/medio_contacto/";
            idDiv = "#HomeContent";
            break;
        case 4:
            //Catalogo medios de contacto
            nameView = "frm_tipo_atencion.php";
            urlPhp = "modules/01HelpDesk/views/tipo_atencion/";
            idDiv = "#HomeContent";
            break;

    }

    $.ajax({
        url:urlPhp + nameView,
        type:"POST",
        data:{opcion:opc}
    }).done(function(data){

        $(idDiv).html(data);

    }).fail(function(jqHR,txtEstatus,errno){
        if(console && console.log){
            MyAlert("Error al realizar la carga de la vista: <br>{<br> view => "+nameView +", <br>"+txtEstatus+ " =>  "+errno +"<br> "+jqHR+ "<br>}","error"
            );
        }
    });


}

/**
 * CAtalogos Tipos de Atención
 * @param opc
 */
function fn_cat_tipo_atencion_lista(opc){
    var nameView,divResult,strData,sendAjaxs = false,urlPhp;

    // listar todos los puestos
    urlPhp = "modules/01HelpDesk/src/tipo_atencion/";
    nameView = "fn_lista_tipo_atencion.php";
    strData = {opcion:opc};
    divResult = "#lListTable";
    sendAjaxs = true;

    if(sendAjaxs){
        $.ajax({
            url:urlPhp+nameView+"",
            type:"POST",
            data:strData
        }).done(function (data){

            $(divResult).html(data);

        }).fail(function(jqHR,textEstatus,error){
            if(console && console.log){
                MyAlert("Error al realizar la carga de la vista:{ views: "+textEstatus +" - "+error+ " - "+nameView+" }","error");
            }
        });
    }

}



/**
 * Catalogo Medio de Contacto
 * @param opc
 */
function fn_cat_medio_contacto_lista(opc){
    var nameView,divResult,strData,sendAjaxs = false,urlPhp;

    // listar todos los puestos
    urlPhp = "modules/01HelpDesk/src/medio_contacto/";
    nameView = "fn_listar_medio_contacto.php";
    strData = {opcion:opc};
    divResult = "#lListTable";
    sendAjaxs = true;

    if(sendAjaxs){
        $.ajax({
            url:urlPhp+nameView+"",
            type:"POST",
            data:strData
        }).done(function (data){

            $(divResult).html(data);

        }).fail(function(jqHR,textEstatus,error){
            if(console && console.log){
                MyAlert("Error al realizar la carga de la vista:{ views: "+textEstatus +" - "+error+ " - "+nameView+" }","error");
            }
        });
    }

}
function fn_cat_nuevo_medio_contacto(opc){

    var nameView,divResult,strData,sendAjax = false,urlPhp;

    switch (opc){
        case 1:
            //mostrar modal para registrar el area
            nameView = "frm_nuevo_medio_contacto.php";
            urlPhp = "modules/01HelpDesk/views/medio_contacto/";
            divResult = "ShowModal";
            strData = {opcion:opc};
            sendAjax = true;
            break;
        case 2:
            //funcion para registrar el area
            nameView = "fn_nuevo_medio_contacto.php";
            urlPhp = "modules/01HelpDesk/src/medio_contacto/";
            divResult = "imgLoad";

            var nombre_medio_contacto = $('#nombre_medio_contacto').val();

            if($.trim(nombre_medio_contacto) == ""){
                MyAlert("Ingresa el nombre del medio de contacto","alert");
            }else{
                strData = {opcion:opc,nombre_medio_contacto:nombre_medio_contacto};
                sendAjax = true;
            }

            break;
    }

    if(sendAjax){
        SendAjax(
            urlPhp,
            nameView,null,divResult,'post',null,strData
        );
    }

}
function fn_cat_editar_medio_contacto(opc,idMedioContacto){

    var nameView,divResult,strData,sendAjax = false,urlPhp,NoEstado;

    switch (opc){
        case 1:
            //mostrar modal para registrar el area
            nameView = "frm_editar_medio_contacto.php";
            urlPhp = "modules/01HelpDesk/views/medio_contacto/";
            divResult = "ShowModal";
            strData = {opcion:opc,idMedioContacto:idMedioContacto};
            sendAjax = true;
            break;
        case 2:
            //funcion para registrar el area
            nameView = "fn_editar_medio_contacto.php";
            urlPhp = "modules/01HelpDesk/src/medio_contacto/";
            divResult = "imgLoad";

            var nombre_medio_contacto = $('#nombre_medio_contacto').val(),
                NoEstado = $('#editar_medio_contacto').val();

            if($.trim(nombre_medio_contacto) == ""){
                MyAlert("Ingresa el nombre del medio de contacto","alert");
            }
            else{

                strData = {opcion:opc,idMedioContacto:idMedioContacto,nombre_medio_contacto:nombre_medio_contacto,NoEstado:NoEstado};
                sendAjax = true;
            }

            break;
    }

    if(sendAjax){
        SendAjax(
            urlPhp,
            nameView,null,divResult,'post',null,strData
        );
    }

}


/**
 * ABC de catalogo de categorias
 */
function fn_cat_editar_categoria(opc,nocategoria,NoArea,depto){

    var nameView,divResult,strData,sendAjax = false,urlPhp,NoEstado;

    switch (opc){
        case 1:
            //mostrar modal para registrar el area
            nameView = "frm_editar_categoria.php";
            urlPhp = "modules/01HelpDesk/views/categorias/";
            divResult = "ShowModal";
            strData = {opcion:opc,nocategoria:nocategoria,NoArea:NoArea,NoDepartamento:depto};
            sendAjax = true;
            break;
        case 2:
            //funcion para registrar el area
            nameView = "fn_editar_categoria.php";
            urlPhp = "modules/01HelpDesk/src/categorias/";
            divResult = "imgLoad";

            var nombre_categoria = $('#editar_nombre_categoria').val(),
                NoDepartamento = $('#editar_NoDepartamento').val(),
                NoArea = $('#id_noarea').val(),
                NoEstado = $('#editar_NoEstado').val();

            if($.trim(nombre_categoria) == ""){
                MyAlert("Ingresa el nombre de la categoría","alert");
            }else if(NoDepartamento == 0){
                MyAlert("Selecciona el Departamento del categoría","alert");
            }else if(NoArea == 0){
                MyAlert("Seleccione el área de la categoría","alert");
            }
            else{

                strData = {opcion:opc,nocategoria:nocategoria,nombre_categoria:nombre_categoria,NoArea:NoArea,NoEstado:NoEstado,NoDepartamento:NoDepartamento};
                sendAjax = true;
            }

            break;
    }

    if(sendAjax){
        SendAjax(
            urlPhp,
            nameView,null,divResult,'post',null,strData
        );
    }

}

function fn_catalogo_categorias_lista(opc){
    var nameView,divResult,strData,sendAjaxs = false,urlPhp;

    // listar todos los puestos
    urlPhp = "modules/01HelpDesk/src/categorias/";
    nameView = "fn_listar_categorias.php";
    strData = {opcion:opc};
    divResult = "#lListTable";
    sendAjaxs = true;

    if(sendAjaxs){
        $.ajax({
            url:urlPhp+nameView+"",
            type:"POST",
            data:strData
        }).done(function (data){

            $(divResult).html(data);

        }).fail(function(jqHR,textEstatus,error){
            if(console && console.log){
                MyAlert("Error al realizar la carga de la vista:{ views: "+textEstatus +" - "+error+ " - "+nameView+" }","error");
            }
        });
    }

}

function fn_cat_nueva_categoria(opc){

    var nameView,divResult,strData,sendAjax = false,urlPhp;

    switch (opc){
        case 1:
            //mostrar modal para registrar el area
            nameView = "frm_nueva_categoria.php";
            urlPhp = "modules/01HelpDesk/views/categorias/";
            divResult = "ShowModal";
            strData = {opcion:opc};
            sendAjax = true;
            break;
        case 2:
            //funcion para registrar el area
            nameView = "fn_nueva_categoria.php";
            urlPhp = "modules/01HelpDesk/src/categorias/";
            divResult = "imgLoad";

            var nombre_categoria = $('#nombre_categoria').val(),
                NoDepartamento = $('#NoDepartamento').val(),
                NoArea = $('#id_noarea').val();

            if($.trim(nombre_categoria) == ""){
                MyAlert("Ingresa el nombre de la categoría","alert");
            }else if(NoDepartamento == 0){
                MyAlert("Selecciona el Departamento de la categoría","alert");
            }else if(NoArea.toString == '0'){
                MyAlert("Seleccione el área correspondiente","alert");
            }else{
                strData = {opcion:opc,nombre_categoria:nombre_categoria,NoDepartamento:NoDepartamento,NoArea:NoArea};
                sendAjax = true;
            }

            break;
    }

    if(sendAjax){
        SendAjax(
            urlPhp,
            nameView,null,divResult,'post',null,strData
        );
    }

}

/**
 * ABC de Catalogo de Areas
 */
function fn_cat_editar_area(opc,NoArea,depto){

    var nameView,divResult,strData,sendAjax = false,urlPhp,NoEstado;

    switch (opc){
        case 1:
            //mostrar modal para registrar el area
            nameView = "frm_editar_area.php";
            urlPhp = "modules/01HelpDesk/views/areas/";
            divResult = "ShowModal";
            strData = {opcion:opc,NoArea:NoArea,NoDepartamento:depto};
            sendAjax = true;
            break;
        case 2:
            //funcion para registrar el area
            nameView = "fn_editar_area.php";
            urlPhp = "modules/01HelpDesk/src/areas/";
            divResult = "imgLoad";

            var nombre_area = $('#editar_nombre_area').val(),
                NoDepartamento = $('#editar_NoDepartamento').val(),
                NoEstado = $('#editar_NoEstado').val();

            if(NoArea <= 0){
                MyAlert("El área no existe","error");
            }else if($.trim(nombre_area) == ""){
                MyAlert("Ingresa el nombre del área","alert");
            }else if(NoDepartamento == 0){
                MyAlert("Selecciona el Departamento del área","alert");
            }else{

                strData = {opcion:opc,NoArea:NoArea,NoEstado:NoEstado,nombre_area:nombre_area,NoDepartamento:NoDepartamento};
                sendAjax = true;
            }

            break;
    }

    if(sendAjax){
        SendAjax(
            urlPhp,
            nameView,null,divResult,'post',null,strData
        );
    }


}

function fn_cat_nueva_area(opc){

    var nameView,divResult,strData,sendAjax = false,urlPhp;

    switch (opc){
        case 1:
            //mostrar modal para registrar el area
            nameView = "frm_nueva_area.php";
            urlPhp = "modules/01HelpDesk/views/areas/";
            divResult = "ShowModal";
            strData = {opcion:opc};
            sendAjax = true;
            break;
        case 2:
            //funcion para registrar el area
            nameView = "fn_nueva_area.php";
            urlPhp = "modules/01HelpDesk/src/areas/";
            divResult = "imgLoad";

            var nombre_area = $('#nombre_area').val(),
                NoDepartamento = $('#NoDepartamento').val();

            if($.trim(nombre_area) == ""){
                MyAlert("Ingresa el nombre del área","alert");
            }else if(NoDepartamento == 0){
                MyAlert("Selecciona el Departamento del área","alert");
            }else{
                strData = {opcion:opc,nombre_area:nombre_area,NoDepartamento:NoDepartamento};
                sendAjax = true;
            }

            break;
    }

    if(sendAjax){
        SendAjax(
          urlPhp,
            nameView,null,divResult,'post',null,strData
        );
    }

}

function fn_catalogoAreas_lista(opc){
    var nameView,divResult,strData,sendAjaxs = false,urlPhp;

    // listar todos los puestos
    urlPhp = "modules/01HelpDesk/src/areas/";
    nameView = "fn_listar_areas.php";
    strData = {opcion:opc};
    divResult = "#lListTable";
    sendAjaxs = true;

    if(sendAjaxs){
        $.ajax({
            url:urlPhp+nameView+"",
            type:"POST",
            data:strData
        }).done(function (data){

            $(divResult).html(data);

        }).fail(function(jqHR,textEstatus,error){
            if(console && console.log){
                MyAlert("Error al realizar la carga de la vista:{ views: "+textEstatus +" - "+error+ " - "+nameView+" }","error");
            }
        });
    }

}

// #################################################


/**
 * Reasignacion de Equipos de Uso interno
 * Opc = 1 Mostar Modal para ingresar los nuevos datos del empleado
 * Opc = 2 Llamar a la funcion para realizar la reasignacion del Equipo
 * @param opc
 */

function fnReasignarEquipo(opc,confirm ){

    switch (opc){
        case 1:

            var folio = $("#NoFolio").val(),
                EstadoEquipo = $("#estadoequipo").val();

            if(EstadoEquipo != 2 && confirm == false){

                MyAlert("La reasignacion no aplica para el estatus actual","error");

            }else if(parseInt(folio) <= 0 || typeof(folio) === "undefined" ){
                MyAlert("No se encontro el folio para la reasignacion","error");
            }else{



                SendAjax(
                    "modules/01HelpDesk/views/equipos/",
                    "frm_reasignacion.php",
                    null,
                    "idgeneral",
                    "post",null,{
                        folio:folio
                    }
                );

            }

            break;
        case 2:

            var Folio = $("#rFolio").val();

            var NoEmpleado = $('#rNoEmpleado').val(),
                NombreEmpleado = $("#rNoEmpleado option:selected").text(),
                Puesto = $('#rPuesto').val(),
                NoDepartamento = $("#rNoDepartamento").val(),
                FechaRegistro = $('#rFechaRegistro').val();

           if(parseInt(Folio) <= 0 || typeof(Folio) === "undefined" ){
                MyAlert("No se encontro el folio para la reasignacion","error");
            }else if(NoEmpleado == 0 ){
                MyAlert("Seleccione un empleado","alert");
            }else if($.trim(Puesto) == ""){
                MyAlert("Ingrese el puesto del empleado","alert");
            }else if(NoDepartamento == 0){
                MyAlert("Seleccione un departamento","alert");
            }else{

                $.ajax({
                    url:"modules/01HelpDesk/src/equipos/fn_reasigar_equipo.php",
                    type:"post",
                    dataType:"json",
                    beforeSend:function(){
                        fnloadSpinner(1);
                    },
                    data:{
                        Folio:Folio,
                        NoEmpleado:NoEmpleado,
                        NombreEmpleado:NombreEmpleado,
                        Puesto:Puesto,
                        NoDepartamento:NoDepartamento,
                        FechaRegistro:FechaRegistro
                    }
                }).done(function (response){
                    fnloadSpinner(2);


                    switch (response.result){

                        case 'ok':

                            fnsdMenu(13,"?fl="+response.data.Folio);
                            $("#mdlBtnClose").click();
                            MyAlert("Reasignacion exitosa","success");


                            break;
                        case 'error':
                            MyAlert(response.mensaje,"error");
                            break;
                    }


                }).fail(function(JqH,textStatus,errno){
                    fnloadSpinner(2);

                    if(console && console.log){

                        if(textEstatus == 'timeout')
                        {
                            MyAlert('EL tiempo de la solicitud a sido agotado','alert');
                            //do something. Try again perhaps?

                        }else{
                            MyAlert("Error al Realizar el proceso: "+ textStatus +errno + JqH ,"alert");

                        }

                    }


                });

            }

            break;
    }


}

function ActualizaEstatusEquipo(opc,folio,estado,url){
    var Mypage= "",mydiv = "",SendAjax = false;
    var param1,param2,param3,param4;
    switch (opc){
        case 1:
            //Carta de Asignacion
            Mypage = "modules/01HelpDesk/src/equipos/fn_carta_responsiva.php";
            SendAjax = true;
            mydiv = "ShowModal";

            var motivo_asignacion = $("#motivo_asignacion").val(),
                caracteristica_equipo = $("#caracteristica_equipo").val();

            $.ajax({
                url:Mypage,
                data:{opc:opc,folio:folio,estado:estado,url:url,motivo_asignacion:motivo_asignacion,caracteristica_equipo:caracteristica_equipo},
                type:"POST",
                dataType:"JSON"
            }).done(function (data) {

                if(data.result == "ok"){

                    $('#mdl_asignacion_equipo').modal('toggle');

                    setTimeout(function() {
                        fnsdMenu(13,"?fl="+folio+"");
                    }, 1000);

                    setTimeout(function() {
                        window.open(""+url+"");
                    }, 2000);
                }


            }).fail(function (jqh,textStatus) {


                MyAlert("Error al realizar la asignacion: "+textStatus,"alert");

            });


            break;
        case 2:
            //Carte de entrega
            Mypage = "modules/01HelpDesk/src/equipos/fn_entrega_equipo.php";

            var condicionEntrega2 = $("#condicionEntrega2").val(),
                motivoentrega2 = $("#motivoentrega2").val(),
                usuarioequipo = $("#usuarioequipo").val(),
                contrasenaequipo = $("#contrasenaequipo").val();

            $.ajax({
                url:Mypage,
                data:{opc:opc,folio:folio,estado:estado,url:url,condicionEntrega2:condicionEntrega2,motivoentrega2:motivoentrega2,usuarioequipo:usuarioequipo,contrasenaequipo:contrasenaequipo},
                type:"POST",
                dataType:"JSON"
            }).done(function (data) {

                if(data.result == "ok"){

                    $('#mdl_entrega_equipo').modal('toggle');

                    setTimeout(function() {
                        fnsdMenu(13,"?fl="+folio+"");
                    }, 1000);

                    setTimeout(function() {
                        window.open(""+url+"");
                    }, 2000);
                }


            }).fail(function (jqh,textStatus) {

                MyAlert("Error al realizar la entrega: "+textStatus,"alert");

            });



            break;
        case 3:
            Mypage = "modules/01HelpDesk/src/equipos/fn_envio_cedis.php";
            SendAjax = true;
            mydiv = "ShowModal";

            var condicionEntrega2 = $("#condicionEntrega2").val(),
                motivoentrega2 = $("#motivoentrega2").val();

            $.ajax({
                url:Mypage,
                data:{opc:opc,folio:folio,estado:estado,url:url,condicionEntrega2:condicionEntrega2,motivoentrega2:motivoentrega2},
                type:"POST",
                dataType:"JSON"
            }).done(function (data) {

                if(data.result == "ok"){

                    $('#mdl_envio_equipo').modal('toggle');

                    setTimeout(function() {
                        fnsdMenu(13,"?fl="+folio+"");
                    }, 1000);

                    setTimeout(function() {
                        window.open(""+url+"");
                    }, 2000);
                }


            }).fail(function (jqh,textStatus) {

                MyAlert("Error al realizar el envio: "+textStatus,"alert");

            });


            break;
    }

}

// Funcion para imprimir el documento de la carta responsiva.
function fnsdimprimirEquipo(opc){

    var folio = $('#NoFolio').val();
    var estado = $('#estadoequipo').val(),senAjax = false;
    var urlReporte = 'modules/01HelpDesk/reportes/equipos/rpt.carta_responsiva.php?fl='+folio+'&est='+estado+'&rf='+opc+'&a=0';
    // 1 = Asignacion: 2 = Entregado: 3 = Enviado a Cedis: 4 = En Proces: 5 = Reasignado.
    //Evaluar que Documento se quiere Imprimir
    if(opc == 1 ){
        //si el Estatus es en Proceso, se le Informa que al Imprimir el documento, automaticamente
        //pasara a Estatus de Asignado;
        if(estado == 4){
            bootbox.confirm({
                title:"Imprmir carta responsiva de equipo",
                message:ptabla + " Se realizara cambio de estatus a: Asignado",
                size:"small",
                callback:function(result){
                    if(result){

                        //ActualizaEstatusEquipo(opc,folio,estado,urlReporte);
                        SendAjax(
                            "modules/01HelpDesk/views/equipos/",
                            "frm_asignacion.php",
                            null,
                            "idgeneral",
                            "post",
                            null,
                            {
                                opc:opc,
                                folio:folio,
                                estado:estado,
                                urlReporte:urlReporte

                            }
                        );
                    }
                }

            });
        }else{
            //Si el Estado es diferente a en Proceso, se Manda a Imrpimir el Documento
            window.open(urlReporte);
        }
        //Se vuelve a Evaluar la Opcion si es 2, se manda a Imprimir la Carta de Entrega a Sistemas
    }else if(opc == 2){

        if(estado != 4){
            if(estado >= 2 ){
                window.open(urlReporte);
            }else{
                bootbox.confirm({
                    title:"Imprmir carta de entrega a sistemas",
                    message: ptabla + "Se realizara cambio de estatus a: Entregado ",
                    size:"small",
                    callback:function(result){
                        if(result){


                            //ActualizaEstatusEquipo(opc,folio,estado,urlReporte);

                            SendAjax(
                                "modules/01HelpDesk/views/equipos/",
                                "frm_entrega.php",
                                null,
                                "idgeneral",
                                "post",
                                null,
                                {
                                    opc:opc,
                                    folio:folio,
                                    estado:estado,
                                    urlReporte:urlReporte

                                }
                            );

                        }
                    }

                });
            }
        }else{
            MyAlert("Primero debe realizar la asignación del equipo","alert");
        }

    }else if(opc == 3){



        if(estado != 1 ){
            if(estado !=5){
                if(estado == 3){
                    window.open(urlReporte);
                }else{
                    bootbox.confirm({
                        title:"Imprmir carta de envio a cedis",
                        message: ptabla + "Se realizara cambio de estatus a: Enviado ",
                        size:"small",
                        callback:function(result){
                            if(result){

                                //ActualizaEstatusEquipo(opc,folio,estado,urlReporte);
                                SendAjax(
                                    "modules/01HelpDesk/views/equipos/",
                                    "frm_envio.php",
                                    null,
                                    "idgeneral",
                                    "post",
                                    null,
                                    {
                                        opc:opc,
                                        folio:folio,
                                        estado:estado,
                                        urlReporte:urlReporte

                                    }
                                );
                            }
                        }

                    });
                }
            }else{

                MyAlert("El equipo se encuentra reasignado","alert");
            }
        }else{
            MyAlert("El equipo no se a entregado","alert");

        }
    }
}

// funcion para buscar equipos internos
function fnsdBuscar_equipo_inerno(opc){

    var mdata ;

    switch (opc){
        case 1:
            if($.trim($("#e_text_buscar").val()) == "" && $("#e_tipo_equipo").val() == 0 && $("#e_departamento").val() == 0 && $("#e_estado").val() == 0 && $("#e_usuario_registra").val() == 0 ){
                MyAlert("Seleccione algun filtro para realizar la busqueda","error");
            }else{

                mdata = {
                    mtext:$("#e_text_buscar").val(),
                    tpo_equipo:$("#e_tipo_equipo").val(),
                    departamento:$("#e_departamento").val(),
                    estado:$("#e_estado").val(),
                    user:$("#e_usuario_registra").val(),
                    opt:6
                };

                $.ajax({
                    url:"modules/01HelpDesk/src/equipos/fn_listar_equipos.php",
                    type:"POST",
                    data:mdata,
                    beforeSend:function(){
                        fnloadSpinner(1,"fa-search","mdl_btn_search");
                    }
                }).done(function(data){
                    fnloadSpinner(2,"fa-search","mdl_btn_search");
                    $('#listTable').html(data);

                }).fail(function(jqXHR,textStatus,errorthwor){

                });

            }
            break;
        case 2:

            mdata = {
                fch01:$("#e_fch01").val(),
                fch02:$("#e_fch02").val(),
                tpo_equipo:$("#e_tipo_equipo").val(),
                departamento:$("#e_departamento").val(),
                estado:$("#e_estado").val(),
                user:$("#e_usuario_registra").val(),
                opt:7
            };

            $.ajax({
                url:"modules/01HelpDesk/src/equipos/fn_listar_equipos.php",
                type:"POST",
                data:mdata,
                beforeSend:function(){
                    fnloadSpinner(1,"fa-search","mdl_btn_search");
                }
            }).done(function(data){
                fnloadSpinner(2,"fa-search","mdl_btn_search");
                $('#listTable').html(data);

            }).fail(function(jqXHR,textStatus,errorthwor){

            });
            break
    }


}

//Imprimir y Actualizar el Estatus del Equipo
function fnsdImprimeyActualizaEquipo(folio,url,CloseModal){
    //alert(folio);


        $('#myModal').modal('toggle');


    setTimeout(function() {
        fnsdMenu(13,"?fl="+folio+"");
    }, 1000);

    setTimeout(function() {
        if(CloseModal){
            window.open(""+url+"");
        }
    }, 2000);
}

//function para Subir Archivos al servidor por medio de Ajax
function fnsd_uploadAjax(param01,param02,param03,tpodoc){

    var inputFileImage = document.getElementById("selectedFile");
    var file = inputFileImage.files[0],
        dpto = param03;
    var data = new FormData();
    data.append('archivo',file);
    var url = 'modules/01HelpDesk/src/tickets/fn_upload_files.php?pr01='+param01+"&pr02="+param02+"&pr03="+dpto+"&doc="+tpodoc;

    $.ajax({
        url:url,
        type:'post',
        contentType:false,
        data:data,
        processData:false,
        cache:false,
        success:function(data){
            //ShowHideLoading(2);
            $('#uploadfile').html(data);
        }
    });

}

function fn_cerrar_ticket(folio,anio,dpto){
    var param01 = $("#t_atencion").val();
    var param02 = $("#tipo_cierre").val();
    var param03 = $("#solucion").val();

    if(param01 == "0"){
        MyAlert("Seleccione el tipo de atencion","alert")
    }else if($.trim(param03) == ""){
        MyAlert("El campo de solución no debe estar vacio","alert");
    }else{

        var strData = {folio:folio,anio:anio,dpto:dpto,t_atencion:param01,tipo_cierre:param02,solucion:param03};
        SendAjax(
            "modules/01HelpDesk/src/tickets/","fn_cerrar_ticket.php",null,"imgLoad","POST","btnSave",strData
        );

    }
}

function fn_seguimiento_ticket(folio,anio,dpto){

    var param01 = $("#t_atencion").val(); //ComboBox Tipo de Atencion
    var param02 = $("#seguimiento").val(); //TextArea Seguimiento

    if(param01 == "0"){
        MyAlert("Seleccione el tipo de seguimiento","alert");
    }else if($.trim(param02) == ""){
        MyAlert("El campo seguimiento no debe estar vacio","alert");
    }else{
        var strData = {folio:folio,anio:anio,dpto:dpto,tipo_atencion:param01,seguimiento:param02};
        SendAjax(
            "modules/01HelpDesk/src/tickets/","fn_seguimiento_ticket.php",null,"imgLoad","POST","btnSave",strData
        );

    }
}

//Funcion para reasignar el Ticket
function fn_reasignar_ticket(folio,anio,dpto){

    var NoUsuarioAsignado = $("#asignen").val();

    var strData = {folio:folio,anio:anio,dpto:dpto,UsuarioAsignado:NoUsuarioAsignado};


    SendAjax(
        "modules/01HelpDesk/src/tickets/","fn_reasignar_ticket.php",null,"imgLoad","POST","btnSave",strData
    );

}

//Fin de Las Graficas
function CloseModalAndReload(){
    $('#myModal').modal('toggle');

    setTimeout(function() {
        BotonReload();
    }, 1000);
}

//Boton Recarga Funciones Ticket en ->VerTicket.php
function BotonReload(){
    $('#btnReload').click();
}

function fnsdEditarTicket(folio,anio,dpto){

    // Recoger Variables del Formulario
    var nosucursal = $("#eDepartamento").val(),
        solicitante = $("#eNombreSolicita").val(),
        mediocontacto = $("#eMedioDeContacto").val(),
        noarea = $("#earea").val(),
        ncategoria = $("#id_categorias").val(),
        descripcioncorta = $("#eDescripcion").val(),
        descripcion = $("#eInformacion").val(),
        fechaalta = $("#efchalta").val(),
        nprioridad = $("#ePrioridad").val(),
        nestatus = $("#eEstatus").val(),
        fechapromesa = $("#fecha_promesa").val(),
        tpoAtencion = $("#etipomantenimiento").val(),
        UsuarioAsignado = $("#eAsignenPerson").val();

    if($.trim(nosucursal) == 0){
        MyAlert("Seleccione una Sucursal","alert");
    }else if($.trim(solicitante) == ""){
        MyAlert("Ingrese el nombre del solicitante","alert");
    }else if($.trim(mediocontacto) == 0){
        MyAlert("Seleccione el medio de contacto","alert");
    }else if($.trim(noarea) == 0 && dpto != 2){
        MyAlert("Seleccione unas de las  areas","alert");
    }else if($.trim(descripcioncorta) == ""){
        MyAlert("La Descripcion del reporte, no debe estar vacia","alert");
    }else if($.trim(descripcion) == ""){
        MyAlert("La Descripcion Detallada, no debe estar vacia","alert");
    }else if($.trim(fechaalta) == ""){
        MyAlert("Fecha Incorrecta Intentelo Nuevamente","alert");
    }else if($.trim(nprioridad) == 0){
        MyAlert("Seleccione la prioridad del reporte","alert");
    }else if($.trim(fechapromesa) == ""){
        MyAlert("Fecha Promesa Incorrecta, Intentelo nuevamente","alert");
    }else if($.trim(tpoAtencion) == 0){
        MyAlert("Seleccione el tipo de Atencion","alert");
    }else{
        $("#btnFrmSave").attr('disabled', true);
        bootbox.confirm({
            title:"Guardar Cambios ",
            message:ptabla +"Esta seguro de guardar los cambios",
            size:"small",
            callback:function(result){
                if(result){

                    fnloadSpinner(1,"fa-save","btnSave");

                    $.ajax({
                        url:"modules/01HelpDesk/src/tickets/fn_editar_ticket.php",
                        type:"POST",
                        data:{fl:folio,an:anio,nodpto:dpto,sucursal:nosucursal,solicita:solicitante,mcontacto:mediocontacto,area:noarea,categoria:ncategoria,desc01:descripcioncorta,desc02:descripcion,
                            falta:fechaalta,prioridad:nprioridad,estado:nestatus,fchpromesa:fechapromesa,tpatencion:tpoAtencion,usuario:UsuarioAsignado},
                        success:function(data){
                            fnloadSpinner(2,"fa-save","btnSave");
                            $("#callBackEdit").html(data);
                        }
                    });
                }
            }

        });
    }


}

//Eliminar Adjuntos
function fnsdEliminaAdjunto(idAdjunto,NoFolio,Anio,NoDepartamentoTicket,tpofile){
    //Div donde se Mostrara el Resultado
    var divClosed;
    if(tpofile==1){
        divClosed = 'tabs-6';
    }else{
        divClosed = 'tabs-5';
    }

    var strData = {idadd:idAdjunto,fl:NoFolio,an:Anio,nodpto:NoDepartamentoTicket,opt:tpofile};
    //Pregutar si esta seguro de Eliminar

    bootbox.confirm({
        title:"Eliminar Adjunto",
        message:ptabla +"Esta seguro de eliminar el documento adjunto",
        size:"small",
        callback:function(result){
            if(result){

                SendAjax(
                    "modules/01HelpDesk/src/tickets/",
                    "fn_elimina_adjunto.php",
                    null,
                    divClosed,
                    "POST",
                    null,
                    strData
                );
            }
        }
    });
}

//function para enviar correo de recordatorio
function fnsdSendRecordatorio(opc,ticket,anio,dpto){

    var asunto = $("#subject").val(),
        mensaje = $("#mensaje").val();
    if($.trim(asunto) == ""){
        MyAlert("Ingrese el Asunto del Mensaje","alert");
    }else{

        var strData = {opt:opc,fl:ticket,an:anio,nodpto:dpto,subject:asunto,msg:mensaje};

        $.ajax({
            url:'modules/01HelpDesk/src/tickets/fn_enviar_correo.php',
            type:"POST",
            data:strData,
        }).done(function(data){
            $('#modalbtnclose').click();
        }).fail(function(jqXHR,textEstatus,error){

            if(console && console.log){
                MyAlert("La solicitud a fallado: " +  textEstatus,'error');
            }

        });
        $('#modalbtnclose').click();





    }
}

// funcion para Cargar los empleados al registrar el ticket de acuerdo a su departamento
function fnsdCargarUsuarios(NoDepartamento){

    if(NoDepartamento != '0'){
        fnsdshownameEncargada(NoDepartamento);
    }else{
        MyAlert("Selecciona una Sucursal","alert");
    }
}

//funcion para seleccionar el empleado una vez que se halla realizado la busqueda de el.
function jsSdSeleccionarEmpleado(nodpto,nombredpto,idEmpleado,nombreEmpleado,idBtnClosed,nomina){

    if($.trim(nomina) == ""){
        MyAlert("El Empleado no cuenta con su numero de nomina","error");
    }else{
        if($("#Departamento").val() == "0"){
            AgregarDataSelect('Departamento',''+ nodpto +'',''+ nombredpto +'(' + nodpto +' )');

            fnsdCargarUsuarios(''+nodpto+'');
        }

        setTimeout(function() {
            AgregarDataSelect('NombreSolicita',''+ idEmpleado +'',''+ nombreEmpleado +'');
            $('#'+idBtnClosed+'').click();
        }, 600);
    }

}

function AgregarDataSelect(idDiv,valor,Descripcion){

    $("#"+idDiv+"").append($("<option value='"+valor+"' selected='selected'></option>").text(Descripcion));
}

//Funcion para Abrir modales y cargar los Formularios
function fnsdAbrirModalTicket(opcion,folio,anio,dpto){
    var rtaFile,frmEnviar = false,iddiv="ModalTicket";
    switch (opcion){
        case 1:
            rtaFile = "modules/01HelpDesk/views/tickets/frm_editar_ticket.php";
            frmEnviar = true;
            break;
        case 2:
            rtaFile = "modules/01HelpDesk/views/tickets/frm_reasigna_ticket.php";
            frmEnviar = true;
            break;
        case 3:
            break;
        case 4:
            //modal para dar seguimiento al Ticket
            rtaFile = "modules/01HelpDesk/views/tickets/frm_seguimiento_ticket.php";
            frmEnviar = true;
            break;
        case 5:
            //modal para cerrar Ticket
            rtaFile = "modules/01HelpDesk/views/tickets/frm_cerrar_ticket.php";
            frmEnviar = true;
            break;
        case 6:
            //Adjuntar Documento
            rtaFile = "modules/01HelpDesk/views/tickets/frm_add_documento.php";
            frmEnviar = true;
            break;
        case 7:
            //Adjuntar Imagen
            rtaFile = "modules/01HelpDesk/views/tickets/frm_add_image.php";
            frmEnviar = true;
            break;
        case 8:
            //Imprimir Reporte
            window.open('modules/01HelpDesk/reportes/tickets/rpt.reporte_ticket.php?fl='+folio+"&an="+anio+"&nodpto="+dpto+"");
            break;
        case 9:
            rtaFile = "modules/01HelpDesk/views/tickets/frm_enviar_correo.php";
            frmEnviar = true;
            break;
        case 10:
            //alert(opcion);
            rtaFile = "layout/workorders/FrmMdlAgregarWorkOrder.php";
            frmEnviar = true;
            break;
        case 11:
            //modal para mostrar las imagenes Adjuntas
            rtaFile = "modules/01HelpDesk/src/tickets/fn_modal_ver_img_adjunto.php";
            frmEnviar = true;
            break;
        case 12:
            //modal para mostrar las imagenes Adjuntas
            rtaFile = "modules/01HelpDesk/views/tickets/frm_firma_ticket.php";
            frmEnviar = true;
            break;
    }
    if(frmEnviar){
        $.ajax({
            url:""+rtaFile+"",
            type:"POST",
            data:{opt:opcion,fl:folio,an:anio,nodpto:dpto},
        }).done(function(data){
            $('#'+iddiv+'').html(data);
        }).fail(function(jqXHR,textEstatus,error){

            if(console && console.log){
                MyAlert("La solicitud a fallado: " +  textEstatus,'error');
            }

        });
    }
}

//Funcion para Registra el Equipo Asignado
function fnsdAsignarEquipo(){

    var idempleado = $('#nombrecompleto').val();
    var nombre = $("#nombrecompleto option:selected").text();

    var departamento = $('#depto').val();
    var puesto = $('#puesto').val();
    var fecha_registro = $('#fecha_registro').val();

    var equipo = $('#equipo').val();
    var marca = $('#marca').val();
    var modelo = $('#modelo').val();
    var procesador = $('#procesador').val();
    var memoria = $('#memoria').val();
    var disco = $('#disco').val();

    var codigo = $('#codigo').val();
    var serie = $('#serie').val();
    var serieequipo = $('#serieequipo').val();

    var motivo = $('#motivo').val();
    var caracteristicas = $('#caracteristicas').val();
    if(nombre == ""){
        MyAlert("Ingrese el nombre a quien se asginara el equipo.","alert");
    }else if(puesto == ""){
        MyAlert("Ingrese el puesto del usuario.","alert");
    }else if(fecha_registro == ""){
        MyAlert("Ingrese la fecha de asignación del Equipo.","alert");
    }else if(equipo == ""){
        MyAlert("Ingrese el nombre del equipo.","alert");
    }else if(marca == ""){
        MyAlert("Ingrese la marca del equipo.","alert");
    }else if(modelo == ""){
        MyAlert("Ingrese el modelo del equipo.","alert");
    }else if(codigo == ""){
        MyAlert("Ingrese el codigo cedis del equipo.","alert");
    }else if($('#codigo').val().length < 5){
        MyAlert("El codigo ingresado no es valido, favor de revisarlo.","alert");
    }else if(serie == ""){
        MyAlert("Ingrese la Serie Cedis del Equipo.","alert");
    }else if($('#serie').val().length < 8){
        MyAlert("La serie cedis del equipo es invalida, favor de revisarlo.","alert");
    }else if(serieequipo == ""){
        MyAlert("Ingrese el numero de serie del equipo.","alert");
    }else{

        var strData = {nombrecompleto:nombre,dpto:departamento,puestou:puesto,fecha_registro:fecha_registro,equipou:equipo,marcau:marca,modelou:modelo,procesadoru:procesador,memoriau:memoria,discou:disco,codigou:codigo,serieu:serie,serieequipou:serieequipo,motivou:motivo,caracteristicasu:caracteristicas};

        SendAjax(
            "modules/01HelpDesk/src/equipos/",
            "fn_asignar_equipo.php",
            null,
            "listTable",
            "POST",
            null,
            strData
        );
    }
}

// Ventana Modal para Adjuntar los Documentos e Imagenes
function fnsdShowModalAddDocuments(opc){
    var folio = $("#NoFolio").val();

    $.ajax({
        url:"modules/01HelpDesk/views/equipos/frm_add_documento.php",
        type:"POST",
        data:{opt:opc,fl:folio},
        success:function(data){
            $("#ShowModal").html(data);
        }
    });
}

//Funcion para guardar los cambios realizados en el equipo asignado del Usuario
function fnsdEditarEquipoAsignado(){
    var folio = $('#num_folio').text();

    var nombre = $("#nombrecompleto option:selected").text();
    var idempleado = $('#nombrecompleto').val();

    var departamento = $('#depto').val();
    var puesto = $('#puesto').val();
    var fechaasig = $('#fechaasig').val();
    var nuevoestado = $('#estadoequipo').val();

    var equipo = $('#equipo').val();
    var marca = $('#marca').val();
    var modelo = $('#modelo').val();
    var procesador = $('#procesador').val();
    var memoria = $('#memoria').val();
    var disco = $('#disco').val();

    var codigo = $('#codigo').val();
    var serie = $('#serie').val();
    var serieequipo = $('#serieequipo').val();

    var motivo = $('#motivo').val();
    var caracteristicas = $('#caracteristicas').val();
    var condicionEntrega = $('#condicionEntrega').val();
    var motivoentrega = $('#motivoentrega').val();

    if(nuevoestado <= 2 || nuevoestado == 4){
        bootbox.confirm({
            title:"Guardar Cambios",
            message: ptabla +"Esta de guardar los cambios",
            size:"small",
            callback:function(result){
                if(result){

                    var strData = {opt:4,fl:folio,newestado:nuevoestado,nombrecompleto:nombre,dpto:departamento,puestou:puesto,fecha:fechaasig,equipou:equipo,marcau:marca,modelou:modelo,procesadoru:procesador,memoriau:memoria,discou:disco,codigou:codigo,serieu:serie,serieequipou:serieequipo,motivou:motivo,caracteristicasu:caracteristicas,condicionEntregau:condicionEntrega,motivoentregau:motivoentrega};

                    SendAjax(
                        "modules/01HelpDesk/src/equipos/",
                        "fn_editar_equipo.php",
                        null,
                        "listTable",
                        "POST",
                        null,
                        strData
                    );
                }
            }
        });
    }else{
        MyAlert("No se puede modificar el equipo, por que ya se encuentra en Cedis.","alert");
    }


}

//Funcion para Mostrar la Lista de Equipos Internos
function fnsdMostrarListaEquipos(opc,cond){
    confirm_close = false;
    $.ajax({
        url:"modules/01HelpDesk/src/equipos/fn_listar_equipos.php",
        type:"POST",
        data:{opt:opc},
        success:function(data){
            $('#listTable').html(data);
        }
    });
}

//Funcion para mostrar el Diagrama en el Modal.
function fnsdmostrar_diagrama(opc){
    var sucu = $("#Departamento").val();

    if(opc == 1){
        if(sucu == "-- --"){
            alert("Por Favor Seleccione una Sucursal.");
        }else{

            $.ajax({
                url:"modules/01HelpDesk/src/tickets/frm_diagrama_sucursal.php",
                type:"POST",
                data:{suc:sucu},
                success:function(data){
                    $("#diag-suc").html(data);
                }
            });
        }
    }else if(opc  == 2){
        $.ajax({
            url:"modules/applications/views/empleados/frm_modal_nuevo_empleado.php",
            type:"POST",
            data:{suc:sucu},
            success:function(data){
                $("#diag-suc").html(data);
            }
        });
    }else if(opc == 3){
        $.ajax({
            url:"modules/applications/views/empleados/frm_buscar_empleado.php",
            type:"POST",
            data:{suc:sucu},
            success:function(data){
                $("#diag-suc").html(data);
            }
        });
    }
}

//funcion para registrar el ticket
function fnsdRegistraTicket(type_regis,nodepa,mesa_deayuda){

    var mySend = false,myfaIcon,myidBtn,mystrData,myUrlPhp,myidDiv,myNameView ;

    switch (type_regis){

        case 1:

            // registro de Ticket por Tecnico
            var solicitante = $('#NombreSolicita').val();
            var prioridad = $('#Prioridad').val();
            var nsucursal = $('#Departamento').val();
            var estado = $('#Estatus').val();
            var mcontac = $('#MedioDeContacto').val();
            var area = $('#area').val();
            var categoria = $('#id_categorias').val();
            var asignen= $('#AsignenPerson').val();
            var tposervice = $('#tipomantenimiento').val();
            var descr = $('#Descripcion').val();
            var report = $('#Informacion').val();
            var fchAlta = $('#fchalta').val();

            if(nodepa == '0205'){
                area = 0;
                categoria = 0;
            }

            myUrlPhp = "modules/01HelpDesk/src/tickets/";
            myNameView = "fn_registra_tickets.php";
            myfaIcon = "fa-save";
            myidBtn = "btnSaveTicket";
            myidDiv = "HomeContent";

            if(nodepa != mesa_deayuda){
                //si el departamento es diferente a la mesa de ayuda seleccionada

                if (solicitante == 0){
                    MyAlert("Por Favor Ingrese El Nombre del Solicitante.","alert");
                }else if(nsucursal == 0){
                    MyAlert("El Campo de Solicitante no Debe Estar Vacio.","alert");
                }else if(descr == ""){
                    MyAlert("El Campo de Descripcion no Debe Estar Vacio.","alert");
                }else if(report == ""){
                    MyAlert("El Campo de la Descripcion Detallada no Debe Estar Vacio.","alert");
                }else{
                    $('#btn_regticket').hide();
                    $('#btn_cacel').hide();

                    mystrData = {
                        param:2,
                        dptoasignen:mesa_deayuda,
                        request:solicitante,
                        suc:nsucursal,
                        desc:descr,
                        reporte:report
                    };
                    mySend = true;

                }

            }else{

                if (solicitante == 0){
                    MyAlert("Por Favor Ingrese El Nombre del Solicitante.","alert");
                }else if(nsucursal == 0){
                    MyAlert("El Campo de Solicitante no Debe Estar Vacio.","alert");
                }else if(area == "-- --"){
                    MyAlert("Por Favor Seleccione el Area Afectada.","alert");

                }else if(descr == ""){
                    MyAlert("El Campo de Descripcion no Debe Estar Vacio.","alert");
                }else if(report == ""){
                    MyAlert("El Campo de la Descripcion Detallada no Debe Estar Vacio.","alert");
                }else{
                    $('#btn_regticket').hide();
                    $('#btn_cacel').hide();

                    mystrData = {
                        request:solicitante,
                        priority:prioridad,
                        suc:nsucursal,
                        status:estado,
                        contac:mcontac,
                        sarea:area,
                        scatego:categoria,
                        uasig:asignen,
                        tposerv:tposervice,
                        desc:descr,
                        reporte:report,
                        fcha:fchAlta
                    };
                    mySend = true;

                }

            }

            break;
        case 2:
            // registro de Ticket por Solicitante

            // Recibir datos y Guardarlos en una variable
            var solicita = $("#rNombreSolicita").val();
            var Departamento = $("#rAsignenDpto").val();
            var Descripcion1 = $("#rDescripcion").val();
            var Descripcion2 = $("#rInformacion").val();

            if(Departamento==0){
                MyAlert("Error al Registrar el Ticket, Seleccione un Departamento.","alert");
            }else if($.trim(solicita) == ""){
                MyAlert("Error al Registrar el Ticket, El Campo de Nombre No debe Estar Vacio.","alert");
            }else if($.trim(Descripcion1) == ""){
                MyAlert("Error al Registrar el Ticket, El Campo de Descripción Breve No debe Estar Vacio.","alert");
            }else if($.trim(Descripcion2) ==""){
                MyAlert("Error al Registrar el Ticket, El Campo de Descripción Detallada No debe Estar Vacio.","alert");
            }else{

                myUrlPhp = "modules/01HelpDesk/src/tickets/";
                myNameView = "fn_registra_tickets.php";
                mystrData =
                    {
                        param:2,
                        dptoasignen:Departamento,
                        request:solicita,
                        suc:nodepa,
                        desc:Descripcion1,
                        reporte:Descripcion2
                    };
                myidDiv = "HomeContent";
                myfaIcon = "fa-save";
                myidBtn = "btnTicketrequest";
                mySend = true;


            }

            break;
        default :
            MyAlert("error, la opcion solicitada no existe","error");


    }

    if(mySend){

        SendAjax(
            myUrlPhp,
            myNameView,
            null,
            myidDiv,
            "post",
            myidBtn,
            mystrData
        );
    }



}

//Funcion para mostrar la Fecha Promesa
function fnsdCalculaFechaPromesa(prioridad,fechaactual){
    $.ajax({
        url:"modules/01HelpDesk/src/tickets/fn_calcula_fecha_promesa.php",
        type:"POST",
        data:{fch:fechaactual,prio:prioridad},
        success:function(data){

            if (data.estado === "ok") {


                var fecha_promesa = data.fecha_promesa ;

                $("#fecha_promesa").val(fecha_promesa);

            }
        }
    })
}

// Exportar Reporte de Encuestas de Servicio
function fnsdExportarConsulta(){


    if($("#num").text() != '0'){
        window.open("modules/01HelpDesk/reportes/encuestas/rpt_encuestas_de_servicio.php");
    }else{
        MyAlert("No se encontraron resultados","alert");
    }

}
//Funcion para Abrir el Modal con la Informacion de la Encuesta de Servicio Contestada por el Usuario
function fnsdVerEncuesta(folioEncuesta){
    $.ajax({
        url:"modules/01HelpDesk/views/encuestas/frm_ver_encuesta.php",
        type:"POST",
        data:{fl:folioEncuesta},
        beforeSend:function(){
           fnloadSpinner(1);
        },
        success:function(data){
            $("#modl").html(data);
            fnloadSpinner(2);
        }
    });
}
//Funcion Buscar Encuesta de Servicio
function fnsdBuscarEncuesta(){

    var fch01 = $("#fch1").val(),
        fch02 = $("#fch2").val(),
        nosuc = $("#nosuc").val(),
        nouse = $("#nouser").val();

    $.ajax({
        url:"modules/01HelpDesk/src/encuestas/fn_buscar_encuesta_servicio.php",
        type:"POST",
        data:{opc:1,fch1:fch01,fch2:fch02,nosucursal:nosuc,nouser:nouse},
        beforeSend:function(){
            fnloadSpinner(1,"fa-search","mdl_search_encuestas");
        },
        success:function(data){
            fnloadSpinner(2,"fa-search","mdl_search_encuestas");
            $("#lListarTabla").html(data);
        }
    });

}

//Funcion para Imprimir los Reportes
function fnsdImprimirReportes(opc){

    var cons = $("#sql_query").html();
    if(cons == ""){
        alert("No se encontraron resultados");
    }else{
        window.open('modules/01HelpDesk/reportes/tickets/rpt.buscar_reporte_tickets.php?opc='+opc+'&tpoate='+1);
    }
}

//Funcion para Buscar Reportes
function fnsdBuscaReporte(){
    var sucursales = $("#suc").val(),
        estado = $("#est").val(),
        usuario = $("#user").val(),
        seguimiento = $("#seg").val(),
        fecha01 = $("#f01").val(),
        fecha02 = $("#f02").val(),
        contacto = $("#cont").val(),
        area = $("#id_noarea").val(),
        categoria = $("#cat").val(),
        nodepartamento = $("#NoDepartamento").val();

    if(nodepartamento == 0){
        MyAlert("Seleccione el departamento a Buscara");
    }else{
        $.ajax({
            url:"modules/01HelpDesk/src/tickets/fn_buscar_reportes.php",
            type:"POST",
            data:{nodepartamento:nodepartamento,suc:sucursales,est:estado,user:usuario,seg:seguimiento,f01:fecha01,f02:fecha02,cont:contacto,are:area,cat:categoria},
            beforeSend:function(){
                fnloadSpinner(1,'fa-search','btn-search');
                //ShowHideLoading(1,"Buscando . .");
            },
            success:function(data){
                fnloadSpinner(2,'fa-search','btn-search');
                $("#content-report").html(data);
            }
        });
    }

}

//Funcion para Guardar los Cambios del Perfil del Departamento Seleccionado
function fnsdGuardaCambios_InfoSucursal(){
    var nosuc = $("#e-nodepartamento").val();
    var nombresuc = $("#e-nombresucursal").val();
    var direc = $("#e-direccion").val();

    var correo = $("#e-correo").val();
    var tel1 = $("#e-tel1").val();
    var tel2 = $("#e-tel2").val();
    var cell = $("#e-celular").val();
    var nomenc = $("#e-encargada").val();

    if($.trim(nombresuc) == ""){
        MyAlert("El nombre del departamento no debe estar vacio !","alert");
    }else if($.trim(correo)==""){
        MyAlert("El correo no debe estar vacio !","alert");
    }else if($.trim(tel1) == ""){
        MyAlert("Ingrese el numero telefonico del departamento !","alert");
    }else if($.trim(direc) == ""){
        MyAlert("Ingrese la direccion del departamento !","alert");
    }else{
        $.ajax({
            url:'modules/01HelpDesk/src/tickets/fn_editar_sucursal.php',
            type:'POST',
            data:{tipo_cambio:1,nosucursal:nosuc,namesuc:nombresuc,direcion:direc,email:correo,telefono1:tel1,telefono2:tel2,cel:cell,nameenca:nomenc},
            success:function(data){
                $("#resultsave").html(data);
                //ticketinfouser(2);
                //shownameEncargada(nosuc,0);
            }
        });
    }
}

// Funcion Para cargar las Categorias segun el Area Seleccionada.
function fnsdloadCategorias(area,nodepto){
    $.ajax({
        url:"modules/01HelpDesk/src/tickets/fn_load_categorias.php",
        type:"POST",
        data:{NoArea:area,NoDepartamento:nodepto},
        success:function(data){
            $("#id_categorias").html(data);
        }
    });

    fnsdShowHistoryTicket_Sucursal($('#Departamento').val(),1,false);
}

// funcion para Cargar los empleados al registrar el ticket de acuerdo a su departamento
function fnsdCargarUsuarios(NoDepartamento){

    if(NoDepartamento != '-- --'){
        fnsdshownameEncargada(NoDepartamento);
    }else{
        MyAlert("Selecciona una Sucursal","alert");
    }
}

//Función para mostrar el nombre de la encargada de la sucursal.
function fnsdshownameEncargada(suc,dpto){
    $.ajax({
        url:"modules/01HelpDesk/src/tickets/fn_lista_empleados_departamento.php",
        type:"POST",
        data:{nosuc:suc,nodepto:dpto},
        success:function(data){
            $('#NombreSolicita').html(data);
        }
    });
}

//Funcion para mostrar la Informacion de la Sucursal.
function fnsdticketinfouser(param){
    var suc = $("#Departamento").val();

    if(suc == 0){
        MyAlert("No se encontró la sucursal, favor de seleccionar una","alert");
    }else{
        if(param == 1){
            $("#ticket_infouser").show();
            $("#cont2").hide();
            $("#HistoryReport").hide();
            $.ajax({
                url:"modules/01HelpDesk/src/tickets/fn_informacion_sucursal.php",
                type:"POST",
                data:{nosuc:suc},
                success:function(data){
                    $('#infouser2').html(data);
                }
            });
        }else{
            $("#ticket_infouser").hide();
            $("#cont2").show();
            $("#HistoryReport").show();

        }
    }
}

function fnsdShowHistoryTicket_Sucursal(suc,dpto,loadEmployed){

    fnsdticketinfouser(2);

    var idArea = $('#area').val();

    $.ajax({
        url:"modules/01HelpDesk/src/tickets/fn_historial_tickets.php",
        type:"POST",
        data:{nosuc:suc,nodepto:dpto,noarea:idArea},
        success:function(data){
            $('#HistoryReport').html(data);
            fnsdmostrar_diagrama(suc);
        }
    });

    if(loadEmployed){fnsdshownameEncargada(suc,dpto);}
    //ParpadearMyDivs(100);

}

function fnsdListarTickets(opc,dpto,nameDpto){
    $.ajax({
        url:"modules/01HelpDesk/src/tickets/fn_json_listar_tickets.php",
        type:"POST",
        data:{opt:opc,dpto:dpto,nameDpto:nameDpto},
        success:function(data){
            $("#myGrid").html(data);
        }
    });
}

//Funcion para cargar Graficas de la vista Dashboard
function fnsd_cargar_graficas(tipo,opc){

    if(tipo == 1) {
        //Grafica por dia
        $.ajax({
            url: "modules/01HelpDesk/src/indicadores/fn_dashboard_por_dia.php",
            type: "POST",
            data: {opc: 1},
            success: function (data) {
                $("#tabs1").html(data);
            }
        });

        //Grafica por Mes
        $.ajax({
            url: "modules/01HelpDesk/src/indicadores/fn_dashboard_por_mes.php",
            type: "POST",
            data: {opc: 1},
            success: function (data) {
                $("#tabs2").html(data);
            }
        });

        //Grafica por Año
        $.ajax({
            url: "modules/01HelpDesk/src/indicadores/fn_dashboard_por_anio.php",
            type: "POST",
            data: {opc: 1},
            success: function (data) {
                $("#tabs3").html(data);
            }
        });

        //Grafica por Usuarios
        $.ajax({
            url: "modules/01HelpDesk/src/indicadores/fn_dashboard_por_usuario.php",
            type: "POST",
            data: {opc: 1},
            success: function (data) {
                $("#tabs4").html(data);
            }
        });
    }else if(tipo == 2){

        switch (opc.toString()){
            case '1':
                //Grafica Principal # 1
                $("#select_area").hide();
                $("#select_categoria").hide();
                $("#select_mes").hide();

                $("#select_anio").show();

                var anio = $("#select_anio").val();
                var mes = $("#select_mes").val();

                $.ajax({
                    url:"modules/01HelpDesk/src/indicadores/fn_grafica_mensual.php",
                    type:"POST",
                    data:{v_anio:anio,v_mes:mes},
                    success:function(data){
                        $('.panel-body').html(data);

                    }
                });

                break;
            case '2':
                $("#select_area").hide();
                $("#select_categoria").hide();
                $("#select_mes").hide();
                $("#select_anio").show();

                var anio = $("#select_anio").val();
                var mes = $("#select_mes").val();

                $.ajax({
                    url:"modules/01HelpDesk/src/indicadores/fn_grafica_estatus.php",
                    type:"POST",
                    data:{v_anio:anio,v_mes:mes},
                    success:function(data){
                        $('.panel-body').html(data);

                    }
                });
                break;
            case '3':
                $("#select_area").hide();
                $("#select_categoria").hide();
                $("#select_mes").show();
                $("#select_anio").show();
                var anio = $("#select_anio").val();
                var mes = $("#select_mes").val();

                $.ajax({
                    url:"modules/01HelpDesk/src/indicadores/fn_grafica_usuarios.php",
                    type:"POST",
                    data:{v_anio:anio,v_mes:mes},
                    success:function(data){
                        $('.panel-body').html(data);

                    }
                });
                break;
            case '4':
                $("#select_area").hide();
                $("#select_categoria").hide();
                $("#select_anio").hide();
                $("#select_mes").hide();

                var anio = $("#select_anio").val();
                var mes = $("#select_mes").val();

                $.ajax({
                    url:"modules/01HelpDesk/src/indicadores/fn_grafica_anual.php",
                    type:"POST",
                    data:{v_anio:anio,v_mes:mes},
                    success:function(data){
                        $('.panel-body').html(data);

                    }
                });
                break;
            case '5':
                // GRAFICA POR AREAS

                $("#select_area").show();
                $("#select_categoria").hide();
                $("#select_anio").show();
                $("#select_mes").show();

                var anio = $("#select_anio").val();
                var mes = $("#select_mes").val();
                var area = $("#select_area").val();

                $.ajax({
                    url:"modules/01HelpDesk/src/indicadores/fn_grafica_areas.php",
                    type:"POST",
                    data:{v_anio:anio,v_mes:mes,noarea:area},
                    success:function(data){
                        $('.panel-body').html(data);

                    }
                });

                break;
            case '6':
                // GRAFICA POR PRIORIDAD
                $("#select_area").hide();
                $("#select_categoria").hide();
                $("#select_anio").show();
                $("#select_mes").hide();

                var anio = $("#select_anio").val();
                var mes = $("#select_mes").val();
                var area = $("#select_area").val();

                $.ajax({
                    url:"modules/01HelpDesk/src/indicadores/fn_grafica_prioridad.php",
                    type:"POST",
                    data:{v_anio:anio,v_mes:mes,noarea:area},
                    success:function(data){
                        $('.panel-body').html(data);

                    }
                });
                break;
            case '7':
                // GRAFICA POR SUCURSALES
                $("#select_area").hide();
                $("#select_categoria").hide();
                $("#select_anio").show();
                $("#select_mes").show();

                var anio = $("#select_anio").val();
                var mes = $("#select_mes").val();
                var area = $("#select_area").val();

                $.ajax({
                    url:"modules/01HelpDesk/src/indicadores/fn_grafica_sucursales.php",
                    type:"POST",
                    data:{v_anio:anio,v_mes:mes,noarea:area},
                    success:function(data){
                        $('.panel-body').html(data);

                    }
                });
                break;
            default:
                MyAlert("Error al cargar la vista: #"+opc+"","alert");

        }
    }

}

