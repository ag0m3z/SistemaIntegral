/**
 * Created by alejandro.gomez on 01/11/2016.
 */


/**
 *  Funciones para las TAREAS
 */

function editar_detalle_tarea(opc,id_proyecto,id_actividad,id_tarea,idAvance){
    if(opc == 1){
        // mostrar modal
        var strData = {
            id_proyecto:id_proyecto,
            id_actividad:id_actividad,
            id_tarea:id_tarea,
            idAvance:idAvance
        }

        gnSenderAjax(
            "modules/02Proyectos/views/tareas/",
            "frm_editar_avance_tarea.php",
            null,
            "modal_alta_detalle_tarea",
            "fa-save",
            "mdl_btn_save",
            50000,
            strData
        );
    }else if(opc == 2){

        var avanceHrs = $("#avance_horas").val(),
            avancePor = $("#avance_porcentaje").val(),
            descripcion = $("#avance_descripcion").val(),
            id_avance_tarea = $("#avance_idavance_tarea").val(),
            strData,total_avance;

        total_avance = (parseInt(avancePor) + parseInt(avance_porcentaje));

        if($.trim(avanceHrs) == ""){
            MyAlert("EL avance en horas no es correcto","error");
        }else if($.trim(avancePor) == ""){
            MyAlert("El avance del % porcentaje no es correcto","error");
        }else if(avancePor == 0){
            MyAlert("El avance del % porcentaje no es correcto","error");
        }else if($.trim(descripcion) == ""){
            MyAlert("Ingrese el seguimiento del avance", "alert");
        }else{

            strData = {
                avance:avanceHrs,
                porcentaje:avancePor,
                Comentario:descripcion,
                id_proyecto:id_proyecto,
                id_actividad:id_actividad,
                id_tarea:id_tarea,
                idAvance:idAvance
            }

            gnSenderAjax(
                "modules/02Proyectos/layout/tareas/",
                "fn_registra_detalle_tarea.php",
                null,
                "seguimiento_tarea",
                "fa-save",
                "mdl_btn_save",
                50000,
                strData
            );


        }



    }else if(opc == 3){

        $("#modal_seguimiento_tarea").modal('toggle');

        setTimeout(function(){
            $("#btn_tarea_refresh").click();
            setTimeout(function () {
                $("#btn_tarea_coments").click();
            },500)
        },500);

    }


}

function eliminar_detalle_tarea(id_proyecto,id_actividad,id_tarea,id_detalle_tarea){

    var strData = {
        idProyecto:id_proyecto,
        idActividad:id_actividad,
        idTarea:id_tarea,
        idAvance:id_detalle_tarea
    }

    bootbox.confirm({
        title:"Eliminar Seguimiento",
        message: ptabla +"Esta seguro de eliminar el seguimiento de la tarea",
        size:"small",
        callback:function(result){
            if(result){

                gnSenderAjax(
                    "modules/02Proyectos/layout/tareas/",
                    "fn_elimina_detalle_tarea.php",
                    null,
                    "seguimiento_tarea",
                    "fa-save",
                    "mdl_btn_save",
                    50000,
                    strData
                );

            }
        }
    });
}
function guardar_detalle_tarea(id_proyecto,id_actividad,id_tarea,avance_porcentaje){
    var avanceHrs = $("#avance_horas").val(),
        avancePor = $("#avance_porcentaje").val(),
        descripcion = $("#avance_descripcion").val(),
        id_avance_tarea = $("#avance_idavance_tarea").val(),
        strData,total_avance;

    total_avance = (parseInt(avancePor) + parseInt(avance_porcentaje));

    if($.trim(avanceHrs) == ""){
        MyAlert("EL avance en horas no es correcto","error");
    }else if($.trim(avancePor) == ""){
        MyAlert("El avance del % porcentaje no es correcto","error");
    }else if(avancePor == 0){
        MyAlert("El avance del % porcentaje no es correcto","error");
    }else if(total_avance <= avance_porcentaje){
        MyAlert("El avance del % porcentaje no es correcto","error");
    }else if(total_avance > 100){
        MyAlert("El avance del % porcentaje no es correcto" + total_avance,"error");
    }else if($.trim(descripcion) == ""){
        MyAlert("Ingrese el seguimiento del avance", "alert");
    }else{

        strData = {
            avance:avanceHrs,
            porcentaje:avancePor,
            Comentario:descripcion,
            id_proyecto:id_proyecto,
            id_actividad:id_actividad,
            id_tarea:id_tarea,
            idAvance:id_avance_tarea
        }

        gnSenderAjax(
            "modules/02Proyectos/layout/tareas/",
            "fn_registra_detalle_tarea.php",
            null,
            "seguimiento_tarea",
            "fa-save",
            "mdl_btn_save",
            50000,
            strData
        );


    }


}
function ver_detalle_tarea(id_proyecto,id_actividad,id_tarea){

    var stringData = {
        id_proyecto:id_proyecto,
        id_actividad:id_actividad,
        id_tarea:id_tarea
    }

    gnSenderAjax(
        "modules/02Proyectos/views/tareas/",
        "frm_detalle_tarea.php",
        null,
        "detalle_tarea",
        "fa-save",
        "mdl_btn_save",
        50000,
        stringData
    );



}

function fn02_registrar_tarea(idProyecto,idActividad){

    var nombre = $("#02alta_nombre"),
        descripcion = $('#02alta_descripcion'),
        solicitante = $('#02alta_solicitante'),
        responsable = $('#02alta_responsable'),
        fchsolicitud = $("#02alta_fchasolicitud"),
        fchinicial = $("#02alta_fchainicial"),
        fchfinal = $("#02alta_fchafinal"),
        prioridad = $("#02alta_prioridad"),
        tiempoestimado = $("#02alta_tiempoestimado");
    if($.trim(nombre.val()) == ""){
        MyAlert("Ingrese el nombre de la tarea","alert");
    }else if($.trim(descripcion.val()) == " "){
        MyAlert("Ingrese la descripción de la tarea","alert");
    }else if(solicitante.val() == 0){
        MyAlert("Seleccione el solicitante","alert");
    }else if($.trim( responsable.val() ) == 0 ){
        MyAlert("Seleccione el responsable de la tarea","alert");
    }else if($.trim(fchsolicitud.val()) == ""){
        MyAlert("Ingrese la fecha de solicitud","alert");
    }else if($.trim( fchinicial.val() ) == ""){
        MyAlert("Ingrese la fecha inicial","alert");
    }else if($.trim(fchfinal.val()) == ""){
        MyAlert("Ingrese la fecha final","alert");
    }else if(prioridad.val() == 0 ){
        MyAlert("Seleccione la prioridad de la tarea","alert");
    }else{

        var stringData = {
            nombre:nombre.val(),
            descripcion:descripcion.val(),
            solicitante:solicitante.val(),
            responsable:responsable.val(),
            fchasolicitud:fchsolicitud.val(),
            fchainicial:fchinicial.val(),
            fchafinal:fchfinal.val(),
            prioridad:prioridad.val(),
            tiempoestimado:tiempoestimado.val(),
            idProyecto:idProyecto,
            idActividad:idActividad

        };

        gnSenderAjax(
            "modules/02Proyectos/layout/tareas/",
            "fn_registra_tarea.php",
            null,
            "lListTask",
            "fa-save",
            "mdl_btn_save",
            50000,
            stringData
        );

    }



}


/**
 * Funciones de las Actividades Actividades
 */

function fn02_editar_actividad(opc,idActividad,idProyecto)
{
    /**
     * funcion para editar la actividad seleccionada
     * Parametro de Opc
     *
     * opc = 1: Mostrar modal para editar
     * opc = 2: ejecutar  funcion para editar la actividad
     *
     */
    var nombre,descripcion;
    nombre = $('#nameActivity');
    descripcion = $("#descripActivity");

    switch (opc){
        case 1:
            // mostrar modal para editar la activida
            gnSenderAjax(
                "modules/02Proyectos/views/actividades/",
                "FrmEditarActividad.php",
                null,
                "ajax_load",
                "fa-refresh",
                "btn_refresh",
                50000,
                {
                    idActividad:idActividad,
                    idProyecto:idProyecto
                }
            );
            break;
        case 2:



            if($.trim(nombre.val()) == ""){

                MyAlert("El nombre de la actividad, no deb estar vacio","alert");

            }else if($.trim(descripcion.val()) == ""){

                MyAlert("La descripción de la actividad, no deb estar vacio","alert");

            }else{

                gnSenderAjax(
                    "modules/02Proyectos/layout/actividades/",
                    "fnEditarActividad.php",
                    null,
                    "result_ajax",
                    "fa-refresh",
                    "btn_refresh",
                    50000,
                    {
                        idActividad:idActividad,
                        idProyecto:idProyecto,
                        nombre:nombre.val(),
                        descripcion:descripcion.val()
                    }
                );


            }
            break;
        default:
            MyAlert('Error al ejecurar la funcion, opcion no existe','error');
    }

}

function fn02_ver_actividad(idActividad,idProyecto){

    gnSenderAjax(
        "modules/02Proyectos/views/actividades/",
        "FrmActividades.php",
        null,
        "HomeContent",
        "fa-refresh",
        "btn_refresh",
        50000,
        {
            idActividad:idActividad,
            idProyecto:idProyecto
        }
    );


}



/**
 * Funciones Catalogo Tipo de Proyectos
 */
function fn02_listar_tipo_proyectos(opc){

    var urlPhp = "modules/02Proyectos/layout/catalogos/",
        nameView = "json_listar_tipo_proyectos.php",
        params = null,
        idDiv = "lListTable",
        faIcon = "fa-refresh",
        idBtn = "btn-refresh",
        timeout = 50000,
        stringData = {opcion:opc};

    gnSenderAjax(urlPhp,nameView,params,idDiv,faIcon,idBtn,timeout,stringData);


}
function fn02_nuevo_tipo_proyecto(opc){
    // opc: 1 = Mostrar modal Frm_nuevo_tipo_proyecto.php, 2 = fnRegistra_tipo_proyecto;
    var urlPhp,
        nameView,
        params,
        idDiv,
        faIcon,
        idBtn,
        timeout,
        stringData,
        SendAjax = false ;

    switch (opc){
        case 1:
            // mostrar FRM para el alta de tipo proyecto

            if($.trim( $('#tpo_project_name').val() ) == "" ) {
                MyAlert("Ingrese el nombre del tipo de proyecto","alert");
            }else {

                urlPhp = "modules/02Proyectos/layout/catalogos/";
                nameView = "fnRegistra_tipo_proyecto.php";
                params = null;
                idDiv = "lListTable";
                faIcon = "fa-save";
                idBtn = "mdl_btn_save";
                timeout = 50000;
                stringData = {
                    opcion:opc,
                    nombre:$('#tpo_project_name').val(),
                    descripcion:$('#tpo_project_description').val(),
                    estado:$('#tpo_project_estatus').val()
                };
                SendAjax = true;
            }

            break;
        case 2:
            break;
        default :
            MyAlert("Acceso no encontrado, intentelo nuevamente o contacte a sistemas");
    }

    if(SendAjax){
        gnSenderAjax(urlPhp,nameView,params,idDiv,faIcon,idBtn,timeout,stringData);
    }
}


/**
 * Funciones Catalogo Grupos de Trabajo
 * @param opc
 */

// Mostrar lista de Grupos de Proyectos

function fn02_listarGrupos(opc){

    var urlPhp = "modules/02Proyectos/layout/catalogos/",
        nameView = "json_listar_grupos.php",
        params = null,
        idDiv = "lListTable",
        faIcon = "fa-refresh",
        idBtn = "btn-refresh",
        timeout = 50000,
        stringData = {opcion:opc};

    gnSenderAjax(urlPhp,nameView,params,idDiv,faIcon,idBtn,timeout,stringData);


}

// alta de Grupo de Proyectos
function fn02_nuevo_grupo(opc){
    // opc : 1 = mostar modal FrmNuevoGrupo, 2 = FnRegistrarGrupo

    var urlPhp,
        nameView,
        params,
        idDiv,
        faIcon,
        idBtn,
        timeout,
        stringData,
        SendAjax = false ;
    switch (opc){
        case 1:
            // Mostrar Modal para registrar Nuevo grupo
            urlPhp = "modules/02Proyectos/views/catalogos/";
            nameView = "FrmNuevoGrupo.php";
            params = null;
            idDiv = "modal_ajax_02";
            faIcon = "fa-file";
            idBtn = "btnNuevo";
            timeout = 3000;
            stringData = {opc:opc};
            SendAjax = true;
            break;
        case 2:
            // alta de Nuevo Grupo de trabajo

            if($.trim( $("#02_alta_nombre").val() ) == "" ){
                MyAlert("Ingrese el nombre del grupo","error");
            }else {
                urlPhp = "modules/02Proyectos/layout/catalogos/";
                nameView = "fnRegistraGrupo.php";
                params = null;
                idDiv = "resultModal";
                faIcon = "fa-save";
                idBtn = "mdl_btn_save";
                timeout = 3000;


                stringData = {
                    nombre:$("#02_alta_nombre").val(),
                    descripcion:$("#02_alta_descripcion").val(),
                    estado:$("#02_alta_estado").val(),
                    users:$("#02_alta_usuarios").val()
                };

                SendAjax = true;

            }
            break;
        default:
            MyAlert("No se encontro la opcion solicitada","errpr");
    }


    if(SendAjax){
        gnSenderAjax(urlPhp,nameView,params,idDiv,faIcon,idBtn,timeout,stringData);
    }



}

function fn02_Menu(opcion){

    var urlPhp,nameView,idDiv,params,timeout,stringData,faIcon,idBtn,SendAjax =false ;

    switch (opcion){
        case 1:
            //Cargar Lista de Proyectos
            urlPhp = "modules/02Proyectos/layout/proyectos/";
            nameView = "json_listar_proyectos.php";
            idDiv = "lListTable";
            faIcon = "fa-refresh";
            idBtn = "btn_refresh";
            timeout = 10000;
            stringData = {
                firstName:"John",
                lastName:"Doe",
                age:46
            };
            SendAjax = true;
            break;
        case 2:
            // registro de nuevo Proyecto
            urlPhp = "modules/02Proyectos/layout/proyectos/";
            nameView = "fnRegistraProyecto.php";
            idDiv = "lListTable";
            faIcon = "fa-save";
            idBtn = "btnSave";
            timeout = 10000;

            var nombre = $('#nameProject'),
                descripcion = $('#descripProject'),
                tipo = $('#typeProject').val(),
                grupo = $('#groupProject').val(),
                fchsolicitud = $('#fchSolicitud').val(),
                fchpromesa = $('#fchPromesa').val(),
                fchalta = $('#fchAlta').val();

            if($.trim(nombre.val()) == ""){
                MyAlert("El nombre del proyecto no debe estar vacio.","alert");
            }else if($.trim(descripcion.val()) == ""){
                MyAlert("La descripción del proyecto no debe estar vacia.","alert");
            }else if(tipo == 0 ){
                MyAlert("Seleccione el tipo de proyecto.","alert");
            }else if(grupo == 0){
                MyAlert("Seleccione el grupo de trabajo.","alert");
            }else if($.trim(fchsolicitud) == ""){
                MyAlert("La fecha de solicitud es incorrecta","alert")
            }else if($.trim(fchpromesa) == ""){
                MyAlert("La fecha promesa es incorrecta","alert")
            }else if($.trim(fchalta) == ""){
                MyAlert("La fecha alta es incorrecta","alert")
            }else{
                stringData = {
                    enombre:nombre.val(),
                    edescripcion:descripcion.val(),
                    etipo:tipo,
                    egrupo:grupo,
                    efchsolicitud:fchsolicitud,
                    efchpromesa:fchpromesa,
                    efchalta:fchalta
                };
                SendAjax = true;
            }
            break;
        case 3:
            //Abrir Modal Nueva Actividad
            urlPhp = "modules/02Proyectos/views/proyectos/";
            nameView = "FrmNuevaActividad.php";
            idDiv = "loadModals";
            faIcon = "fa-flag";
            idBtn = "02newActivity";
            timeout = 10000;
            stringData = {
                firstName:"John",
                lastName:"Doe",
                age:46
            };
            SendAjax = true;
            break;
        case 4:
            // registro de nueva Actividad
            urlPhp = "modules/02Proyectos/layout/proyectos/";
            nameView = "fnRegistraActividad.php";
            idDiv = "lListTable";
            faIcon = "fa-save";
            idBtn = "mdl_btn_actividad_save";
            timeout = 10000;

            var nombre = $('#nameActivity'),
                descripcion = $('#descripActivity'),
                proyecto = $('#idProyecto').val(),
                fchalta = $('#fchAlta').val(),
                fchum = $('#fchUM').val();

            if($.trim(nombre.val()) == ""){
                MyAlert("El nombre de la actividad no debe estar vacio.","alert");
            }else if($.trim(descripcion.val()) == ""){
                MyAlert("La descripción del la actividad no debe estar vacia.","alert");
            }else if(proyecto == 0 ){
                MyAlert("Seleccione el proyecto de la actividad.","alert");
            }else if($.trim(fchalta) == ""){
                MyAlert("La fecha alta es incorrecta","alert")
            }else if($.trim(fchum) == ""){
                MyAlert("La fecha ultima modificacion es incorrecta","alert")
            }else{
                stringData = {
                    enombre:nombre.val(),
                    edescripcion:descripcion.val(),
                    eproyecto:proyecto,
                    efchalta:fchalta,
                    efchum:fchum
                };
                SendAjax = true;
            }
            break;
        case 5:
            // Mostrar Catalogo de Grupos
            urlPhp = "modules/02Proyectos/views/catalogos/";
            nameView = "FrmHomeGrupos.php";
            idDiv = "HomeContent";
            faIcon = "fa-save";
            idBtn = "btnSave";
            timeout = 10000;
            SendAjax = true;
            break;
        case 6:
            // Mostrar Catalogo Tipo de Proyecto
            // Mostrar Catalogo de Grupos
            urlPhp = "modules/02Proyectos/views/catalogos/";
            nameView = "FrmTipoProyectos.php";
            idDiv = "HomeContent";
            faIcon = "fa-save";
            idBtn = "btnSave";
            timeout = 10000;
            SendAjax = true;
            break;
        default:
            MyAlert("La Opcion seleccionada no existe");

    }

    if(SendAjax){ gnSenderAjax(urlPhp,nameView,null,idDiv,faIcon,idBtn,timeout,stringData);}


}

function gnSenderAjax(urlPhp,nameView,params,idDiv,faIcon,idBtn,timeout,stringData){
    if(params == null){
        params = "";
    }
    $.ajax({
        url:urlPhp + nameView + params,
        cache:false,
        data:stringData,
        beforeSend:function(){
            fnloadSpinner(1,faIcon,idBtn);
        },
        timeout:timeout,
        type:"post"
    }).done(function(data){
        fnloadSpinner(2,faIcon,idBtn);

        $("#"+idDiv).html(data);

    }).fail(function(jqHR,textEstatus,errno){

        fnloadSpinner(2,faIcon,idBtn);

        if(console && console.log){
            MyAlert(
                "Error al realizar la carga de la vista: <br>" +
                "{<br> url=>"+urlPhp +"  <br> view => "+nameView +", <br>"+textEstatus+ " =>  "+errno +"<br> "+jqHR+ "<br>}","error"
            );
        }else if(textEstatus == 'timeout')
        {
            MyAlert('Failed from timeout','error');
            //do something. Try again perhaps?
        }


    });
}