<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Player
 *
 * @author phantom
 */
class Player extends MY_Controller {
    
   public function __construct() {
        parent::__construct();
        
       $this->load->model('player_model', 'playerModel');
       $this->load->model('club_model', 'clubModel');
    }
    public function index()
    {        
        
        $players = $this->playerModel->getPlayerList();        
        $this->data = $players;        
        $this->middle = 'player/playerList'; 
        $this->layout();
    }
    
    public function ajaxListPlayer(){
        $club = null;
        if(isset($this->request['club'])){
            if($this->request['club'] == "Todos"){
                $club = null;
            }
            else{
                $club = $this->request['club'];
            }
        }

        $players = $this->playerModel->getPlayerList($club);

        
        foreach($players as $player){
            
            $link = $player->idjugador;
            $clubData = $this->clubModel->getClubById($player->idclub);
            $disciplinaData = $this->clubModel->getDisciplinaById($player->disciplina);

            $player->idclub = $clubData->name;
            $player->disciplina = $disciplinaData->name;
            $player->buttons =  '<a href="javascript:loadPlayerData('.$link.');" class="button cycle-button bg-darkCobalt fg-white"><span class="mif-pencil"></span></a>' ;
            //$player->notas =  '<a href="javascript:loadNotasData('.$link.')" class="button cycle-button bg-darkCobalt fg-white"><span class="mif-search"></span></a>' ;
            if($player->documento == "SI")
                $player->kardex =  '<a href="javascript:loadKardexData('.$link.');" class="button cycle-button bg-amber fg-darkCobalt"><span class="mif-profile"></span></a>' ;
            else
                $player->kardex = "NO Kardex";
                

        } 
        $data['recordsTotal'] = count($players);
        $data['data'] = $players;
        
        echo json_encode($data);
    }
    public function jsonGuardarNuevo()
    {
        $result = new stdClass();
        try{            
            $data['name']        = $this->request['name'];
            $data['last'] = $this->request['last'];
            $data['ci']        = $this->request['ci'];
            $data['fechanacimiento']        = $this->request['fechanacimiento'];
            $data['idclub']        = $this->request['club'];
            $data['parentesco']        = $this->request['parentesco'];
            $data['documento']        = $this->request['documento'];
            $data['disciplina']        = $this->request['disciplina'];
            $data['notas']        = $this->request['notas'];
            //print_r($data);
            $userData = $this->playerModel->insert($data);

            if ($userData) {
                $result->message = html_message("Se agrego correctamente el jugador","success");
            }

        } catch (Exception $e) {
            $result->message = "No se pudo agregar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    
    public function jsonGuardarPlayer()
    {
        $result = new stdClass();
        try{            
            $data['name']        = $this->request['name'];
            $data['last'] = $this->request['last'];
            $data['ci']        = $this->request['ci'];
            $data['fechanacimiento']        = $this->request['fechanacimiento'];
            $data['idclub']        = $this->request['club'];
            $data['parentesco']        = $this->request['parentesco'];
            $data['documento']        = $this->request['documento'];
            $data['disciplina']        = $this->request['disciplina'];
            $data['notas']        = $this->request['notas'];
            
            $idplayer = $this->request['idplayer'];
            //print_r($data);
            $userData = $this->playerModel->updatePlayer($idplayer, $data);

            if ($userData) {
                $result->message = html_message("Se actualizo correctamente los datos del jugador","success");
            }

        } catch (Exception $e) {
            $result->message = "No se pudo actualizar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    /**
    *
    */
    public function ajaxGetPlayerById()
    {
        $playerId = $this->request['playerId'];
        
        $playerData = $this->playerModel->getPlayerById($playerId);
        
        echo json_encode($playerData);
    }
    public function updatePlayer(){
        //players = $this->playerModel->getPlayerList();        
        //$this->data = $players;        
        $this->middle = 'player/updatePlayer'; 
        $this->layout();
    }
}
