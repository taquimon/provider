 <style>
    .login-dialog .modal-dialog {
        width: 1000px;
    }
</style>
<script type="text/javascript" charset="utf-8">
var tablePedido;
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
    var vendedor = "";
    var clienteSelected = "";
        var dataPedido = {
            idPedido: idPedido
        };
        $.ajax({
            url: "<?=site_url('pedido/ajaxGetPedidoById'); ?>",
            dataType: "json",
            data: dataPedido,
            type: 'GET',
            success: function(json) {
                var icon = '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>';
                var $content = $('<div></div>');
                var checked = "";
                if(json.tipo_pedido == 'CONTADO') {
                    checked = "checked";
                }
                console.log(json.idVendedor);
                vendedor = parseInt(json.idVendedor);
                clienteSelected = parseInt(json.idCliente);
                $tableDetalle = '<table><tr><td>Fecha:</td><td><div class="input-group date" id="fecha"><input type="text" class="form-control" name="fecha" id="fecha" value="'+json.fecha+'">' + icon + '</div></td>';
                $tableDetalle += '<td>Tipo de Pedido:</td><td><div class="checkbox"><label class="checkbox-inline"><input type="checkbox" id="tipo_pedido" ' + checked + ' data-toggle="toggle" data-on="CONTADO" data-off="CREDITO" data-onstyle="success" data-offstyle="danger"></label></div></td>';
                
                $tableDetalle += '<td>Vendedor</td><td><select id="vendedores" class="selectpicker" data-live-search="true" data-style="btn-warning" name="vendedores"></select></td></tr>'
                $tableDetalle += '<tr><td>Todos - Vendedor</td><td><div class="checkbox"><label class="checkbox-inline"><input type="checkbox" id="all_clientes" ' + checked + ' data-toggle="toggle" data-on="VENDEDOR" data-off="TODOS" data-onstyle="success" data-offstyle="danger"></label></div></td>';
                $tableDetalle += '<td>Clientes-RS-Codigo</td><td>';
                $tableDetalle += '<div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-user blue"></i></span>';
                $tableDetalle += '<select id="clientes" name="clientes" class="selectpicker" data-live-search="true" data-style="btn-primary" data-show-subtext="true"></select></div></td></tr><table>';

                $tableDetalle += '<div><b>Detalle del Pedido</b></div>';
                $tableDetalle += '<table id="detalle_table_update" class="table table-striped table-bordered datatable">';
                $tableDetalle += '<thead><tr><th>Codigo</th><th style="width:60%">Descripcion</th><th style="width:20%">Cantidad</th><th style="width:20%">Precio (Bs)</th><th>Quitar</th></tr></thead>';
                if(json.detalle.length > 0) {
                    oldProducts = [];
                    oldQuantities = [];
                    for(x=0; x< json.detalle.length; x++) {
                        var iconMinus = '<i class="glyphicon glyphicon-minus"></i>';
                        id = json.detalle[x].IdProducto;
                        oldProducts.push(id);
                        oldQuantities.push(json.detalle[x].cantidad);
                        $tableDetalle += '<tr><td>' + id + '</td><td>'+json.detalle[x].descripcion +
                        '</td><td><input type="number" class="form-control" id="cantidad' + id + '" name="cantidad'+ id + '" value="' + 
                        json.detalle[x].cantidad+'"></td><td><input type="number" class="form-control" id="precio" name="precio' + id + '" value="' + 
                        json.detalle[x].precio+'"></td><td valign="center"><button class="btn btn-primary btn-sm" onclick="removerow(' + id + ')">'+ iconMinus +'</button></td></tr>';
                    }

                }
                $tableDetalle += '</table>';

                $tablex = '';
                $tablex += '<div class="box-content"><div class="row"><div class="col-md-6"><label for="productos">Agregar Producto(s)</label><div class="input-group col-md-6"><span class="input-group-addon">';
                $tablex += '<i class="glyphicon glyphicon-shopping-cart blue"></i></span>';
                $tablex += '<select id="productos" class="selectpicker" onchange="addProducts()" data-live-search="true" data-style="btn-primary" title="Elija un producto..." data-selected-text-format="count > 2" multiple>';
                $tablex += '</select></div></div></div></div>';
                $tablex += '<div class="row"><div class="col-md-12"><label for="observacion">Observaciones</label><div class="input-group col-md-12">';
                $tablex += '<textarea class="form-control rounded-0" id="observaciones" rows="2">' + json.observaciones + '</textarea></div></div></div>';
                                
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
                         $('#tipo_pedido').bootstrapToggle();
                         $('#all_clientes').bootstrapToggle();                         
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
                        var urlVendedor = "<?=site_url('vendedor/ajaxGetVendedores'); ?>";
                        fillVendedor(urlVendedor, "vendedores", vendedor);
                        // $('#vendedores').selectpicker('refresh');
                        fillClientes(json.idVendedor, json.idCliente);
                        $('#all_clientes').change(function() {
                            console.log("change clients")

                            fillClientes(json.idVendedor, json.idCliente);
                        });
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
                            var tipoPedido = $('#tipo_pedido').prop('checked') == true ? 'CONTADO' : 'CREDITO';
                            var dataPedido = {
                                idPedido: idPedido,
                                idUser: '1',
                                fecha: $("#fecha").data('date'),
                                detalle: JSON.stringify(dt),
                                detalleNuevo: JSON.stringify(dtu),
                                oldProductos: oldProducts,
                                newProductos: $("#productos").val(),
                                tipoPedido: tipoPedido,
                                oldQuantities: oldQuantities,    
                                idVendedor: $("#vendedores").val(),
                                idCliente: $("#clientes").val(),
                                observaciones: $("#observaciones").val(),
                            }
                            
                            $.ajax({
                                url: "<?=site_url('pedido/jsonGuardarPedido'); ?>",
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
                                    var urln = "<?= site_url('pedido/ajaxListOrder'); ?>";
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
                        url: "<?=site_url('pedido/jsonEliminarPedido'); ?>",
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
                            var urln = "<?= site_url('pedido/ajaxListOrder'); ?>";
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
                
        var currentYear = "<?php echo date('Y'); ?>";
        $("#yearPedido").val(currentYear);
        
        tablePedido = $("#pedidos_table").DataTable({
            destroy: true,
            info: false,
            responsive: true,
            ajax: {
                url: "<?= site_url('pedido/ajaxListOrder'); ?>",
                data: function(d) {
                    d.year = $("#yearPedido").val()                    
                }, 
                dataSrc: 'data',                
            },
            "columns": [
                { "data": "numPedido", sDefaultContent: ""}, 
                { "data": "razonSocial", sDefaultContent: ""},
                { "data": "codigoCliente", sDefaultContent: ""},
                { "data": "nombres", sDefaultContent: ""},
                { "data": "fecha", sDefaultContent: ""},                                 
                { "data": "tipo_pedido", sDefaultContent: ""},                                 
                { "data": "options", sDefaultContent: ""},                
            ],
            paging: true,
        });                    

    });
    function fillProductos() {
        $.ajax({
            url: "<?=site_url('producto/ajaxGetProductos'); ?>",
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
            url: "<?=site_url('producto/ajaxGetProductosByIds'); ?>",
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
    function getPedidoByYear() {        
        tablePedido.ajax.reload();
    }
    function fillClientes(idVendedor, clienteSelected = "") {
        
        var url = "<?=site_url('cliente/ajaxGetClientes'); ?>";        
        // var idVendedor = $("#vendedores").val();
        var allClientes = $('#all_clientes').prop('checked');
        console.log(allClientes);
        var vendedorData = {};
        if(allClientes) {
            vendedorData = {
                idVendedor : idVendedor,
            }
        }
        $.ajax({
            url: url,
            dataType: "json",
            data: vendedorData,
            type: 'GET',
            success: function(json) {                
            var options = '';
            for (var x = 0; x < json.length; x++) {
               options += '<option value="' + json[x].idCliente + '" data-subtext="';
               options += json[x].codigoCliente + '">';
               options += json[x].nombres + " " + json[x].apellidos + ", ";
               options += json[x].razonSocial + '</option>';
            }
            console.log("Cliente Selected: " + clienteSelected)            
            $('#clientes').html(options);
            if(clienteSelected != "") {
                $('#clientes').val(clienteSelected);
            }      
            $('#clientes').selectpicker('refresh');
            },
            error: function() {
                alert(options);
            }
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
    <div class="col-sm-1">               
        <div class="input-group">                
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar blue"></i></span>                
                <select id='yearPedido' class="selectpicker" data-style="btn-danger" onchange="getPedidoByYear()">
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
		    <option value="2022">2022</option>
		    <option value="2023">2023</option>
                </select>                
        </div>            
    </div>    
</div>
<div class="row">    
    <div class="box col-md-12">    
        <div class="box-inner">        
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-th"></i> Lista de Pedidos</h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
                <div style="text-align:right;">
                    <a href="<?= site_url('pedido/newOrder'); ?>" title="Agregar nuevo producto" data-toggle="tooltip" class="btn btn-round">
                        <i class="glyphicon glyphicon-plus"></i> Nuevo Pedido</a>
                </div>
            </div>
            <div class="box-content">

                <table id="pedidos_table" class="table table-striped table-bordered bootstrap-datatable datatable responsive" style="width:100%;">
                    <thead>
                        <tr>
                            <th style="width:15%">numPedido</th>
                            <th style="width:15%">Cliente</th>
                            <th style="width:10%">Codigo Cliente</th>
                            <th style="width:10%">Vendedor</th>
                            <th style="width:15%">Fecha</th>                            
                            <th style="width:10%">Tipo Pedido</th>           
                            <th style="width:25%">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
