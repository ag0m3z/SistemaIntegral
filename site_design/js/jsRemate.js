/**
 * Created by alejandro.gomez on 8/07/16.
 */


function fnmt_ImportarExcel(){
    var archivo = document.getElementById('btn_file');

    var file = archivo.files[0];
    var data = new FormData();
    if (archivo.files && archivo.files[0]) {

        data.append('archivo',file);

        $.ajax({
            url:"modules/04PVenta/src/metales/fn_ImportarExcel.php",
            type:"post",
            contentType:false,
            data:data,
            processData:false,
            dataType:"json",
            cache:false,
            beforeSend:function(){
                fnloadSpinner(1);
            },
            success:function(response){
                fnloadSpinner(2);

                if(response.result){


                    $("#modalbtnclose").click();
                    MyAlert(response.message,"ok");
                    fnmt_ListarCotizaciones(99);

                }else{
                    MyAlert(response.message,"error");
                }
                //console.log(response);
            }
        });

    }else{

        MyAlert("No se encontro una imagen valida, seleccione una","error");

    }
}

function fnmt_ListarCotizaciones(opc){
    $.ajax({
        url:"modules/04PVenta/src/metales/jsonListarCotizaciones.php",
        type:"POST",
        data:{opt:opc},
        success:function(data){
            $("#lListarTabla").html(data);
        }
    });
}

function fnmt_VerMetal(opc,Kilataje){
    $.ajax({
        url:"modules/04PVenta/views/metales/frm_detalle_metal.php",
        type:"POST",
        data:{opt:opc,kil:Kilataje},
        success:function(data){
            $("#showModal").html(data);
        }
    });

}

function fnmt_ImportarArchivo(tpoFile){
    $.ajax({
        url:"modules/04PVenta/views/metales/frm_importar_metal.php",
        type:"POST",
        data:{opt:tpoFile},
        success:function(data){
            $("#showModal").html(data);
        }
    });
}

function fnmt_ExportarResultado(TipoDocumento,md5){
    var condicion = $("#whereConsulta").text(),
        md5 = "v34s12w23";
    switch (TipoDocumento){
        case 1:
            MyAlert("Opcion Invalida, Intentelo Nuevamente","alert");
            break;
        case 2:
            window.open("modules/04PVenta/reportes/metales/rpt.cotizacion_metales.php");
            break;
        case 3:
            MyAlert("Opcion Invalida, Intentelo Nuevamente","alert");
            break;
        default :
            MyAlert("Opcion Invalida, Intentelo Nuevamente","alert");
            break;
    }
}

function MenuRM(opcion){

    switch (opcion){
        case 1:
            fnmt_ListarCotizaciones(99);
            break;
        case 2:
            MyAlert("La opcion solicitada no existe","error");
            break;
        default :
            MyAlert("La opcion solicitada no existe","error");
            break;
    }
}