<script type="text/javascript" charset="utf-8">
    var detailTable;
    
    $(function() {
        $('#fechaReporte').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        fillZonas();
    });

    function fillZonas() {
        $.ajax({
            url: "<?=site_url('zona/ajaxGetZonas')?>",
            dataType: "json",
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
</script>
<?php echo form_open('pedido/printReport');?>
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
                    <div class="col-md-2">
                        <label for="fecha">Seleccione Fecha:</label>
                        <div class="form-group">
                            <div class="input-group date" id="fechaReporte" >
                                <input type="text" class="form-control" name="fechaReporte"/>
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
                            <i class="glyphicon glyphicon-user blue"></i>
                            </span>
                            <select id="opciones" class="selectpicker" data-live-search="true" data-style="btn-primary" name="opcion">
                                <option value="pedido">Total Pedidos</option>
                                <option value="producto">Total Productos</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">                                       
                        <label class="checkbox-inline">
                        <input type="checkbox" id="zona" value="zona" name="zona"/>Zona</label>
                        <div class="input-group col-md-4">
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-globe blue"></i>
                            </span>
                            <select id="zonas" class="selectpicker" data-live-search="true" data-style="btn-primary" name="zonas[]" multiple data-selected-text-format="count > 3">                                
                            </select>                                                   
                        </div>        
                    </div>
                    <div class="col-md-2">
                        <label class="button">&nbsp;</label>
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