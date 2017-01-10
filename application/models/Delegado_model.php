<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Account_Model
 *
 * @author Edwin Taquichiri
 */
class Delegado_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        
    }

    /**
     * Get the list of players
     *
     * @return array
     */
    public function getDelegadoList()
    {
       
        $this->db->select('*')
                ->from('delegado c');
                //->where('fechaBorrado is null');
        
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }
    
}
