<script type="text/javascript" charset="utf-8">
    var detailTable;
    
    $(function() {
        // var start = moment().subtract(29, 'days');
        var start = moment().subtract(5, 'days');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        $('input[name="daterange"]').daterangepicker({
            startDate: start,
            endDate: end,            
            locale: {
                format: 'YYYY-MM-DD'
            }
        });        
        loadProducts();
        loadCategoria();        
        $("#buscar").on("click",function(){
            console.log("Buscando...");
            var url = "<?php echo site_url('producto/ajaxGetCompraVenta') ?>";
            var dataCV = {            
            fecha: $('#daterange').val(),            
            productos: $("#productos").val(), 
        };

            $.ajax({
                url: url,
                dataType: "json",
                data: dataCV,
                type: 'POST',
                success: function(json) {
                   console.log("Getting data...");
                },
                error: function() {
                    var n = noty({
                        type: "error",
                        text: "Error al cargar datos",
                        animation: {
                            open: {
                                height: 'toggle'
                            },
                            close: {
                                height: 'toggle'
                            },
                            easing: 'swing',
                            speed: 500 
                        }
                    });

                }
            });
        });
    });
    
    function loadProducts() {
    var url = "<?php echo site_url('producto/ajaxGetProductos') ?>";

    $.ajax({
        url: url,
        dataType: "json",
        type: 'POST',
        success: function(json) {
            productData = json;
            var options = '';
            for (var x = 0; x < json.length; x++) {
                options += '<option value="' + json[x].idProducto
                options += '" data-subtext="' + json[x].descripcion + '">' 
                options += json[x].codigoExterno + '</option>';
            }

            $('#productos').html(options);
            $('#productos').selectpicker('refresh');            
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
                productData = json;
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
<?php echo form_open('producto/compraVentaReport');?>
<h1>Ingresos</h1>
<div class="box-content">
                <div class="row">
                    <div class="col-md-3">
                        <label for="fecha">Seleccione Fecha:</label>
                        <div class="form-group date">
                            <div class="input-group">                               
                                <input type="text" id="daterange" name="daterange" class="form-control"/>
                                <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="cliente">Empresa</label>
                        <div class="input-group col-md-3">
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-tasks blue"></i>
                            </span>
                            <select id="empresa" class="selectpicker" 
                            data-live-search="true" name="empresa" 
                            data-selected-text-format="count > 3" 
                            multiple data-style="btn-warning">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="cliente">Producto</label>
                        <div class="input-group col-md-3">
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-tasks blue"></i>
                            </span>
                            <select id="productos" class="selectpicker" 
                            data-live-search="true" name="productos[]" 
                            data-selected-text-format="count > 3" 
                            multiple data-style="btn-warning" 
                            data-actions-box="true"
                            title="Todos los productos...">
                            </select>
                        </div>
                    </div>     
                    <div class="col-md-3">                                           
                        <label for="Buscar">Buscar</label>
                        <div class="input-group col-md-9">
                        <button title="Buscar" type="submit" data-toggle="tooltip" 
                           class="btn btn-primary" id="buscar">
                        <i class="glyphicon glyphicon-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
<?php echo form_close();?>