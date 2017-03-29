 <style>
    .login-dialog .modal-dialog {
        width: 1000px;
    }
</style>
<script type="text/javascript" charset="utf-8">
var detailTableUpdated;
var oldProducts = [];
function removerow(id){
    $('#cantidad'+id).closest('tr').remove();

    var i = oldProducts.indexOf(id.toString());
    console.log(id)
    if(i != -1) {
        oldProducts.splice(i, 1);
    }

    console.log(oldProducts);

}
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
                var icon = '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>';
                var $content = $('<div></div>');
                $tableDetalle = 'Fecha: <div class="input-group date" id="fecha"><input type="text" class="form-control" name="fecha" id="fecha" value="'+json.fecha+'">' + icon + '</div>';
                $tableDetalle += '<div><b>Detalle del Pedido</b></div>';
                $tableDetalle += '<table id="detalle_table_update" class="table table-striped table-bordered datatable">';
                $tableDetalle += '<thead><tr><th>Codigo</th><th style="width:60%">Descripcion</th><th style="width:20%">Cantidad</th><th style="width:20%">Precio (Bs)</th><th>Quitar</th></tr></thead>';
                if(json.detalle.length > 0) {
                    oldProducts = [];
                    for(x=0; x< json.detalle.length; x++) {
                        var iconMinus = '<i class="glyphicon glyphicon-minus"></i>';
                        id = json.detalle[x].IdProducto;
                        oldProducts.push(id);
                        $tableDetalle += '<tr><td>' + id + '</td><td>'+json.detalle[x].descripcion +
                        '</td><td><input type="number" class="form-control" id="cantidad' + id + '" name="cantidad'+ id + '" value="' + 
                        json.detalle[x].cantidad+'"></td><td><input type="number" class="form-control" id="precio" name="precio' + id + '" value="' + 
                        json.detalle[x].precio+'"></td><td valign="center"><button class="btn btn-primary btn-sm" onclick="removerow(' + id + ')">'+ iconMinus +'</button></td></tr>';
                    }

                }
                $tableDetalle += '</table>';

                $tablex = '';
                $tablex +='<div class="box-content"><div class="row"><div class="col-md-6"><label for="productos">Agregar Producto(s)</label><div class="input-group col-md-6"><span class="input-group-addon">';
                $tablex +='<i class="glyphicon glyphicon-shopping-cart blue"></i></span>';
                $tablex +='<select id="productos" class="selectpicker" onchange="addProducts()" data-live-search="true" data-style="btn-primary" title="Elija un producto..." data-selected-text-format="count > 2" multiple>';
                $tablex +='</select></div></div></div></div>';
                                
                $tableUpdated = '<table id="table_new_products" class="table table-striped table-bordered"><thead><tr><th>producto</th><th>codigo</th><th>descripcion</th><th>cantidad</th><th>precio</th><th>total</th></tr></thead></table>'
                $tablex += $tableUpdated;
                $tableDetalle += $tablex;
                BootstrapDialog.show({
                    title: 'Editar Pedido',
                    message: $content.append($tableDetalle),
                    onshown: function(){
                        $('#fecha').datetimepicker({
                            format: 'YYYY-MM-DD HH:mm:ss'
                        });
                        /* get current */
                        var currentdt = $('#detalle_table_update input').serializeArray();
                        currentdt = $("#detalle_table_update").DataTable({
                            destroy: true,
                            info: false,
                            paging: false,
                            searching: false,
                            ordering: false
                        });
                        console.log(currentdt);
                        fillProductos();

                        detailTableUpdated = $("#table_new_products").DataTable({
                            destroy: true,
                            info: false,
                            paging: false,
                            searching: false,
                            ordering: false
                        });
                    },
                    cssClass: 'login-dialog',
                    buttons: [{
                        icon: 'glyphicon glyphicon-check',
                        label: 'Aceptar',
                        title: 'Aceptar',
                        autospin: true,
                        cssClass: 'btn-primary',
                        action: function(dialogRef){                                                                                                                

                            var dt = $('#detalle_table_update input').serializeArray();
                            var dtu = $('#table_new_products input').serializeArray();
                            console.log(dt);
                            console.log(JSON.stringify(dt));
                            console.log(JSON.stringify(dtu));
                            
                            var dataPedido = {
                                idPedido: idPedido,
                                idUser: '1',
                                fecha: $("#fecha").data('date'),
                                detalle: JSON.stringify(dt),
                                detalleNuevo: JSON.stringify(dtu),
                                oldProductos: oldProducts,
                                newProductos: $("#productos").val(),

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
        $('#fecha').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });

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
    function fillProductos() {
        $.ajax({
            url: "<?=site_url('producto/ajaxGetProductos')?>",
            dataType: "json",
            type: 'GET',
            success: function(json) {
                var options = '';
                for (var x = 0; x < json.length; x++) {
                    options += '<option value="' + json[x].idProducto + '">' + json[x].descripcion + '</option>';
                }

                $('#productos').html(options);
                $('#productos').selectpicker('refresh');
            },
            error: function() {
                console.log(options);
            }
        });
    }

    function addProducts() {
        var dataProduct = {
            products: $("#productos").val(),
        };
        $.ajax({
            url: "<?=site_url('producto/ajaxGetProductosByIds')?>",
            data: dataProduct,
            dataType: "json",
            type: 'POST',
            success: function(json) {                
                // detailTableUpdated = $("#table_new_products").DataTable();
                detailTableUpdated.clear();
                var totalBruto = 0;
                var valorTotal = 0;
                for (x=0; x< json.length; x++) {
                    total = json[x].cantidad * json[x].precioUnitario;
                    totalBruto +=  total;
				detailTableUpdated.row.add([
                    json[x].idProducto,
                    json[x].codigoExterno,
                    json[x].descripcion,
					'<div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span><input type="number" class="form-control" value="' +
                    json[x].cantidad + '" name="cantidad' + json[x].idProducto + '" id="cantidad' + json[x].idProducto + '"></div>',
					'<div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span><input type="number" class="form-control" value="' +
                    json[x].precioUnitario + '" name="precioUnitario' + json[x].idProducto + '" onChange="calcularTotalIndividual('+json[x].idProducto +')" '+
                    ' id= "precioUnitario' + json[x].idProducto + '"></div>',
                    '<input type="number" class="form-control" placeholder="totalBruto" id="total' + json[x].idProducto+'" disabled value="' + total + '">'
				]).draw();                
                }
                $('#totalBruto').val(totalBruto);
                descuento = $('#descuento').val();
                valorTotal = totalBruto - descuento;
                $('#valorTotal').val(valorTotal);
                //fillProductos();
			},
            error: function() {}
        });
    }
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