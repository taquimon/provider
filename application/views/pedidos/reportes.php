<script type="text/javascript" charset="utf-8">
    var detailTable;
    $(function() {
        $('#fechaReporte').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
</script>
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
                            <div class="input-group date" id="fechaReporte">
                                <input type="text" class="form-control" />
                                <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="cliente">Opciones</label>
                        <div class="input-group col-md-4">
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-user blue"></i>
                            </span>
                            <select id="clientes" class="selectpicker" data-live-search="true" data-style="btn-primary">
                                <option>Total Pedidos</option>
                                <option>Total Productos</option>
                            </select>
                            <a onclick="buscarReporte()" title="Buscar" data-toggle="tooltip" class="btn btn-primary">
                                <i class="glyphicon glyphicon-ok-sign"></i> Buscar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>