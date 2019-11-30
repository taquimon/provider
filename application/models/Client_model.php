<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Account_Model.
 *
 * @author Edwin Taquichiri
 */
class Client_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->__exposedFields = array(
            'codigoCliente',
            'nombres',
            'apellidos',
            'email',
            'direccion',
            'telefono',
            'celular',
            'nit',
            'observaciones',
            'zona',
        );
    }

    /**
     * Get the list of clients.
     *
     * @return array
     */
    public function getClientList()
    {
        $this->db->select('*')
            ->from('clientes c')
            ->order_by('nombres', 'asc');

        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function getClientById($idClient)
    {
        $query = $this->db->select()
            ->where('idCliente', $idClient)
            ->get('clientes');

        $client = $query->first_row();

        if (!$client) {
            return null;
//            throw new Exception("No se encontro el cliente [$idClient].");
        }

        return $client;
    }

    public function insert($data)
    {
        $data['fechaCreacion'] = date('Y-m-d H:i:s');
        $result = $this->db->insert('clientes', $data);

        return $result;
    }

    /**
     * This method insert new data into club.
     */
    public function updateCliente($idClient, $data)
    {
        $this->db->where('idCliente', $idClient);
        $data['fechaModificacion'] = date('Y-m-d H:i:s');
        $result = $this->db->update('clientes', $data);

        return $result;
    }

    public function getTipoClientes()
    {
        $this->db->select('*')
        ->from('tipo_cliente tc');
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function getClientes()
    {
        $this->db->select()
            ->from('clientes c')
            ->order_by('nombres', 'asc');
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function deleteCliente($idCliente)
    {
        $queryString = 'SET FOREIGN_KEY_CHECKS = 0';
        $query = $this->db->query($queryString);

        $this->db->delete('clientes', array('idCliente' => $idCliente));
        $queryStrin = 'SET FOREIGN_KEY_CHECKS = 1';
        $query = $this->db->query($queryString);
    }

    public function getZonas()
    {
        $this->db->distinct();
        $this->db->select('zona')
            ->from('clientes c');
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    /**
     * GetClientsByVendedor.
     *
     * @param mixed $idVendedor id from vendedor
     *
     * @return array array of clients belong to vendedor
     */
    public function getClientsByVendedor($idVendedor, $idUser = null)
    {
        if ($idUser == null) {
            $idUser = $idVendedor;
        }
        $queryString = "
            SELECT c.idCliente,
            c.codigoCliente,
            c.nombres,
            c.apellidos,
            c.direccion,
            c.nit,
            c.razonSocial,
            c.zona,
            c.tipoCliente            
            FROM clientes c
            INNER JOIN zona_xref_vendedor AS zv ON zv.idZona = c.zona
            LEFT JOIN vendedor AS v ON v.idVendedor = zv.idVendedor
            WHERE zv.idVendedor = $idVendedor                
            ";
        $query = $this->db->query($queryString);
        $result = $query->result();

        return $result;
    }
}
