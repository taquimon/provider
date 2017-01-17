<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Account_Model
 *
 * @author Edwin Taquichiri
 */
class Club_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        
    }

    /**
     * Get the list of clubs
     *
     * @return array
     */
    public function getClubList($filters = null)
    {

        $this->db->select('idclub')
                ->from('club_disc_xref cd');

        if($filters != null){
            $this->db->where($filters);
        }

        $query = $this->db->get();

        $result = $query->result();

        $array_ids = array();
        foreach($result as $r){
            array_push($array_ids, $r->idclub);
        }

        $this->db->select('*')
                ->from('club c');
            if($array_ids != null) {
                $this->db->where_in('idclub',$array_ids);
            }
        $queryFinal = $this->db->get();
        $res =  $queryFinal->result();

        return $res;
    }

    function getAllClubs(){
        $this->db->distinct();
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('club');

        return $query->result();
    }
    /**
    * This method insert new data into club
    */
    public function insert($data)
    {
                
        $data ['fechaCreacion'] = date('Y-m-d H:i:s');        
        $result = $this->db->insert('club', $data);                
        $insert_id = $this->db->insert_id();
        
        return $insert_id;

    }

    public function insertClubXrefDisciplina($data) {
        //$data ['fechaCreacion'] = date('Y-m-d H:i:s');        
        $result = $this->db->insert('club_disc_xref', $data);
        $insert_id = $this->db->insert_id();
        
        return $insert_id;        
    }
    
    public function getClubById($idClub)
    {
        $query = $this->db->select()
                        ->where('idclub', $idClub)
                        ->get('club');

        $club = $query->first_row();

        if (!$club) {
            throw new Exception("No se encontro el club [$idClub].");
        }

        return $club;
    }
    public function getDisciplinaById($idDisciplina)
    {
        $query = $this->db->select()
            ->where('iddisciplina', $idDisciplina)
            ->get('disciplina');

        $club = $query->first_row();

        if (!$club) {
            throw new Exception("No se encontro la disciplina [$idDisciplina].");
        }

        return $club;
    }
     /**
    * This method insert new data into club
    */
    public function updateClub($idClub, $data)
    {
                
        $this->db->where('idclub', $idClub);
        $result = $this->db->update('club', $data);      
        
        return $result;

    }
    public function getClubXrefDisciplina($idClub, $gestion, $idDisciplina = null) {
           $this->db->select()
            ->where('idclub', $idClub)
            ->where('gestion', $gestion);
            if($idDisciplina != null) {
                $this->db->where('idDisciplina', $idDisciplina);
            }
            $query = $this->db->get('club_disc_xref');

        $club = $query->result();

        return $club;
    }

    public function  removeClubXrefDisciplina($idClub, $gestion){
        $this->db->where('idClub', $idClub);
        $this->db->where('gestion', $gestion);
        $this->db->delete('club_disc_xref');
    }

    public function getClubListByGestion($disciplina, $gestion) {
        $query = $this->db->select()
                ->where('iddisciplina', $disciplina)
                ->where('gestion', $gestion)                        
                ->get('club_disc_xref');

        $club = $query->result();

        return $club;
    }
}
