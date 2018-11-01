<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Zona
 *
 * @author phantom
 */
class Zona extends MY_Controller {

   public function __construct() {
       parent::__construct();

       $this->load->model('zona_model', 'zonaModel');

   }
   public function index() {
        $zonas = $this->zonaModel->getZonaList();
        $this->data = $zonas;
        $this->middle = 'zona/zonaList';
        $this->layout();
   }

   public function ajaxListZona(){

        $zonas = $this->zonaModel->getZonaList();

	   foreach($zonas as $zona) {
            $id = $zona->idZona;
            $zona->options = '<a href="#" onclick="editZona('.$id.')" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit glyphicon-white"></i> Editar</a>&nbsp;'.
           '<a href="#" onclick="deleteZona('.$id.')" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove-sign glyphicon-white"></i> Borrar</a>';
        }
        $data['data'] = $zonas;

        echo json_encode($data);
    }
    public function newZona() {
        $this->middle = 'zona/newZona';
        $this->layout();
    }

    public function jsonGuardarNuevo()
    {
        $result = new stdClass();
        try{
            
            $data['nombre']        = $this->request['nombre'];            
            $data['descripcion']   = $this->request['descripcion'];            

            $zonaData = $this->zonaModel->insert($data);

            if ($zonaData) {
                $result->message = html_message("Se agrego correctamente la Zona","success");
            }

        } catch (Exception $e) {
            $result->message = "No se pudo agregar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    
    public function ajaxGetZonas() {
        $zonas = $this->zonaModel->getZonas();
        echo json_encode($zonas);
    }
    public function ajaxGetZonaById() {
        if(isset($this->request['idZona'])){
            $idZona = $this->request['idZona'];
        }
        $zona = $this->zonaModel->getZonaById($idZona);

        echo json_encode($zona);
    }

	public function jsonGuardarZona()
    {
        $result = new stdClass();
        try{            
            $data['nombre']    = $this->request['nombre'];
            $data['descripcion'] = $this->request['descripcion'];            

            $idZona = $this->request['idZona'];

            $zonaData = $this->zonaModel->updateZona($idZona, $data);

            if ($zonaData) {
                $result->message = "Se actualizo correctamente los datos de la Zona";
            }

        } catch (Exception $e) {
            $result->message = "No se pudo actualizar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    public function jsonEliminarZona()
    {
        $result = new stdClass();
        try{
            $idZona     = $this->request['idZona'];
            $this->zonaModel->deleteZona($idZona);
            $result->message = "Se elimino correctamente la Zona";

        } catch (Exception $e) {
            $result->message = "No se pudo eliminar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
}
