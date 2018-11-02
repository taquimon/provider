<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
// use Restserver\Libraries\REST_Controller;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Discal extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->load->model('order_model', 'orderModel');
        $this->load->model('product_model', 'productModel');
        $this->load->model('client_model', 'clientModel');
    }

    public function api_get($data, $name, $single = NULL, $id = NULL)
    {
        $id = $this->get('id');

        if ($id === NULL)
        {
            // Check if the pedidos data store contains pedidos (in case the database result returns NULL)
            if ($data)
            {
                // Set the response and exit
                $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No '.$name.' were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
        // Find and return a single record for a particular user.
        else {
            $id = (int) $id;

            // Validate the id.
            if ($id <= 0)
            {
                // Invalid id, set the response and exit.
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            // Get the user from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.


            if (!empty($data))
            {
                $element = $single;
            }

            if (!empty($element))
            {
                $this->set_response($element, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $this->set_response([
                    'status' => FALSE,
                    'message' => $name.' could not be found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }
    public function pedido_get()
    {
        $pedido = '';
        $pedidos = [];
        $id = null;
        if ($this->get('id')) {            
            $id = $this->get('id');
            $pedido = $this->orderModel->getOrderById($id);
        }                            
        if ($this->get('ajaxListOrder')) {
            $orders = $this->orderModel->getPedidoList();    
        
                foreach($orders as $order) {
                    $id = $order->numPedido;
                    $link = "pedido/factura/".$id;
                    $order->options = '<a href="#" onclick="editPedido('.$id.')" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit glyphicon-white"></i> Editar</a>&nbsp;'.
                    '<a href="'.$link.'" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-list-alt glyphicon-white"></i> Factura</a>&nbsp;'.
                    '<a href="#" onclick="deletePedido('.$id.')" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove-sign glyphicon-white"></i> Borrar</a>';
                }
                $data['data'] = $orders;
                $pedidos = $data;
        }
        else {
            $orders = $this->orderModel->getOrderList();
            
        }
        
        $this->api_get($pedidos, "pedido", $pedido, $id);


    }

    public function producto_get()
    {
        $producto = '';
        $productos = [];
        $id = null;
        if ($this->get('id') ){
            $id = $this->get('id');
            $producto = $this->productModel->getProductById($id);
        }   
        if ($this->get('getAjaxListProducts')) {
            $products = $this->productModel->getProductList();
            $data['data'] = $products;
            foreach($products as $product) {
                $id = $product->idProducto;
                if ($product->activo == 1) {
                    $status = '<span class="label-success label label-default">Activo</span>';
                } else {
                    $status = '<span class="label-warning label label-default">Inactivo</span>';
                }
                $product->status = $status;
                $product->options = '<a href="#" onclick="editProduct('.$id.')">'. $product->descripcion .'</a>';
    
    
            }
    
            $productos = $data;
        }
        else {
            $productos = $this->productModel->getProductList();            
        }                
        $this->api_get($productos, "producto", $producto, $id);
    }
    public function cliente_get()
    {
        $id = $this->get('id');

        $clientes = $this->clientModel->getClientList();

        if (!empty($clientes)) {

            $cliente = $this->clientModel->getClientById($id);
        }

        $this->api_get($clientes, "cliente", $cliente, $id);
    }

    public function producto_post()
    {
        $data  =  $this->post();


        $data_result = $this->productModel->insert($data);
        if($data_result) {
            $message = 'product added correctly';
        }
        $message = [
            'message' => $message
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function pedido_delete()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }
            

}