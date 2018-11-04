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
        $this->load->model('Ion_auth_model', 'authModel');
    }

    public function api_get($data, $name, $single = NULL, $id = NULL)
    {
        //$id = $this->get('id');        
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
        else if ($id == 'login') {
            $this->set_response([
                'status' => FALSE,
                'message' => $name.' login'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }   // Find and return a single record for a particular user.
        else {
            $id = (int) $id;

            // Validate the id.
            // print_r($id);
            // print_r($data);
            if ($id <= 0)
            {
                // Invalid id, set the response and exit.
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            // Get the user from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.


            if (empty($data))
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
        $id = NULL;
        if ($this->get('id')) {            
            $id = $this->get('id');
            $pedido = $this->orderModel->getOrderById($id);
        }                            
        else if ($this->get('ajaxListOrder')) {
            $pedidos = $this->orderModel->getOrderList();    
        
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
            $pedidos = $this->orderModel->getOrderList();            
        }
        
        $this->api_get($pedidos, "pedido", $pedido, $id);
    }

    public function producto_get()
    {
        $producto = '';
        $productos = [];
        $id = NULL;
        if ($this->get('id') ){
            $id = $this->get('id');
            $producto = $this->productModel->getProductById($id);
        }   
        else if ($this->get('getAjaxListProducts')) {
            $id = NULL;            
            $products = $this->productModel->getProductList();
            $data['data'] = $products;
            foreach($products as $product) {
                $idProduct = $product->idProducto;
                if ($product->activo == 1) {
                    $status = '<span class="label-success label label-default">Activo</span>';
                } else {
                    $status = '<span class="label-warning label label-default">Inactivo</span>';
                }
                $product->status = $status;
                $product->options = '<a href="#" onclick="editProduct('.$idProduct.')">'. $product->descripcion .'</a>';
    
    
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
        $cliente = '';
        $clientes = [];
        $id = NULL;
        if ($this->get('id') ){
            $id = $this->get('id');
            $cliente = $this->clientModel->getClientById($id);
        }   
        else {
            $clientes = $this->clientModel->getClientList();
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
    public function user_get()
    {
        $user = '';
        $users = [];
        $id = NULL;
        if ($this->get('id') ){
            $id = $this->get('id');
            $user = $this->authModel->user($id)->row();            
        }   
        else if ($this->get('login') ){
            $id = $this->get('login');
            $username = $this->get('username');
            $password = $this->get('password');
            $user = $this->authModel->login($username, $password, TRUE);
        }   
     
        else {
            $users = $this->authModel->users();
        }                
        $this->api_get($users, "user", $user, $id);
    }           

}