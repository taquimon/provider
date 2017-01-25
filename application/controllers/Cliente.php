<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Client
 *
 * @author phantom
 */
class Cliente extends MY_Controller {

   public function __construct() {
       parent::__construct();

       $this->load->model('client_model', 'clientModel');

   }
   public function index() {
        $clients = $this->clientModel->getClientList();
        $this->data = $clients;
        $this->middle = 'clients/clientList';
        $this->layout();
   }

   public function ajaxListClient(){

        $clients = $this->clientModel->getClientList();

	   foreach($clients as $client) {
            $id = $client->idCliente;
            $client->options = '<a href="#" onclick="editClient('.$id.')" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit glyphicon-white"></i> Editar</a>&nbsp;';


        }
        $data['data'] = $clients;

        echo json_encode($data);
    }
    public function newClient() {
        $this->middle = 'clients/newClient';
        $this->layout();
    }

    public function jsonGuardarNuevo()
    {
        $result = new stdClass();
        try{

            $data['codigoCliente']  = $this->request['codigoCliente'];
            $data['nombres']        = $this->request['nombres'];
            $data['apellidos']      = $this->request['apellidos'];
            $data['email']          = $this->request['email'];
            $data['direccion']      = $this->request['direccion'];
            $data['telefono']       = $this->request['telefono'];
            $data['celular']        = $this->request['celular'];
            $data['razonSocial']    = $this->request['razonSocial'];
            $data['observaciones']  = $this->request['observaciones'];
            $data['zona']           = $this->request['zona'];
            $data['tipoCliente']    = $this->request['tipoCliente'];

            $clientData = $this->clientModel->insert($data);

            if ($clientData) {
                $result->message = html_message("Se agrego correctamente el cliente","success");
            }

        } catch (Exception $e) {
            $result->message = "No se pudo agregar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
    public function ajaxGetTipoClientes() {
        $tipoClientes = $this->clientModel->getTipoClientes();
        echo json_encode($tipoClientes);
    }
    public function ajaxGetClientes() {
        $clientes = $this->clientModel->getClientes();
        echo json_encode($clientes);
    }
    public function ajaxGetClienteById() {
        if(isset($this->request['idCliente'])){
            $idClient = $this->request['idCliente'];
        }
        $cliente = $this->clientModel->getClientById($idClient);

        echo json_encode($cliente);
    }
	public function jsonGuardarCliente()
    {
        $result = new stdClass();
        try{
            $data['codigoCliente']  = $this->request['codigoCliente'];
            $data['nombres']    = $this->request['nombres'];
            $data['apellidos']       = $this->request['apellidos'];
            $data['direccion']    = $this->request['direccion'];
            $data['email'] = $this->request['email'];
            $data['telefono'] = $this->request['telefono'];
			$data['celular'] = $this->request['celular'];
			$data['zona'] = $this->request['zona'];
            $data['razonSocial'] = $this->request['razonSocial'];
			$data['observaciones'] = $this->request['observaciones'];
			$data['tipoCliente'] = $this->request['tipoCliente'];


            $idCliente = $this->request['idCliente'];

            $clientData = $this->clientModel->updateCliente($idCliente, $data);

            if ($clientData) {
                $result->message = "Se actualizo correctamente los datos del cliente";
            }

        } catch (Exception $e) {
            $result->message = "No se pudo actualizar los datos ".$e->getMessage();
        }
        echo json_encode($result);
    }
}
