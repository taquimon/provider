<style>
.dataTables tbody tr {
    min-height: 35px; /* or whatever height you need to make them all consistent */
}
.warning {
    background-color: orange !important;
}
</style>

<script type="text/javascript" charset="utf-8">

    function editProduct(idProduct) {
        var dataProduct = {
            idProduct: idProduct
        };
        $.ajax({
            url: "<?=site_url('producto/ajaxGetProductById')?>",
            dataType: "json",
            data: dataProduct,
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
                $content.append('<tr><td>Estado</td><td><div class="checkbox"><label class="checkbox-inline"><input id="status" type="checkbox" ' + checked + ' data-toggle="toggle"></label></div></td></tr>');

                BootstrapDialog.show({
                    title: 'Editar Producto',
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
                            var dataProduct = {
                                idProducto: idProduct,
                                codigoExterno: $('#codigoExterno').val(),
                                descripcion: $('#descripcion').val(),
                                cantidad: $('#cantidad').val(),                                
                                unidadVenta: $('#unidadVenta').val(),
                                numeroUnidades: $('#numeroUnidades').val(),
                                precioUnitario: $('#precioUnitario').val(),
                                activo: activo,
                            }
                            $.ajax({
                                url: "<?=site_url('producto/jsonGuardarProducto')?>",
                                dataType: "json",
                                data: dataProduct,
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
                                    var urln = "<?= site_url('producto/ajaxListProduct')?>";
                                    $("#product_table").DataTable().ajax.url(urln);
                                    $("#product_table").DataTable().ajax.reload();                                    
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
        // $('#product_table').DataTable( {
        //     paging: false
        // } );
        var url = "<?= site_url('producto/ajaxListProduct')?>";
        var table = $("#product_table").DataTable({
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
                { "data": "idProducto", sDefaultContent: "n/a"}, 
                { "data": "codigoExterno", sDefaultContent: ""}, 
                { "data": "descripcion", sDefaultContent: ""},
                { "data": "cantidad", sDefaultContent: ""}, 
                { "data": "unidadVenta", sDefaultContent: ""}, 
                { "data": "numeroUnidades", sDefaultContent: ""}, 
                { "data": "precioUnitario", sDefaultContent: ""},
                { "data": "status", sDefaultContent: ""},
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
</script>
<ul class="breadcrumb">
    <li>
        <a href="#">Inicio</a>
    </li>
    <li>
        <a href="#">Productos</a>
    </li>
</ul>
<div class="row">
    <div class="box col-sm-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="Lista de Productos">
                <h2><i class="glyphicon glyphicon-th"></i> Lista de Productos</h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
                <div style="text-align:right;">
                    <a href="<?= site_url('producto/newProduct')?>" title="Agregar nuevo producto" data-toggle="tooltip" class="btn btn-round">
                        <i class="glyphicon glyphicon-plus"></i> Nuevo Producto</a>
                </div>
            </div>
            <div class="box-content">
                <table id="product_table" class="table table-striped table-bordered bootstrap-datatable" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width:5%">IdProducto</th>
                            <th style="width:10%">Codigo Externo</th>
                            <th style="width:30%">Descripcion</th>
                            <th style="width:5%">Cantidad</th>
                            <th style="width:10%">Unidad de Venta</th>
                            <th style="width:10%">Numero de Unidades</th>
                            <th style="width:10%">Precio Unitario</th>                            
                            <th style="width:10%">Estado</th>
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