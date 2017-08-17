<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

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

        $id = $this->get('id');

        $pedidos = $this->orderModel->getPedidoList();

        if (!empty($pedidos)) {

            $pedido = $this->orderModel->getOrderById($id);
        }

        $this->api_get($pedidos, "pedido", $pedido, $id);

    }

    public function producto_get()
    {
        $id = $this->get('id');

        $productos = $this->productModel->getProductList();

        if (!empty($productos)) {

            $producto = $this->productModel->getProductById($id);
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
