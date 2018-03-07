<script>
    var productData = {};
    $(function() {
        loadProducts();
        loadCategoria();
        $('#productos').on('change', function() {
            var productSelected = $(this).find("option:selected").val();
            var currentProduct = {}
            console.log("loadProductData method");
            console.log(productData);
            for (var i = 0; i < productData.length; i++) {
                if (productData[i].idProducto == productSelected) {
                    currentProduct = productData[i];
                    console.log(productData[i]);
                }
            }
            cantidad = currentProduct.cantidad;
            html_cantidad = 'Stock <span class="badge badge-success"> '
            html_cantidad += cantidad + '</span>';
            $('#stock').html(html_cantidad);
            $("#codigoExterno").val(currentProduct.codigoExterno);
            $("#descripcion").val(currentProduct.descripcion);
            $("#precioUnitario").val(currentProduct.precioUnitario);
        });
    });

    function addNewProductData() {
        var dataProduct = {
            idProducto: $("#productos").val(),
            codigoExterno: $("#codigoExterno").val(),
            descripcion: $("#descripcion").val(),
            cantidad: $("#cantidad").val(),
            unidadVenta: $("#unidadVenta").val(),
            numeroUnidades: $("#numeroUnidades").val(),
            precioUnitario: $("#precioUnitario").val(),
            precioVenta: $("#precioVenta").val(),
            factura: $("#factura").val(),
            valorTotal: $("#valorTotal").val(),
            idCategoria: $("#empresa").val(),
        };

        var url = "<?=site_url('producto/jsonGuardarNuevo')?>";

        $.ajax({
            url: url,
            data: dataProduct,
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
            // },
            // error: function() {
            //     var n = noty({
            //         type: "error",
            //         text: "Error al guardar, compruebe que los datos sean correctos",
            //         animation: {
            //             open: {
            //                 height: 'toggle'
            //             },
            //             close: {
            //                 height: 'toggle'
            //             },
            //             easing: 'swing',
            //             speed: 500 // opening & closing animation speed
            //         }
            //     });

            }
        });
    }

    function loadProducts() {

        var url = "<?php echo site_url('producto/ajaxGetProductos') ?>";

        $.ajax({
            url: url,
            dataType: "json",
            type: 'POST',
            success: function(json) {
                productData = json;
                var options = '<option value="-1">Selecciona un producto...</option>';
                for (var x = 0; x < json.length; x++) {
                    options += '<option value="' + json[x].idProducto
                    options += '" data-subtext="' + json[x].descripcion + '">' 
                    options += json[x].codigoExterno + '</option>';
                }

                $('#productos').html(options);
                $('#productos').selectpicker('refresh');
                // var n = noty({
                //     type: "success",
                //     text: json.message,
                //     animation: {
                //         open: {
                //             height: 'toggle'
                //         },
                //         close: {
                //             height: 'toggle'
                //         },
                //         easing: 'swing',
                //         speed: 500 // opening & closing animation speed
                //     }
                // });
            },
            error: function() {
                var n = noty({
                    type: "error",
                    text: "Error al cargar productos",
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

    function loadCategoria() {
        var url = "<?php echo site_url('producto/ajaxGetCategoria') ?>";

        $.ajax({
            url: url,
            dataType: "json",
            type: 'POST',
            success: function(json) {                
                var options = '';
                for (var x = 0; x < json.length; x++) {
                    options += '<option value="' + json[x].idCategoriaProducto;
                    options += '" data-subtext="' + json[x].idCategoriaProducto + '">';
                    options += json[x].nombre + '</option>';
                }

                $('#empresa').html(options);
                $('#empresa').selectpicker('refresh');                
            },
            error: function() {
                var n = noty({
                    type: "error",
                    text: "Error al cargar productos",
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
                        <a href="#" class="btn btn-minimize btn-round btn-default">
                        <i class="glyphicon glyphicon-chevron-up"></i></a>
                    </div>
                </div>
                <div class="box-content">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="codigoExterno">Codigo Externo</label>
                            <div class="input-group col-md-9">
                                <select name="productos" id="productos" 
                                    class="selectpicker" data-live-search="true" 
                                    data-style="btn-success">
                                </select>
                            </div>
                            <div class="input-group col-md-9">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-barcode blue"></i>
                            </span>
                                <input type="text" class="form-control" 
                                placeholder="codigoExterno" 
                                id="codigoExterno" required>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <label for="descripcion">Descripcion</label>
                            <div class="input-group col-md-12">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-list-alt blue"></i>
                            </span>
                                <input type="text" class="form-control" 
                                placeholder="descripcion" id="descripcion">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="cantidad">Cantidad</label>
                            <div class="input-group col-md-9">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-pencil blue"></i>
                            </span>
                                <input type="number" class="form-control" 
                                placeholder="cantidad" id="cantidad" />
                            </div>
                            <button href="#" type="button" id="stock" 
                            class="btn btn-success">Stock ?</button>
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
                                <input type="text" class="form-control" 
                                placeholder="Unidad de Venta" id="unidadVenta">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="numero">Numero de Unidades</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-pencil blue"></i>
                            </span>
                                <input type="number" class="form-control" 
                                placeholder="Numero de Unidades" id="numeroUnidades">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="precio unitario">Precio Unitario</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-usd blue"></i>
                            </span>
                                <input type="number" class="form-control" 
                                placeholder="Precio Unitario" id="precioUnitario">
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="precio unitario">Precio Venta</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                                <i class="glyphicon glyphicon-usd blue"></i>
                                </span>
                                <input type="number" class="form-control" 
                                placeholder="Precio de Venta" id="precioVenta">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="precio unitario">#Factura</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                                <i class="glyphicon glyphicon-list blue"></i>
                                </span>
                                <input type="number" class="form-control" 
                                placeholder="factura" id="factura">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="precio unitario">Valor Total</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                                <i class="glyphicon glyphicon-usd blue"></i>
                                </span>
                                <input type="number" class="form-control" 
                                placeholder="Valor Total" id="valorTotal">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <label for="Empresa">Empresa</label>
                        <div class="input-group col-md-3">
                                <select name="categoria" id="empresa" 
                                    class="selectpicker" data-live-search="true" 
                                    data-style="btn-warning">
                                </select>
                            </div>
                        </div>
                    </div>    
                    <!-- row 4-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="group">
                                <a onclick="addNewProductData()" 
                                title="Agregar nuevo producto" data-toggle="tooltip" 
                                class="btn btn-primary">
                                <i class="glyphicon glyphicon-ok-sign">
                                </i> Guardar</a>
                                <a href="<?= site_url('producto/newProduct')?>" 
                                title="Agregar nuevo producto" data-toggle="tooltip" 
                                class="btn btn-warning">
                                <i class="glyphicon glyphicon-remove-sign">
                                </i> Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>