var ROOTAPP = '/SistemaIntegral/';

var bandera01 = false;

var extenciones = '.jpg, .jpeg, .png, .xls, .xlsx, .doc, .docx, .zip, .rar, .pps, .ppsx';

String.prototype.nl2br = function()
{
    return this.replace(/\n/g, "<br />");
}


function getUploadChatFile(UsuarioRecibe,input) {


    if (input.files && input.files[0]) {
        var reader = new FileReader();


        reader.readAsDataURL(input.files[0]);
    }

    var filename = document.getElementById('MyDataFile');
    var file = filename.files[0];
    var data = new FormData();

    data.append('archivo',file);
    data.append('NoUsuarioRecibe',UsuarioRecibe);

    $.ajax({
        url:"modules/applications/src/mensajes/fnUploadChatFile.php",
        type:"post",
        contentType:false,
        dataType:"json",
        data:data,
        processData:false,
        cache:false,
        success:function(response){

            if(response.result){

                var objects = {
                    TipoMensaje:response.data.TipoMensaje,
                    NombreArchivo:response.data.NombreArchivo,
                    NombreUnico:response.data.NombreUnico,
                    NoUsuarioRecibe:response.data.NoUsuarioRecibe,
                    RutaImagen:response.data.RutaImagen
                };

                fngnEnviarMensajeChat(2,objects);

            }else{
                MyAlert(response.message,"error");
            }
        }
    });
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

            if(res.result){
                $("#fecha_ultimo_mensaje").text(''+res.data.nuevo+'');



                if(res.data.mensaje.length > 0){

                    console.log(res.data);
                    var newMnesaje = '';

                    newMnesaje = res.data.mensaje[0].Mensaje.nl2br();


                    if(res.data.mensaje[0].TipoMensaje == 2){
                        newMnesaje = getFormatoMensajeConImagen(
                            res.data.mensaje[0].NombreImagen,
                            res.data.mensaje[0].RutaImagen,
                            res.data.mensaje[0].NombreUnico
                        );
                    }

                    var lista = '<div class="direct-chat-msg right"><div class="direct-chat-info clearfix">\n' +
                        '             <span class="direct-chat-name pull-right">'+res.data.mensaje[0].UsuarioEnvia+'</span>' +
                        '             <span class="direct-chat-timestamp pull-left">'+res.data.mensaje[0].Fecha+' - '+res.data.mensaje[0].Hora+'</span></div>' +
                        '             <img class="direct-chat-img" src="site_design/img/'+res.data.mensaje[0].ImagenEnvia+'" alt="message user image">' +
                        '               <div class="direct-chat-text">'+ newMnesaje +'</div>' +
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

function fngnEnviarMensajeChat(TipoMensaje,dataFile){

    var NoUsuarioRecive = $("#idusuariorecive").val();
    var Mensaje = $("#txtMensajeChat").val();
    var RutaImagen = '';
    var NombreImagen = '';
    var NombreUnico = '';

    Mensaje.nl2br();

    if(TipoMensaje == 2){

        NoUsuarioRecive = dataFile.NoUsuarioRecibe;

        RutaImagen = dataFile.RutaImagen;
        NombreImagen = dataFile.NombreArchivo;
        NombreUnico = dataFile.NombreUnico

        Mensaje = getFormatoMensajeConImagen(dataFile.NombreArchivo,RutaImagen,NombreUnico) ;

    }

    if(Mensaje == ""){
        $("#txtMensajeChat").val('').focus();
    }else{
        //Enviar Mensaje

        $("#txtMensajeChat").val('').focus();

        $.ajax({
            url:"modules/applications/src/mensajes/fnRegistrarMensajeChat.php",
            type:"post",
            dataType:"json",
            data:{NoUsuarioRecive:NoUsuarioRecive,Mensaje:Mensaje,TipoMensaje:TipoMensaje,RutaImagen:RutaImagen,NombreImagen:NombreImagen,NombreUnico:NombreUnico}
        }).done(function (response) {

            if(response.result){
                var lista='';


                lista = '<div class="direct-chat-msg left"><div class="direct-chat-info clearfix">\n' +
                    '             <span class="direct-chat-name pull-left">'+response.data.nombre+'</span>' +
                    '             <span class="direct-chat-timestamp pull-right">'+response.data.hora+'</span></div>' +
                    '             <img class="direct-chat-img" src="site_design/img/'+response.data.img+'" alt="message user image">' +
                    '               <div class="direct-chat-text">'+Mensaje +'</div>' +
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

function getVerImagenAdjunto(RutaArchivo,NombreUnico) {

    var ext = NombreUnico.substr(-3);

    ext = ext.toLowerCase();

    var array = ['png','jpg','gif','peg'];

    if(array.includes(ext)){

        //<div class="modal-header"><h4 class="modal-title">Archivo Adjunto </h4> </div>
        var html = '<div class="modal fade" tabindex="-1" id="mdlImagenChat" >\n' +
            '  <div class="modal-dialog modal-lg" role="document">\n' +
            '    <div class="modal-content">\n' +
            '      <div class="modal-body no-padding bg-black "><img class="img-responsive center-block" src="modules/applications/'+RutaArchivo+NombreUnico+'"></div>' +
            '    </div>\n' +
            '  </div>\n' +
            '</div>'
        $("#imgchat").html(html);

        setOpenModal("mdlImagenChat");

    }else{
        window.open("modules/applications/"+RutaArchivo+NombreUnico);
    }




}
function getFormatoMensajeConImagen(NombreArchivo,RutaArchivo,NombreUnico) {

    var MensajeImagen = '';
    var ext='',iconfile='';

    ext = NombreArchivo.substr(-3);

    switch (ext.toLowerCase()){
        case 'png':
            iconfile = 'fa-image';
            break;
        case 'jpg':
            iconfile = 'fa-image';
            break;
        case 'gif':
            iconfile = 'fa-image';
            break;
        case 'peg':
            iconfile = 'fa-image';
            break;
        case 'xls':
            iconfile = 'fa-file-excel-o';
            break;
        case 'lsx':
            iconfile = 'fa-file-excel-o';
            break;
        case 'doc':
            iconfile = 'fa-file-word-o';
            break;
        case 'ocx':
            iconfile = 'fa-file-word-o';
            break;
        case 'pps':
            iconfile = 'fa-file-powerpoint-o';
            break;
        case 'psx':
            iconfile = 'fa-file-powerpoint-o';
            break;
        case 'zip':
            iconfile = 'fa-file-zip-o';
            break;
        case 'rar':
            iconfile = 'fa-file-zip-o';
            break;
        case 'pdf':
            iconfile = 'fa-pdf-o';
            break;

        default:
            iconfile = 'fa-file-o';
            break;
    }

    MensajeImagen = "<i class='fa "+iconfile+" fa-2x'></i><br>"+NombreArchivo+"<a href='#' onclick='getVerImagenAdjunto(\""+RutaArchivo+"\",\""+NombreUnico+"\")' class='pull-right text-black' download>&nbsp;<i class='fa fa-eye'></i></a>&nbsp;<a href='modules/applications/"+RutaArchivo+"/"+NombreUnico+"' class='pull-right text-black' download>&nbsp;<i class='fa fa-download'></i></a>";

    return MensajeImagen ;

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
        '            <textarea id="txtMensajeChat" class="form-control input-sm margin-bottom" placeholder="Escriba un mensaje"></textarea><div class="pull-right mar"><button class="btn btn-xs btn-default" onclick="fngnEnviarMensajeChat(1)"><i class="fa fa-paper-plane"></i> Enviar</button>&nbsp;&nbsp;<input  class="hidden" type="file" accept=".jpg, .jpeg, .png, .xls, .xlsx, .doc, .docx,.pdf, .zip, .rar, .pps, .ppsx," id="MyDataFile" onchange="getUploadChatFile('+dataUsuario.id+',this)" /><button class="btn btn-xs btn-default" onclick="$(\'#MyDataFile\').click()" title="Adjuntar"><i class="fa fa-paperclip"></i> Adjuntar</button></div>\n' +
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


        if(response.result){

            $("#listaMensajes").html(" ");

            if(response.data.length > 0){

                $("#fecha_ultima_conversacion").html(response.data[0].NombreDepartamento + '<span class="pull-right">ultima conversacion: '+response.data[0].Fecha+'</span>');
                $("#codigousuario").text(response.keyconversacion);
                //$("#fecha_ultimo_mensaje").text(response.data[0].UltimoMensaje);
                var lista= '';
                var posicion ='';
                var back = '';
                var Mensaje ='';
                for(i=0;i<response.data.length;i++){

                    Mensaje = response.data[i].Mensaje.nl2br();

                    if(response.NoUsuario == response.data[i].NoUsuarioEnvia){
                        posicion = 'left';
                        back= 'right';
                    }else{
                        posicion = 'right';
                        back= 'left';

                    }

                    if(response.data[i].TipoMensaje == 2){

                        Mensaje = getFormatoMensajeConImagen(response.data[i].NombreImagen,response.data[i].RutaImagen,response.data[i].NombreUnico);

                    }

                    lista = lista + '<div class="direct-chat-msg '+posicion+'"><div class="direct-chat-info clearfix">\n' +
                        '             <span class="direct-chat-name pull-'+posicion+'">'+response.data[i].UsuarioEnvia+'</span>' +
                        '             <span class="direct-chat-timestamp pull-'+back+'">'+response.data[i].Fecha+' - '+response.data[i].Hora+'</span></div>' +
                        '             <img class="direct-chat-img" src="site_design/img/'+response.data[i].ImagenEnvia+'" alt="message user image">' +
                        '               <div class="direct-chat-text">'+ Mensaje +'</div>' +
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