<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Account_Model
 *
 * @author Edwin Taquichiri
 */
class Product_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->__exposedFields = array(
            'codigoProducto',
            'descripcion',
            'cantidad',
            'unidadVenta',
            'numeroUnidades',
            'precioUnitario',
            'valorBruto',
            'descuento',
            'valorTotal'            
        );
    }

    /**
     * Get the list of Products
     *
     * @return array
     */
    public function getProductList($fechaIngreso = null, $cantidad = null)
    {

        $this->db->select('*')
        ->from('producto p')
        ->order_by("descripcion", "asc");
        //->where('YEAR(fechaIngreso)',$fechaIngreso);
        if($cantidad != null){
            $this->db->where('cantidad',$cantidad);
        }


        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function getProductById($idProduct)
    {
        $query = $this->db->select()
            ->where('idProducto', $idProduct)
            ->get('producto');

        $Product = $query->first_row();

        if (!$Product) {
            throw new Exception("No se encontro el Producto [$idProduct].");
        }

        return $Product;
    }
    public function getProductosByIds($productIds) {
        $query = $this->db->select('idProducto, codigoExterno,descripcion, cantidad, precioUnitario')
            ->where_in('idProducto', $productIds)
            ->order_by('descripcion','asc')
            ->get('producto');
        
        $result = $query->result();

        return $result;
    }

    public function insert($data)
    {

        $data ['fechaIngreso'] = date('Y-m-d H:i:s');
        $result = $this->db->insert('producto', $data);

        return $result;

    }

    /**
     * This method insert new data into club
     */
    public function updateProducto($idProduct, $data)
    {

        $this->db->where('idProducto', $idProduct);
        $data ['fechaActualizacion'] = date('Y-m-d H:i:s');
        $result = $this->db->update('producto', $data);


        return $result;

    }
    
    public function getProductos()
    {

        $this->db->select('idProducto, descripcion')
        ->from('producto')
        ->order_by('descripcion','asc'); 
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }
}
