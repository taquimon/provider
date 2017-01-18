<script>
    function addNewProductData(){                
        var dataProduct = {                        
                codigoExterno:  $("#codigoExterno").val(),
                descripcion:    $("#descripcion").val(),
                cantidad:       $("#cantidad").val(),
                unidadVenta :   $("#unidadVenta").val(),
                numeroUnidades: $("#numeroUnidades").val(),
                precioUnitario: $("#precioUnitario").val(),
                // valorBruto:     $("#valorBruto").val(),
                // descuento:      $("#descuento").val(),
                // valorTotal:     $("#valorTotal").val(),                        
            };
        
        var url = "<?=site_url('producto/jsonGuardarNuevo')?>";        
            
        $.ajax({
            url: url,
            data: dataProduct,
            dataType: "json",
            type: 'POST',
            success: function (json) {                                                                                                                                                               
                var n = noty({
                    type: "success",
                    text: json.message,
                    animation: {
                        open: {height: 'toggle'},
                        close: {height: 'toggle'},
                        easing: 'swing',
                        speed: 500 // opening & closing animation speed
                    }
                });
            },
            error: function () {                            
                var n = noty({
                    type: "error",
                    text: "Error al guardar, compruebe que los datos esten correctos",
                    animation: {
                        open: {height: 'toggle'},
                        close: {height: 'toggle'},
                        easing: 'swing',
                        speed: 500 // opening & closing animation speed
                    }
                });

            }
        });
    }
</script>

<ul class="breadcrumb">
    <li>
        <a href="<?=site_url('home')?>">Inicio</a>
    </li>
    <li>
        <a href="<?=site_url('producto')?>">Productos</a>
    </li>
    <li>
        <a href="<?=site_url('producto/newProduct')?>">Nuevo Producto</a>
    </li>
</ul>
<form>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-th"></i> Nuevo Producto</h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-md-4">
                        <label for="codigoExterno">Codigo Externo</label>
                        <div class="input-group col-md-6">                            
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-barcode blue"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="codigoExterno" id="codigoExterno" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="descripcion">Descripcion</label>
                        <div class="input-group col-md-9">                            
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-list-alt blue"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="descripcion" id="descripcion">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="cantidad">Cantidad</label>
                        <div class="input-group col-md-6">
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-pencil blue"></i>
                            </span>
                            <input type="number" class="form-control" placeholder="cantidad" id="cantidad">
                        </div>
                    </div>
                </div>
                <!-- row 2-->
                <div class="row">
                    <div class="col-md-4">
                        <label for="codigoExterno">Unidad de Venta</label>
                        <div class="input-group col-md-6">                            
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-pencil blue"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Unidad de Venta" id="unidadVenta">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="numero">Numero de Unidades</label>
                        <div class="input-group col-md-6">                            
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-pencil blue"></i>
                            </span>
                            <input type="number" class="form-control" placeholder="Numero de Unidades" id="numeroUnidades">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="precio unitario">Precio Unitario</label>
                        <div class="input-group col-md-6">                            
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-usd blue"></i>
                            </span>
                            <input type="number" class="form-control" placeholder="Precio Unitario" id="precioUnitario">
                        </div>
                    </div>
                </div>               
                <!-- row 4-->                                
                <div class="row">
                    <div class="col-md-12">
                        <div class="group">                            
                            <a onclick="addNewProductData()" title="Agregar nuevo producto" data-toggle="tooltip" class="btn btn-primary">
                            <i class="glyphicon glyphicon-ok-sign"></i> Guardar</a>                                                                       
                            <a href="<?= site_url('producto/newProduct')?>" title="Agregar nuevo producto" data-toggle="tooltip" class="btn btn-warning">
                            <i class="glyphicon glyphicon-remove-sign"></i> Cancelar</a>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
</form>