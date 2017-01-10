<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of MY_Session
 *
 * @author jabber
 */
class MY_Session extends CI_Session
{
    var $sess_update_ajax_attempt   = TRUE;
    private $_params = array();
    
    function __construct($params = array())
    {
        $this->_params = $params;
        parent::__construct($params);
    }
    
    function sess_update()
    {
        $this->sess_update_ajax_attempt = (isset($this->_params['sess_update_ajax_attempt'])) ? $this->_params['sess_update_ajax_attempt'] : $this->CI->config->item('sess_update_ajax_attempt');
        if ($this->CI->input->is_ajax_request()) {
            if (($this->userdata['last_activity'] + $this->sess_time_to_update) < $this->now)
            {
                if (!$this->sess_update_ajax_attempt) {
                    log_message('debug', "Session update by ajax request avoided");
                    return;
                }
            }
        }
        parent::sess_update();
    }
}
