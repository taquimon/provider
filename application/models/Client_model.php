<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Account_Model
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
            'zona'            
        );
    }

    /**
     * Get the list of clients
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

        $data ['fechaCreacion'] = date('Y-m-d H:i:s');
        $result = $this->db->insert('clientes', $data);

        return $result;

    }

    /**
     * This method insert new data into club
     */
    public function updateCliente($idClient, $data)
    {

        $this->db->where('idCliente', $idClient);
        $data ['fechaModificacion'] = date('Y-m-d H:i:s');
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

        $this->db->select('idCliente, codigoCliente, nombres, apellidos')
        ->from('clientes c')
        ->order_by('nombres','asc'); 
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function deleteCliente($idCliente) {

        $this->db->delete('clientes', array('idCliente' => $idCliente));
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
}
