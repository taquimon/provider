<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Manage_Model
 *
 * @author Jafeth Garcia
 */
class Manage_Model extends MY_Model
{

    /**
     *
     * @var object
     */
    private $_activeUser;
    
    /**
     * @var object
     */
    private $_sucursal;
    
    /**
     * Initialize Parent data.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * set the active user currently logged into the application.
     * @param object $activeUser 
     */
	function setActiveUser($activeUser)
    {
        $userModel = & $this->__get('userModel');
        $user = $userModel->getUserById($activeUser->idUsuario);

        $this->_activeUser = $user;
    }

    /**
     * Returns TRUE or FALSE after check if the active user has the privilege 
     * required.
     * @param string $stringPrivilege if this value is not set the default value 
     * is an empty string in that case the method only verifies if the user is
     * Admin so that he can get access to all
     * @return boolean 
     */
 	function hasPrivilege($stringPrivilege = '')
    {
        if ($stringPrivilege) {
            $privilege = $this->ci->privilegeKey($stringPrivilege);
            /**
             * checking if the user has the selected privilege type.
             */
            if ($privilege == $this->_activeUser->tipoUsuario)
                return TRUE;
        }
        /**
         * If is ADMIN user grant all access.
         */
        if ($this->_activeUser->tipoUsuario == "ADMIN")
           return TRUE;

        return FALSE;
    }

    /**
     * Throws an exception whe the active user does not have proper privilege.
     * @param string $stringPrivilege
     * @throws Exception 
     */
	function checkAccess($stringPrivilege)
    {
        $hasPrivilege = $this->hasPrivilege($stringPrivilege);

        if (!$hasPrivilege)
            throw new Exception('Acceso denegado!!');
    }

    /**
     * Saves a new record into database for any table.
     * @param array $data
     * @param string $tableName
     * @return boolean 
     */
    public function saveObject($data, $tableName)
    {
        $this->db->trans_start();
        $this->db->insert($tableName, $data);
        return $this->db->trans_complete();
    }

}