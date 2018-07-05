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
        $queryOrder = "SELECT
                            p.numPedido,
                            p.fecha,
                            c.razonSocial,
                            c.codigoCliente,
                            p.tipo_pedido,
                            v.nombres,
                            v.apellidos
                        FROM
                            pedido p
                        LEFT JOIN vendedor v on v.idVendedor = p.idVendedor
                        LEFT JOIN clientes c on c.idCliente = p.idCliente";

        $query = $this->db->query($queryOrder);
        $result = $query->result();

        return $result;
    }

    public function getPedidoList()
    {

        $query = $this->db->query('SELECT p.numPedido, p.fecha, c.razonSocial, u.username, p.tipo_pedido from pedido p, clientes c, user u WHERE c.idCliente = p.idCliente and p.idUser=u.idUser');
        $result = $query->result();

        //print_r($result);
        foreach ($result as $r) {
            $query = $this->db->query('SELECT d.cantidad, d.precio, d.IdProducto, d.descuento, d.idPedido, p.idProducto, p.codigoExterno, p.descripcion, p.unidadVenta FROM detalle d, producto p WHERE d.idProducto = p.idProducto and d.idPedido=' . $r->numPedido);
            $result2 = $query->result();
            $r->detalle = $result2;
        }

        return $result;
    }

    public function getPedidosByDate($fecha, $fecha2= null, $zona = null, $tipo_pedido = 'TODOS', $idVendedor = NULL) {
        if ($fecha2 == null) {
            $fecha2 = $fecha;
        }
        $sqlZona = "";
        if ($zona != null) {
            $zonaGroup = implode ("','" , $zona);
            $zonaGroup = "'".$zonaGroup."'";
            $sqlZona = "and c.zona in (".$zonaGroup.")";
        }
        $sqlTipoPedido = '';
        if ($tipo_pedido != 'TODOS') {
            $sqlTipoPedido = 'AND p.tipo_pedido = "'.$tipo_pedido.'"';
        }
        $sqlCreditoVariables = "";
        $sqlCreditoJoinTable = "";
        $sqlCreditoCancelado = "";
        $sqlNumPedido = "";
        $groupCancelado = "";

        if ($tipo_pedido == 'CREDITO') {
            $sqlCreditoVariables = ",
            p.numPedido AS numPedido,
            pc.cancelado,
            SUM(pc.acuenta) AS acuenta,
            MIN(pc.saldo) AS saldo
            ";
            $sqlCreditoJoinTable = "LEFT OUTER JOIN pedido_credito pc ON p.numPedido = pc.idPedido";
            $sqlCreditoCancelado = "AND (pc.cancelado = 'NO' OR pc.cancelado is null )";
            $groupCancelado = ", pc.cancelado ";
        } else {
            $sqlNumPedido = ", p.numPedido";
        }
        $sqlVendedor = "";
        if ($idVendedor != NULL) {
            $sqlVendedor = "AND p.idVendedor = ". $idVendedor;
        }

        $queryString = "
        SELECT
            c.razonSocial,
            c.idCliente,
            c.codigoCliente,
            c.zona,
            p.fecha,
            p.tipo_pedido,
            p.idVendedor
            $sqlNumPedido
            $sqlCreditoVariables

        FROM
            pedido p
                JOIN clientes c ON p.idCliente = c.idCliente
                $sqlCreditoJoinTable                
        WHERE
                (fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59')
                $sqlTipoPedido
                $sqlVendedor
                $sqlZona
                $sqlCreditoCancelado
        GROUP BY numPedido $groupCancelado
        ORDER BY numPedido , c.zona;
        ";
        // print_r($queryString);
        // if($zona == null) {
        //     $queryString = "SELECT p.numPedido, c.razonSocial, c.idCliente, c.codigoCliente, c.zona, p.fecha, p.tipo_pedido FROM pedido p, clientes c ";
        //     $queryString .= "where p.idCliente=c.idCliente and (fecha between'".$fecha." 00:00:00' and '".$fecha2." 23:59:59') ". $sqlTipoPedido ." order by p.numPedido, c.zona ;";
        // } else {

        //     $queryString = "SELECT p.numPedido, c.razonSocial, c.idCliente, c.codigoCliente, c.zona, p.fecha, p.tipo_pedido FROM pedido p, clientes c ";
        //     $queryString .= "where p.idCliente=c.idCliente and (fecha between'".$fecha." 00:00:00' and '".$fecha2." 23:59:59') ". $sqlTipoPedido ." and c.zona in (".$zonaGroup.") order by p.numPedido, c.zona ;";
        // }

        $query = $this->db->query($queryString);
        $result = $query->result();

        return $result;
    }
    public function getTotalProductsByDate($fecha, $fecha2 = null, $zona = null) {
        if ($fecha2 == null) {
            $fecha2 = $fecha;
        }
        $sqlZona = "";
        if($zona != null) {
            $zonaGroup = implode ("','" , $zona);
            $zonaGroup = "'".$zonaGroup."'";
            $sqlZona = "AND c.zona in (".$zonaGroup.")";
        }
            // $query = $this->db->query("select d.cantidad, pr.idProducto, pr.codigoExterno, pr.descripcion from detalle d, producto pr WHERE d.idProducto = pr.idProducto and d.idPedido in (
            // SELECT p.numPedido FROM pedido p where  (p.fecha between'".$fecha." 00:00:00' and '".$fecha2." 23:59:59')) order by pr.descripcion, pr.idProducto ;");
        // } else {

        //     $query = $this->db->query("select d.cantidad, pr.idProducto, pr.codigoExterno, pr.descripcion from detalle d, producto pr WHERE d.idProducto = pr.idProducto and d.idPedido in (
        //     SELECT p.numPedido FROM pedido p, clientes c where  (p.fecha between'".$fecha." 00:00:00' and '".$fecha2." 23:59:59') and p.idCliente=c.idCliente and c.zona in (".$zonaGroup.") ) order by pr.descripcion, pr.idProducto ;");

        // }

        $queryString = "
        SELECT
            d.idPedido,
            d.cantidad,
            pr.idProducto,
            pr.codigoExterno,
            pr.descripcion,
            pe.fecha
        FROM
            detalle d
        JOIN pedido pe ON pe.numPedido = d.idPedido
        JOIN producto pr ON d.idProducto = pr.idProducto
        WHERE
            d.idPedido IN (SELECT
                    p.numPedido
                FROM
                    pedido p,
                    clientes c
                WHERE
                    (p.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59')
                        AND p.idCliente = c.idCliente
                $sqlZona)
        ORDER BY pr.descripcion , pr.idProducto;";
        $query = $this->db->query($queryString);
        $result = $query->result();


        return $result;
    }

    public function getOrderById($idOrder)
    {
        $query = $this->db->select()
            ->where('numPedido', $idOrder)
            ->get('pedido');

        $order = $query->first_row();


        if (!$order) {
            //throw new Exception("No se encontro el pedido [$idOrder].");
            return null;
        }
        else {
            $query = $this->db->query('SELECT d.cantidad, d.precio, d.IdProducto, d.descuento, d.idPedido, p.idProducto, p.codigoExterno, p.descripcion, p.unidadVenta FROM detalle d, producto p WHERE d.idProducto = p.idProducto and d.idPedido='.$idOrder.' order by p.descripcion');

            $result2 = $query->result();
        }
        $order->detalle = $result2;

        return $order;
    }
    public function getDetailById($idOrder)
    {
        $query = $this->db->query('SELECT d.cantidad, d.precio, d.IdProducto, d.descuento, d.idPedido, p.idProducto, p.codigoExterno, p.descripcion, p.unidadVenta FROM detalle d, producto p WHERE d.idProducto = p.idProducto and d.idPedido='.$idOrder.' order by p.descripcion');
        $result = $query->result();

        return $result;
    }
    public function getCreditoById($idOrder)
    {
        $query = $this->db->query('SELECT c.* FROM pedido_credito c WHERE c.idPedido='.$idOrder.' order by c.fechaUpdate');
        $result = $query->result();

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
    public function insertCredito($data)
    {

        $result = $this->db->insert('pedido_credito', $data);

        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * This method insert new data into club
     */
    public function updateOrder($idOrder, $data)
    {

        $this->db->where('numPedido', $idOrder);
        $data ['fechaModificacion'] = date('Y-m-d H:i:s');
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

    public function getLastDate() {
        $query = $this->db->query('SELECT MAX(fecha) as fecha from pedido');
        $result = $query->result();

        return $result;
    }
    public function deleteAllDetalle($idPedido) {

        $this->db->delete('detalle', array('idPedido' => $idPedido));
    }

    public function updateQuantity($idProducto, $cantidad) {
        $query = $this->db->query('UPDATE cantidad  producto where idProducto = '.$idProducto);
    }
    /**
     * This method returns compra venta report
     */
    public function getPedidoByDateandProduct($fecha, $fecha2 = null, $products=null)
    {
        if ($fecha2 == null) {
            $fecha2 = $fecha;
        }
        if ($products == null) {
            $addProducts = "";
        } else {
            $idProducts = implode("','", $products);
            $idProducts = "'".$idProducts."'";
            $addProducts = "AND d.idProducto IN ($idProducts)";

        }
        $query = "
        SELECT
            *.d
        FROM
            detalle d
        WHERE
            d.fechaCreacion BETWEEN '$fecha 00:00:00' AND '$fecha 23:59:59'
            $addProducts;
        ";
        $queryResult = $this->db->query($query);
        $result = $queryResult->result();

        return $result;
    }
    public function getCreditosList()
    {
        $queryString = "
            SELECT
                p.numPedido,
                p.fecha,
                c.razonSocial,
                c.codigoCliente,
                p.tipo_pedido,
                v.idVendedor,
                v.nombres,
                v.apellidos
            FROM
                pedido p
            JOIN clientes c on c.idCliente = p.idCliente
            LEFT JOIN vendedor v on v.idVendedor = p.idVendedor
            WHERE p.tipo_pedido = 'CREDITO'
        ";
        $query = $this->db->query($queryString);
        $result = $query->result();

        return $result;
    }
    public function getVendedorByClient($idCliente) {
        $queryString = "
        SELECT
            idVendedor
        FROM
            provider.zona_xref_vendedor
        WHERE
            idZona = (SELECT
                        zona
                    FROM
                        clientes
                    WHERE
                        idCliente = $idCliente);
        ";
        $query = $this->db->query($queryString);
        $result = $query->result();

        return $result;
    }
}
