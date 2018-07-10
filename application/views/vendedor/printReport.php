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
if($this->data->tipo == "pedido") {
?>
<div class="row" style="align-content: center">
    <div class="box col-xs-12">                    
        <div class="box-content">
            <div class="row">
                <div class="col-xs-12">
                    <h5 style="text-align: center">Ventas por Factura <br><br>
                    <?php
                        echo 'Del '. $this->data->startDate . ' al '. $this->data->endDate;
                    ?>
                    </h5>
                </div>
            </div>                    
            <div class="row">
                <div class="col-xs-12">                                    
                    <table style="width:100%;text-align:left;text-size:10px;" border="1">
                        <thead>
                            <tr>
                                <th style="width:10%;text-align:left;">No.</th>
                                <th style="width:20%;text-align:left;">Fecha</th>
                                <th style="width:10%;text-align:left;">Tipo de Pedido</th>
                                <th style="width:10%;text-align:left;">Zona</th>
                                <th style="width:10%;text-align:left;">Codigo Cliente</th>
                                <th style="width:30%;text-align:left;">Razon Social</th>
                                <?php 
                                if ($this->data->tipoPedido == 'CREDITO') {
                                    echo '<th style="width:5%;text-align:right;">A cuenta</th>';
                                    echo '<th style="width:5%;text-align:right;">Saldo</th>';
                                }
                                ?>
                                <th style="width:10%;text-align:right;">Total</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $list = $this->data->lista;
                            $totalGeneral = 0;
                            $totalClientes = 0;
                            $totalSaldo = 0;
                            $totalAcuenta = 0;
                            $array_clientes = array();
                            $totalCredito = 0;
                            foreach($list as $li) {                                
                                
                                if ($this->data->tipoPedido === 'CREDITO') {
                                    if (($li->cancelado === "NO" || $li->cancelado === null) && ($li->saldo != 0.0 || $li->saldo === null)) {
                                        echo '<tr style="horizantal-align:left;vertical-align:top">';
                                        echo '<td>'.$li->numPedido.'</td>';
                                        echo '<td>'.$li->fecha.'</td>';
                                        echo '<td>'.$li->tipo_pedido.'</td>';
                                        // echo '<td>'.$li->zona.'</td>';
                                        if (!isset($this->data->zonas[$li->zona])) {
                                            echo '<td>'.$li->zona.'</td>';
                                        } else {
                                            echo '<td>'.$this->data->zonas[$li->zona].'</td>';
                                        }
                                        echo '<td>'.$li->codigoCliente.'</td>';
                                        echo '<td>'.$li->razonSocial.'</td>';
                                        echo '<td style="width:5%;text-align:right;">'.$li->acuenta.'</td>';
                                        if($li->saldo === NULL) {
                                            $saldo = $li->total;
                                        } else {
                                            $saldo = $li->saldo;
                                        }
                                        echo '<td style="width:5%;text-align:right;">'.number_format($saldo, 2).'</td>';
                                        $totalSaldo += $saldo;
                                        $totalAcuenta += $li->acuenta;
                                
                                        echo '<td style="width:10%;text-align:right;">'.number_format($li->total, 2).'</td>';
                                        echo '</tr>';
                                        $totalCredito += $li->total;
                                    }
                                } else {
                                        echo '<tr style="horizantal-align:left;vertical-align:top">';
                                        echo '<td>'.$li->numPedido.'</td>';
                                        echo '<td>'.$li->fecha.'</td>';
                                        echo '<td>'.$li->tipo_pedido.'</td>';
                                        // echo '<td>'.$li->zona.'</td>';
                                        if (!isset($this->data->zonas[$li->zona])) {
                                            echo '<td>'.$li->zona.'</td>';
                                        } else {
                                            echo '<td>'.$this->data->zonas[$li->zona].'</td>';
                                        }
                                        echo '<td>'.$li->codigoCliente.'</td>';
                                        echo '<td>'.$li->razonSocial.'</td>';
                                        echo '<td style="width:10%;text-align:right;">'.number_format($li->total, 2).'</td>';
                                        echo '</tr>';
                                     }
                                    $totalGeneral += $li->total;
                                    // $totalSaldo += $li->saldo;
                                    // $totalAcuenta += $li->acuenta;
                                
                                    array_push($array_clientes, $li->idCliente);
                            }                            
                            if ($this->data->tipoPedido === 'CREDITO') {
                                $totalGeneral = $totalCredito;
                            }
                            $totalClientes = count(array_unique($array_clientes));
                            
                            ?>                                
                        </tbody>
                    </table>              
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4"></div>
                <div class="col-xs-5"></div>
                <div class="col-xs-2">                
                    <b>Totales:</b>
                </div>
                <div class="col-xs-1" style="text-align:right">                
                    <b><?=number_format($totalGeneral, 2)?></b>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4"></div>
                <div class="col-xs-5"></div>
                <div class="col-xs-2">                
                    <b>Total Acuenta:</b>
                </div>
                <div class="col-xs-1" style="text-align:right">                
                    <b><?=number_format($totalAcuenta, 2)?></b>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4"></div>
                <div class="col-xs-5"></div>
                <div class="col-xs-2">                
                    <b>Total Saldo:</b>
                </div>
                <div class="col-xs-1" style="text-align:right">                
                    <b><?=number_format($totalSaldo, 2)?></b>
                </div>
            </div>            
            <div class="row">                
                <div class="col-xs-12">                    
                </div>
            </div>            
            <div class="row">               
                <div class="col-xs-4"></div>
                <div class="col-xs-5"></div>                
                <div class="col-xs-2">                
                    <b>Total Clientes:</b>
                </div>
                <div class="col-xs-1" style="text-align:right">                
                    <b><?=$totalClientes?></b>
                </div>
            </div>
            <div class="row">                
                <div class="col-xs-4"></div>
                <div class="col-xs-5"></div>
                <div class="col-xs-2">                
                    <b>Total Facturas: </b>          
                </div>
                <div class="col-xs-1" style="text-align:right">                
                    <b><?=count($list)?></b>
                </div>
            </div> 
        </div>    
    </div>
</div>
<?php } //endif
    if($this->data->tipo == 'producto') {    
?>
<div class="row" style="align-content: center">
    <div class="box col-xs-12">                    
        <div class="box-content">
            <div class="row">
                <div class="col-xs-12">
                    <h5 style="text-align: center">Total Productos</h5>
                </div>
            </div>                 
            <div class="row">
                <div class="col-xs-12">
                    <h5 style="text-align: center"><b>Zonas:</b> 
                    <?php 
                    $zonas = $this->data->zonas;
                    if (isset($zonas)) {
                        echo (implode(',',$zonas). '-');
                    } else {
                        echo 'Todas - ';
                    }
                    echo ' <b>Del</b> '. $this->data->startDate . ' <b>al</b> '. $this->data->endDate;
                    ?>
                    
                    </h5>
                </div>
            </div>                    
            <div class="row">
                <div class="col-xs-12">                                    
                    <table style="width:100%;text-align:left;text-size:10px;" border="1">
                        <thead>
                            <tr>
                                <th style="width:10%;text-align:left;">Id Producto</th>
                                <th style="width:10%;text-align:left;">Fecha</th>
                                <th style="width:10%;text-align:left;">Codigo Producto</th>                                
                                <th style="width:50%;text-align:left;">Descripcion</th>                                
                                <th style="width:10%;text-align:right;">Total Vendido</th>                          
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $productos = $this->data->lista;
                            foreach($productos as $p){
                                echo '<tr style="horizantal-align:left;vertical-align:top">';                                
                                echo '<td>'.$p->idProducto.'</td>';
                                echo '<td>'.substr($p->fecha,0,10).'</td>';
                                echo '<td>'.$p->codigoExterno.'</td>';
                                echo '<td>'.$p->descripcion.'</td>';                                
                                echo '<td style="width:10%;text-align:right;"><b>'.$p->cantidad.'</b></td>';
                            }
                        ?>
                        </tbody>
                    <table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    } //endif
?>