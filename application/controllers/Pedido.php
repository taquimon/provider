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

   }
   public function index() {
        $orders = $this->orderModel->getOrderList();        
        $this->data = $orders;   
        $this->middle = 'pedidos/pedidoList'; 
        $this->layout();
   }

   public function ajaxListOrder(){        

        $orders = $this->orderModel->getOrderList();
        $data['data'] = $orders;
                
        echo json_encode($data);
    }
    public function newOrder() {
        $this->middle = 'pedidos/newOrder'; 
        $this->layout();   
    }

    public function jsonGuardarNuevo()
    {
        $result = new stdClass();
        try{            
            
            $data['numPedido']      = $this->request['numPedido'];
            $data['idCliente']      = $this->request['idCliente'];
            $data['fecha']          = $this->request['fecha'];
            $data['idUser']         = $this->request['idUser'];                        
            
            $clientData = $this->orderModel->insert($data);

            if ($clientData) {
                $result->message = html_message("Se agrego correctamente el pedido","success");
            }

        } catch (Exception $e) {
            $result->message = "No se pudo agregar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    public function factura() {
        $this->middle = 'pedidos/factura'; 
        $this->layout();
    }
}
