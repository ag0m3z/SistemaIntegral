var ROOTAPP = '/SistemaIntegral/';

var bandera01 = false;

String.prototype.nl2br = function()
{
    return this.replace(/\n/g, "<br />");
}

function getLongPollingMensajes(NoUsuarioRecibe,KeyConversacion) {

    var timestamp = $("#fecha_ultimo_mensaje").text();
        var nameModal = $("#codigousuario").text();

    $.ajax({
        type: "get",
        url: "modules/applications/src/mensajes/getLongPollingMensajes.php",
        data: {NoUsuarioRecibe:NoUsuarioRecibe,Nombre:KeyConversacion,timestamp:timestamp,nameModal:nameModal},
        dataType:"json",
        async:true,
        cache:false,
        success: function(res)
        {
            console.log(res);

            if(res.result){
                $("#fecha_ultimo_mensaje").text(''+res.data.nuevo+'');


                if(res.data.mensaje.length > 0){

                    var lista = '<div class="direct-chat-msg right"><div class="direct-chat-info clearfix">\n' +
                        '             <span class="direct-chat-name pull-right">'+res.data.mensaje[0].UsuarioEnvia+'</span>' +
                        '             <span class="direct-chat-timestamp pull-left">'+res.data.mensaje[0].Fecha+' - '+res.data.mensaje[0].Hora+'</span></div>' +
                        '             <img class="direct-chat-img" src="site_design/img/'+res.data.mensaje[0].ImagenEnvia+'" alt="message user image">' +
                        '               <div class="direct-chat-text">'+res.data.mensaje[0].Mensaje.nl2br() +'</div>' +
                        '        </div><!-- /.box-comment -->';
                    $("#listaMensajes").html( $("#listaMensajes").html() + lista);
                    $("#listaMensajes").animate({ scrollTop: $('#listaMensajes').prop("scrollHeight")}, 1);
                }



                getLongPollingMensajes(NoUsuarioRecibe,KeyConversacion);

            }else{
            }

        }
    });

}

function fnBuscarContactoChat(opc){

    var contacto = $("#searchContact").val();

    $.ajax({
        url:"modules/applications/src/mensajes/fnBuscarContactoChat.php",
        type:"post",
        dataType:"json",
        data:{contacto:contacto,opc:opc},
        beforeSend:function(){
         fnloadSpinner(1);
        }
    }).done(function (response) {

        if(response.result){

            var lista = '',nomensajes = '';
            $("#lista_search").html(lista);

            for(i=0;i<response.data.length;i++){
                lista = lista +'<li><a href="javascript:void(0);" onclick="fngnMostrarBoxChat({id:'+response.data[i].NoUsuario+',MyImg:\''+response.MyImg+'\',name:\''+response.data[i].NombreDePila+'\',img:\''+response.data[i].Imagen+'\'})" class="text-black"> <img class="img-responsive img-circle img-sm" src="site_design/img/'+response.data[i].Imagen+'" alt="alt text"><div class="menu-info"><h4 class="control-sidebar-subheading">'+response.data[i].NombreDePila+'</h4><p>'+response.data[i].NombreDepartamento+'<span class="pull-right badge">'+nomensajes+'</span></p></div></a></li>';

            }

            switch (opc){
                case 1:
                    $("#titulo_lista").text("Se encontraron "+response.data.length +" registros");

                    break;
                case 2:
                    $("#titulo_lista").text(""+response.data.length +" Mensajes sin leer ");

                    break;
                case 3:
                    $("#titulo_lista").text("Contactos Recientes ");

                    break;
            }

            $("#lista_search").html(lista);
            fnloadSpinner(2);



        }else{
            fnloadSpinner(2);

            switch (opc){
                case 1:
                    MyAlert(response.message,"error");

                    break;
                case 2:
                    $("#titulo_lista").text(" No hay mensajes");
                    $("#lista_search").html('');

                    break;
                case 3:
                    $("#titulo_lista").text("No hay contactos ");
                    $("#lista_search").html('');

                    break;
            }

        }

    }).fail(function(jqhR,textStatus,errno){
        fnloadSpinner(2);
        if(console && console.log){

            if(textStatus == "timeout"){
                MyAlert("Tiempo de espera agotado","error");
            }else{
                MyAlert("No se encontro la vista","error");
            }

        }

    });
}

function fngnEnviarMensajeChat(){

    var NoUsuarioRecive = $("#idusuariorecive").val();
    var Mensaje = $("#txtMensajeChat").val();

    if(Mensaje == ""){
        $("#txtMensajeChat").val('').focus();
    }else{
        //Enviar Mensaje


        $("#txtMensajeChat").val('').focus();

        $.ajax({
            url:"modules/applications/src/mensajes/fnRegistrarMensajeChat.php",
            type:"post",
            dataType:"json",
            data:{NoUsuarioRecive:NoUsuarioRecive,Mensaje:Mensaje}
        }).done(function (response) {



            if(response.result){
                var lista='';
                lista = '<div class="direct-chat-msg left"><div class="direct-chat-info clearfix">\n' +
                    '             <span class="direct-chat-name pull-left">'+response.data.nombre+'</span>' +
                    '             <span class="direct-chat-timestamp pull-right">'+response.data.hora+'</span></div>' +
                    '             <img class="direct-chat-img" src="site_design/img/'+response.data.img+'" alt="message user image">' +
                    '               <div class="direct-chat-text">'+Mensaje.nl2br() +'</div>' +
                    '        </div><!-- /.box-comment -->';


                $("#listaMensajes").html($("#listaMensajes").html()+lista);
                $("#fecha_ultimo_mensaje").text(''+response.data.fechaultimomensaje+'');
                $("#listaMensajes").animate({ scrollTop: $('#listaMensajes').prop("scrollHeight")}, 1);

            }



        }).fail(function(jqhr,textEstatus,errno){
            if(console && console.log){

                if(textEstatus == "timeout"){
                    MyAlert("Tiempo de espera agotado","error");
                }else{
                    MyAlert("No se encontro la vista","error");
                }

            }
        });

    }
}

function fngnMostrarBoxChat(dataUsuario){

    $("#chatHome").html(" ");
    $("#chatHome").html('<div class="box box-success direct-chat direct-chat-success no-margin  no-border no-shadow" style="height: 52vh;" >\n' +
        '    <div class="box-header with-border" style="border-bottom: 1px solid rgba(154,170,183,0.91);">\n' +
        '        <div class="user-block">\n' +
        '            <img class="img-circle" id="img_request" src="" alt="user image">\n' +
        '            <span class="username"><a href="#" id="name_request"></a></span><i id="codigousuario" class="hidden"></i>\n' +
        '            <span class="description" id="fecha_ultima_conversacion"></span><span class="description hidden" id="fecha_ultimo_mensaje"></span>\n' +
        '            <input id="idusuariorecive" class="hidden" value="'+dataUsuario.id+'"/>' +
        '        </div><!-- /.user-block -->\n' +
        '        <div class="box-tools">\n' +
        '            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>\n' +
        '        </div><!-- /.box-tools -->\n' +
        '    </div><!-- /.box-header -->\n' +
        '\n' +
        '    <div class="box-body no-margin   ">\n' +
        '    <div  id="listaMensajes" class="direct-chat-messages scroll-auto"  style="height: 44vh">' +
        '    </div>' +
        '    </div>\n' +
        '</div><!-- /.box -->\n' +
        '<div class="box-footer no-margin " >\n' +
        '        <!-- .img-push is used to add margin to elements next to floating images -->\n' +
        '        <div class="img-push">\n' +
        '            <textarea id="txtMensajeChat" class="form-control input-sm margin-bottom" placeholder="Escriba un mensaje"></textarea><div class="pull-right mar"><button class="btn btn-xs btn-default" onclick="fngnEnviarMensajeChat()"><i class="fa fa-paper-plane"></i> Enviar</button>&nbsp;&nbsp;<input class="hidden" type="file" id="MyDataFile"><button class="btn btn-xs btn-default" onclick="$(\'#MyDataFile\').click()" title="Adjuntar"><i class="fa fa-paperclip"></i> Adjuntar</button></div>\n' +
        '        </div>\n' +
        '</div><!-- /.box-footer -->');

    $("#img_request").attr('src','/SistemaIntegral/site_design/img/'+dataUsuario.img+'');
    $("#name_request").text(dataUsuario.name);
    $("#img_sender").attr('src','/SistemaIntegral/site_design/img/'+dataUsuario.MyImg+'');
    $("#txtMensajeChat").focus();
    fngnMostrarMensajesChat(dataUsuario.id);
}

function fngnMostrarMensajesChat(NoUsuario,opc){


    $.ajax({
        url:"modules/applications/src/mensajes/fnCargarMensajes.php",
        type:"post",
        dataType:"json",
        data:{NoUsuario:NoUsuario,opc:opc},
    }).done(function (response) {

        console.log(response);

        if(response.result){

            $("#listaMensajes").html(" ");

            if(response.data.length > 0){

                $("#fecha_ultima_conversacion").html(response.data[0].NombreDepartamento + '<span class="pull-right">ultima conversacion: '+response.data[0].Fecha+'</span>');
                $("#codigousuario").text(response.keyconversacion);
                //$("#fecha_ultimo_mensaje").text(response.data[0].UltimoMensaje);
                var lista= '';
                var posicion ='';
                var back = '';
                for(i=0;i<response.data.length;i++){

                    if(response.NoUsuario == response.data[i].NoUsuarioEnvia){
                        posicion = 'left';
                        back= 'right';
                    }else{
                        posicion = 'right';
                        back= 'left';

                    }

                    lista = lista + '<div class="direct-chat-msg '+posicion+'"><div class="direct-chat-info clearfix">\n' +
                        '             <span class="direct-chat-name pull-'+posicion+'">'+response.data[i].UsuarioEnvia+'</span>' +
                        '             <span class="direct-chat-timestamp pull-'+back+'">'+response.data[i].Fecha+' - '+response.data[i].Hora+'</span></div>' +
                        '             <img class="direct-chat-img" src="site_design/img/'+response.data[i].ImagenEnvia+'" alt="message user image">' +
                        '               <div class="direct-chat-text">'+response.data[i].Mensaje.nl2br() +'</div>' +
                        '        </div><!-- /.box-comment -->';
                    posicion ='';
                    back ='';
                }
                $("#listaMensajes").html(lista);
                $("#listaMensajes").animate({ scrollTop: $('#listaMensajes').prop("scrollHeight")}, 1);
            }else{
                $("#fecha_ultima_conversacion").text('No existe ultima conversacion');
            }
            getLongPollingMensajes(NoUsuario,response.keyconversacion);


        }else{
            MyAlert(response.message,"error");
        }

    }).fail(function(jqhR,textStatus,errno){

        if(console && console.log){

            if(textStatus == "timeout"){
                MyAlert("Tiempo de espera agotado","error");
            }else{
                MyAlert("No se encontro la vista"+textStatus+errno+jqhR,"error");
            }

        }

    });


}