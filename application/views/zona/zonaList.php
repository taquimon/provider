<style>
.dataTables tbody tr {
    min-height: 35px; /* or whatever height you need to make them all consistent */
}
.warning {
    background-color: orange !important;
}
</style>

<script type="text/javascript" charset="utf-8">

    function editZona(idZona) {
        var dataZona = {
            idZona: idZona
        };
        $.ajax({
            url: "<?=site_url('zona/ajaxGetZonaById')?>",
            dataType: "json",
            data: dataZona,
            type: 'GET',
            success: function(json) {
                var $content = $('<table></table>');
                var checked = "";
                if(json.activo == 1) {
                    checked = "checked";
                }
                $content.append('<tr><td>Nombre</td><td><input type="text" class="form-control" placeholder="nombre" id="nombre" value="'+json.nombre+'"></td></tr>');
                $content.append('<tr><td>Descripcion</td><td style="width:70%";><input type="text" class="form-control" placeholder="descripcion" id="descripcion" value="'+json.descripcion+'"></td></tr>');                

                BootstrapDialog.show({
                    title: 'Editar Zona',
                    message: $content,
                    onshown: function(){                           
                             $('#status').bootstrapToggle({  
                                 on: 'Activo',
                                 off: 'Inactivo'
                            });
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-check',
                        label: 'Aceptar',
                        title: 'Aceptar',
                        autospin: true,
                        cssClass: 'btn-primary',
                        action: function(dialogRef){
                            console.log($('#status').prop('checked'));
                            var activo = $('#status').prop('checked') == true ? 1 : 0;
                            var dataZona = {
                                idZona: idZona,
                                nombre: $('#nombre').val(),
                                descripcion: $('#descripcion').val(),                                
                                activo: activo,
                            }
                            $.ajax({
                                url: "<?=site_url('zona/jsonGuardarZona')?>",
                                dataType: "json",
                                data: dataZona,
                                type: 'POST',   
                                success: function(json) {
                                    var n = noty({
                                        type: "success",
                                        layout: "top",
                                        text: json.message,
                                        animation: {
                                            open: {
                                                height: 'toggle'
                                            },
                                            close: {
                                                height: 'toggle'
                                            },
                                            easing: 'swing',
                                            speed: 500 // opening & closing animation speed
                                        }
                                    });
                                    var urln = "<?= site_url('zona/ajaxListZona')?>";
                                    $("#zona_table").DataTable().ajax.url(urln);
                                    $("#zona_table").DataTable().ajax.reload();                                    
                                    setTimeout(function(){
                                        dialogRef.close();
                                    }, 3000);
                                }
                            });
                        }
                    }, {
                        icon: 'glyphicon glyphicon-ban-circle',
                        label: 'Cancelar',
                        title: 'Cancelar',
                        cssClass: 'btn-warning',
                        action: function(dialogItself){
                            dialogItself.close();
                        }
                    }]
                });                
            },
            error: function() {                
            }
        });       
    }
    $(document).ready(function() {
        
        var url = "<?= site_url('zona/ajaxListZona')?>";
        var table = $("#zona_table").DataTable({
            destroy: true,
            paging: true,
            info: false,            
            language: {
            "lengthMenu": "Mostrar _MENU_ filas por pagina",
            "zeroRecords": "No se encontro datos - lo sentimos",
            "info": "Mostrar pagina _PAGE_ de _PAGES_",
            "search": "Buscar",
            "infoEmpty": "No se encontraron datos",
            "infoFiltered": "(filtrar _MAX_ total filas)",
            "paginate": {
                "first":      "Primer",
                "last":       "Ultimo",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            },            
            ajax: {
                url: url,
                dataSrc: 'data',                
            },
            columns: [
                { "data": "idZona", sDefaultContent: "n/a"}, 
                { "data": "nombre", sDefaultContent: ""}, 
                { "data": "descripcion", sDefaultContent: ""},                
                { "data": "options", sDefaultContent: ""},
            ],
            "fnRowCallback": function( nRow, aoData, iDisplayIndex, iDisplayIndexFull ) {                
                    if ( aoData.cantidad < 3 )
                    {
                        $('td:eq(3)', nRow).css('background-color', '#FC9A9A');
                    }
                    else
                    {
                        $('td:eq(3)', nRow).css('background-color', '#59BD4D');
                    }
                    if ( aoData.activo == 0 )
                    {
                        $('td', nRow).css('background-color', '#F5D167');
                    }
            },
        });                                

    });   
    /*Delete Zona*/
    function deleteZona(idZona) {
        var $content = $('<div></div>');
        $content.append('Desea Eliminar la Zona?');

        BootstrapDialog.show({
            title: 'Eliminar Zona',
            message: $content,
            buttons: [{
                icon: 'glyphicon glyphicon-check',
                label: 'Si',
                title: 'Si',                
                cssClass: 'btn-primary',
                action: function(dialogRef){                    
                    var dataPedido = {
                    idZona: idZona
                    }                    
                    $.ajax({
                        url: "<?=site_url('zona/jsonEliminarZona')?>",
                        dataType: "json",
                        data: dataPedido,
                        type: 'POST',   
                        success: function(json) {
                            var n = noty({
                                type: "success",
                                layout: "top",
                                text: json.message,
                                animation: {
                                    open: {
                                        height: 'toggle'
                                    },
                                    close: {
                                        height: 'toggle'
                                    },
                                    easing: 'swing',
                                    speed: 500 // opening & closing animation speed
                                }
                            });                                    
                            var urln = "<?= site_url('zona/ajaxListZona')?>";
                            $("#zona_table").DataTable().ajax.url(urln);
                            $("#zona_table").DataTable().ajax.reload();                                    
                            setTimeout(function(){
                                dialogRef.close();
                            }, 3000);
                        }
                    });
                }
            }, {
                icon: 'glyphicon glyphicon-ban-circle',
                label: 'No',
                title: 'No',
                cssClass: 'btn-warning',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });                    
    }
</script>
<ul class="breadcrumb">
    <li>
        <a href="#">Inicio</a>
    </li>
    <li>
        <a href="#">Zonas</a>
    </li>
</ul>
<div class="row">
    <div class="box col-sm-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="Lista de Zonaos">
                <h2><i class="glyphicon glyphicon-th"></i> Lista de Zonas</h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
                <div style="text-align:right;">
                    <a href="<?= site_url('zona/newZona')?>" title="Agregar Nueva Zona" data-toggle="tooltip" class="btn btn-round">
                        <i class="glyphicon glyphicon-plus"></i> Nueva Zona</a>
                </div>
            </div>
            <div class="box-content">
                <table id="zona_table" class="table table-striped table-bordered bootstrap-datatable" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width:5%">IdZonao</th>
                            <th style="width:10%">Codigo Externo</th>
                            <th style="width:30%">Descripcion</th>                            
                            <th style="width:20%">Opciones</th>                            
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>