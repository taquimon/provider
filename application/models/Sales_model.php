<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Sales_Model
 *
 * @author Edwin Taquichiri
 */
class Sales_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->__exposedFields = array(
            'idVendedor',
            'nombres',
            'apellidos',
            'email',
            'direccion',
            'telefono',
            'celular',
            'observaciones',
            'zona'            
        );
    }

    /**
     * Get the list of vendedores
     *
     * @return array
     */
    public function getVendedorList()
    {

        $this->db->select('*')
        ->from('vendedor v');                

        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function getVendedorById($idVendedor)
    {
        $query = $this->db->select()
            ->where('idVendedor', $idVendedor)
            ->get('vendedor');

        $client = $query->first_row();

        if (!$client) {
            return null;
//            throw new Exception("No se encontro el vendedor [$idClient].");
        }

        return $client;
    }

    public function insert($data)
    {
        
        $result = $this->db->insert('vendedor', $data);
        $insert_id = $this->db->insert_id();
        
        return $insert_id;

    }

    /**
     * This method insert new data into vendedor
     */
    public function updateVendedor($idVendedor, $data)
    {

        $this->db->where('idvendedor', $idVendedor);
        //$data ['fechaModificacion'] = date('Y-m-d H:i:s');
        $result = $this->db->update('vendedor', $data);


        return $result;

    }  
    public function getNameZonasVendedor($idVendedor)
    {
        
        $queryString = "SELECT z.nombre FROM zona_xref_vendedor zxv, zonas z WHERE zxv.idZona = z.idZona and zxv.idVendedor = ".$idVendedor;
        $query = $this->db->query($queryString);
        
        $result = $query->result();

        return $result;
    }
    public function getZonasVendedor($idVendedor)
    {
        $this->db->select('*')
               ->from('zona_xref_vendedor zxv')
               ->where('idvendedor', $idVendedor); 
            
        $query = $this->db->get();

        
        $result = $query->result();

        return $result;
    }
     public function getVendedores()
    {

        $this->db->select('idVendedor, nombres, apellidos')
        ->from('vendedor v')
        ->order_by('apellidos','desc'); 
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function deleteVendedor($idVendedor) {

        $this->db->delete('vendedor', array('idVendedor' => $idVendedor));
    }

    public function getZonas()
    {
        $this->db->distinct();
        $this->db->select('zona')
            ->from('vendedor c');
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }
    
    public function insertVendedorZonas($data)
    {        

        $result = $this->db->insert_batch('zona_xref_vendedor', $data);
        
        $insert_id = $this->db->insert_id();
        
        return $insert_id;
    }
    public function updateZonaXrefVendedor($idVendedor, $data) 
    {
        $this->db->delete('zona_xref_vendedor', array('idVendedor' => $idVendedor));
        
        $result = $this->db->insert_batch('zona_xref_vendedor', $data);        
        $insert_id = $this->db->insert_id();
        
        return $insert_id;
    }
}
