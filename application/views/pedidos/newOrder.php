<script type="text/javascript" charset="utf-8">
    var detailTable;
    $(function() {        


        $.ajax({
            url: "<?=site_url('pedido/getLastDate')?>",
            dataType: "json",
            type: 'GET',
            success: function(json) {
                console.log(json);
                $('#datetimepicker1').datetimepicker({
                    format: 'YYYY-MM-DD HH:mm:ss',
                    defaultDate: json.fecha
                });

            },
            error: function() {
                console.log("cannot add date");
            }
        });

        fillClientes();
        fillProductos();
        detailTable = $("#detalleTable").DataTable({
            destroy: true,
            info: false,
            paging: false,
            searching: false,
			ordering: false
        });             
        totalsTable = $("#totalsTable").DataTable({
            destroy: true,
            info: false,
            paging: false,
            searching: false,
			ordering: false
        });
    });

    function addNewOrderData() {
        detailTable = $("#detalleTable").DataTable();
        var dataDetail = detailTable.$("input").serializeArray();

        var dataOrder = {
            numPedido: $("#numPedido").val(),
            idCliente: $("#clientes").val(),
            productos: $("#productos").val(),
            idUser: '1',
            fecha: $("#datetimepicker1").data('date'),
            detalle: JSON.stringify(dataDetail),
        };

        var url = "<?=site_url('pedido/jsonGuardarNuevo')?>";

        $.ajax({
            url: url,
            data: dataOrder,
            dataType: "json",
            type: 'POST',
            success: function(json) {
                var n = noty({
                    type: "success",
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
            },
            error: function() {
                var n = noty({
                    type: "error",
                    text: "Error al guardar, compruebe que los datos esten correctos",
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

            }
        });
    }

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
                alert(options);
            }
        });
    }

    function fillClientes() {
        $.ajax({
            url: "<?=site_url('cliente/ajaxGetClientes')?>",
            dataType: "json",
            type: 'GET',
            success: function(json) {
                var options = '';
                for (var x = 0; x < json.length; x++) {
                    options += '<option value="' + json[x].idCliente + '" data-subtext="' + json[x].codigoCliente + '">' + json[x].apellidos + " " + json[x].nombres + '</option>';
                }

                $('#clientes').html(options);
                $('#clientes').selectpicker('refresh');
            },
            error: function() {
                alert(options);
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
                detailTable.clear();
                var totalBruto = 0;
                var valorTotal = 0;
                for (x=0; x< json.length; x++) {
                    total = json[x].cantidad * json[x].precioUnitario;
                    totalBruto +=  total;
				detailTable.row.add([
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
    function removeProduct() {        
        detailTable = $("#detalleTable").DataTable();        
        detailTable.row($(this).closest("tr").get(0)).remove().draw();       
    }
    function calcularTotal() {
        totalBruto = $('#totalBruto').val();
        descuento = $('#descuento').val();
        valorTotal = totalBruto - descuento;
        $('#valorTotal').val(valorTotal);
    }
    function calcularTotalIndividual(id) {
        total = $('#cantidad'+id).val() * $('#precioUnitario'+id).val();                
        $('#total'+id).val(total);
    }
</script>

<ul class="breadcrumb">
    <li>
        <a href="<?=site_url('home')?>">Inicio</a>
    </li>
    <li>
        <a href="<?=site_url('pedido')?>">Pedidos</a>
    </li>
    <li>
        <a href="<?=site_url('pedido/newOrder')?>">Nuevo Pedido</a>
    </li>
</ul>
<form id="form1">
    <div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-th"></i> Nuevo Pedido</h2>
                    <div class="box-icon">
                        <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                    </div>
                </div>
                <div class="box-content">
                    <div class="row">
                        <div class="col-md-4">
							<label for="cliente">Cliente / codigo</label>
                            <div class="input-group col-md-4">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-user blue"></i>
                            </span>
                                <select id="clientes" class="selectpicker" data-live-search="true" data-style="btn-primary" data-show-subtext="true">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="fecha">Fecha</label>
                            <div class="form-group">
                                <div class="input-group date" id="datetimepicker1">
                                    <input type="text" class="form-control"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row 2-->
                    <div class="row">
                        <div class="col-md-4">
                            <label for="direccion">Producto(s)</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-shopping-cart blue"></i>
                            </span>
                                <select id="productos" onchange="addProducts()" class="selectpicker" data-live-search="true" data-style="btn-primary" title="Elija un producto..." data-selected-text-format="count > 3" multiple data-width="200px">
                                </select>                                
                            </div>
                        </div>
						<div class="col-md-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="box col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <table id="detalleTable" class="table table-striped table-bordered bootstrap-datatable datatable">
                        <thead>
                            <tr>
                                <th>IDPRODUCTO</th>
                                <th>CODIGO</th>
                                <th>DESCRIPCION</th>
                                <th>CANTIDAD</th>
                                <th>PRECIO UNITARIO</th>								
                                <th>TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <hr>
                </div>
            </div>
            <div class="row">
                        <div class="col-md-4">
                            <label for="totalBruto">Total Bruto</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-barcode blue"></i>
                            </span>
                                <input type="number" class="form-control" placeholder="totalBruto" id="totalBruto" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="descuento">Descuento</label>
                            <div class="input-group col-md-9">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-usd blue"></i>
                            </span>
                                <input type="number" class="form-control" placeholder="descuento" id="descuento" value = "0" onchange="calcularTotal()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="apellidos">Valor Total</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-usd blue"></i>
                            </span>
                                <input type="number" class="form-control" placeholder="Valor Total" id="valorTotal" disabled>
                            </div>
                        </div>
                    </div>
            <!-- row 2-->
            <div class="row">
                <div class="col-md-12">
                    <div class="group">
                        <a onclick="addNewOrderData()" title="Agregar Nuevo Ordere" data-toggle="tooltip" class="btn btn-primary">
                            <i class="glyphicon glyphicon-ok-sign"></i> Guardar</a>
                        <a href="<?= site_url('pedido')?>" title="Agregar Nuevo Pedido" data-toggle="tooltip" class="btn btn-warning">
                            <i class="glyphicon glyphicon-remove-sign"></i> Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>