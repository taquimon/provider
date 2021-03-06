<style>
    .login-dialog .modal-dialog {
        width: 1200px;
    }
</style>
<script type="text/javascript" charset="utf-8">

	function fillTipoCliente(idTipoCliente) {
        $.ajax({
            url: "<?=site_url('cliente/ajaxGetTipoClientes'); ?>",
            dataType: "json",
            type: 'GET',
            success: function(json) {
                var options = '';
                for (var x = 0; x < json.length; x++) {
                    options += '<option value="' + json[x].tipoCliente + '">' + json[x].descripcion + '</option>';
                }

                $('#tipoCliente').html(options);
 				$('#tipoCliente').val(idTipoCliente);
                $('#tipoCliente').selectpicker('refresh');

            },
            error: function() {
                alert(options);
            }
        });
    }
    function fillZonas(idZona) {
        $.ajax({
            url: "<?=site_url('zona/ajaxGetZonas'); ?>",
            dataType: "json",
            type: 'GET',
            success: function(json) {
                var options = '';
                for (var x = 0; x < json.length; x++) {
                    options += '<option value="' + json[x].idZona + '">' + json[x].nombre + '</option>';
                }

                $('#zona').html(options);
                $('#zona').val(idZona);
                $('#zona').selectpicker('refresh');
            },
            error: function() {
                alert(options);
            }
        });
    }
	function editClient(idClient) {

        var dataProduct = {
            idCliente: idClient
        };
        $.ajax({
            url: "<?=site_url('cliente/ajaxGetClienteById'); ?>",
            dataType: "json",
            data: dataProduct,
            type: 'GET',
            success: function(json) {
                var checked = "";
                if(json.activo == "1") {
                    checked = "checked";
                }
                // var $content = $('<table></table>');
                // $content.append('<tr><td>Codigo Cliente:</td><td><input type="text" class="form-control" placeholder="codigocliente" id="codigoCliente" value="'+json.codigoCliente+'"></td></tr>');
                // $content.append('<tr><td>Nombre(s):</td><td style="width:70%";><input type="text" class="form-control" placeholder="nombres" id="nombres" value="'+json.nombres+'"></td></tr>');
                // $content.append('<tr><td>Apellidos:</td><td><input type="text" class="form-control" placeholder="apellidos" id="apellidos" value="'+json.apellidos+'"></td></tr>');
                // $content.append('<tr><td>Direccion:</td><td><input type="text" class="form-control" placeholder="direccion" id="direccion" value="'+json.direccion+'"></td></tr>');
                // $content.append('<tr><td>Email:</td><td><input type="text" class="form-control" placeholder="Emal" id="email" value="'+json.email+'"></td></tr>');
                // $content.append('<tr><td>Telefono:</td><td><input type="text" class="form-control" placeholder="Telefono" id="telefono" value="'+json.telefono+'"></td></tr>');
				// $content.append('<tr><td>Celular:</td><td><input type="text" class="form-control" placeholder="Celular" id="celular" value="'+json.celular+'"></td></tr>');
                // $content.append('<tr><td>NIT/CI:</td><td><input type="text" class="form-control" placeholder="nit" id="nit" value="'+json.nit+'"></td></tr>');
				// $content.append('<tr><td>Zona:</td><td><select id="zona" data-style="btn-primary"></select></td></tr>');
                // $content.append('<tr><td>Razon Social:</td><td><input type="text" class="form-control" placeholder="Razon Social" id="razonSocial" value="'+json.razonSocial+'"></td></tr>');
 				// $content.append('<tr><td>Observaciones:</td><td><input type="text" class="form-control" placeholder="Observaciones" id="observaciones" value="'+json.observaciones+'"></td></tr>');
                // $content.append('<tr><td>Tipo Cliente:</td><td><select id="tipoCliente" data-style="btn-primary"></select></td></tr>');
                var $content = $('<div class="row"></div>');
                $content.append('<div class="col-sm-6"><label for="codigoCliente">Codigo Cliente</label><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-lock blue"></i></span><input type="text" class="form-control" placeholder="codigocliente" id="codigoCliente" value="'+json.codigoCliente+'"></div></div>');
                $content.append('<div class="col-sm-6"><label for="email">Email</label><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-envelope blue"></i></span><input type="text" class="form-control" placeholder="Emal" id="email" value="'+json.email+'"></div></div>');
                $content.append('<div class="row">');
                $content.append('<div class="col-sm-6"><label for="nombres">Nombre(s)</label><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-user blue"></i></span><input type="text" class="form-control" placeholder="nombres" id="nombres" value="'+json.nombres+'"></div></div>');
                $content.append('<div class="col-sm-6"><label for="apellidos">Apellidos</label><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-user blue"></i></span><input type="text" class="form-control" placeholder="apellidos" id="apellidos" value="'+json.apellidos+'"></div></div>');
                $content.append('<div class="row">');
                $content.append('<div class="col-sm-6"><label for="telefono">Telefono</label><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-earphone blue"></i></span><input type="text" class="form-control" placeholder="Telefono" id="telefono" value="'+json.telefono+'"></div></div>');
                $content.append('<div class="col-sm-6"><label for="celular">Celular</label><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-phone blue"></i></span><input type="text" class="form-control" placeholder="Celular" id="celular" value="'+json.celular+'"></div></div>');
                $content.append('<div class="row">');
                $content.append('<div class="col-sm-6"><label for="direccion">Direccion</label><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-hand-right blue"></i></span><input type="text" class="form-control" placeholder="direccion" id="direccion" value="'+json.direccion+'"></div></div>');
                $content.append('<div class="col-sm-6"><label for="nit">NIT/CI</label><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-list-alt blue"></i></span><input type="text" class="form-control" placeholder="nit" id="nit" value="'+json.nit+'"></div></div>');                
                $content.append('<div class="row">');
                $content.append('<div class="col-sm-6"><label for="zona">Zona</label><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-globe blue"></i></span><select id="zona" data-style="btn-primary"></select></div></div>');
                $content.append('<div class="col-sm-6"><label for="tipoCliente">Tipo Cliente</label><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-shopping-cart blue"></i></span><select id="tipoCliente" data-style="btn-primary"></select></div></div>');                
                $content.append('<div class="row">');                
                $content.append('<div class="col-sm-6"><label for="razonSocial">Razon Social</label><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-user blue"></i></span><input type="text" class="form-control" placeholder="Razon Social" id="razonSocial" value="'+json.razonSocial+'"></div></div>');
                $content.append('<div class="col-sm-6"><label for="observaciones">Observaciones</label><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-eye-open blue"></i></span><input type="text" class="form-control" placeholder="Observaciones" id="observaciones" value="'+json.observaciones+'"></div></div>');                
                $content.append('<div class="row">');
                $content.append('<div class="col-sm-6"><label for="activo">Estado</label><div class="checkbox"><label class="checkbox-inline"><input type="checkbox" id="activo" ' + checked + ' data-toggle="toggle" data-on="ACTIVO" data-off="INACTIVO" data-onstyle="success" data-offstyle="danger"></label></div></div></div>');                                
                $content.append('</div>');
                var idTipoCliente = json.tipoCliente;                
                var idZona = json.zona;

                BootstrapDialog.show({
                    title: 'Editar Cliente',
                    message: $content,
					onshown: function(dialogRef){
						fillTipoCliente(idTipoCliente);
                        fillZonas(idZona);
                        $('#activo').bootstrapToggle();
					},
                    buttons: [{
                        icon: 'glyphicon glyphicon-check',
                        label: 'Aceptar',
                        title: 'Aceptar',
                        autospin: true,
                        cssClass: 'btn-primary',
                        action: function(dialogRef){
                            var activo = $('#activo').prop('checked') == true ? '1' : '0';
                            var dataProduct = {
                                idCliente: idClient,
                                codigoCliente: $('#codigoCliente').val(),
                                nombres: $('#nombres').val(),
                                apellidos: $('#apellidos').val(),
                                direccion: $('#direccion').val(),
                                email: $('#email').val(),
                                telefono: $('#telefono').val(),
 								celular: $('#celular').val(),
                                nit: $('#nit').val(),
 								zona: $('#zona').val(),
                                razonSocial: $('#razonSocial').val(),
 								observaciones: $('#observaciones').val(),
                                tipoCliente: $('#tipoCliente').val(),
                                activo: activo, 
                            }
                            $.ajax({
                                url: "<?=site_url('cliente/jsonGuardarCliente'); ?>",
                                dataType: "json",
                                data: dataProduct,
                                type: 'POST',   
                                success: function(json) {
                                    var n = noty({
                                        type: "success",
                                        layout: "top",
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
                                    var urln = "<?= site_url('cliente/ajaxListClient'); ?>";
                                    $("#client_table").DataTable().ajax.url(urln);
                                    $("#client_table").DataTable().ajax.reload();
                                    setTimeout(function(){
                                        dialogRef.close();
                                    }, 3000);
                                }
                            });
                        }
                    }, {
                        icon: 'glyphicon glyphicon-ban-circle',
                        label: 'Cancelar',
                        title: 'Cancelar',
                        cssClass: 'btn-warning',
                        action: function(dialogItself){
                            dialogItself.close();
                        }
                    }]
                });
            },
            error: function() {
            }
        });
     }

    /*Delete Cliente*/
    function deleteCliente(idCliente) {
        var $content = $('<div></div>');
        $content.append('Desea Eliminar el Cliente?');

        BootstrapDialog.show({
            title: 'Eliminar Cliente',
            message: $content,
            buttons: [{
                icon: 'glyphicon glyphicon-check',
                label: 'Si',
                title: 'Si',
                cssClass: 'btn-primary',
                action: function(dialogRef){
                    var dataCliente = {
                        idCliente: idCliente
                    }
                    $.ajax({
                        url: "<?=site_url('cliente/jsonEliminarCliente'); ?>",
                        dataType: "json",
                        data: dataCliente,
                        type: 'POST',
                        success: function(json) {
                            var n = noty({
                                type: "success",
                                layout: "top",
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
                            var urln = "<?= site_url('cliente/ajaxListClient'); ?>";
                            $("#client_table").DataTable().ajax.url(urln);
                            $("#client_table").DataTable().ajax.reload();
                            setTimeout(function(){
                                dialogRef.close();
                            }, 3000);
                        }
                    });
                }
            }, {
                icon: 'glyphicon glyphicon-ban-circle',
                label: 'No',
                title: 'No',
                cssClass: 'btn-warning',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
    }
    $(document).ready(function(){
    $("#client_table").DataTable({
            destroy: true,
            info: false,
            responsive: true,
            ajax: {
                url: "<?= site_url('cliente/ajaxListClient'); ?>",
                dataSrc: 'data',
            },
            "columns": [
                { "data": "idCliente", sDefaultContent: "" },
				{ "data": "codigoCliente", sDefaultContent: "" },
                { "data": "nombres" , sDefaultContent: ""},
                { "data": "apellidos" , sDefaultContent: ""},
                { "data": "direccion" , sDefaultContent: ""},
                { "data": "email" , sDefaultContent: ""},
                { "data": "telefono" , sDefaultContent: ""},
                { "data": "celular" , sDefaultContent: ""},
                { "data": "nit" , sDefaultContent: ""},
                { "data": "razonSocial" , sDefaultContent: ""},
                { "data": "observaciones" , sDefaultContent: ""},
                { "data": "zona" , sDefaultContent: ""},
                { "data": "tipoCliente" , sDefaultContent: ""},
				{ "data": "options" , sDefaultContent: ""},
            ],
            paging: true,
        });

      });
</script>
<ul class="breadcrumb">
    <li>
        <a href="#">Inicio</a>
    </li>
    <li>
        <a href="#">Clientes</a>
    </li>
</ul>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-th"></i> Lista de clientes</h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
                <div style="text-align:right;">
                <a href="<?= site_url('cliente/newClient'); ?>" title="Agregar Nuevo Cliente" data-toggle="tooltip" class="btn btn-round">
                <i class="glyphicon glyphicon-plus"></i> Nuevo Cliente</a>
                </div>
            </div>
            <div class="box-content">

                <table id="client_table" class="table table-striped table-bordered bootstrap-datatable datatable responsive" style="width:100%;">
                    <thead>
                        <tr>
                            <th>IdCliente</th>
							<th>Codigo Cliente</th>
                            <th>Nombre(s)</th>
                            <th>Apellido(s)</th>
                            <th>Direccion</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Celular</th>
                            <th>NIT/CI</th>
                            <th>Razon Social</th>
                            <th>Observaciones</th>
                            <th>Zona</th>
                            <th>Tipo Cliente</th>
							<th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>