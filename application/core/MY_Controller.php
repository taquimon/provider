<?php
if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/*
*/
class MY_Controller extends CI_Controller
{
    /**
    * This varible handles all the GET and POST data.
    * @var array
    */    
    public $request = array();

    //set the class variable.
    var $template = array();
    var $data     = array();
    
    public function __construct()
    {
    
        parent::__construct();
    
        $this->load->helper(array('html','url','form','login'));
        
        $this->processRequest();
    }
        
    /**
    * Method that build the layout
    *
    */
    public function layout()
    {
        // making temlate and send data to view.
        $this->template['header'] = $this->load->view('layout/header', $this->data, true);
        $this->template['left']   = $this->load->view('layout/left', $this->data, true);
        $this->template['middle'] = $this->load->view($this->middle, $this->data, true);
        $this->template['footer'] = $this->load->view('layout/footer', $this->data, true);

        $this->load->view('layout/index', $this->template);
    }//end layout()
    
    /*
     * This function will put all post and get data into a unique variable 
     * called $request
     */
    private function processRequest()
    {
        if (isset($_GET['data'])) {
            $this->request = $this->input->get('data');
        } elseif (isset($_POST['data'])) {
            $this->request = $this->input->post('data');
        } 
        if (TRUE) {
            $post = $this->input->post() ? $this->input->post() : array();
            $get = $this->input->get() ? $this->input->get() : array();
            $this->request += $post + $get;
            unset($this->request['data']);
        }

        if (isset($_GET['layout']) || isset($_POST['layout'])) {
            $this->layout->shouldUseLayout(FALSE);
        }

        if (isset($_GET['aIndex'])) {
            $this->layout->setAIndex((int) $_GET['aIndex']);
            $this->accordionIndex = (int) $_GET['aIndex'];
        }
    }

    /**
     * Returns a user object.
     * @return object
     */
    private function getUserFromSession()
    {
        if ($this->session->userdata('idUsuario')) {
            $user = $this->userModel->getUserById(
                $this->session->userdata('idUsuario')
            );
            return $user;
        }
        return NULL;
    }

    /**
     * Set a user object in current session, returns resource full name if 
     * success and Null if fails.
     * @param string $login
     * @param string $password
     * @param boolean $rememberMe
     * @return string 
     */

    public function setUserSession($login, $password, $rememberMe = FALSE)
    {
        $user = $this->loginModel->check_login($login, $password);
        if ($user) {
            $this->session->set_userdata((array) $user);
        }
        else
            throw new Exception("Internal Error!");
    }

    /**
     * Unset current resource or possible resource logged in.
     */
    public function unsetUserSession()
    {
        $arrayItems = array(
            'idUsuario' => '', 
            'tipoUsuario' => '', 
            'nombre' => '',
            'apellidos' => '', 
            'login' => ''
        );
        $this->session->unset_userdata($arrayItems);
    }
  /**
     * Returns current user which logged in and throws and error if no user 
     * is logged in.
     * 
     * @return Object
     * @throws Exception
     */
    public function getActiveUser()
    {
        if (!isset($this->_activeUser->idUsuario)) {
            $this->layout->setLoginLayout();
            $this->session->set_flashdata("url", current_url());
            $msg = 'No se ha iniciado session o la session a expirado.';

            throw new Exception($msg);
        } else {
            $this->manageModel->setActiveUser($this->_activeUser);
            $this->layout->setMainLayout();
        }

        return $this->_activeUser;
    }

}
