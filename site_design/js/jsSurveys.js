/**
 * Created by alejandro.gomez on 28/10/2016.
 */

function NuevaEncuestaServicio(){



    MyAlert("prueba",'alert','idFoco');




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

//Guardar Encuesta de Servicio
function GuardarEncuesta(tpreguntas,anio,dpto,ticket,idEncuesta){

    var cont = 0;
    var contestadas = 0 ;
    while(cont < tpreguntas){
        cont++;
        var opciones = document.getElementsByName("resp"+cont);
        var seleccionado = false;
        for(var i=0; i<opciones.length; i++) {
            if(opciones[i].checked) {
                seleccionado = true;
                contestadas++;
            }
        }
        if(!seleccionado) {
            MyAlert("Una de las Preguntas no ha sido contestada, revise e intentelo nuevamente",'encuesta');
            return;
        }
    }
    if(contestadas == tpreguntas){

        var pr1 = $("input:radio[name='resp1']:checked").val(),
            pr2 = $("input:radio[name='resp2']:checked").val(),
            pr3 = $("input:radio[name='resp3']:checked").val(),
            pr4 = $("input:radio[name='resp4']:checked").val(),
            pr5 = $("input:radio[name='resp5']:checked").val(),
            cmt = $("#comentarios").val(),
            otra = $("#otras5").val();

        if(pr1 == 5){
            if($.trim(otra)== ""){
                MyAlert("Describe el medio de contacto ","encuesta");
                return;
            }
        }
        if($.trim(cmt) == ""){
            MyAlert("Ingrese los Comentarios","encuesta");
        }else{

            $.ajax({
                url:"../layout/encuestas/fnSaveSurveyServicioTecnico.php",
                type:"POST",
                data:{qest1:pr1,qest2:pr2,qest3:pr3,qest4:pr4,qest5:pr5,coment:cmt,otr:otra,ani:anio,dpt:dpto,tic:ticket,idEnc:idEncuesta},
                beforeSend:function(){
                    fnloadSpinner(1);
                },
                success:function(data){
                    fnloadSpinner(2);
                    console.log(data);
                    $("#result22").html(data);
                }
            });

        }
    }
}