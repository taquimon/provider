<script>
    function addNewZonaData(){                
        var dataZona = {                        
                nombre: $("#nombre").val(),
                descripcion:    $("#descripcion").val(),                
            };
        
        var url = "<?=site_url('zona/jsonGuardarNuevo')?>";        
            
        $.ajax({
            url: url,
            data: dataZona,
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
        <a href="<?=site_url('zona')?>">Zonas</a>
    </li>
    <li>
        <a href="<?=site_url('zona/newZona')?>">Nueva Zona</a>
    </li>
</ul>
<form>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-th"></i> Nueva Zona</h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-md-4">
                        <label for="codigoExterno">Nombre</label>
                        <div class="input-group col-md-9">                            
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-map-marker blue"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="nombre" id="nombre" required>
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
                </div>        
                <!-- row 4-->                                
                <div class="row">
                    <div class="col-md-12">
                        <div class="group">                            
                            <a onclick="addNewZonaData()" title="Agregar Nueva Zona" data-toggle="tooltip" class="btn btn-primary">
                            <i class="glyphicon glyphicon-ok-sign"></i> Guardar</a>                                                                       
                            <a href="<?= site_url('zona/newZona')?>" title="Agregar Nueva Zona" data-toggle="tooltip" class="btn btn-warning">
                            <i class="glyphicon glyphicon-remove-sign"></i> Cancelar</a>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
</form>