<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of partido
 *
 * @author phantom
 */
class Partido extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('partido_model', 'partidoModel');
        $this->load->model('club_model', 'clubModel');
        $this->load->model('partido_model', 'partidoModel');
    }
    public function index()
    {

        $partidos = $this->partidoModel->getPartidoList();
        $this->data = $partidos;
        $this->middle = 'partido/partidoList';
        $this->layout();
    }

    public function ajaxListPartido($gestion = null,$disciplina = null){

        if(isset($this->request['disciplina'])){
            $disciplina = $this->request['disciplina'];
            $gestion = $this->request['gestion'];
            if($disciplina == "Todos"){
                $disciplina = null;
            }
                
        }
        $partidos = $this->partidoModel->getPartidoList($gestion, $disciplina);

        foreach($partidos as $partido){
            $link = $partido->idpartido;
            $clubData = $this->clubModel->getClubById($partido->equipo1);      
            $clubData2 = $this->clubModel->getClubById($partido->equipo2);
            $partido->equipo1 = $clubData->name;            
            $partido->equipo2 = $clubData2->name;
            $partido->buttons =  '<a href="javascript:loadPartidoData('.$link.')" class="button cycle-button bg-cobalt fg-white"><span class="mif-pencil"></span></a>' ;            
        }
        $data['recordsTotal'] = count($partidos);
        $data['data'] = $partidos;

        echo json_encode($data);
    }
    public function jsonGuardarNuevo()
    {
        $result = new stdClass();
        try{
            $data['iddisciplina']        = $this->request['disciplina'];
            $data['goles1'] = $this->request['goles1'];
            $data['goles2']        = $this->request['goles2'];
            $data['equipo1']        = $this->request['equipo1'];
            $data['equipo2']        = $this->request['equipo2'];
            $fecha = $this->request['fecha'];
            $hora = $this->request['hora'];
            $minutos = $this->request['minuto'];
            $time = new DateTime($fecha);
            $time->setTime($hora, $minutos);
            $stamp = $time->format('Y-m-d H:i'); 
            $data['fecha']        = $stamp;
            $data['puntos1']        = $this->request['puntos1'];
            $data['puntos2']        = $this->request['puntos2'];
            $data['comments']        = $this->request['comments'];
            //print_r($data);
            $userData = $this->partidoModel->insert($data);

            if ($userData) {
                $result->message = html_message("Se agrego correctamente el partido","success");
            }

        } catch (Exception $e) {
        $result->message = "No se pudo agregar los datos ".$e->getMessage();
    }
        echo json_encode($result);
    }

    public function jsonGuardarPartido()
    {
        $result = new stdClass();
        try{
            $data['goles1'] = $this->request['goles1'];
            $data['goles2']        = $this->request['goles2'];
            $data['equipo1']        = $this->request['equipo1'];
            $data['equipo2']        = $this->request['equipo2'];
            $fecha = $this->request['fecha'];
            $hora = $this->request['hora'];
            $minutos = $this->request['minuto'];
            $time = new DateTime($fecha);
            $time->setTime($hora, $minutos);
            $stamp = $time->format('Y-m-d H:i');            
            $data['fecha']        = $stamp;
            $data['puntos1']      = $this->request['puntos1'];
            $data['puntos2']      = $this->request['puntos2'];
            $data['comments']     = $this->request['comments'];
            
            

            $idpartido = $this->request['idpartido'];
            //print_r($data);
            $userData = $this->partidoModel->updatepartido($idpartido, $data);

            if ($userData) {
                $result->message = html_message("Se actualizo correctamente los datos del partido","success");
            }

        } catch (Exception $e) {
        $result->message = "No se pudo actualizar los datos ".$e->getMessage();
    }
        echo json_encode($result);
    }
    /**
     *
     */
    public function ajaxGetpartidoById()
    {
        $partidoId = $this->request['partidoId'];

        $partidoData = $this->partidoModel->getPartidoById($partidoId);
        //print_r($partidoData);
        $fecha = $partidoData->fecha;
        $time = strtotime($fecha);
        $horas = date('G', $time);
        $minutos = date('i', $time);
        $partidoData->hora = $horas;
        $partidoData->minutos = intval($minutos);
        
        echo json_encode($partidoData);
    }
    
    public function ranking()
    {        
        //$this->data = $data;
        $this->middle = 'partido/ranking';
        $this->layout(); 
    }
    public function ajaxRanking()
    {
        if(isset($this->request['disciplina'])){
            $disciplina['iddisciplina'] = $this->request['disciplina'];    
        }else{
            $disciplina['iddisciplina'] = 1;
        }
        if(isset($this->request['gestion'])){
            $gestion = $this->request['gestion'];    
        }else{
            $gestion = 2016;
        }
           
        
        $clubes = $this->clubModel->getClubListByGestion($disciplina['iddisciplina'], $gestion);
        //print_r($clubes);
        $rankingPartidos = [];
        foreach($clubes as $club){
            $result = $this->clubModel->getClubById($club->idclub);
            $club->name = $result->name;
            $ranking = $this->partidoModel->getRanking($disciplina['iddisciplina'], $club->idclub, $gestion);
            //print_r($ranking);
            $puntos = 0;
            $pj = count($ranking);
            $pg = 0;
            $pe = 0;
            $pp = 0;
            $gf = 0;
            $gc = 0;
            $dg = 0;
            foreach($ranking as $rank){
                if( ($rank->puntos1 == 3 && $rank->equipo1 == $club->idclub) || ($rank->puntos2 == 3 && $rank->equipo2 == $club->idclub)){
                    $pg++; 
                    $puntos = $puntos + 3;    
                }
                if(($rank->puntos1 == 1 && $rank->equipo1 == $club->idclub) || ($rank->puntos2 == 1 && $rank->equipo2 == $club->idclub)){
                    $pe++; 
                    $puntos = $puntos + 1;
                }
                if(($rank->puntos1 == 0 && $rank->equipo1 == $club->idclub) || ($rank->puntos2 == 0 && $rank->equipo2 == $club->idclub)){
                    $pp++; 
                    $puntos = $puntos + 0;
                }
                if($rank->equipo1 == $club->idclub){
                    $gf = $gf + $rank->goles1;
                    $gc = $gc + $rank->goles2;
                }
                if($rank->equipo2 == $club->idclub){
                    $gf = $gf + $rank->goles2;
                    $gc = $gc + $rank->goles1;
                }
            } 
            $dg = $gf - $gc;
            $rankingData = new stdClass();
            $rankingData->club = strtoupper($club->name); 
            $rankingData->pj = $pj;
            $rankingData->pg = $pg;
            $rankingData->pe = $pe;
            $rankingData->pp = $pp;
            $rankingData->gf = $gf;
            $rankingData->gc = $gc;
            $rankingData->dg = $dg;
            $rankingData->puntos = $puntos;                    
            array_push($rankingPartidos, $rankingData);
        }
        $data['recordsTotal'] = count($rankingPartidos);
        $data['data'] = $rankingPartidos;
                
        echo json_encode($data);
        
    }
}
