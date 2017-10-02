/**
 * Created by alejandro.gomez on 11/03/16.
 */

var loginIn = function() {

    var us = $("#luser").val(),
        pa = $("#lpass").val();

    if($.trim($("#luser").val()) == ""){
        MyAlert("Usuario incorrecto, int&eacute;ntelo nuevamente","alert");
        $("#luser").focus();
    }else if($.trim($("#lpass").val())== ""){
        MyAlert("La contrase\u00f1a es incorrecta, int&eacute;ntelo nuevamente","alert");
        $("#lpass").focus();
    }else{


        $.ajax({
            url: 'modules/applications/src/login/fn_login.php',
            type: 'post',
            data: { user: us, pass: pa },
            timeout:50000,
            beforeSend:function(){
                $('#btn_login i').removeClass('fa-sign-in');
                $('#btn_login i').addClass('fa-spinner fa-pulse');
                $('#btn_login').prop('disabled',true);
            }
        }).done(function(data){

            $("#divresult").html(data);
            $('#btn_login i').removeClass('fa-spinner fa-pulse');
            $('#btn_login i').addClass('fa-sign-in');
            $('#btn_login').prop('disabled',false);

        }).fail(function(jqXHR, textStatus){
            if ( console && console.log ) {
                MyAlert( "La solicitud a fallado, error:{ file not exist "+textStatus+"}","alert");
            }else if(textStatus == 'timeout')
            {
                MyAlert('Failed from timeout');
                //do something. Try again perhaps?
            }
        });

    }
};

function MyAlert(message,rta_img){
    if(rta_img == null){
        rta_img = "site_design/img/icons//alertaico";
    }else{
        rta_img = "site_design/img/icons/alerta.ico";
    }

    var tabla = "<table><tr><td rowspan='2' valign='middle'><img src='"+ rta_img +"' width='32'></td></tr><tr><td style='text-align: center'>&nbsp;&nbsp;&nbsp;&nbsp;" + message;

    bootbox.alert({
        title:"Alerta",
        message:tabla,
        size:"small",
        buttons:{
            ok:{
                label:"Aceptar",
                className:"btn-primary btn-sm"
            }
        },
        className: ' animated fadeIn',
        callback: function () {

            $("#lpass").focus();
        }
    });
}

