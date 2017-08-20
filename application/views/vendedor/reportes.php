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
        fillVendedor();        
    });

    function fillZonas() {
        var dataVendedor = {
            idVendedor: $("#vendedores").val()
        };
        $.ajax({            
            url: "<?=site_url('vendedor/ajaxGetZonasByVendedor')?>",
            dataType: "json",
            data: dataVendedor,
            type: 'GET',
            success: function(json) {
                var options = '';
                for (var x = 0; x < json.length; x++) {
                    options += '<option value="' + json[x].idZona + '">' + json[x].nombre + '</option>';
                }

                $('#zonas').html(options); 				
                $('#zonas').selectpicker('refresh');                

            },
            error: function() {
                //alert(options);
            }
        });
    }
    function fillVendedor() {
        $.ajax({
            url: "<?=site_url('vendedor/ajaxGetVendedores')?>",
            dataType: "json",
            type: 'GET',
            success: function(json) {
                var options = '';
                for (var x = 0; x < json.length; x++) {
                    options += '<option value="' + json[x].idVendedor + '">' + json[x].nombres + ' ' + json[x].apellidos + '</option>';
                }

                $('#vendedores').html(options); 				
                $('#vendedores').selectpicker('refresh');
                fillZonas();

            },
            error: function() {
                //alert(options);
            }
        });
    }
</script>
<?php echo form_open('vendedor/printReport');?>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-th"></i> Reportes</h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-md-3">
                        <label for="fecha">Seleccione Fecha:</label>
                        <div class="form-group date">
                            <div class="input-group" id="daterange">
                                <!--<input type="text" class="form-control" name="fechaReporte"/>-->
                                <input type="text" name="daterange" class="form-control"/>
                                <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="cliente">Opciones</label>
                        <div class="input-group col-md-4">
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-tasks blue"></i>
                            </span>
                            <select id="opciones" class="selectpicker" data-live-search="true" data-style="btn-primary" name="opcion">
                                <option value="pedido">Total Pedidos</option>
                                <option value="producto">Total Productos</option>
                            </select>
                        </div>
                    </div>
                     <div class="col-md-2">                   
                    
                        <label class="checkbox-inline" for="Vendedor">
                        <input type="checkbox" id="vendedor" value="Vendedor" name="vendedor">Vendedor
                        </label>
                        <div class="input-group col-md-4">
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-user blue"></i>
                            </span>
                            <select id="vendedores" class="selectpicker" data-live-search="true" data-style="btn-primary" name="vendedores" onchange="fillZonas();">
                            </select>                        

                        </div>        
                    </div>
                    <div class="col-md-2">                   
                    
                        <label class="checkbox-inline" for="Zona">
                        <input type="checkbox" id="zona" value="zona" name="zona">Zona
                        </label>
                        <div class="input-group col-md-4">
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-globe blue"></i>
                            </span>
                            <select id="zonas" class="selectpicker" data-live-search="true" data-style="btn-primary" name="zonas[]" multiple data-selected-text-format="count > 3">                                
                            </select>                        

                        </div>        
                    </div>
                    <div class="col-md-2">                   
                    
                        <label>Tipo Pedido</label>
                        <div class="input-group col-md-4">
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-usd green"></i>
                            </span>
                            <select id="tipoPedido" class="selectpicker" data-live-search="true" data-style="btn-success" name="tipoPedido">                                
                                <option value="TODOS" checked>TODOS</option>
                                <option value="CONTADO">CONTADO</option>
                                <option value="CREDITO">CREDITO</option>
                            </select>                        

                        </div>        
                    </div>
                    <div class="col-md-1">                   
                        
                        <label class="checkbox-inline" for="Zona">&nbsp</label>
                        <div class="input-group">
                            <button title="Buscar" type="submit" data-toggle="tooltip" class="btn btn-primary">
                            <i class="glyphicon glyphicon-ok-sign"></i> Buscar</button>                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close();?>