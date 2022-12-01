<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('CodigoControl/CodigoControlV7.php');
class Codigocontrol {
 
    public function getCodigoControl($numero_factura="", $nit_cliente="", $fecha_compra="", $monto_compra="") {
        

        // $numero_autorizacion = '29040011007';
        // $numero_factura = '1503';
        // $nit_cliente = '4189179011';
        // $fecha_compra = '20070702';
        // $monto_compra = '2500';
        // $clave = '9rCB7Sv4X29d)5k7N%3ab89p-3(5[A';
        
        $numero_autorizacion = '7904004313753';
        $numero_factura = '826384';
        $nit_cliente = '1666982';
        $fecha_compra = '20080622';
        $monto_compra = '61103';
        $clave = 'Ebs[$c2d2NCg5FYj@6nU5y##a5d]eDVz%]xW6bzcd}Kd)\w\=c+)dZHneF#bqVL@';

        return CodigoControlV7::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);
    }
 
}