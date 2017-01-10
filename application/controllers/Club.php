<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Club
 *
 * @author phantom
 */
class Club extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('club_model', 'clubModel');
    }
    public function index()
    {
        
        $clubs = $this->clubModel->getClubList();
        
        $this->data   = $clubs;
        $this->middle = 'clubs/clubList';
        $this->layout();
    }
    public function ajaxListClub($disciplina = "Todos"){
        
        $filters = array();
        if(isset($this->request['disciplina'])){
            $filters['iddisciplina'] = $this->request['disciplina'];
            if($filters['iddisciplina']=='Todos'){
                $filters = null;
            }
        }

        $clubs = $this->clubModel->getClubList($filters);
        
        foreach($clubs as $club){
            $link = $club->idclub;
            $disciplinas = $this->getDisciplinasByClub($club->idclub, 2016);
            $club->disciplinas = $disciplinas;            
            $club->buttons =  '<a href="javascript:loadClubData('.$link.')" class="button cycle-button bg-darkCobalt fg-white"><span class="mif-pencil"></span></a>';
        } 
        $data['recordsTotal'] = count($clubs);
        $data['data'] = $clubs;
        
        echo json_encode($data);
    }
    function getDisciplinasByClub($id_club, $gestion) {
        $disciplinas = $this->clubModel->getClubXrefDisciplina($id_club, $gestion);
        $disciplinas = array_filter($disciplinas);
        $arrayDisciplinas = [];
        if(!empty($disciplinas))
        {
            $arrayDisciplinas = [];
            foreach ($disciplinas as $disc) {
                $d = $this->clubModel->getDisciplinaById($disc->iddisciplina);
                array_push($arrayDisciplinas, $d->name);
            };
        }
        return implode(",", $arrayDisciplinas);
    }

    public function ajaxGetAllClubs(){
        $clubs = $this->clubModel->getAllClubs();

        $data['recordsTotal'] = count($clubs);
        $data['data'] = $clubs;
        
        echo json_encode($data);

    }
    /**
    * Get the name of clubes
    */
    public function ajaxGetClubNames(){
        $clubs = $this->clubModel->getAllClubs();
        
        foreach($clubs as $club){
            $dataNames[] = array(
            'id' => $club->idclub,
            'name' => strtoupper($club->name)
           );        
        }                 
        
        echo json_encode($dataNames);
    }
    public function jsonGuardarNuevo()
    {
        $result = new stdClass();
        try{            
            $data['name']        = $this->request['name'];
            $data['description'] = $this->request['description'];
            $disciplinas = explode(',',$this->request['disciplinas']);
            $gestion = $this->request['gestion'];
            $data['gestion'] = $gestion;
            
            $clubData = $this->clubModel->insert($data);

            if ($clubData > 0) {                
                $this->updateDisciplinas($disciplinas, $clubData, $gestion);
                $result->message = html_message("Se agrego correctamente el nuevo Club","success");
            }

        } catch (Exception $e) {
            $result->message = "No se pudo agregar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    
    
    public function jsonGuardarClub()
    {
        $result = new stdClass();
        try{            
            $data['name']        = $this->request['name'];
            $data['description'] = $this->request['description'];
            $disciplinas = explode(',',$this->request['disciplinas']);
            $gestion = $this->request['gestion'];
            
            $idClub = $this->request['idClub'];
            
            $userData = $this->clubModel->updateClub($idClub, $data);

            if ($userData) {
                $this->updateDisciplinas($disciplinas, $idClub, $gestion);
                $result->message = html_message("Se actualizo correctamente los datos del Club","success");
            }

        } catch (Exception $e) {
            $result->message = "No se pudo actualizar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    
    public function ajaxGetClubById()
    {
        $clubId = $this->request['clubId'];
        
        $clubData = $this->clubModel->getClubById($clubId);
        
        echo json_encode($clubData);
    }

    public function updateDisciplinas($disciplinas, $idClub, $gestion) {
        if(!empty($disciplinas)) {
            /*removing all xrefs first*/
            $this->clubModel->removeClubXrefDisciplina($idClub, $gestion);
            foreach ($disciplinas as $disciplina) {
                
                $data_club_xref_disc = [];
                $data_club_xref_disc ['idclub'] = $idClub;
                $data_club_xref_disc ['iddisciplina'] = $disciplina;
                $data_club_xref_disc ['gestion'] = $gestion;

                $club_disc = $this->clubModel->insertClubXrefDisciplina($data_club_xref_disc);
                
            }
        }        
    }
}
