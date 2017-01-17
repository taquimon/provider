<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Delegado
 *
 * @author phantom
 */
class Delegado extends MY_Controller {
    
   public function __construct() {
        parent::__construct();
        
       $this->load->model('delegado_model', 'delegadoModel');
    }
    public function index()
    {        
        
        $delegados = $this->delegadoModel->getDelegadoList();        
        $this->data = $delegados;        
        $this->middle = 'delegados/delegadoList'; 
        $this->layout();
    }
}
