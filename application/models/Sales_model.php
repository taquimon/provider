<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Sales_Model.
 *
 * @author Edwin Taquichiri <taquimon@gmail.com>
 */
class Sales_model extends CI_Model
{
    /**
     * __construct.
     */
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
            'zona',
        );
    }

    /**
     * Get the list of vendedores.
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

    /**
     * GetVendedorById.
     *
     * @param mixed $idVendedor id from vendedor
     *
     * @return object return the vendedor object
     */
    public function getVendedorById($idVendedor)
    {
        $query = $this->db->select()
            ->where('idVendedor', $idVendedor)
            ->get('vendedor');

        $client = $query->first_row();

        if (!$client) {
            return null;
            // throw new Exception("No se encontro el vendedor [$idClient].");
        }

        return $client;
    }

    /**
     * Insert.
     *
     * @param mixed $data the vendedor object to be inserted
     *
     * @return int return int id if success
     */
    public function insert($data)
    {
        $result = $this->db->insert('vendedor', $data);
        $insert_id = $this->db->insert_id();

        return $insert_id;
    }

    /**
     * UpdateVendedor.
     *
     * @param mixed $idVendedor id from vendedor
     * @param mixed $data       vendedor object to be updated
     *
     * @return int result of updating vendedor
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
        $queryString = 'SELECT z.nombre FROM zona_xref_vendedor zxv, zonas z WHERE zxv.idZona = z.idZona and zxv.idVendedor = '.$idVendedor;
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

    /**
     * GetVendedores.
     */
    public function getVendedores()
    {
        $this->db->select('idVendedor, nombres, apellidos')
            ->from('vendedor v')
            ->order_by('apellidos', 'desc');
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    /**
     * DeleteVendedor.
     *
     * @param mixed $idVendedor id from vendedor
     */
    public function deleteVendedor($idVendedor)
    {
        $this->db->delete('vendedor', array('idVendedor' => $idVendedor));
    }

    /**
     * GetZonas.
     *
     * @return object the zonas assigned to vendedor
     */
    public function getZonas()
    {
        $this->db->distinct();
        $this->db->select('zona')
            ->from('vendedor c');
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    /**
     * InsertVendedorZonas.
     *
     * @param mixed $data data to be inserted in table
     *
     * @return int return the id from insertion
     */
    public function insertVendedorZonas($data)
    {
        $result = $this->db->insert_batch('zona_xref_vendedor', $data);

        $insert_id = $this->db->insert_id();

        return $insert_id;
    }

    /**
     * UpdateZonaXrefVendedor.
     *
     * @param mixed $idVendedor id from vendedor
     * @param mixed $data       data to be updated
     */
    public function updateZonaXrefVendedor($idVendedor, $data)
    {
        $this->db->delete('zona_xref_vendedor', array('idVendedor' => $idVendedor));

        $result = $this->db->insert_batch('zona_xref_vendedor', $data);
        $insert_id = $this->db->insert_id();

        return $insert_id;
    }

    /**
     * Get Vendedor By user id.
     *
     * @param int $idUser id user
     *
     * @return object vendedor data
     */
    public function getVendedorByUser($idUser)
    {
        $query = $this->db->select()
            ->where('id_usuario', $idUser)
            ->get('vendedor');

        $vendedor = $query->first_row();

        if (!$vendedor) {
            return null;
            // throw new Exception("No se encontro el vendedor [$idClient].");
        }

        return $vendedor;
    }
}
