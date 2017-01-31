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
            'numPedido',
            'idCliente',
            'idUser',
            'fecha'                   
        );
    }

    /**
     * Get the list of Orders
     *
     * @return array
     */
    public function getOrderList()
    {

        $query = $this->db->query('SELECT p.numPedido, p.fecha, c.razonSocial, u.username from pedido p, clientes c, user u WHERE c.idCliente = p.idCliente and p.idUser=u.idUser');
        $result = $query->result();

        return $result;
    }

    public function getPedidosByDate($fecha, $zona = null) {
        if($zona == null) {
            $queryString = "SELECT p.numPedido, c.razonSocial, c.idCliente, c.zona, p.fecha FROM pedido p, clientes c where p.idCliente=c.idCliente and (fecha between'".$fecha." 00:00:00' and '".$fecha." 23:59:59') order by c.zona ;";
        } else {
            $queryString = "SELECT p.numPedido, c.razonSocial, c.idCliente, c.zona, p.fecha FROM pedido p, clientes c where p.idCliente=c.idCliente and (fecha between'".$fecha." 00:00:00' and '".$fecha." 23:59:59') and c.zona='".$zona."' order by c.zona ;";
        }        
        $query = $this->db->query($queryString);
        $result = $query->result();

        return $result;
    }
    public function getTotalProductsByDate($fecha) {
        $query = $this->db->query("select d.cantidad, pr.idProducto, pr.codigoExterno, pr.descripcion from detalle d, producto pr WHERE d.idProducto = pr.idProducto and d.idPedido in (
SELECT p.numPedido FROM pedido p where  (fecha between'".$fecha." 00:00:00' and '".$fecha." 23:59:59')) order by pr.idProducto ;");
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
    public function getDetailById($idOrder)
    {
        $query = $this->db->query('SELECT d.cantidad, d.precio, d.IdProducto, d.descuento, d.idPedido, p.idProducto, p.codigoExterno, p.descripcion, p.unidadVenta FROM detalle d, producto p WHERE d.idProducto = p.idProducto and d.idPedido='.$idOrder);
            // ->where('idPedido', $idOrder)
            // ->where('d.idProducto', 'p.idProducto')

            // ->get('detalle d, producto p');

        $result = $query->result();
        
        /*if (!$result) {
            throw new Exception("No se encontro el detalle del pedido [$idOrder].");
        }*/

        return $result;
    }

    public function insert($data)
    {
        
        $result = $this->db->insert('pedido', $data);
        
        $insert_id = $this->db->insert_id();
        return $insert_id;

    }
    public function insertDetalle($data)
    {
        
        $result = $this->db->insert_batch('detalle', $data);
        
        $insert_id = $this->db->insert_id();
        
        return $insert_id;
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
    public function updateDetalle($idOrder, $data)
    {

        $this->db->where('idPedido', $idOrder);
        $data ['fechaActualizacion'] = date('Y-m-d H:i:s');
        $result = $this->db->update('detalle', $data);


        return $result;

    }    
    public function deleteOrder($idPedido) {

        $this->db->delete('detalle', array('idPedido' => $idPedido));
        $this->db->delete('pedido', array('numPedido' => $idPedido));
    }
}
