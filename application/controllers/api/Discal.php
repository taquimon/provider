<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH.'/libraries/REST_Controller.php';

// use namespace
// use Restserver\Libraries\REST_Controller;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @category        Controller
 *
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 *
 * @see            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Discal extends REST_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->load->model('order_model', 'orderModel');
        $this->load->model('product_model', 'productModel');
        $this->load->model('client_model', 'clientModel');
        $this->load->model('Ion_auth_model', 'authModel');
        $this->load->model('sales_model', 'salesModel');
    }

    public function api_get($data, $name, $single = null, $id = null)
    {
        //$id = $this->get('id');
        if ($id === null) {
            // Check if the pedidos data store contains pedidos (in case the database result returns NULL)
            if ($data) {
                // Set the response and exit
                $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                // Set the response and exit
                $this->response(
                    [
                        'status' => false,
                        'message' => 'No '.$name.' were found',
                    ],
                    REST_Controller::HTTP_NOT_FOUND
                ); // NOT_FOUND (404) being the HTTP response code
            }
        } elseif ($id == 'login') {
            if ($data) {
                $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->set_response(
                    [
                        'status' => false,
                        'message' => $name.' login',
                    ],
                    REST_Controller::HTTP_NOT_FOUND
                ); // NOT_FOUND (404) being the HTTP response code
            }
        } else {
            $id = (int) $id;

            // Validate the id.
            // print_r($id);
            // print_r($data);
            if ($id <= 0) {
                // Invalid id, set the response and exit.
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            // Get the user from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.

            if (empty($data)) {
                $element = $single;
            }

            if (!empty($element)) {
                $this->set_response($element, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->set_response(
                    [
                        'status' => false,
                        'message' => $name.' could not be found',
                    ],
                    REST_Controller::HTTP_NOT_FOUND
                ); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }

    /**
     *  This method get the pedidos.
     *
     * @return return response
     */
    public function pedido_get()
    {
        $pedido = '';
        $pedidos = [];
        $id = null;
        if ($this->get('id')) {
            $id = $this->get('id');
            $pedido = $this->orderModel->getOrderById($id);
        } elseif ($this->get('ajaxListOrder')) {
            $pedidos = $this->orderModel->getOrderList();

            foreach ($orders as $order) {
                $id = $order->numPedido;
                $link = 'pedido/factura/'.$id;
                $order->options = '<a href="#" onclick="editPedido('.$id.')" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit glyphicon-white"></i> Editar</a>&nbsp;'.
                '<a href="'.$link.'" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-list-alt glyphicon-white"></i> Factura</a>&nbsp;'.
                '<a href="#" onclick="deletePedido('.$id.')" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove-sign glyphicon-white"></i> Borrar</a>';
            }
            $data['data'] = $orders;
            $pedidos = $data;
        } elseif ($this->get('vendedor')) {
            $idVen = $this->get('idVendedor');
            $idUser = $this->get('idUser');
            $fecha = $this->get('fecha');
            //$fecha = '2019-01-28';
            $orders = $this->orderModel->getPedidosByDate($fecha, null, null, 'TODOS', $idVen);

            if ($this->get('dataFlag')) {
                $pedidos['data'] = $orders;
            } else {
                $pedidos = $orders;
            }
        } else {
            $pedidos = $this->orderModel->getOrderList();
        }

        $this->api_get($pedidos, 'pedido', $pedido, $id);
    }

    public function producto_get()
    {
        $producto = '';
        $productos = [];
        $id = null;
        if ($this->get('id')) {
            $id = $this->get('id');
            $producto = $this->productModel->getProductById($id);
        } elseif ($this->get('getAjaxListProducts')) {
            $id = null;
            $products = $this->productModel->getProductList();
            $data['data'] = $products;

            foreach ($products as $product) {
                $idProduct = $product->idProducto;
                if ($product->activo == 1) {
                    $status = '<span class="label-success label label-default">Activo</span>';
                } else {
                    $status = '<span class="label-warning label label-default">Inactivo</span>';
                }
                $product->status = $status;
                $product->options = '<a href="#" onclick="editProduct('.$idProduct.')">'.$product->descripcion.'</a>';
            }

            $productos = $data;
        } else {
            $productos = $this->productModel->getProductList();
        }
        $this->api_get($productos, 'producto', $producto, $id);
    }

    public function cliente_get()
    {
        $cliente = '';
        $clientes = [];
        $id = null;

        if ($this->get('id')) {
            $id = $this->get('id');
            $cliente = $this->clientModel->getClientById($id);
        } elseif ($this->get('vendedor')) {
            $idVen = $this->get('idVendedor');
            $idUser = $this->get('idUser');
            $clients = $this->clientModel->getClientsByVendedor($idVen, $idUser);

            if ($this->get('dataFlag')) {
                $clientes['data'] = $clients;
            } else {
                $clientes = $clients;
            }
        } else {
            $clientes = $this->clientModel->getClientList();
        }

        $this->api_get($clientes, 'cliente', $cliente, $id);
    }

    public function producto_post()
    {
        $data = $this->post();

        $data_result = $this->productModel->insert($data);
        if ($data_result) {
            $message = 'product added correctly';
        }
        $message = [
            'message' => $message,
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function pedido_delete()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0) {
            // Set the response and exit
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource',
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

    /**
     * user_get.
     */
    public function user_get()
    {
        $user = '';
        $users = [];
        $id = null;
        if ($this->get('id')) {
            $id = $this->get('id');
            $user = $this->authModel->user($id)->row();
        } elseif ($this->get('login')) {
            $id = 'login';
            $username = $this->get('username');
            $password = $this->get('password');
            $users['login'] = $this->authModel->login($username, $password, true);
            $users['dataUser'] = $this->authModel->user()->row();
            $idUser = $users['dataUser']->id;
            $users['dataVendedor'] = $this->salesModel->getVendedorByUser($idUser);
        } else {
            $users = $this->authModel->users();
        }

        $this->api_get($users, 'user', $user, $id);
    }

    /**
     * Create a pedido with details included.
     *
     * @return return A success message if pedido was created
     */
    public function pedido_post()
    {
        $data = $this->post();
        $detalle = $data['detalle'];
        unset($data['detalle']);
        // print_r($detalle);
        $products = $data['productos'];
        // print_r($detalle);
        unset($data['productos']);
        $dataResult = $this->orderModel->insert($data);
        if ($dataResult) {
            $dataDetails = $this->transformDetalle($detalle, $products, $dataResult);
            $detalleResults = $this->orderModel->insertDetalle($dataDetails);
            $message = 'Pedido agregado correctamente';
        }
        $message = [
            'message' => $message,
        ];

        // CREATED (201) being the HTTP response code
        $this->set_response($message, REST_Controller::HTTP_CREATED);
    }

    public function transformDetalle($dataDetalle, $productos, $pedidoData)
    {        
        $arrayDetails = json_decode($dataDetalle);
        // print_r($arrayDetails);
        $dataDetails = array();
        foreach ($productos as $p) {
            $dataArray = array();
            $dataArray['idPedido'] = $pedidoData;
            $dataArray['idProducto'] = $p;
            $dataArray['cantidad'] = $this->getArrayValue($arrayDetails, 'cantidad'.$p);
            $dataArray['precio'] = $this->getArrayValue($arrayDetails, 'precioUnitario'.$p);
            $dataArray['descuento'] = $this->getArrayValue($arrayDetails, 'descuento'.$p);
            $dataArray['fechaCreacion'] = date('Y-m-d H:i:s');
            array_push($dataDetails, $dataArray);
            $this->productModel->updateProductQuantity($dataArray);
        }

        return $dataDetails;
    }

    public function getArrayValue($array, $value)
    {
        $item = null;
        // print_r($array);
        foreach ($array as $struct) {
            if ($value == $struct->name) {
                $item = $struct->value;
                break;
            }
        }
        
        return $item;
    }
}
