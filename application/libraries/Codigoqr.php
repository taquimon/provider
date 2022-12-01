<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('phpqrcode/qrlib.php');
class Codigoqr {
 
    public function getCodigoQR($nit="",$numeroFactura="") {
        $cuf = "";
        $t = "1";    
        $contenido = "https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=".$nit."&cuf=".$cuf."&numero=".$numeroFactura."&t=".$t;

        // Exportamos una imagen llamado resultado.png que contendra el valor de la avriable $content
        return $contenido; // QRcode::png($contenido,"resultado.png",QR_ECLEVEL_L,10,2);
    }
 
}