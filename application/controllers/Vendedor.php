<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Vendedor
 *
 * @author phantom
 */
class Vendedor extends MY_Controller {

   public function __construct() {
       parent::__construct();

       $this->load->model('sales_model', 'salesModel');
       $this->load->model('zona_model', 'zonasModel');

   }
   public function index() {
        $saless = $this->salesModel->getVendedorList();
        $this->data = $saless;
        $this->middle = 'vendedor/vendedorList';
        $this->layout();
   }

   public function ajaxListVendedor(){

        $saless = $this->salesModel->getVendedorList();

	   foreach($saless as $sales) {
            $id = $sales->idVendedor;
            $zonasString = $this->salesModel->getNameZonasVendedor($id);            
            $salesZonas = array();
            foreach($zonasString as $zs) {
                 array_push($salesZonas, $zs->nombre);
            }
            $sales->zona = implode(",", $salesZonas);
            $sales->options = '<a href="#" onclick="editVendedor('.$id.')" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit glyphicon-white"></i> Editar</a>&nbsp;'.
           '<a href="#" onclick="deleteVendedor('.$id.')" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove-sign glyphicon-white"></i> Borrar</a>';


        }
        $data['data'] = $saless;

        echo json_encode($data);
    }
    public function newVendedor() {
        $this->middle = 'vendedor/newVendedor';
        $this->layout();
    }

    public function jsonGuardarNuevo()
    {
        $result = new stdClass();
        try{
            
            $data['nombres']        = $this->request['nombres'];
            $data['apellidos']      = $this->request['apellidos'];
            $data['email']          = $this->request['email'];
            $data['direccion']      = $this->request['direccion'];
            $data['telefono']       = $this->request['telefono'];
            $data['celular']        = $this->request['celular'];
            $data['observaciones']  = $this->request['observaciones'];
            
            

            $salesDataId = $this->salesModel->insert($data);

            if ($salesDataId) {
                $result->message = "Se agrego correctamente el vendedor";
            }
            
            $dataZonas = $this->request['zona'];
            $dataZonaVendedor = array();
            $arrayZonaVendedor = array();
            foreach($dataZonas as $dz) {
                $dataZonaVendedor['idVendedor'] = $salesDataId;
                $dataZonaVendedor['idZona'] = $dz;
                array_push($arrayZonaVendedor, $dataZonaVendedor);
            }

            $this->salesModel->insertVendedorZonas($arrayZonaVendedor);            

        } catch (Exception $e) {
            $result->message = "No se pudo agregar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    
    public function ajaxGetVendedores() {
        $vendedors = $this->salesModel->getVendedores();
        echo json_encode($vendedors);
    }
    public function ajaxGetVendedorById() {
        if(isset($this->request['idVendedor'])){
            $idsales = $this->request['idVendedor'];
        }
        $vendedor = $this->salesModel->getVendedorById($idsales);
        $zonasByVendedor = $this->salesModel->getZonasVendedor($idsales);
        $vendedor->zonas = $zonasByVendedor;

        echo json_encode($vendedor);
    }
    public function ajaxGetZonas() {
        
        $zonas = $this->zonasModel->getZonas();        

        echo json_encode($zonas);
    }
    public function ajaxZonasByVendedor() {
        $idVendedor = $this->request['idVendedor'];
        $zonas = $this->zonasModel->getZonasByVendedor($idVendedor);        
        print_r($zonas);    
        echo json_encode($zonas);
    }
	public function jsonGuardarVendedor()
    {
        $result = new stdClass();
        try{
            $data['idVendedor']  = $this->request['idVendedor'];
            $data['nombres']    = $this->request['nombres'];
            $data['apellidos']       = $this->request['apellidos'];
            $data['direccion']    = $this->request['direccion'];
            $data['email'] = $this->request['email'];
            $data['telefono'] = $this->request['telefono'];
			$data['celular'] = $this->request['celular'];
			$data['observaciones'] = $this->request['observaciones'];			


            $idVendedor = $this->request['idVendedor'];

            $salesData = $this->salesModel->updateVendedor($idVendedor, $data);            

            if ($salesData) {
                $result->message = "Se actualizo correctamente los datos del vendedor";
                $zonas = $this->request['zonas'];                
                $dataZonaVendedor = array();
                $arrayZonaVendedor = array();
                foreach($zonas as $dz) {
                    $dataZonaVendedor['idVendedor'] = $idVendedor;
                    $dataZonaVendedor['idZona'] = $dz;
                    array_push($arrayZonaVendedor, $dataZonaVendedor);
                }
                $this->salesModel->updateZonaXrefVendedor($idVendedor, $arrayZonaVendedor);                
            }

        } catch (Exception $e) {
            $result->message = "No se pudo actualizar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    public function jsonEliminarVendedor()
    {
        $result = new stdClass();
        try{
            $idvendedor     = $this->request['idVendedor'];
            $this->salesModel->deletevendedor($idvendedor);
            $result->message = "Se elimino correctamente el vendedor";

        } catch (Exception $e) {
            $result->message = "No se pudo eliminar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    public function reportes() {
        $this->middle = 'vendedor/reportes'; 
        $this->layout();   
    }

    public function printReport() {        
        if(isset($this->request['daterange'])) {
           $fecha = $this->request['daterange'];
           $opcion = $this->request['opcion'];           
           if(isset($this->request['zona'])) {
               $zonaSelected = $this->request['zona'];               
           }
           else {
                $zonaSelected = null;                
           }                      
           if(isset($this->request['zonas'])) {
                $zonas = $this->request['zonas'];
            } else {
                $zonas = null;            
            }
            print_r($fecha);
            $fechas = explode(" - ", $fecha);
            $startDate = $fechas[0];
            $endDate = $fechas[1];
            print_r($startDate);
            print_r($endDate);
        }
        
        // switch($opcion) {
        //     case "pedido": $totalInfo = $this->orderModel->getPedidosByDate($fecha, $zonas);
        //                    foreach($totalInfo as $pedido) {
        //                        $detalleInfo = $this->orderModel->getDetailById($pedido->numPedido);
        //                        $pedido = $this->getTotals($pedido, $detalleInfo); 
        //                    } 
                           
        //                 break;                        
        //     case "producto": $totalInfo = $this->orderModel->getTotalProductsByDate($fecha, $zonas);
        //                      $totalInfo = $this->sumProducts($totalInfo);                             
        //                      //print_r($totalInfo);
        //                 break;
        // }
        $reporteArray = new stdClass();                
        $reporteArray->tipo = $opcion;
        
        // $reporteArray->lista = $totalInfo;
        // $reporteArray->fecha = $fecha;
        // $reporteArray->zonas = $zonas;
        
        //print_r($reporteArray);
        // $this->data = $reporteArray;
        $this->middle = 'vendedor/printReport';
        $this->layout();
    }
}