<script>
    $(function() {
        delegado_table = $("#delegado_table").dataTable(
            {
                "bProcessing": true,
                "bJQueryUI": true                
            }
        );
    });
</script>
<?php
$delegados = $this->data;
?>
<h1>delegado</h1>
<a href="" id="new_delegado_button" style="color:orange">Agregar Nuevo</a>
<p>
<table id="delegado_table" class="table stripped hovered dataTable" style="width: 100%">
    <thead>
        <tr>
            <th style="width: 10%">Id</th>
            <th style="width: 20%">Nombre</th>
            <th style="width: 30%">Apellidos</th>
            <th style="width: 10%">Club</th>
            <th style="width: 20%">Titular</th>
            <th style="width: 10%">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($delegados as $delegado): ?>
         <tr>
            <td style="width: 10%"><?=$delegado->iddelegado?></td>
            <td style="width: 20%"><?=$delegado->name?></td>
            <td style="width: 30%"><?=$delegado->last?></td>            
             <th style="width: 10%"><?=$delegado->club?></th>
            <td style="width: 10%">Actions</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


