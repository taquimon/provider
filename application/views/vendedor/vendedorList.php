<script type="text/javascript" charset="utf-8">

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
                alert(options);
            }
        });
    }
	function editVendedor(idVendedor) {

        var dataVendedor = {
            idVendedor: idVendedor
        };
        $.ajax({
            url: "<?=site_url('vendedor/ajaxGetVendedorById')?>",
            dataType: "json",
            data: dataVendedor,
            type: 'GET',
            success: function(json) {
                var $content = $('<table></table>');                
                $content.append('<tr><td>Nombre(s):</td><td style="width:70%";><input type="text" class="form-control" placeholder="nombres" id="nombres" value="'+json.nombres+'"></td></tr>');
                $content.append('<tr><td>Apellidos:</td><td><input type="text" class="form-control" placeholder="apellidos" id="apellidos" value="'+json.apellidos+'"></td></tr>');
                $content.append('<tr><td>Direccion:</td><td><input type="text" class="form-control" placeholder="direccion" id="direccion" value="'+json.direccion+'"></td></tr>');
                $content.append('<tr><td>Email:</td><td><input type="text" class="form-control" placeholder="Emal" id="email" value="'+json.email+'"></td></tr>');
                $content.append('<tr><td>Telefono:</td><td><input type="text" class="form-control" placeholder="Telefono" id="telefono" value="'+json.telefono+'"></td></tr>');
				$content.append('<tr><td>Celular:</td><td><input type="text" class="form-control" placeholder="Celular" id="celular" value="'+json.celular+'"></td></tr>');				
 				$content.append('<tr><td>Observaciones:</td><td><input type="text" class="form-control" placeholder="Observaciones" id="observaciones" value="'+json.observaciones+'"></td></tr>');
				$content.append('<tr><td>Zonas:</td><td><select id="zonas" data-style="btn-primary" multiple></select></td></tr>');
				var idVendedor = json.idVendedor;

                BootstrapDialog.show({
                    title: 'Editar Vendedor',
                    message: $content,
					onshown: function(dialogRef){
						fillZonas();
					},
                    buttons: [{
                        icon: 'glyphicon glyphicon-check',
                        label: 'Aceptar',
                        title: 'Aceptar',
                        autospin: true,
                        cssClass: 'btn-primary',
                        action: function(dialogRef){
                            var dataProduct = {
                                idVendedor: idVendedor,
                                nombres: $('#nombres').val(),
                                apellidos: $('#apellidos').val(),
                                direccion: $('#direccion').val(),
                                email: $('#email').val(),
                                telefono: $('#telefono').val(),
 								celular: $('#celular').val(),
 								zonas: $('#zonas').val(),              
 								observaciones: $('#observaciones').val(), 								
                            }
                            $.ajax({
                                url: "<?=site_url('vendedor/jsonGuardarVendedor')?>",
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
                                    var urln = "<?= site_url('vendedor/ajaxListVendedor')?>";
                                    $("#vendedor_table").DataTable().ajax.url(urln);
                                    $("#vendedor_table").DataTable().ajax.reload();
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

    /*Delete vendedor*/
    function deleteVendedor(idVendedor) {
        var $content = $('<div></div>');
        $content.append('Desea Eliminar el Vendedor?');

        BootstrapDialog.show({
            title: 'Eliminar Vendedor',
            message: $content,
            buttons: [{
                icon: 'glyphicon glyphicon-check',
                label: 'Si',
                title: 'Si',
                cssClass: 'btn-primary',
                action: function(dialogRef){
                    var datavendedor = {
                        idVendedor: idVendedor
                    }
                    $.ajax({
                        url: "<?=site_url('vendedor/jsonEliminarVendedor')?>",
                        dataType: "json",
                        data: datavendedor,
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
                            var urln = "<?= site_url('vendedor/ajaxListVendedor')?>";
                            $("#vendedor_table").DataTable().ajax.url(urln);
                            $("#vendedor_table").DataTable().ajax.reload();
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
    $("#vendedor_table").DataTable({
            destroy: true,
            info: false,
            responsive: true,
            ajax: {
                url: "<?= site_url('vendedor/ajaxListVendedor')?>",
                dataSrc: 'data',
            },
            "columns": [
                { "data": "idvendedor", sDefaultContent: "" },				
                { "data": "nombres" , sDefaultContent: ""},
                { "data": "apellidos" , sDefaultContent: ""},
                { "data": "direccion" , sDefaultContent: ""},
                { "data": "email" , sDefaultContent: ""},
                { "data": "telefono" , sDefaultContent: ""},
                { "data": "celular" , sDefaultContent: ""},                
                { "data": "observaciones" , sDefaultContent: ""},
                { "data": "zona" , sDefaultContent: ""},                
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
        <a href="#">Vendedores</a>
    </li>
</ul>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-th"></i> Lista de vendedors</h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
                <div style="text-align:right;">
                <a href="<?= site_url('vendedor/newVendedor')?>" title="Agregar Nuevo vendedor" data-toggle="tooltip" class="btn btn-round">
                <i class="glyphicon glyphicon-plus"></i> Nuevo vendedor</a>
                </div>
            </div>
            <div class="box-content">

                <table id="vendedor_table" class="table table-striped table-bordered bootstrap-datatable datatable responsive" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Idvendedor</th>							
                            <th>Nombre(s)</th>
                            <th>Apellido(s)</th>
                            <th>Direccion</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Celular</th>                            
                            <th>Observaciones</th>
                            <th>Zonas</th>                            
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