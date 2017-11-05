<style>
/* Print styling */

@media print {

[class*="col-sm-"] {
	float: left;
}

[class*="col-xs-"] {
	float: left;
}

.col-sm-12, .col-xs-12 { 
	width:100% !important;
}

.col-sm-11, .col-xs-11 { 
	width:91.66666667% !important;
}

.col-sm-10, .col-xs-10 { 
	width:83.33333333% !important;
}

.col-sm-9, .col-xs-9 { 
	width:75% !important;
}

.col-sm-8, .col-xs-8 { 
	width:66.66666667% !important;
}

.col-sm-7, .col-xs-7 { 
	width:58.33333333% !important;
}

.col-sm-6, .col-xs-6 { 
	width:50% !important;
}

.col-sm-5, .col-xs-5 { 
	width:41.66666667% !important;
}

.col-sm-4, .col-xs-4 { 
	width:33.33333333% !important;
}

.col-sm-3, .col-xs-3 { 
	width:25% !important;
}

.col-sm-2, .col-xs-2 { 
	width:16.66666667% !important;
}

.col-sm-1, .col-xs-1 { 
	width:8.33333333% !important;
}
  
.col-sm-1,
.col-sm-2,
.col-sm-3,
.col-sm-4,
.col-sm-5,
.col-sm-6,
.col-sm-7,
.col-sm-8,
.col-sm-9,
.col-sm-10,
.col-sm-11,
.col-sm-12,
.col-xs-1,
.col-xs-2,
.col-xs-3,
.col-xs-4,
.col-xs-5,
.col-xs-6,
.col-xs-7,
.col-xs-8,
.col-xs-9,
.col-xs-10,
.col-xs-11,
.col-xs-12 {
float: left !important;
}

body {
	margin: 0;
	padding 0 !important;
	min-width: 768px;
}

.container {
	width: auto;
	min-width: 750px;
}

body {
	font-size: 10px;
}

a[href]:after {
	content: none;
}

.noprint, 
div.alert, 
header, 
.group-media, 
.btn, 
.footer, 
form, 
#comments, 
.nav, 
ul.links.list-inline,
ul.action-links {
	display:none !important;
}

}
</style>

<?php
// print_r($this->data->detalle)
?>
<div class="row" style="align-content: center">
    <div class="box col-xs-12">                    
        <div class="box-content">
            <div class="row">
                <div class="col-xs-12">
                    <h5 style="text-align: center">NOTA DE VENTA</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">                        
                    Cochabamba, <?php 
                    $date = new DateTime($this->data->pedido->fecha);
                        echo $date->format('d-m-Y');                    
                    ?> 
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-3">Pedido: <?=$this->data->pedido->tipo_pedido?></div>
            </div>            
        
            <div class="row">
                <div class="col-xs-7">                
                    Senor(es): <?=$this->data->cliente->razonSocial?>
                </div>
                <div class="col-xs-2">                
                    NIT/CI: <?=$this->data->cliente->nit?>
                </div>
                <div class="col-xs-3">                
                    Codigo: <?=$this->data->cliente->codigoCliente?>
                </div>
            </div>    
            <div class="row">
                <div class="col-xs-10">                                    
                    Direccion: <?=$this->data->cliente->direccion?>
                </div>
            </div>        
            <div class="row">
                <div class="col-xs-12">                                    
                    <table style="width:100%;text-align:center;text-size:10px;" border="1">
                        <thead>
                            <tr>
                                <th style="width:10%;text-align:center;">CODIGO</th>
                                <th style="width:10%;text-align:center;">CANTIDAD</th>
                                <th style="width:10%;text-align:center;">U/M</th>
                                <th style="width:50%;text-align:center;">CONCEPTO</th>
                                <th style="width:10%;text-align:center;">P/U</th>
                                <th style="width:10%;text-align:center;">TOTAL</th>            
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="height:250px;horizantal-align:center;vertical-align:top">
                                <?php
                                    $det = $this->data->detalle;
                                    echo '<td style="width:10%;text-align:center;">';
                                    foreach ($det as $d) {
                                        echo $d->codigoExterno.'<br>';
                                    }
                                    echo '</td>';
                                    echo '<td style="width:10%;text-align:right;">';
                                    foreach ($det as $d) {
                                        echo $d->cantidad.'<br>';
                                    }
                                    echo '</td>';
                                    echo '<td style="width:10%;text-align:center;">';
                                    foreach ($det as $d) {
                                        echo 'UN<br>';
                                    }
                                    echo '</td>';
                                    echo '<td style="width:10%;text-align:left;">';
                                    foreach ($det as $d) {
                                        echo $d->descripcion.'<br>';
                                    }
                                    echo '</td>';
                                    echo '<td style="width:10%;text-align:right;">';
                                    foreach ($det as $d) {
                                        echo number_format($d->precio, 2).'<br>';
                                    }
                                    echo '</td>';
                                    echo '<td style="width:10%;text-align:right;">';
                                    foreach ($det as $d) {
                                        echo number_format($d->precio* $d->cantidad, 2).'<br>';
                                    }
                                    echo '</td>';
                                
                                    ?>                                
                            </tr>
                        </tbody>
                    </table>              
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4"></div>
                <div class="col-xs-6"></div>
                <div class="col-xs-1">                
                    <b>Desc. Fin.:</b>
                </div>
                <div class="col-xs-1">                
                    <b><?=number_format($this->data->pedido->descuento, 2)?></b>
                </div>
                <div class="col-xs-4"></div>
                <div class="col-xs-6"></div>
                <div class="col-xs-1">                
                    <b>Total:</b>
                </div>
                <div class="col-xs-1">                
                    <b><?=number_format($this->data->pedido->total - $this->data->pedido->descuento, 2)?></b>
                </div>
            </div>            
            <div class="row">                
                <div class="col-xs-12">                    
                </div>
            </div>            
            <div class="row">                                
                <div class="col-xs-6">                
                    Son:    <?=$this->data->pedido->totalLiteral?>
                </div>
                <div class="col-xs-4">                                    
                </div>
                <div class="col-xs-2">                
                    Bolivianos                
                </div>
            </div>
            <div class="row">                
                <div class="col-xs-12">
                    <hr style="width:100%;height:1px;">    
                </div>
            </div> 
        </div>    
    </div>
</div>
