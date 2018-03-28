<style>
.dataTables tbody tr {
    min-height: 35px; /* or whatever height you need to make them all consistent */
}
.warning {
    background-color: orange !important;
}
</style>

<script type="text/javascript" charset="utf-8">

    function editIngreso(idIngreso) {
        var dataIngreso = {
            idIngreso: idIngreso
        };
        var optionSelected = "";
        $.ajax({
            url: "<?=site_url('producto/ajaxGetIngresoById')?>",
            dataType: "json",
            data: dataIngreso,
            type: 'GET',
            success: function(json) {
                var $content = $('<table></table>');
                var checked = "";
                if(json.activo == 1) {
                    checked = "checked";
                }
                $content.append('<tr><td>Codigo Externo</td><td><input type="text" class="form-control" placeholder="codigoExterno" id="codigoExterno" value="'+json.codigoExterno+'"></td></tr>');
                $content.append('<tr><td>Descripcion</td><td style="width:70%";><input type="text" class="form-control" placeholder="descripcion" id="descripcion" value="'+json.descripcion+'"></td></tr>');
                $content.append('<tr><td>Cantidad</td><td><input type="text" class="form-control" placeholder="codigoExterno" id="cantidad" value="'+json.cantidad+'"></td></tr>');
                $content.append('<tr><td>Unidad de Venta</td><td><input type="text" class="form-control" placeholder="codigoExterno" id="unidadVenta" value="'+json.unidadVenta+'"></td></tr>');
                $content.append('<tr><td>Numero de Unidades</td><td><input type="text" class="form-control" placeholder="codigoExterno" id="numeroUnidades" value="'+json.numeroUnidades+'"></td></tr>');
                $content.append('<tr><td>Precio Unitario</td><td><input type="text" class="form-control" placeholder="codigoExterno" id="precioUnitario" value="'+json.precioUnitario+'"></td></tr>');
                $content.append('<tr><td>Empresa</td><td><select class="selectpicker" data-live-search="true" id="empresa" title="Elija una Empresa..."></select></td></tr>');
                $content.append('<tr><td>Estado</td><td><div class="checkbox"><label class="checkbox-inline"><input id="status" type="checkbox" ' + checked + ' data-toggle="toggle"></label></div></td></tr>');
                optionSelected = json.idCategoria;    
                BootstrapDialog.show({
                    title: 'Editar Ingresoo',
                    message: $content,
                    onshown: function(){                           
                             $('#status').bootstrapToggle({  
                                 on: 'Activo',
                                 off: 'Inactivo'
                            });
                            var url = "<?php echo site_url('Ingresoo/ajaxGetCategoria') ?>";
                            loadCategoria(url, "empresa", optionSelected);
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
                            var dataIngreso = {
                                idIngresoo: idIngreso,
                                codigoExterno: $('#codigoExterno').val(),
                                descripcion: $('#descripcion').val(),
                                cantidad: $('#cantidad').val(),                                
                                unidadVenta: $('#unidadVenta').val(),
                                numeroUnidades: $('#numeroUnidades').val(),
                                precioUnitario: $('#precioUnitario').val(),
                                empresa: $('#empresa').val(),
                                activo: activo,
                            }
                            $.ajax({
                                url: "<?=site_url('Ingresoo/jsonGuardarIngresoo')?>",
                                dataType: "json",
                                data: dataIngreso,
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
                                    var urln = "<?= site_url('Ingresoo/ajaxListIngreso')?>";
                                    $("#Ingreso_table").DataTable().ajax.url(urln);
                                    $("#Ingreso_table").DataTable().ajax.reload();                                    
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
        // $('#Ingreso_table').DataTable( {
        //     paging: false
        // } );
        var url = "<?= site_url('producto/ajaxListIngreso')?>";
        var table = $("#ingreso_table").DataTable({
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
                { "data": "idIngreso", sDefaultContent: "n/a"}, 
                { "data": "fechaIngreso", sDefaultContent: ""}, 
                { "data": "factura", sDefaultContent: ""},
                { "data": "idProducto", sDefaultContent: ""}, 
                { "data": "descripcion", sDefaultContent: ""}, 
                { "data": "cantidad", sDefaultContent: ""}, 
                { "data": "valorUnitario", sDefaultContent: ""},
                { "data": "valorTotal", sDefaultContent: ""},                

            ],
            // "fnRowCallback": function( nRow, aoData, iDisplayIndex, iDisplayIndexFull ) {                
            //         if ( aoData.cantidad < 3 )
            //         {
            //             $('td:eq(3)', nRow).css('background-color', '#FC9A9A');
            //         }
            //         else
            //         {
            //             $('td:eq(3)', nRow).css('background-color', '#59BD4D');
            //         }
            //         if ( aoData.activo == 0 )
            //         {
            //             $('td', nRow).css('background-color', '#F5D167');
            //         }
            // },
        });                                

    });   
</script>
<ul class="breadcrumb">
    <li>
        <a href="#">Inicio</a>
    </li>
    <li>
        <a href="#">Ingresos</a>
    </li>
</ul>
<div class="row">
    <div class="box col-sm-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="Lista de Ingresos">
                <h2><i class="glyphicon glyphicon-th"></i> Lista de Ingresos</h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <table id="ingreso_table" class="table table-striped table-bordered bootstrap-datatable" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width:5%">IdIngreso</th>
                            <th style="width:10%">Fecha</th>
                            <th style="width:20%">factura</th>
                            <th style="width:5%">idProducto</th>
                            <th style="width:5%">Descripcion</th>
                            <th style="width:10%">Cantidad</th>
                            <th style="width:10%">Valor Unitario</th>
                            <th style="width:10%">Valor Total</th>                            
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>