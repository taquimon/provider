<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        // $('#product_table').DataTable( {
        //     paging: false
        // } );
        $("#product_table").DataTable({
            destroy: true,
            info: false,
            responsive: true,
            ajax: {
                url: "<?= site_url('producto/ajaxListProduct')?>",
                dataSrc: 'data',
                // ajax: {
                //     url: "<?= site_url('producto/ajaxListProduct')?>",
                //     type: "POST",
                //     data: function(d) {
                //     //     d.producto = clubid;
                //         alert("d");
                //     }
            },
            "columns": [
                { "data": "numPedido", sDefaultContent: ""}, 
                { "data": "fecha", sDefaultContent: ""}, 
                { "data": "cliente", sDefaultContent: ""},
                { "data": "productos", sDefaultContent: ""}, 
                { "data": "status", sDefaultContent: ""}, 
                { "data": "options", sDefaultContent: ""}, 
               
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
        <a href="#">Pedidos</a>
    </li>
</ul>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-th"></i> Lista de Pedidos</h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
                <div style="text-align:right;">
                    <a href="<?= site_url('producto/newProduct')?>" title="Agregar nuevo producto" data-toggle="tooltip" class="btn btn-round">
                        <i class="glyphicon glyphicon-plus"></i> Nuevo Producto</a>
                </div>
            </div>
            <div class="box-content">

                <table id="product_table" class="table table-striped table-bordered bootstrap-datatable datatable responsive" style="width:100%;">
                    <thead>
                        <tr>
                            <th>numPedido</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Detalles</th>
                            <th>Estado</th>
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