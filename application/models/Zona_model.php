<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Zonas_Model
 *
 * @author Edwin Taquichiri
 */
class Zona_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->__exposedFields = array(
            'idZona',
            'nombre',
            'descripcion',                      
        );
    }

    /**
     * Get the list of zonas
     *
     * @return array
     */
    public function getZonaList()
    {

        $this->db->select('*')
        ->from('zonas v');                

        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function getZonaById($idZona)
    {
        $query = $this->db->select()
            ->where('idZona', $idZona)
            ->get('zonas');

        $client = $query->first_row();

        if (!$client) {
            return null;
//            throw new Exception("No se encontro el Zona [$idClient].");
        }

        return $client;
    }

    public function insert($data)
    {

        //$data ['fechaCreacion'] = date('Y-m-d H:i:s');
        $result = $this->db->insert('zonas', $data);

        return $result;

    }

    /**
     * This method insert new data into Zona
     */
    public function updateZona($idZona, $data)
    {

        $this->db->where('idZona', $idZona);
        //$data ['fechaModificacion'] = date('Y-m-d H:i:s');
        $result = $this->db->update('zonas', $data);

        return $result;

    }  
    public function getZonasXrefVendedor()
    {

        $this->db->select('*')
        ->from('zonas_xref_vendedor zxv'); 
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }
     public function getZonas()
    {

        $this->db->select('idZona, nombre, descripcion')
        ->from('zonas v')
        ->order_by('nombre','asc'); 
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function deleteZona($idZona) {

        $this->db->delete('zonas', array('idZona' => $idZona));
    }

    public function getZona()
    {
        $this->db->distinct();
        $this->db->select('zonas')
            ->from('zonas c');
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }
    public function getZonasByVendedor($idVendedor) {

      $query = $this->db->select()
            ->where('idVendedor', $idVendedor)
            ->get('zona_xref_vendedor');

        $z_x_v = $query->first_row();

        if (!$z_x_v) {
            return null;
        }

        return $z_x_v;   
    }
}