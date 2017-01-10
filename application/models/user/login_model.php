<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_Model extends MY_Model
{
        public function __construct() {
                parent::__construct();
        }
         
        public function check_login($login, $password)
    {
        $password = md5($password);
        $userModel =& $this->__get('userModel');

                $user = $userModel->getUserByLogin($login, $password);

                return $user;
    }
}

