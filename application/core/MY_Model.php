<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 */
class MY_Model extends CI_Model
{

    /**
     *
     * @var CI_DB_active_record
     */
    public $db;

    /**
     * Doctrine entity manager
     *
     * @var Doctrine\ORM\EntityManager
     */
    public $em;
    
    /**
     *
     * @var MY_Controller
     */
    public $ci;
    
    protected $_queryMethods = array(
        "IGUAL" => " = ",
        "MENOR_QUE" => " < ",
        "MAYOR_QUE" => " > ",
        "NO_IGUAL" => " <> ",
        "MENOR_IGUAL_QUE" => " <= ",
        "MAYOR_IGUAL_QUE" => " >= ",
        "CONTIENE" => " LIKE "
    );

    /**
     * will handle the atributes that a table should expose, filled by the 
     * model.
     * @var array
     */
    protected $_exposedFields = array();

    public function __construct()
    {
        parent::__construct();
        //$this->em = $this->doctrine->em;
        $this->db = $this->load->database('default', TRUE);
        $this->ci =& get_instance();
    }

    /**
     * Adds an alias value as prefix in the exposed files.
     * @param string $alias
     * @return string 
     */
    public function getExposedFields($exposedFields, $alias = '')
    {
        if (!$alias)
            return $exposedFields;

        $result = array();
        foreach ($exposedFields as $exposedField) {
            $result[] = $alias . '.' . $exposedField;
        }

        return $result;
    }

    /**
     * 
     * @param object $object
     * @param string $key
     * @param string $value
     * @return type
     * @throws Exception
     */
    public function toDropDownArray($object, $key, $value)
    {
        $errMsg = "No valid key or value provided to convert to drop down.";
        if (is_object($object)) {
            if (isset($object->$key) && isset($object->$value))
                return array($object->$key => $object->$value);
            else
                throw new Exception($errMsg);
        } elseif (is_array($object)) {
            $result = array();
            foreach ($object as $obj) {
                if (is_object($obj)) {
                    if (is_array($value)) {
                        $contactValue = '';
                        foreach($value as $val){
                            if (isset($obj->$val))
                                $contactValue .= $obj->$val.' ';
                            else 
                                throw new Exception($errMsg);
                        }
                        if (isset($obj->$key))
                            $result[$obj->$key] = trim($contactValue);
                        else
                            throw new Exception($errMsg);
                    } else {
                        if (isset($obj->$key) && isset($obj->$value))
                            $result[$obj->$key] = $obj->$value;
                        else
                            throw new Exception($errMsg);
                    }
                }
            }
            return $result;
        }
        else
            return array();
    }

    /**
     *
     * @param array $filterOptions format example array(
     * array(
     *  "field" => "issueStatus", 
     *  "method" => "CONTAINS", 
     *  "value" => "OPEN")
     * )
     * @param string $prefix the prefix used as alias in the query.
     * @param array $extraPrefix array of arrays each element must have a 
     * key and the value is an other array with 'prefix' and 'field' values
     * Example (array(
     *  "issueStatus" => array ("prefix" => "is", "field" = "issueStatusId")))
     * @return string 
     */
    function processQueryFilter($filterOptions, $prefix, $extraPrefix = array())
    {
        $result = '';
        $defaultMethod = "CONTIENE";
        $logicalOperator = "AND";

        if (!is_array($filterOptions))
            return "";

        foreach ($filterOptions as $filter) {
            if (key_exists("field", $filter) && key_exists("value", $filter)) {
                $method = $defaultMethod;
                if (key_exists("method", $filter))
                    $method = strtoupper($filter["method"]);

                if (key_exists($method, $this->_queryMethods)) {
                    if (key_exists($filter['field'], $extraPrefix)) {
                        $field = $extraPrefix[$filter['field']]['prefix'] . 
                            '.' . $extraPrefix[$filter['field']]['field'];
                    } else {
                        $field = $prefix . "." . $filter['field'];
                    }

                    if ($filter['value'] != "") {
                        $value = $this->db->escape($filter['value']) . " ";
                        if ($method == "CONTIENE")
                            $value = "'%" . $filter['value'] . "%' ";

                        if (key_exists("operator", $filter))
                            $logicalOperator = $filter['operator'];

                        if ($result)
                            $result .= $logicalOperator . " " . $field . 
                                $this->_queryMethods[$method] . $value;
                        else
                            $result .= $field . 
                                $this->_queryMethods[$method] . $value;
                    }
                }
            }
        }
        return $result;
    }
    
    /**
     * If the result provided is not valid it will throw and exception
     * and the rollback any database transaction in progress
     * @param type $result
     * @return type
     * @throws Exception
     */
    function validateDBTransactionResult($result)
    {
        if (!$result) {
            $errorMessage = "Error de base de datos - " .
                $this->db->_error_message();
            $this->db->trans_rollback();
            throw new Exception($errorMessage);
        }
        return;
    }
}