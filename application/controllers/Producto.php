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
            $product->options = '<a href="editProdyuct('.$id.')" class="btn btn-primary"><i class="glyphicon glyphicon-edit glyphicon-white"></i></a>';
            
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
            // $data['valorBruto']      = $this->request['valorBruto'];
            // $data['descuento']       = $this->request['descuento'];
            // $data['valorTotal']      = $this->request['valorTotal'];
            
            $productData = $this->productModel->insert($data);

            if ($productData) {
                $result->message = html_message("Se agrego correctamente el producto","success");
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
}
