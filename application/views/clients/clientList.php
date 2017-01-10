<script type="text/javascript" charset="utf-8">
    
    
    $(document).ready(function(){    
    $("#client_table").DataTable({            
            destroy: true,
            info: false,  
            responsive: true,                                                                        
            ajax: {
                url: "<?= site_url('cliente/ajaxListClient')?>",
                dataSrc: 'data',               
            },            
            "columns": [ 
                { "data": "idCliente", sDefaultContent: "n/a" },
                { "data": "nombres" , sDefaultContent: ""},
                { "data": "apellidos" , sDefaultContent: ""},
                { "data": "direccion" , sDefaultContent: ""},
                { "data": "email" , sDefaultContent: ""},                               
                { "data": "telefono" , sDefaultContent: ""},
                { "data": "celular" , sDefaultContent: ""},
                { "data": "razonSocial" , sDefaultContent: ""}, 
                { "data": "observaciones" , sDefaultContent: ""},
                { "data": "zona" , sDefaultContent: ""},                                
                { "data": "tipoCliente" , sDefaultContent: ""},                
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
                <a href="<?= site_url('cliente/newClient')?>" title="Agregar Nuevo Cliente" data-toggle="tooltip" class="btn btn-round">
                <i class="glyphicon glyphicon-plus"></i> Nuevo Cliente</a>    
                </div>                
            </div>
            <div class="box-content">
                
                <table id="client_table" class="table table-striped table-bordered bootstrap-datatable datatable responsive" style="width:100%;">
                    <thead>
                        <tr>
                            <th>IdCliente</th>
                            <th>Nombre(s)</th>
                            <th>Apellido(s)</th>
                            <th>Direccion</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Celular</th>
                            <th>Razon Social</th>
                            <th>Observaciones</th>                        
                            <th>Zona</th>                        
                            <th>Tipo Cliente</th>                            
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>