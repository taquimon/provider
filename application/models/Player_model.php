<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Account_Model
 *
 * @author Edwin Taquichiri
 */
class Player_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->__exposedFields = array(
                                  'idCuenta',
            'titulo',
            'descripcion',
            'monto',
            'idSucursal ',
            'idUsuario',
            'esEntrada',
            'fechaAplicacionInicio',
            'fechaAplicacionFin',
            'fechaCreacion',
            'categoria'
        );
    }

    /**
     * Get the list of players
     *
     * @return array
     */
    public function getPlayerList($club = null)
    {
       
        $this->db->select('*')
                ->from('jugador c');
        if($club != null){
            $this->db->where('idclub',$club);
        }

        
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }
    
    public function getPlayerById($idjugador)
    {
        $query = $this->db->select()
                        ->where('idjugador', $idjugador)
                        ->get('jugador');

        $jugador = $query->first_row();

        if (!$jugador) {
            throw new Exception("No se encontro el jugador [$idjugador].");
        }

        return $jugador;
    }
    
    public function insert($data)
    {
                
        $data ['fechaCreacion'] = date('Y-m-d H:i:s');        
        $result = $this->db->insert('jugador', $data);                

        return $result;

    }
    
     /**
    * This method insert new data into club
    */
    public function updatePlayer($idPlayer, $data)
    {
                
        $this->db->where('idjugador', $idPlayer);
        $result = $this->db->update('jugador', $data);      


        return $result;

    }
}
