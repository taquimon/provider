<?php
require_once('CodigoControlV7.php');

$numero_autorizacion = '29040011007';
$numero_factura = '1503';
$nit_cliente = '4189179011';
$fecha_compra = '20070702';
$monto_compra = '2500';
$clave = '9rCB7Sv4X29d)5k7N%3ab89p-3(5[A';

echo CodigoControlV7::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);

// l QR impreso en la Factura o Nota Crédito - Débito esta compuesto de la siguiente forma:

 

//     https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=valorNit&cuf=valorCuf&numero=valorNroFactura&t=valorTamaño
    
     
    
//     Donde:
    
//     Ruta: Es una ruta o enlace a los servicios de la Administración Tributaria, la ruta definitiva será publicada una vez salga al ambiente de producción.
//     valorNit: Es el valor del NIT del emisor de la Factura o Nota de Crédito-Débito.
//     valorCuf: Es el Código Único de la Factura de la Factura o Nota de Crédito-Débito.
//     valorNroFactura: Es el número correlativo de la Factura o Nota de Crédito-Débito.
//     valorTamaño: es el tamaño para la pre visualización 1 = rollo, 2 = media hoja, si no se incluye este parámetro su valor por defecto será 1.

?>