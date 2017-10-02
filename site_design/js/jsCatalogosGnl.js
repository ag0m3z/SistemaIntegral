/**
 * Created by alejandro.gomez on 04/10/2016.
 */

$.fn.Init(function(){$(this).addClass('waves-effect');});


/**
 * Catalogo de Puestos
 */

function fnCatAltaPuesto(opc) {
    //opcion 1 Mostrar modal
    //Opcion 2 Registrar Puesto
    var nameView,divResult,strData,sendAjax = false,urlPhp;

    switch (opc){
        case 1:
            urlPhp = "modules/applications/views/puestos_empleados/";
             nameView = "FrmNuevoPuesto.php";
             divResult = "#ShowModal";
             strData = {opcion:opc};
             sendAjax = true;
            break;
        case 2:

            var nomnbre_puesto = $("#nombre_puesto").val(),
                descripcion_puesto = $("#descripcion_puesto").val();

            if($.trim(nomnbre_puesto) == ""){
                MyAlert("Ingrese el nombre del puesto","alert");
            }else{
                sendAjax = true;
            }

            urlPhp = "modules/applications/src/puestos_empleados/";
            nameView = "fn_registra_puesto.php";
            divResult = "#imgLoad";
            strData = {opcion:opc,nomnbre_puesto:nomnbre_puesto,descripcion_puesto:descripcion_puesto};

            break;
        default:
            MyAlert("No se encontro la opción solicitada","error");
            break;
    }

    if(sendAjax){
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

function fnCatEditarPuesto(opc,idPuesto){
    //opcion 1 Mostrar modal
    //Opcion 2 Editar Puesto
    var nameView,divResult,strData,sendAjax = false,urlPhp;

    switch (opc){
        case 1:
            urlPhp = "modules/applications/views/puestos_empleados/";
            nameView = "FrmEditarPuesto.php";
            divResult = "#ShowModal";
            strData = {opcion:opc,idpuesto:idPuesto};
            sendAjax = true;
            break;
        case 2:

            var nomnbre_puesto = $("#nombre_puesto").val(),
                descripcion_puesto = $("#descripcion_puesto").val();

            if($.trim(nomnbre_puesto) == ""){
                MyAlert("Ingrese el nombre del puesto","alert");
            }else{
                sendAjax = true;
            }

            urlPhp = "modules/applications/src/puestos_empleados/";
            nameView = "fn_editar_puesto.php";
            divResult = "#imgLoad";
            strData = {opcion:opc,idPuesto:idPuesto,nomnbre_puesto:nomnbre_puesto,descripcion_puesto:descripcion_puesto};


            break;
        default:
            MyAlert("No se encontro la opción solicitada","error");
    }

    if(sendAjax){
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

function fnCatListarPuestos(opc){

    var nameView,divResult,strData,sendAjax = false,urlPhp;

    // listar todos los puestos
    urlPhp = "modules/applications/src/puestos_empleados/";
    nameView = "fn_listar_puestos.php";
    strData = {opcion:opc};
    divResult = "#lListTable";
    sendAjax = true;

    if(sendAjax){
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
 * CAtalogo de modulos y aplicaciones
 *
 */

function fnCatListarModulos(opc){

    var nameView = "jsonListaModulos.php";
    $.ajax({
        url:"modules/00consola/layout/modulos/"+nameView+"",
        type:"POST",
        data:{opcion:opc}
    }).done(function (data){

        $("#lListTable").html(data);

    }).fail(function(jqHR,textEstatus,error){
        if(console && console.log){
            MyAlert("Error al realizar la carga de la vista:{ views: "+textEstatus +" - "+error+ " - "+nameView+" }","error");
        }
    });


}


/**
 * Catalogo departamentos
 */

// Funcion para Buscar el Departamento

function fnCatBuscarDepartamento(opc){

    var nameView = "fnListDepartamentos.php",strData;



    strData = {
        opcion:8,
        "nombre":$("#txtNombre").val(),
        "estado":$("#txtNoEstado").val(),
        "nodepto":$("#txtNoDepartamento").val(),
        "useralta":$("#txtNoUsuarioA").val(),
        "userum":$("#txtNoUsuarioU").val()
    };

    $.ajax({
        url:"modules/applications/src/departamentos/"+nameView+"",
        type:"POST",
        data:strData
    }).done(function (data){

        $("#lListTable").html(data);

    }).fail(function(jqHR,textEstatus,error){
        if(console && console.log){
            MyAlert("Error al realizar la carga de la vista:{ views: "+textEstatus +" - "+error+ " - "+nameView+" }","error");
        }
    });


}

//listar Departamentos
function fnCatListarDepartamentos(opc){
    var nameView = "fnListDepartamentos.php";
    $.ajax({
        url:"modules/applications/src/departamentos/"+nameView+"",
        type:"POST",
        data:{opcion:opc}
    }).done(function (data){

        $("#lListTable").html(data);

    }).fail(function(jqHR,textEstatus,error){
        if(console && console.log){
            MyAlert("Error al realizar la carga de la vista:{ views: "+textEstatus +" - "+error+ " - "+nameView+" }","error");
        }
    });
}

//funcion para cargar los municipios
function ShowHidenGroups(opc,idGroup){

    if (opc ==  1){
        //mostrar
        $('.opc02').hide();
        $('.opc03').hide();
        $('#'+idGroup).show();

    }else if(opc ==2 ){
        // ocultar
        $('.opc02').hide();
        $('.opc03').hide();
    }else if(opc == 3){
        $('.opc03').hide();
        $('#'+idGroup).show();
    }
}


function fnGenListaEmpleados(opc,dpto,param){

    var urlPhp,nameView,idDiv,ajaxSend;

    switch (opc){
        case 1:
            // cargar lista de usuarios
            urlPhp = "modules/applications/src/empleados/";
            nameView = "fn_select_empleados.php";
            idDiv = "#idEmpleado";
            ajaxSend = true;
            param =  "?ok";
            break;
    }

    if(ajaxSend){
        $.ajax({
            url:urlPhp + nameView + param,
            type:"POST",
            data:{opcion:opc,nodpto:dpto,parametro:param},
            success:function(data){
                $(idDiv).html(data);
            }
        });
    }
}


/**
 * Catalogo de Usuarios
 */

function fnCatBuscarUsuario(opcion){

    //opcion (1) = Buscar Usuario
    //opcion (2) = Buscar Empleado

    var nombre = $('#txtNombre'),
        nodpto = $('#txtNoDepartamento'),
        noestado = $('#txtNoEstado'),
        useralta = $('#txtNoUsuarioA'),
        userum = $('#txtNoUsuarioU'),
        fechaalta = $('#txtFechaA'),
        fechaum = $('#txtFechaU');

    var urlPhp = "modules/applications/src/usuarios/";
    var nameView = "fnBuscarUsuario.php";
    var idDiv = "#lListTable";

    fnloadSpinner(1,'fa-search','btnSearch');

    $.ajax({
        url:urlPhp + nameView,
        type:"post",
        data:{enombre:nombre.val(),enodpto:nodpto.val(),enoestado:noestado.val(),euseralta:useralta.val(),euserum:userum.val(),efalta:fechaalta.val(),efum:fechaum.val()},
        timeout:60000
    }).done(function(data){
        fnloadSpinner(2,'fa-search','btnSearch');

        $(idDiv).html(data);

    }).fail(function(xqHR,textEstatus,errno){

    });
}

function fnCatEditarUsuario(opc,nousuario){

    var ajaSend = false,urlPhp,idDiv,nameView;

    if(opc ==  1){
        // mostrar el Formulario para realizar el registro de nuevo usuario
        ajaSend = true;
        urlPhp = "modules/applications/views/usuarios/";
        idDiv = "#lListTable";
        nameView = "FrmEditarUsuario.php";
        $('.btn-success').show();

    }else if(opc ==  2){

        //opcion deshabilitada

        ajaSend = false;
    }

    if(ajaSend){
        $.ajax({
            url:urlPhp + nameView,
            type:"POST",
            data:{opcion:opc,usuario:nousuario}
        }).done(function(data){

            $(idDiv).html(data);

        }).fail(function(jqHR,txtEstatus,errno){
            if(console && console.log){
                MyAlert("Error al realizar la carga de la vista: <br>{<br> view => "+nameView +", <br>"+txtEstatus+ " =>  "+errno +"<br> "+jqHR+ "<br>}","error"
                );
            }
        });
    }

}

function fnCatSaveNuevoUsuario(opcion){

    var rnodpto = $("#dpto"),
        ridEmpleado = $("#idEmpleado"),
        rNombreDePila = $("#NombreDePila"),
        rUserlogin = $("#UsuarioLogin"),
        rClaveLogin = $("#claveLogin"),
        rBDDatos = $("#idBDatos"),
        rPerfil  = $('#idPerfil').val(),
        rNoEstado = $("#noEstado"),
        SendAjax = false;


    var urlPhp,idDiv,nameView,nousuario,passOld ;



        switch (opcion){
            case 1:
                //opcion para guardar nuevo usuario

                if(rnodpto.val() == 0){
                    MyAlert("Seleccione un Departamento","alert");
                }else if(ridEmpleado.val() == 0){
                    MyAlert("Seleccione un Empleado","alert");
                }else if($.trim(rNombreDePila.val()) == ""){
                    MyAlert("Ingrese el Nombre para mostrar","alert");
                }else if($.trim(rUserlogin.val()) == ""){
                    MyAlert("Ingrese el Nombre de usuario","alert");

                }else if($.trim( rClaveLogin.val()) == ""){
                    MyAlert("La contraseña no debe estar vacia","alert");
                }else if(rClaveLogin.val().length < 5){
                    MyAlert("La contraseña es demasiada corta, intentelo nuevamente","alert");

                }else if(rBDDatos.val() == 0){
                    MyAlert("Seleccione la Base de Datos de Acceso","Error");
                }else{
                    SendAjax = true;
                }
                    urlPhp = "modules/applications/src/usuarios/";
                    idDiv = "#lListTable";
                    nameView = "fnRegistraUsuario.php";
                    nousuario = "";
                    passOld = "";
                break;
            case 2:
                //opcion para guardar cambios en el usuario

                if(rnodpto.val() == 0){
                    MyAlert("Seleccione un Departamento","alert");
                }else if($.trim(rUserlogin.val()) == ""){
                    MyAlert("Ingrese el Nombre de usuario","alert");

                }else if($.trim( rClaveLogin.val()) == ""){
                    MyAlert("La contraseña no debe estar vacia","alert");
                }else if(rClaveLogin.val().length < 5){
                    MyAlert("La contraseña es demasiada corta, intentelo nuevamente","alert");

                }else if(rBDDatos.val() == 0){
                    MyAlert("Seleccione la Base de Datos de Acceso","Error");
                }else{
                    SendAjax = true;
                }
                    urlPhp = "modules/applications/src/usuarios/";
                    idDiv = "#lListTable";
                    nameView = "fnEditarUsuario.php";
                    nousuario = $('#idUser').val();
                    passOld = $("#claveLoginOLD").val();
                    SendAjax = true;

                break;
            default :
                break;
        }

    if(SendAjax){
        $.ajax({
            type: "POST",
            data: {
                'idPerfil':rPerfil,
                'usuario':nousuario,
                'uclaveOld':passOld,
                'udpto': rnodpto.val(),
                'uempleado': ridEmpleado.val(),
                'unombre': rNombreDePila.val(),
                'ulogin':rUserlogin.val(),
                'uclave':rClaveLogin.val(),
                'uestado':rNoEstado.val(),
                'BDDatos':rBDDatos.val(),
                'ids': JSON.stringify($('[name="app[]"]').serializeArray())
            },
            url: urlPhp + nameView,
            success : function(data) {
                $("#resultTemp").html(data);

            }
        });
    }


}


function fnCatNuevoUsuario(opc){

    if(opc ==  1){
        // mostrar el Formulario para realizar el registro de nuevo usuario
        var ajaSend = true;
        var urlPhp = "modules/applications/views/usuarios/";
        var idDiv = "#lListTable";
        var nameView = "frm_nuevo_usuario.php";
        $('.btn-success').show();

    }else if(opc ==  2){
        // ejecutar funcion para guardar el nuevo usuario.

        var  ajaSend = false;
    }

    if(ajaSend){
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



}

// listar los usuarios
function fnCatListarUsuarios(opc){

    $('.btn-success').hide();

    var nameView = "fnListUsers.php";
    $.ajax({
        url:"modules/applications/src/usuarios/"+nameView+"",
        type:"POST",
        data:{opcion:opc}
    }).done(function (data){

        $("#lListTable").html(data);

    }).fail(function(jqHR,textEstatus,error){
        if(console && console.log){
            $('button').prop('disabled', true);
            MyAlert("Error al realizar la carga de la vista:{ views: "+textEstatus +" - "+error+ " - "+nameView+" }","error");

        }
    });


}



// funcion para registrar los nuevos empleados
function fnCatAltaEmpleado(opc){

    if(opc == 2){

        $("#myModal").modal('toggle');
        setTimeout(function() {

            fnCatListarEmpleados(7);


        }, 500);

    }else{
        var nEmpresa = $("#idempresa").val(),
            tpoEmpleado = $("#idtpoempleado").val(),
            noEmpleado = $('#noempleado').val(),
            nodpto = $("#nodpto").val(),
            idpuesto = $("#idpuesto").val(),
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
            $.ajax({
                url:"modules/00consola/layout/empleados/fnRegistraEmpleado.php",
                type:"POST",
                data:{
                    noempleado:noEmpleado,
                    nodpto:nodpto,
                    nombre:nombre,
                    appaterno:appaterno,
                    apmaterno:apmaterno,
                    correo:correo,
                    tel01:tel01,
                    tel02:tel02,
                    tel03:tel03,
                    idpuesto:idpuesto,
                    direccion:direccion,
                    tel04:tel04,
                    tel05:tel05,
                    estatus:estatus,
                    idEmpresa:nEmpresa,
                    tpoEmpleado: tpoEmpleado
                },
                beforeSend:function(){
                    $("#spinner").removeClass();
                    $("#spinner").addClass('fa fa-spinner fa-pulse');
                },
                success:function(data){
                    $("#resultrge").html(data);
                }
            });
        }
    }


}

function fnCatFrmNuevoEmpleado(){
    var nameView = "frm_modal_nuevo_empleado.php";

    $.ajax({
        url:"modules/applications/views/empleados/"+nameView,
        type:"POST",
        data:{opc:2}
    }).done(function(data){
        $("#diag-suc").html(data);
    }).fail(function(jqHR,textEstatus,errno){
        if(console && console.log){
            MyAlert(
                "Error al realizar la carga de la vista: <br>{<br> view => "+nameView +", <br>"+textEstatus+ " =>  "+errno +"<br> "+jqHR+ "<br>}","error"
            );
        }
    })

}
//listar los empleados
function fnCatListarEmpleados(opc){
    var nameView = "fnListEmployed.php";
    $.ajax({
        url:"modules/applications/src/empleados/"+nameView+"",
        type:"POST",
        data:{opcion:opc}
    }).done(function (data){

        $("#lListTable").html(data);

    }).fail(function(jqHR,textEstatus,error){
        if(console && console.log){
            MyAlert("Error al realizar la carga de la vista:{ views: "+textEstatus +" - "+error+ " - "+nameView+" }","error");
        }
    });
}
/**
 *
 * Funcion para cargar los catalogos
 */

//funciones para los ir a los catalogos
function fnCatSeleccionarCatalogo(opc,parametro){

    var urlCat,send = false,mydiv,nameView,param;

    switch (opc){
        case 1:
            //Mostrar Lista catalogo de empleados
            nameView = "frm_lista_empleados.php";
            param = parametro;
            mydiv = "#HomeContent";
            urlCat = "modules/applications/views/empleados/" + nameView + param;
            send =true;
            break;
        case 2:
            //Mostrar Lista catalogo de usuarios
            nameView = "frm_lista_usuarios.php";
            mydiv = "#HomeContent";
            urlCat = "modules/applications/views/usuarios/" + nameView + parametro;
            send = true;

            break;
        case 3:
            //Mostrar Lista catalogo de departamentos y sucursales
            nameView = "FrmDepartamentos.php";
            mydiv = "#HomeContent";
            urlCat = "modules/applications/views/departamentos/" + nameView ;
            send = true;

            break;
        case 4:
            //Mostrar Lista catalogo de departamentos y sucursales
            nameView = "FrmModulos.php";
            mydiv = "#HomeContent";
            urlCat = "modules/00consola/views/modulos/" + nameView ;
            send = true;

            break;
        case 5:
            //Mostrar Lista catalogo de Puestos
            nameView = "FrmListarPuestos.php";
            mydiv = "#HomeContent";
            urlCat = "modules/applications/views/puestos_empleados/" + nameView ;
            send = true;

            break;
    }

    if(send){
        $.ajax({
            url:urlCat,
            type:"POST",
            data:{opcion:opc}
        }).done(function (data){

            $(""+mydiv+"").html(data);

        }).fail(function(jqHR,textEstatus,error){
            if(console && console.log){
                MyAlert("Error al cargar la vista:{views:"+opc+"-"+nameView+"","error");
            }
        });
    }
}