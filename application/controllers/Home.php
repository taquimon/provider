<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Home extends MY_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/home
     *	- or -
     * 		http://example.com/index.php/home/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     *
     * @see http://codeigniter.com/user_guide/general/urls.html
     */ 
    public function __construct() {
        parent::__construct();
         
    }
    public function index()
    {
        $this->load->helper(array('html','url'));
        $this->middle = 'home'; // passing middle to function. change this for different views.
        $this->layout();
    }
    public function login_layout()
    {
        //$this->load->helper(array('html','url'));
        //$this->middle = 'login'; 
        //$this->layout();   
        $this->load->view('login_layout');
    }

    public function login()
    {
        if (isset($this->request['username']) 
            && isset($this->request['password'])) {
            try {
                if (!$this->request['username'] || !$this->request['password'])
                    throw new Exception(
                        "Nombre de Usuario y Password son requeridos!"
                    );

                $this->setUserSession(
                    $this->request['username'],
                    $this->request['password']
                );
            } catch (Exception $e) {
                $this->session->set_flashdata(
                    'msg',
                    "<h4>" . $e->getMessage() . "</h4>"
                );
            }

            $uri = "";
            if ($this->session->flashdata("url"))
                $uri = $this->session->flashdata("url");
            redirect($uri);
        } else {
            $this->index();
        }

    }

    public function logout()
    {
        $this->unsetUserSession();
        redirect();
    }

}
