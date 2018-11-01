<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Producto Class
 *
 * @author "Edwin Taquichiri <taquimon@gmail.com>"
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
    public function ajaxListIngreso(){
        $ingresos = $this->productModel->getIngresosList();
        $data['data'] = $ingresos;
        // foreach($products as $product) {
        //     $id = $product->idProducto;
        //     if ($product->activo == 1) {
        //         $status = '<span class="label-success label label-default">Activo</span>';
        //     } else {
        //         $status = '<span class="label-warning label label-default">Inactivo</span>';
        //     }
        //     $product->status = $status;
        //     $product->options = '<a href="#" onclick="editProduct('.$id.')" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit glyphicon-white"></i> Editar</a>&nbsp;';


        // }
        echo json_encode($data);
    }
    public function newProduct() {
        $this->middle = 'product/newProduct'; 
        $this->layout();   
    }
    /**
     * Guardar nuevo producto o ingreso
     * 
     * @return do not return nothing
     */
    public function jsonGuardarNuevo()
    {
        $result = new stdClass();
        $productId = -1;
        try{                
            if ($this->request['idProducto'] == -1) {
                
                /* Guardar en producto*/
                $data['codigoExterno']   = $this->request['codigoExterno'];
                $data['descripcion']     = $this->request['descripcion'];
                $data['cantidad']        = $this->request['cantidad'];
                $data['unidadVenta']     = $this->request['unidadVenta'];
                $data['numeroUnidades']  = $this->request['numeroUnidades'];
                $data['precioUnitario']  = $this->request['precioUnitario'];
                $data['precioVenta']     = $this->request['precioVenta'];
                $data['idCategoria']     = $this->request['idCategoria'];

                $productId = $this->productModel->insert($data);

                if ($productId) {
                    $result->message = "Se agrego correctamente el producto ";
                }               
                $dataIngreso['idProducto'] = $productId;
            } else {
                $dataIngreso['idProducto'] = $this->request['idProducto'];
            }
            /*Guardar en ingresos*/
            // $dataIngreso['codigoExterno']   = $this->request['codigoExterno'];
            $dataIngreso['descripcion']     = $this->request['descripcion'];
            $dataIngreso['cantidad']        = $this->request['cantidad'];
            $dataIngreso['factura']         = $this->request['factura'];
            $dataIngreso['valorUnitario']   = $this->request['precioUnitario'];
            $dataIngreso['valorTotal']      = $this->request['valorTotal'];

            $ingresoData = $this->productModel->insertIngreso($dataIngreso);
            if ($ingresoData) {
                $result->message = "Se agrego correctamente el ingreso ";
                $this->updateCantidadProductos($dataIngreso, "+");
            }               

        } catch (Exception $e) {
            $result->message = "No se pudo agregar los datos ".$e->getMessage();
        }        
        echo json_encode($result);
    }
    /**
     * This method get all the productos from model
     * 
     * @return ajax productos
     */
    public function ajaxGetProductos() 
    {
        $productos = $this->productModel->getProductos();
        echo json_encode($productos);
    }
    public function ajaxGetProductosByIds() 
    {

        if (isset($this->request['products'])) {
            $arrayProductIds = $this->request['products'];
        } else {
            $arrayProductIds = null;
        }

        $productos = $this->productModel->getProductosByIds($arrayProductIds);

        echo json_encode($productos);
    }
    /**
     * This method get the productos by ids from model
     * 
     * @return ajax producto
     */
    public function ajaxGetProductById() 
    {
        if (isset($this->request['idProduct'])) {
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
            $data['idCategoria']    = $this->request['empresa'];
            $data['activo']         = $this->request['activo'];

            $idProducto = $this->request['idProducto'];

            $productData = $this->productModel->updateProducto($idProducto, $data);

            if ($productData) {
                $result->message = "Se actualizo correctamente datos del producto";
            }

        } catch (Exception $e) {
            $result->message = "No se pudo actualizar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    
    /**
    *   function that update the quantity
    **/
    public function updateCantidadProductos($dataProduct, $op) 
    {
        $result = $this->productModel->updateProductQuantity($dataProduct, $op);

    }

    function comprasVentas()
    {
        $this->middle = 'product/compraVenta'; 
        $this->layout();   
    }
    public function ajaxGetCompraVenta() 
    {
        $productos = array();
        if (isset($this->request['fecha'])) {
            $fecha = $this->request['fecha'];
            $productos = $this->request['productos'];
            $fechas = explode(" - ", $fecha);
            $startDate = $fechas[0];
            $endDate = $fechas[1];
            $result = $this->productModel->getCompraVentaByDate($startDate, $endDate, $productos);
            
        }
        echo json_encode($result);
        
    }
    public function compraVentaReport() 
    {
        $productos = array();     
        $fecha = "";
        if (isset($this->request['daterange'])) {
            $fecha = $this->request['daterange'];
            if (isset($this->request['productos'])) {
                $productos = $this->request['productos'];
            } else {
                $productos = null;
            }
                        
            $fechas = explode(" - ", $fecha);
            $startDate = $fechas[0];
            $endDate = $fechas[1];
            $result = $this->productModel->getCompraVentaByDate($startDate, $endDate, $productos);
            //$resultPedido = $this->orderModel->getPedidoByDateandProduct($startDate, $endDate, $productos);
                        
        }        
        $this->data = $result;    
        $this->fecha = $fecha;    
        $this->middle = 'product/compraVentaReport';
        $this->layout();
        
    }
    public function ajaxGetcategoria() 
    {
        $result = $this->productModel->getCategoria();
        echo json_encode($result);        
    }
    public function ingreso() {
        $this->middle = 'product/ingresoList'; 
        $this->layout();   
    }
}

