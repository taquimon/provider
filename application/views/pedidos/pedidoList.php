<script type="text/javascript" charset="utf-8">
function editPedido(idPedido) {
        var dataPedido = {
            idPedido: idPedido
        };
        $.ajax({
            url: "<?=site_url('pedido/ajaxGetPedidoById')?>",
            dataType: "json",
            data: dataPedido,
            type: 'GET',
            success: function(json) {
                var $content = $('<table></table>');
                $content.append('<tr><td>Fecha</td><td colspan="2"><input type="text" class="form-control" placeholder="codigoExterno" id="codigoExterno" value="'+json.fecha+'"></td></tr>');
                $content.append('<tr><td colspan="3"><b>Detalle del Pedido</b></td><td style="width:70%;"></td></tr>');
                $content.append('<tr><td style="width:60%">Descripcion</td><td style="width:20%">Cantidad</td><td style="width:20%">Precio</td></tr>');
                if(json.detalle.length > 0) {
                    
                    for(x=0; x< json.detalle.length; x++) {
                        $content.append('<tr><td>'+json.detalle[x].descripcion +
                        '</td><td><input type="number" class="form-control" placeholder="codigoExterno" id="cantidad" value="' + 
                        json.detalle[x].cantidad+'"></td><td><input type="number" class="form-control" placeholder="codigoExterno" id="precio" value="' + 
                        json.detalle[x].precio+'"></td></tr>');
                    }
                    
                }                            

                BootstrapDialog.show({
                    title: 'Editar Pedido',
                    message: $content,
                    buttons: [{
                        icon: 'glyphicon glyphicon-check',
                        label: 'Aceptar',
                        title: 'Aceptar',
                        autospin: true,
                        cssClass: 'btn-primary',
                        action: function(dialogRef){
                            detailTable = $("#detalleTable").DataTable();
                            var dataDetail = detailTable.$("input").serializeArray();

                            var dataPedido = {
                                numPedido: $("#numPedido").val(),
                                idCliente: $("#clientes").val(),
                                productos: $("#productos").val(),
                                idUser: '1',
                                fecha: $("#datetimepicker1").data('date'),
                                detalle: JSON.stringify(dataDetail),
                            }
                            
                            $.ajax({
                                url: "<?=site_url('pedido/jsonGuardarPedido')?>",
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
                                    var urln = "<?= site_url('pedido/ajaxListOrder')?>";
                                    $("#pedido_table").DataTable().ajax.url(urln);
                                    $("#pedido_table").DataTable().ajax.reload();                                    
                                    setTimeout(function(){
                                        dialogRef.close();
                                    }, 5000);
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
    /*Delete Pedido*/
    function deletePedido(idPedido) {
        var $content = $('<div></div>');
        $content.append('Desea Eliminar el Pedido?');

        BootstrapDialog.show({
            title: 'Eliminar Pedido',
            message: $content,
            buttons: [{
                icon: 'glyphicon glyphicon-check',
                label: 'Si',
                title: 'Si',                
                cssClass: 'btn-primary',
                action: function(dialogRef){                    
                    var dataPedido = {
                    idPedido: idPedido
                    }                    
                    $.ajax({
                        url: "<?=site_url('pedido/jsonEliminarPedido')?>",
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
                            var urln = "<?= site_url('pedido/ajaxListOrder')?>";
                            $("#pedidos_table").DataTable().ajax.url(urln);
                            $("#pedidos_table").DataTable().ajax.reload();                                    
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

    $(document).ready(function() {
        // $('#product_table').DataTable( {
        //     paging: false
        // } );
        $("#pedidos_table").DataTable({
            destroy: true,
            info: false,
            responsive: true,
            ajax: {
                url: "<?= site_url('pedido/ajaxListOrder')?>",
                dataSrc: 'data',                
            },
            "columns": [
                { "data": "numPedido", sDefaultContent: ""}, 
                { "data": "razonSocial", sDefaultContent: ""},
                { "data": "username", sDefaultContent: ""},
                { "data": "fecha", sDefaultContent: ""},                 
                { "data": "detalles", sDefaultContent: ""},                 
                { "data": "options", sDefaultContent: ""},                
            ],
            paging: true,
        });


    });
</script>
<ul class="breadcrumb">
    <li>
        <a href="#">Inicio</a>
    </li>
    <li>
        <a href="#">Pedidos</a>
    </li>
</ul>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-th"></i> Lista de Pedidos</h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
                <div style="text-align:right;">
                    <a href="<?= site_url('pedido/newOrder')?>" title="Agregar nuevo producto" data-toggle="tooltip" class="btn btn-round">
                        <i class="glyphicon glyphicon-plus"></i> Nuevo Pedido</a>
                </div>
            </div>
            <div class="box-content">

                <table id="pedidos_table" class="table table-striped table-bordered bootstrap-datatable datatable responsive" style="width:100%;">
                    <thead>
                        <tr>
                            <th>numPedido</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                            <th>Detalles</th>                            
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>