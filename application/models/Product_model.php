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
            //throw new Exception("No se encontro el Producto [$idProduct].");
            return null;
        }

        return $Product;
    }
    public function getProductosByIds($productIds) 
    {
        $query = $this->db->select('idProducto, codigoExterno,descripcion, cantidad, precioUnitario')
            ->where_in('idProducto', $productIds)
            ->order_by('descripcion', 'asc')
            ->get('producto');
        
        $result = $query->result();

        return $result;
    }

    public function insert($data)
    {

        $data ['fechaIngreso'] = date('Y-m-d H:i:s');
        $result = $this->db->insert('producto', $data);
        $insert_id = $this->db->insert_id();
        
        return $insert_id;

    }
    /**
     * Insert Ingresos
     * 
     * @param stdClass $data data to be inserted
     *
     * @return last id inserted
     */
    public function insertIngreso($data)
    {
        echo "---------------------";
        print_r($data);
        $data ['fechaIngreso'] = date('Y-m-d H:i:s');
        $result = $this->db->insert('ingreso', $data);
        $insert_id = $this->db->insert_id();
        
        return $insert_id;

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

        $this->db->select('idProducto, descripcion, codigoExterno, cantidad, precioUnitario')
        ->from('producto')
        ->where('activo', 1)
        ->order_by('descripcion','asc'); 
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }
    public function updateProductQuantity($dataProduct, $operation = "-") 
    {
        $this->db->set('cantidad', 'cantidad '.$operation.' '. $dataProduct["cantidad"], FALSE);
        $this->db->where('idProducto', $dataProduct["idProducto"]);
        $result = $this->db->update('producto');
        return $result;
    }

    /**
     * This method returns compra venta report
     */
    public function getCompraVentaByDate($fecha, $fecha2 = null, $products=null) 
    {
        if ($fecha2 == null) {
            $fecha2 = $fecha;
        }
        if ($products == null) {
            $addProducts = "";
        } else {
            $idProducts = implode("','", $products);
            $idProducts = "'".$idProducts."'";
            $addProducts = "AND i.idProducto IN ($idProducts)";

        }    
        $query = "
        SELECT 
            i.*, p.codigoExterno
        FROM
            ingreso i,
            producto p
        WHERE
            i.idProducto = p.idProducto                
            $addProducts
            AND i.fechaIngreso BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59'
        ";        
        $queryResult = $this->db->query($query);        
        $result = $queryResult->result();
        
        return $result;
    }
    public function getCategoria()
    {
        $query = $this->db->query('SELECT idCategoriaProducto, nombre FROM categoria_producto');
        $result = $query->result();

        return $result;   
    }

    public function getIngresosList($fechaIngreso = null, $cantidad = null)
    {

        $this->db->select('*')
            ->from('ingreso p')
            ->order_by("descripcion", "asc");
       
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }
}