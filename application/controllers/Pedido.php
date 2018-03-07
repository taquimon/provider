<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Client
 *
 * @author phantom
 */
class Pedido extends MY_Controller {
    
   public function __construct() {
       parent::__construct();
        
       $this->load->model('order_model', 'orderModel');       
       $this->load->model('client_model', 'clientModel');
       $this->load->model('product_model', 'productModel');
       $this->load->model('zona_model', 'zonaModel');

   }
   public function index() {
        $orders = $this->orderModel->getOrderList();        
        $this->data = $orders;   
        $this->middle = 'pedidos/pedidoList'; 
        $this->layout();
   }

   public function ajaxListOrder(){        

        $orders = $this->orderModel->getOrderList();

        foreach ($orders as $order) {
            $id = $order->numPedido;
            $link = "pedido/factura/".$id;
            $order->nombres =  $order->nombres . " ". $order->apellidos;
            $order->options = '<a href="#" onclick="editPedido('.$id.')" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit glyphicon-white"></i> Editar</a>&nbsp;'.
            '<a href="'.$link.'" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-list-alt glyphicon-white"></i> Factura</a>&nbsp;'.
            '<a href="#" onclick="deletePedido('.$id.')" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove-sign glyphicon-white"></i> Borrar</a>';
        }

        $data['data'] = $orders;
                
        echo json_encode($data);
    }
    public function ajaxGetPedidoById() {
        if (isset($this->request['idPedido'])) {
            $idPedido = $this->request['idPedido'];
        }
        $pedido = $this->orderModel->getOrderById($idPedido);
        $pedido->detalle = $this->orderModel->getDetailById($idPedido);
        $pedido->credito = $this->orderModel->getCreditoById($idPedido);

        echo json_encode($pedido);
    }
    public function newOrder() {
        $this->middle = 'pedidos/newOrder'; 
        $this->layout();   
    }
    public function reportes() {
        $this->middle = 'pedidos/reportes'; 
        $this->layout();   
    }

    public function jsonGuardarNuevo()
    {
        $result = new stdClass();
        try{            
            
            //$data['numPedido']      = $this->request['numPedido'];
            $data['idCliente']      = $this->request['idCliente'];            
            $data['fecha']          = $this->request['fecha'];
            $data['idUser']         = $this->request['idUser'];
            $data['fecha']          = $this->request['fecha'];            
            $data['tipo_pedido']    = $this->request['tipo_pedido'];
            $data['descuento']      = $this->request['descuento'];
            $data['idVendedor']     = $this->request['idVendedor'];
            $dataDetalle = $this->request['detalle'];
            $pedidoData = $this->orderModel->insert($data);                        
            
            if ($pedidoData) {
                $result->message = "Se agrego correctamente el pedido";
            }
            
            $arrayDetails = json_decode($dataDetalle);
            $productos  = $this->request['productos'];
            $x=1;
            $dataDetails = array();
            foreach ($productos as $p) {
                $dataArray = array();
                $dataArray['idPedido'] = $pedidoData;
                $dataArray['idProducto'] = $p;
                $dataArray['cantidad'] = $this->getArrayValue($arrayDetails, "cantidad".$p);
                $dataArray['precio'] = $this->getArrayValue($arrayDetails, "precioUnitario".$p);
                $dataArray['descuento'] = $this->getArrayValue($arrayDetails, "descuento".$p);
                $dataArray['fechaCreacion'] = date('Y-m-d H:i:s');
                array_push($dataDetails, $dataArray);
                $this->productModel->updateProductQuantity($dataArray);
            }

            $detalleResults = $this->orderModel->insertDetalle($dataDetails);
            

        } catch (Exception $e) {
            $result->message = "No se pudo agregar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    public function jsonGuardarPedido()
    {
        $result = new stdClass();
        try{
            $idPedido = $this->request['idPedido'];
            //$data['idUser']         = $this->request['idUser'];
            $data['fecha']          = $this->request['fecha'];
            $data['tipo_pedido']    = $this->request['tipoPedido'];
            $data['idVendedor']     = $this->request['idVendedor'];
            $dataDetalle = $this->request['detalle'];
            $dataNewDetalle = $this->request['detalleNuevo'];
            

            $arrayDetails = json_decode($dataDetalle);
            $arrayNewDetails = json_decode($dataNewDetalle);

            /*Update Pedido*/
            $pedidoData = $this->orderModel->updateOrder($idPedido, $data);
            /*Delete all detalle*/
            $detalleData = $this->orderModel->deleteAllDetalle($idPedido);

            /*Insert new and updated detalle*/
            if (isset($this->request['oldProductos'])) {
                $oldProductos  = $this->request['oldProductos'];
                $oldCantidad = array ();
                $oldCantidad   = $this->request['oldQuantities'];                
                $dataDetalleUpdated = array();
                if (!empty($oldProductos)) {
                    $i = 0;
                    foreach ($oldProductos as $p) {
                        $dataArray = array();
                        $dataArray['idPedido'] = $idPedido;
                        $dataArray['idProducto'] = $p;
                        $dataArray['cantidad'] = $this->getArrayValue($arrayDetails, "cantidad" . $p);
                        $dataArray['precio'] = $this->getArrayValue($arrayDetails, "precio" . $p);
                        $dataArray['descuento'] = $this->getArrayValue($arrayDetails, "descuento" . $p);
                        $dataArray['fechaCreacion'] = date('Y-m-d H:i:s'); 
                        array_push($dataDetalleUpdated, $dataArray);
                        $operator = $oldCantidad[$i] - $dataArray['cantidad'] >= 0 ? "+" : '-';
                        $this->productModel->updateProductQuantity($dataArray, $operator);
                        $i++;
                    }
                    
                    $detalleData = $this->orderModel->insertDetalle($dataDetalleUpdated);
                }
            }
            $dataCredito['idPedido'] = $this->request['idPedido'];
            if (isset($this->request['fechaUpdate'])) {
                $dataCredito['fechaUpdate'] = $this->request['fechaUpdate'];
                $dataCredito['acuenta'] = $this->request['acuenta'];
                $dataCredito['saldo'] = $this->request['saldo'];
                $dataCredito['cancelado'] = $this->request['cancelado'];
                $dataCredito['numeroRecibo'] = $this->request['recibo'];
                $dataCredito['idVendedor']   = $this->request['idVendedor'];
                $credito = $this->orderModel->insertCredito($dataCredito);
            }
            

            if (isset($this->request['newProductos'])) {
                $newProductos  = $this->request['newProductos'];
                $dataDetalleNew = array();
                if (!empty($newProductos)) {
                    foreach ($newProductos as $p) {
                        $dataArray = array();
                        $dataArray['idPedido'] = $idPedido;
                        $dataArray['idProducto'] = $p;
                        $dataArray['cantidad'] = $this->getArrayValue($arrayNewDetails, "cantidad" . $p);
                        $dataArray['precio'] = $this->getArrayValue($arrayNewDetails, "precioUnitario" . $p);
                        $dataArray['descuento'] = $this->getArrayValue($arrayNewDetails, "descuento" . $p);
                        $dataArray['fechaCreacion'] = date('Y-m-d H:i:s');
                        array_push($dataDetalleNew, $dataArray);
                    }
                    $detalleData = $this->orderModel->insertDetalle($dataDetalleNew);

                }
            }

            if ($pedidoData) {
                $result->message = "Se actualizo correctamente los datos del Pedido";
            }

        } catch (Exception $e) {
            $result->message = "No se pudo actualizar los datos ".$e->getMessage();
        }

        echo json_encode($result);
    }

    public function jsonEliminarPedido()
    {
        $result = new stdClass();
        try{                                    
            $idPedido     = $this->request['idPedido'];                        
            $this->orderModel->deleteOrder($idPedido);
            $result->message = "Se elimino correctamente el pedido";

        } catch (Exception $e) {
            $result->message = "No se pudo eliminar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    public function factura($id) {        
        $pedidoArray = new stdClass();
        $pedidoInfo = $this->orderModel->getOrderById($id);        
        $detalleInfo = $this->orderModel->getDetailById($id);
        $idCliente = $pedidoInfo->idCliente;
        $clienteInfo = $this->clientModel->getClientById($idCliente);
        $pedidoArray->pedido = $this->getTotals($pedidoInfo, $detalleInfo);
        $pedidoArray->detalle = $detalleInfo;
        $pedidoArray->cliente = $clienteInfo;
        $pedidoArray->descuento = $pedidoInfo->descuento;
        $pedidoArray->tipo_pedido = $pedidoInfo->tipo_pedido;
        
        $this->data = $pedidoArray;
        $this->middle = 'pedidos/factura';
        $this->layout();
    }
    public function printReport() {        
        $zonaNames = array();
        if (isset($this->request['fechaReporte'])) {
            $fecha = $this->request['fechaReporte'];
            $opcion = $this->request['opcion'];           
            if (isset($this->request['zona'])) {                              
                $zonaSelected = $this->request['zona'];
            } else {
                $zonaSelected = null;                
            }    

            if (isset($this->request['zonas'])) {
                $zonas = $this->request['zonas'];
                
                foreach ($zonas as $zx) {
                    $name = $this->zonaModel->getZonaById($zx);
                    $zonaNames[$name->idZona] = $name->nombre;
                }
            } else {
                $zonas = null;
                $zonaList = $this->zonaModel->getZonaList();
                foreach ($zonaList as $zl) {
                    $zonaNames[$zl->idZona] = $zl->nombre;
                }
            }
        }
        
        switch($opcion) {
            case "pedido": $totalInfo = $this->orderModel->getPedidosByDate($fecha, null, $zonas);
                           foreach($totalInfo as $pedido) {
                               $detalleInfo = $this->orderModel->getDetailById($pedido->numPedido);
                               $pedido = $this->getTotals($pedido, $detalleInfo);                                
                           } 
                           
                        break;                        
            case "producto": $totalInfo = $this->orderModel->getTotalProductsByDate($fecha, null, $zonas);
                             $totalInfo = $this->sumProducts($totalInfo);                             
                            //  print_r($totalInfo);
                        break;
        
        }
        
        
        $reporteArray = new stdClass();                
        $reporteArray->tipo = $opcion;
        
        $reporteArray->lista = $totalInfo;
        $reporteArray->fecha = $fecha;
        $reporteArray->zonas = $zonaNames;
        $reporteArray->zonaSelected = $zonas;
                
        $this->data = $reporteArray;
        $this->middle = 'pedidos/printReport';
        $this->layout();
    }
    public function sumProducts($products) {        
        $res  = array();
        foreach ($products as $vals) {
            if (array_key_exists($vals->idProducto, $res)) {
                $res[$vals->idProducto]->cantidad += $vals->cantidad;                
            } else {
                $res[$vals->idProducto]  = $vals;
            }
        }
       
        return $res;
    }

    public function getTotals($pedido, $detalle) 
    {
        
        $totalPedido = 0.0;        
        $totalContado = 0.0;
        $totalCredito = 0.0;

        if ($detalle) {
                
            foreach ($detalle as $d) {
                $total = $d->cantidad * $d->precio;
                $totalPedido += $total;
                if ($pedido->tipo_pedido == 'CONTADO') {                    
                    $totalContado += $d->cantidad * $d->precio;
                }
                if ($pedido->tipo_pedido == 'CREDITO') {                    
                    $totalCredito += $d->cantidad * $d->precio;
                }
            }
        }
        $pedido->total = $totalPedido;
        $pedido->totalContado = $totalContado;
        $pedido->totalCredito = $totalCredito;
        $pedido->totalLiteral = $this->numToLetras($totalPedido);

        return $pedido;
    }
    public function getArrayValue($array, $value) {
        $item = null;
        foreach($array as $struct) {
            if ($value == $struct->name) {
                $item = $struct->value;
                break;
            }
        }
        return $item;
    }

    function numToLetras($numero, $moneda = '', $subfijo = '')
    {
        $xarray = array(
            0 => 'Cero'
            , 1 => 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'
            , 'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'
            , 'VEINTI', 30 => 'TREINTA', 40 => 'CUARENTA', 50 => 'CINCUENTA'
            , 60 => 'SESENTA', 70 => 'SETENTA', 80 => 'OCHENTA', 90 => 'NOVENTA'
            , 100 => 'CIENTO', 200 => 'DOSCIENTOS', 300 => 'TRESCIENTOS', 400 => 'CUATROCIENTOS', 500 => 'QUINIENTOS'
            , 600 => 'SEISCIENTOS', 700 => 'SETECIENTOS', 800 => 'OCHOCIENTOS', 900 => 'NOVECIENTOS'
        );
    
        $numero = trim($numero);
        $xpos_punto = strpos($numero, '.');
        $xaux_int = $numero;
        $xdecimales = '00';
        if (!($xpos_punto === false)) {
            if ($xpos_punto == 0) {
                $numero = '0' . $numero;
                $xpos_punto = strpos($numero, '.');
            }
            $xaux_int = substr($numero, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
            $xdecimales = substr($numero . '00', $xpos_punto + 1, 2); // obtengo los valores decimales
        }
    
        $XAUX = str_pad($xaux_int, 18, ' ', STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
        $xcadena = '';
        for ($xz = 0; $xz < 3; $xz++) {
            $xaux = substr($XAUX, $xz * 6, 6);
            $xi = 0;
            $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
            $xexit = true; // bandera para controlar el ciclo del While
            while ($xexit) {
                if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                    break; // termina el ciclo
                }
    
                $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
                $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
                for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                    switch ($xy) {
                        case 1: // checa las centenas
                            $key = (int) substr($xaux, 0, 3);
                            if (100 > $key) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                                /* do nothing */
                            } else {
                                if (TRUE === array_key_exists($key, $xarray)) {  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                    $xseek = $xarray[$key];
                                    $xsub = $this->subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                    if (100 == $key) {
                                        $xcadena = ' ' . $xcadena . ' CIEN ' . $xsub;
                                    } else {
                                        $xcadena = ' ' . $xcadena . ' ' . $xseek . ' ' . $xsub;
                                    }
                                    $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                                } else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                    $key = (int) substr($xaux, 0, 1) * 100;
                                    $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                    $xcadena = ' ' . $xcadena . ' ' . $xseek;
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 0, 3) < 100)
                            break;
                        case 2: // checa las decenas (con la misma lógica que las centenas)
                            $key = (int) substr($xaux, 1, 2);
                            if (10 > $key) {
                                /* do nothing */
                            } else {
                                if (TRUE === array_key_exists($key, $xarray)) {
                                    $xseek = $xarray[$key];
                                    $xsub = $this->subfijo($xaux);
                                    if (20 == $key) {
                                        $xcadena = ' ' . $xcadena . ' VEINTE ' . $xsub;
                                    } else {
                                        $xcadena = ' ' . $xcadena . ' ' . $xseek . ' ' . $xsub;
                                    }
                                    $xy = 3;
                                } else {
                                    $key = (int) substr($xaux, 1, 1) * 10;
                                    $xseek = $xarray[$key];
                                    if (20 == $key)
                                        $xcadena = ' ' . $xcadena . ' ' . $xseek;
                                    else
                                        $xcadena = ' ' . $xcadena . ' ' . $xseek . ' Y ';
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 1, 2) < 10)
                            break;
                        case 3: // checa las unidades
                            $key = (int) substr($xaux, 2, 1);
                            if (1 > $key) { // si la unidad es cero, ya no hace nada
                                /* do nothing */
                            } else {
                                $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                                $xsub = $this->subfijo($xaux);
                                $xcadena = ' ' . $xcadena . ' ' . $xseek . ' ' . $xsub;
                            } // ENDIF (substr($xaux, 2, 1) < 1)
                            break;
                    } // END SWITCH
                } // END FOR
                $xi = $xi + 3;
            } // ENDDO
            # si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            if ('ILLON' == substr(trim($xcadena), -5, 5)) {
                $xcadena.= ' DE';
            }
    
            # si la cadena obtenida en MILLONES o BILLONES, entonces le agrega al final la conjuncion DE
            if ('ILLONES' == substr(trim($xcadena), -7, 7)) {
                $xcadena.= ' DE';
            }
    
            # depurar leyendas finales
            if ('' != trim($xaux)) {
                switch ($xz) {
                    case 0:
                        if ('1' == trim(substr($XAUX, $xz * 6, 6))) {
                            $xcadena.= 'UN BILLON ';
                        } else {
                            $xcadena.= ' BILLONES ';
                        }
                        break;
                    case 1:
                        if ('1' == trim(substr($XAUX, $xz * 6, 6))) {
                            $xcadena.= 'UN MILLON ';
                        } else {
                            $xcadena.= ' MILLONES ';
                        }
                        break;
                    case 2:
                        if (1 > $numero) {
                            $xcadena = "CERO {$moneda} {$xdecimales}/100 {$subfijo}";
                        }
                        if ($numero >= 1 && $numero < 2) {
                            $xcadena = "UN {$moneda} {$xdecimales}/100 {$subfijo}";
                        }
                        if ($numero >= 2) {
                            $xcadena.= $moneda.' '.$xdecimales.'/100'. $subfijo; //
                        }
                        break;
                } // endswitch ($xz)
            } // ENDIF (trim($xaux) != "")
    
            $xcadena = str_replace('VEINTI ', 'VEINTI ', $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
            $xcadena = str_replace('  ', ' ', $xcadena); // quito espacios dobles
            $xcadena = str_replace('UN UN', 'UN', $xcadena); // quito la duplicidad
            $xcadena = str_replace('  ', ' ', $xcadena); // quito espacios dobles
            $xcadena = str_replace('BILLON DE MILLONES', 'BILLON DE', $xcadena); // corrigo la leyenda
            $xcadena = str_replace('BILLONES DE MILLONES', 'BILLONES DE', $xcadena); // corrigo la leyenda
            $xcadena = str_replace('DE UN', 'UN', $xcadena); // corrigo la leyenda
        } // ENDFOR ($xz)
        return trim($xcadena);
    }
    
    /**
    * Esta función regresa un subfijo para la cifra
    * 
    * @author Ultiminio Ramos Galán <contacto@ultiminioramos.com>
    * @param string $cifras La cifra a medir su longitud
    */
    function subfijo($cifras)
    {
        $cifras = trim($cifras);
        $strlen = strlen($cifras);
        $_sub = ' ';
        if (4 <= $strlen && 6 >= $strlen) {
            $_sub = 'MIL';
        }
    
        return $_sub;
    }

    public function testmoneda() {
        $cantidad =  "326";
        if (empty($cantidad)) {
            echo json_encode(array('leyenda' => 'Debe introducir una cantidad.'));
             
            return;
        }
         
        # verificar si el número no tiene caracteres no númericos, con excepción
        # del punto decimal
        $xcantidad = str_replace('.', '', $cantidad);
         
        if (FALSE === ctype_digit($xcantidad)){
            echo json_encode(array('leyenda' => 'La cantidad introducida no es válida.'));
             
            return;
        }
 
        # procedemos a covertir la cantidad en letras
        
        $response = array(
            'leyenda' => $this->num_to_letras($cantidad)
            , 'cantidad' => $cantidad
            );
        print_r($response);    
        echo json_encode($response);
        
    }

    public function getLastDate()
    {
        $lastDate = $this->orderModel->getLastDate();
        echo json_encode($lastDate[0]);
    }    

    public function creditos() 
    {
        // $this->data = $reporteArray;
        $this->middle = 'pedidos/creditos';
        $this->layout();        
    }

    public function ajaxListCreditos()
    {        

        $orders = $this->orderModel->getCreditosList();

        foreach ($orders as $order) {
            $id = $order->numPedido;
            $link = "pedido/factura/".$id;
            $order->options = '<a href="#" onclick="editPedido('.$id.')" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-edit glyphicon-white"></i> Editar</a>&nbsp;'; 
            // '<i class="glyphicon glyphicon-edit glyphicon-white"></i> Editar</a>&nbsp;'.
            // '<a href="'.$link.'" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-list-alt glyphicon-white"></i> Factura</a>&nbsp;'.
            // '<a href="#" onclick="deletePedido('.$id.')" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove-sign glyphicon-white"></i> Borrar</a>';
        }

        $data['data'] = $orders;
                
        echo json_encode($data);
    }
}
