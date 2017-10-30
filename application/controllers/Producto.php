<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Player
 *
 * @author phantom
 */
class Producto extends MY_Controller {

   public function __construct() {
       parent::__construct();

       $this->load->model('product_model', 'productModel');

   }
   public function index() {
        $products = $this->productModel->getProductList();
        $this->data = $products;
        $this->middle = 'product/productList';
        $this->layout();
   }

   public function ajaxListProduct(){

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
            $product->options = '<a href="#" onclick="editProduct('.$id.')" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit glyphicon-white"></i> Editar</a>&nbsp;';


        }

        echo json_encode($data);
    }
    public function newProduct() {
        $this->middle = 'product/newProduct'; 
        $this->layout();   
    }

    public function jsonGuardarNuevo()
    {
        $result = new stdClass();
        try{            
            $data['codigoExterno']   = $this->request['codigoExterno'];
            $data['descripcion']     = $this->request['descripcion'];
            $data['cantidad']        = $this->request['cantidad'];
            $data['unidadVenta']     = $this->request['unidadVenta'];
            $data['numeroUnidades']  = $this->request['numeroUnidades'];
            $data['precioUnitario']  = $this->request['precioUnitario'];

            $productData = $this->productModel->insert($data);

            if ($productData) {
                $result->message = "Se agrego correctamente el producto";
            }

        } catch (Exception $e) {
            $result->message = "No se pudo agregar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    public function ajaxGetProductos() {
        $productos = $this->productModel->getProductos();
        echo json_encode($productos);
    }
    public function ajaxGetProductosByIds() {

        if(isset($this->request['products'])){
            $arrayProductIds = $this->request['products'];
        }else{
            $arrayProductIds = null;
        }

        $productos = $this->productModel->getProductosByIds($arrayProductIds);

        echo json_encode($productos);
    }
    public function ajaxGetProductById() {
        if(isset($this->request['idProduct'])){
            $idProduct = $this->request['idProduct'];
        }
        $producto = $this->productModel->getProductById($idProduct);

        echo json_encode($producto);
    }

    public function jsonGuardarProducto()
    {
        $result = new stdClass();
        try{
            $data['codigoExterno']  = $this->request['codigoExterno'];
            $data['descripcion']    = $this->request['descripcion'];
            $data['cantidad']       = $this->request['cantidad'];
            $data['unidadVenta']    = $this->request['unidadVenta'];
            $data['numeroUnidades'] = $this->request['numeroUnidades'];
            $data['precioUnitario'] = $this->request['precioUnitario'];
            $data['activo']         = $this->request['activo'];

            $idProducto = $this->request['idProducto'];

            $productData = $this->productModel->updateProducto($idProducto, $data);

            if ($productData) {
                $result->message = "Se actualizo correctamente los datos del producto";
            }

        } catch (Exception $e) {
            $result->message = "No se pudo actualizar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    
    /**
    *   function that update the quantity
    **/
    function updateCantidadProductos($dataProduct) 
    {
        $result = $this->productModel->updateProductQuantity($dataProduct);
        print_r($result);

    }
}

