<script type="text/javascript" charset="utf-8">
    fillTipoCliente();
    fillZonas();
    function addNewClientData() {
        var dataClient = {
            codigoCliente: $("#codigoCliente").val(),
            nombres: $("#nombres").val(),
            apellidos: $("#apellidos").val(),
            email: $("#email").val(),
            direccion: $("#direccion").val(),
            telefono: $("#telefono").val(),
            celular: $("#celular").val(),
            razonSocial: $("#razonSocial").val(),
            observaciones: $("#observaciones").val(),
            zona: $("#zona").val(),
            tipoCliente: $("#tipoCliente").val(),
            nit: $("#nit").val(),
        };

        var url = "<?=site_url('cliente/jsonGuardarNuevo')?>";

        $.ajax({
            url: url,
            data: dataClient,
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

    function fillTipoCliente() {
        $.ajax({
            url: "<?=site_url('cliente/ajaxGetTipoClientes')?>",
            dataType: "json",
            type: 'GET',
            success: function(json) {
                var options = '';
                for (var x = 0; x < json.length; x++) {
                    options += '<option value="' + json[x].tipoCliente + '">' + json[x].descripcion + '</option>';
                }

                $('#tipoCliente').html(options);
                $('#tipoCliente').selectpicker('refresh');
            },
            error: function() {
                alert(options);
            }
        });
    }
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

                $('#zona').html(options);
                $('#zona').selectpicker('refresh');
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
        <a href="<?=site_url('cliente')?>">Clientes</a>
    </li>
    <li>
        <a href="<?=site_url('cliente/newClient')?>">Nuevo Cliente</a>
    </li>
</ul>
<form>
    <div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-th"></i> Nuevo cliente</h2>
                    <div class="box-icon">
                        <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                    </div>
                </div>
                <div class="box-content">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="codigoCliente">Codigo Cliente</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-barcode blue"></i>
                            </span>
                                <input type="text" class="form-control" placeholder="codigoCliente" id="codigoCliente" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="nombre">Nombre(s)</label>
                            <div class="input-group col-md-9">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-user blue"></i>
                            </span>
                                <input type="text" class="form-control" placeholder="nombre" id="nombres">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="apellidos">Apellidos</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-user blue"></i>
                            </span>
                                <input type="text" class="form-control" placeholder="apellidos" id="apellidos">
                            </div>
                        </div>
                    </div>
                    <!-- row 2-->
                    <div class="row">
                        <div class="col-md-4">
                            <label for="direccion">Direccion</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-pencil blue"></i>
                            </span>
                                <input type="text" class="form-control" placeholder="direccion" id="direccion">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="email">email</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-envelope blue"></i>
                            </span>
                                <input type="text" class="form-control" placeholder="email" id="email">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="Telefono">Telefono</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-phone-alt blue"></i>
                            </span>
                                <input type="text" class="form-control" placeholder="Telefono" id="telefono">
                            </div>
                        </div>
                    </div>
                    <!-- row 3-->
                    <div class="row">
                        <div class="col-md-4">
                            <label for="Celular">Celular</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-phone blue"></i>
                            </span>
                                <input type="text" class="form-control" placeholder="Celular" id="celular">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="Razon Social">Razon Social</label>
                            <div class="input-group col-md-9">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-certificate blue"></i>
                            </span>
                                <input type="text" class="form-control" placeholder="Razon Social" id="razonSocial">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="Observaciones">Observaciones</label>
                            <div class="input-group col-md-9">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-pencil blue"></i>
                            </span>
                                <input type="text" class="form-control" placeholder="Observaciones" id="observaciones">
                            </div>
                        </div>
                    </div>
                    <!-- row 4-->
                    <div class="row">
                        <div class="col-md-4">
                            <label for="Zona">Zona</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-globe blue"></i>
                            </span>                               
                                <select id="zona" class="selectpicker form-control" data-live-search="true" data-style="btn-primary">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="Tipo Cliente">Tipo Cliente</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                                <i class="glyphicon glyphicon-tasks blue"></i>
                                </span>                                
                                    <select id="tipoCliente" class="selectpicker form-control" data-live-search="true" data-style="btn-primary">
                                    </select>                                
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="nit">NIT/CI</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-addon">
                            <i class="glyphicon glyphicon-pencil blue"></i>
                            </span>
                                <input type="text" class="form-control" placeholder="nit" id="nit">
                            </div>
                        </div>
                    </div>
                    <!-- row 4-->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="Tipo Cliente"></label>
                            <div class="group">
                                <a onclick="addNewClientData()" title="Agregar Nuevo Cliente" data-toggle="tooltip" class="btn btn-primary">
                                    <i class="glyphicon glyphicon-ok-sign"></i> Guardar</a>
                                <a href="<?= site_url('cliente/newclient')?>" title="Agregar Nuevo Cliente" data-toggle="tooltip" class="btn btn-warning">
                                    <i class="glyphicon glyphicon-remove-sign"></i> Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>