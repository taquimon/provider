
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of user_model
 *
 * @author Jafeth Garcia
 */
class User_Model extends MY_Model
{       
    public function __construct() {
            parent::__construct();
            $this->_exposedFields = array('idUsuario', 'tipoUsuario', 'nombre', 'apellidos', 'login', 'datosPersonales', 'fechaIngreso');
    }
     
    /**
     * Return a user with all hos attrbutes as a object class, where each attribute 
     * is accesible in its camelized form.
     * @param integer $idUser
     * @return object
     */
    public function getUserById($idUser)
    {
        $query = $this->db->select()
                                ->where('idUsuario', $idUser)
                                ->get('usuario');

        $user = $query->first_row();

        if (!$user)
            throw new Exception("No se encontro el Usuario [$idUser].");

        return $user;
    }
     /**
     * Returns a user with limited attributes, (idUsuario, tipoUsuario, nombre, apellidos, login, datosPersonales)
     * @param string $login the login is a unique value which identifies a user.
     * @param string $encryptedPassword it should be the encrypted password string
     * @return type 
     */
    public function getUserByLogin($login, $encryptedPassword)
    {
            $query = $this->db->select('idUsuario, tipoUsuario, nombre, apellidos, login, datosPersonales, fechaInactivo')
                            ->from('usuario')
                            ->where('login', $login)
                            ->where('password', $encryptedPassword)
                            ->get();
            $user = $query->first_row();

            //validating results, if a problem was found this value should an empty array.
            if(!$user) 
            {
                    throw new Exception("El Nombre de Usuario o el Password es invalido!!!");
            }

    if ($user->fechaInactivo) {
        throw new Exception("Usuario Inactivo!!!");
    }
            return $user;
    }
}