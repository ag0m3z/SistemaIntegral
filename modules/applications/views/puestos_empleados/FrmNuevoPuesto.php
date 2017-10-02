
<div class="modal fade" id="myModal" data-backdrop="static"  role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-folder-open"></i> Nuevo Puesto</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    Nombre
                    <input id="nombre_puesto" placeholder="Nombre del puesto" class="form-control input-sm">
                </div>
                <div class="form-group">
                    Descripción
                    <textarea id="descripcion_puesto" placeholder="Descripción del puesto" class="form-control"></textarea>
                </div>
                <div id="imgLoad"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="btnSave" onclick="fnCatAltaPuesto(2)" ><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger btn-sm" id="modalbtnclose" onclick="$('#myModal').modal('toggle')"><i class="fa fa-close"></i> Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript">
    $('#myModal').modal('toggle');
    $("#myModal").draggable({
        handle: ".modal-header"
    });
    $("#nombre_puesto").focus();

</script>
