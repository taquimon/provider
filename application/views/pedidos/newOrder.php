<script type="text/javascript" charset="utf-8">
    fillClientes();
    fillProductos();
    $(function () {
        $('#datetimepicker1').datetimepicker();
    });
    function addNewOrderData() {
        var dataOrder = {
            numPedido: $("#numPedido").val(),
            idCliente: $("#idCliente").val(),
            idProducto: $("#idCliente").val(),
            idUser: $("#idUser").val(),
            fecha: $("#datetimepicker1").val(),
            
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
                    options += '<option value="' + json[x].codigoCliente + '">' + json[x].apellidos + " " + json[x].nombres + '</option>';
                }

                $('#clientes').html(options);                
            },
            error: function() {
                alert(options);
            }
        });
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
<form>
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
                            <label for="Numero Pedido">Numero Pedido</label>
                            <div class="input-group col-md-4">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-barcode blue"></i>
                            </span>
                                <input type="text" class="form-control" placeholder="Numero Pedido" id="numPedido" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="fecha">Fecha</label>
                            <div class="form-group">
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' class="form-control" />
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
                            <label for="cliente">Cliente</label>
                            <div class="input-group col-md-4">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-user blue"></i>
                            </span>                                
                                <select id="clientes" class="selectpicker" data-live-search="true" data-style="btn-primary">                                   
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="direccion">Producto</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-pencil blue"></i>
                            </span>
                                <select id="productos" class="selectpicker" data-live-search="true" data-style="btn-primary" multiple>   
                                </select>
                            </div>
                        </div>                       
                    </div>                    
                    <!-- row 2-->
                    <div class="row">
                        <div class="col-md-4">    
                            <table></table>                        
                        </div>
                        <div class="col-md-4">                            
                          <hr>
                        </div>                       
                    </div>       
                    <div class="row">
                        <div class="col-md-12">
                            <div class="group">
                                <a onclick="addNewOrderData()" title="Agregar Nuevo Ordere" data-toggle="tooltip" class="btn btn-primary">
                                    <i class="glyphicon glyphicon-ok-sign"></i> Guardar</a>
                                <a href="<?= site_url('Ordere/newOrder')?>" title="Agregar Nuevo Ordere" data-toggle="tooltip" class="btn btn-warning">
                                    <i class="glyphicon glyphicon-remove-sign"></i> Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>