<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Account_Model
 *
 * @author Edwin Taquichiri
 */
class Order_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->__exposedFields = array(
            'codigoOrdero',
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
     * Get the list of Orders
     *
     * @return array
     */
    public function getOrderList($fecha = null, $cantidad = null)
    {

        $this->db->select('*')
        ->from('pedido p');
        //->where('YEAR(fechaIngreso)',$fechaIngreso);
        // if($cantidad != null){
        //     $this->db->where('cantidad',$cantidad);
        // }
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function getOrderById($idOrder)
    {
        $query = $this->db->select()
            ->where('numPedido', $idOrder)
            ->get('pedido');

        $Order = $query->first_row();

        if (!$Order) {
            throw new Exception("No se encontro el pedido [$idOrder].");
        }

        return $Order;
    }

    public function insert($data)
    {

        $data ['fecha'] = date('Y-m-d H:i:s');
        $result = $this->db->insert('pedido', $data);

        return $result;

    }

    /**
     * This method insert new data into club
     */
    public function updateOrder($idOrder, $data)
    {

        $this->db->where('idPedido', $idOrder);
        $data ['fechaActualizacion'] = date('Y-m-d H:i:s');
        $result = $this->db->update('pedido', $data);


        return $result;

    }    
}
