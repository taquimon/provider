<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Form Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/form_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Form Declaration
 *
 * Creates the opening portion of the form.
 *
 * @access	public
 * @param	string	the URI segments of the form destination
 * @param	array	a key/value pair of attributes
 * @param	array	a key/value pair hidden data
 * @return	string
 */
if ( ! function_exists('form_open'))
{
	function form_open($action = '', $attributes = '', $hidden = array())
	{
		$ci =& get_instance();

		if ($attributes == '')
		{
			$attributes = 'method="post"';
		}

		// If an action is not a full URL then turn it into one
		if ($action && strpos($action, '://') === FALSE)
		{
			$action = $ci->config->site_url($action);
		}

		// If no action is provided then set to the current url
		$action OR $action = $ci->config->site_url($ci->uri->uri_string());

		$form = '<form action="'.$action.'"';

		$form .= _attributes_to_string($attributes, TRUE);

		$form .= '>';

		// CSRF
		if ($ci->config->item('csrf_protection') === TRUE)
		{
			$hidden[$ci->security->get_csrf_token_name()] = $ci->security->get_csrf_hash();
		}

		if (is_array($hidden) AND count($hidden) > 0)
		{
			$form .= sprintf("\n<div class=\"hidden\">%s</div>", form_hidden($hidden));
		}

		return $form;
	}
}

// ------------------------------------------------------------------------

/**
 * Form Declaration - Multipart type
 *
 * Creates the opening portion of the form, but with "multipart/form-data".
 *
 * @access	public
 * @param	string	the URI segments of the form destination
 * @param	array	a key/value pair of attributes
 * @param	array	a key/value pair hidden data
 * @return	string
 */
if ( ! function_exists('form_open_multipart'))
{
	function form_open_multipart($action, $attributes = array(), $hidden = array())
	{
		if (is_string($attributes))
		{
			$attributes .= ' enctype="multipart/form-data"';
		}
		else
		{
			$attributes['enctype'] = 'multipart/form-data';
		}

		return form_open($action, $attributes, $hidden);
	}
}

// ------------------------------------------------------------------------

/**
 * Hidden Input Field
 *
 * Generates hidden fields.  You can pass a simple key/value string or an associative
 * array with multiple values.
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_hidden'))
{
	function form_hidden($name, $value = '', $recursing = FALSE)
	{
		static $form;

		if ($recursing === FALSE)
		{
			$form = "\n";
		}

		if (is_array($name))
		{
			foreach ($name as $key => $val)
			{
				form_hidden($key, $val, TRUE);
			}
			return $form;
		}

		if ( ! is_array($value))
		{
			$form .= '<input type="hidden" name="'._process_form_name($name).'" value="'.form_prep($value, _process_form_name($name)).'" />'."\n";
		}
		else
		{
			foreach ($value as $k => $v)
			{
				$k = (is_int($k)) ? '' : $k;
				form_hidden($name.'['.$k.']', $v, TRUE);
			}
		}

		return $form;
	}
}

// ------------------------------------------------------------------------

/**
 * Text Input Field
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_input'))
{
	function form_input($data = '', $value = '', $extra = '')
	{
        
		$defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? _process_form_name($data) : ''), 'value' => $value);

		return "<input "._parse_form_attributes($data, $defaults).$extra." />";
	}
}

// ------------------------------------------------------------------------

/**
 * Password Field
 *
 * Identical to the input function but adds the "password" type
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_password'))
{
	function form_password($data = '', $value = '', $extra = '')
	{
		if ( ! is_array($data))
		{
			$data = array('name' => _process_form_name($data));
		}
        
        if (isset($data['class']))
            $data['class'] .= ' text ui-widget-content ui-corner-all';
        else
            $data['class'] = 'text ui-widget-content ui-corner-all';
		$data['type'] = 'password';
		return form_input($data, $value, $extra);
	}
}

// ------------------------------------------------------------------------

/**
 * Upload Field
 *
 * Identical to the input function but adds the "file" type
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_upload'))
{
	function form_upload($data = '', $value = '', $extra = '')
	{
		if ( ! is_array($data))
		{
			$data = array('name' => _process_form_name($data));
		}

		$data['type'] = 'file';
		return form_input($data, $value, $extra);
	}
}

// ------------------------------------------------------------------------

/**
 * Textarea field
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_textarea'))
{
	function form_textarea($data = '', $value = '', $extra = '')
	{
        if ( ! is_array($data) ) {
            if ( ! strpos($extra, 'class')) {
                $extra .= ' class="textarea ui-corner-all"';
            }
        } elseif ( is_array($data) ) {
            if (isset($data['class']))
                $data['class'] .= ' textarea  ui-corner-all';
            else
                $data['class'] = 'textarea ui-corner-all';
        }
        
		$defaults = array('name' => (( ! is_array($data)) ? _process_form_name($data) : ''), 'cols' => '50', 'rows' => '5');

		if ( ! is_array($data) OR ! isset($data['value']))
		{
			$val = $value;
		}
		else
		{
			$val = $data['value'];
			unset($data['value']); // textareas don't use the value attribute
		}

		$name = _process_form_name((is_array($data)) ? $data['name'] : $data);
		return "<textarea "._parse_form_attributes($data, $defaults).$extra.">".form_prep($val, $name)."</textarea>";
	}
}

// ------------------------------------------------------------------------

/**
 * Multi-select menu
 *
 * @access	public
 * @param	string
 * @param	array
 * @param	mixed
 * @param	string
 * @return	type
 */
if ( ! function_exists('form_multiselect'))
{
	function form_multiselect($name = '', $options = array(), $selected = array(), $extra = '')
	{
		if ( ! strpos($extra, 'multiple'))
		{
			$extra .= ' multiple="multiple"';
		}
		
		if ( ! strpos($extra, 'class'))
		{
			$extra .= ' class="multiselect"';
		}
		
		if ( ! strpos($extra, 'humanize'))
		{
			$extra .= ' humanize="humanize"';
		}
		
		return form_dropdown($name, $options, $selected, $extra, 'ARRAY');
	}
}

// --------------------------------------------------------------------

/**
 * Drop-down Menu
 *
 * @access	public
 * @param	string
 * @param	array
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_dropdown'))
{
	function form_dropdown($name = '', $options = array(), $selected = array(), $extra = '', $attributeNameNiceEnd = '')
	{
		if ( ! is_array($selected))
		{
			$selected = array($selected);
		}

		// If no selected state was submitted we will attempt to set it automatically
		if (count($selected) === 0)
		{
			// If the form name appears in the $_POST array we have a winner!
			if (isset($_POST[$name]))
			{
				$selected = array($_POST[$name]);
			}
		}

		if ($extra != '') $extra = ' '.$extra;
		
        if ( ! strpos($extra, 'class')) {
            $extra .= ' class=""';
        }
		
        $humanize = ((strpos($extra, 'humanize') === FALSE) ? FALSE : (function_exists("humanize") ? TRUE : FALSE));
		
		$multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

		$form = '<select name="'._process_form_name($name, $attributeNameNiceEnd).'"'.$extra.$multiple.">\n";

		foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if (is_array($val) && ! empty($val))
			{
				$form .= '<optgroup label="'.$key.'">'."\n";

				foreach ($val as $optgroupKey => $optgroupVal)
				{
					$sel = (in_array($optgroupKey, $selected)) ? ' selected="selected"' : '';

					$form .= '<option value="'.$optgroupKey.'"'.$sel.'>'.(string) ($humanize ? humanize($optgroupVal) : $optgroupVal)."</option>\n";
				}

				$form .= '</optgroup>'."\n";
			}
			else
			{
				$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';

				$form .= '<option value="'.$key.'"'.$sel.'>'.(string) ($humanize ? humanize($val) : $val)."</option>\n";
			}
		}

		$form .= '</select>';

		return $form;
	}
}

// ------------------------------------------------------------------------

/**
 * Checkbox Field
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	bool
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_checkbox'))
{
	function form_checkbox($data = '', $value = '', $checked = FALSE, $extra = '', $attributeNameNiceEnd = '')
	{
		$defaults = array('type' => 'checkbox', 'name' => (( ! is_array($data)) ? _process_form_name($data, $attributeNameNiceEnd) : ''), 'value' => $value);

		if (is_array($data) AND array_key_exists('checked', $data))
		{
			$checked = $data['checked'];

			if ($checked == FALSE)
			{
				unset($data['checked']);
			}
			else
			{
				$data['checked'] = 'checked';
			}
		}

		if ($checked == TRUE)
		{
			$defaults['checked'] = 'checked';
		}
		else
		{
			unset($defaults['checked']);
		}

		return "<input "._parse_form_attributes($data, $defaults, $attributeNameNiceEnd).$extra." />";
	}
}

// ------------------------------------------------------------------------

/**
 * Radio Button
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	bool
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_radio'))
{
	function form_radio($data = '', $value = '', $checked = FALSE, $extra = '', $attributeNameNiceEnd = '')
	{
		if ( ! is_array($data))
		{
			$data = array('name' => _process_form_name($data));
		}

		$data['type'] = 'radio';
		return form_checkbox($data, $value, $checked, $extra, $attributeNameNiceEnd);
	}
}

// ------------------------------------------------------------------------

/**
 * Submit Button
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_submit'))
{
	function form_submit($data = '', $value = '', $extra = '')
	{
		$defaults = array('type' => 'submit', 'name' => (( ! is_array($data)) ? _process_form_name($data, "FORM_SUBMIT") : ''), 'value' => $value);

		return "<input "._parse_form_attributes($data, $defaults, "FORM_SUBMIT").$extra." />";
	}
}

// ------------------------------------------------------------------------

/**
 * Reset Button
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_reset'))
{
	function form_reset($data = '', $value = '', $extra = '')
	{
		$defaults = array('type' => 'reset', 'name' => (( ! is_array($data)) ? _process_form_name($data) : ''), 'value' => $value);

		return "<input "._parse_form_attributes($data, $defaults).$extra." />";
	}
}

// ------------------------------------------------------------------------

/**
 * Form Button
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_button'))
{
	function form_button($data = '', $content = '', $extra = '')
	{
		$defaults = array('name' => (( ! is_array($data)) ? _process_form_name($data, "BUTTON") : ''), 'type' => 'button');

		if ( is_array($data) AND isset($data['content']))
		{
			$content = $data['content'];
			unset($data['content']); // content is not an attribute
		}

		return "<button "._parse_form_attributes($data, $defaults, "BUTTON").$extra.">".$content."</button>";
	}
}

// ------------------------------------------------------------------------

/**
 * Form Label Tag
 *
 * @access	public
 * @param	string	The text to appear onscreen
 * @param	string	The id the label applies to
 * @param	string	Additional attributes
 * @return	string
 */
if ( ! function_exists('form_label'))
{
	function form_label($labelText = '', $id = '', $attributes = array())
	{

		$label = '<label';

		if ($id != '')
		{
			$label .= " for=\"$id\"";
		}

		if (is_array($attributes) AND count($attributes) > 0)
		{
			foreach ($attributes as $key => $val)
			{
				$label .= ' '.$key.'="'.$val.'"';
			}
		}

		$label .= ">$labelText</label>";

		return $label;
	}
}

// ------------------------------------------------------------------------
/**
 * Fieldset Tag
 *
 * Used to produce <fieldset><legend>text</legend>.  To close fieldset
 * use form_fieldset_close()
 *
 * @access	public
 * @param	string	The legend text
 * @param	string	Additional attributes
 * @return	string
 */
if ( ! function_exists('form_fieldset'))
{
	function form_fieldset($legendText = '', $attributes = array())
	{
		$fieldset = "<fieldset";

		$fieldset .= _attributes_to_string($attributes, FALSE);

		$fieldset .= ">\n";

		if ($legendText != '')
		{
			$fieldset .= "<legend>$legendText</legend>\n";
		}

		return $fieldset;
	}
}

// ------------------------------------------------------------------------

/**
 * Fieldset Close Tag
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_fieldset_close'))
{
	function form_fieldset_close($extra = '')
	{
		return "</fieldset>".$extra;
	}
}

// ------------------------------------------------------------------------

/**
 * Form Close Tag
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_close'))
{
	function form_close($extra = '')
	{
		return "</form>".$extra;
	}
}

// ------------------------------------------------------------------------

/**
 * Form Prep
 *
 * Formats text so that it can be safely placed in a form field in the event it has HTML tags.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_prep'))
{
	function form_prep($str = '', $fieldName = '')
	{
		static $preppedFields = array();

		// if the field name is an array we do this recursively
		if (is_array($str))
		{
			foreach ($str as $key => $val)
			{
				$str[$key] = form_prep($val);
			}

			return $str;
		}

		if ($str === '')
		{
			return '';
		}

		// we've already prepped a field with this name
		// @todo need to figure out a way to namespace this so
		// that we know the *exact* field and not just one with
		// the same name
		if (isset($preppedFields[$fieldName]))
		{
			return $str;
		}

		$str = htmlspecialchars($str);

		// In case htmlspecialchars misses these.
		$str = str_replace(array("'", '"'), array("&#39;", "&quot;"), $str);

		if ($fieldName != '')
		{
			$preppedFields[$fieldName] = $fieldName;
		}

		return $str;
	}
}

// ------------------------------------------------------------------------

/**
 * Form Value
 *
 * Grabs a value from the POST array for the specified field so you can
 * re-populate an input field or textarea.  If Form Validation
 * is active it retrieves the info from the validation class
 *
 * @access	public
 * @param	string
 * @return	mixed
 */
if ( ! function_exists('set_value'))
{
	function set_value($field = '', $default = '')
	{
		if (FALSE === ($obj =& _get_validation_object()))
		{
			if ( ! isset($_POST[$field]))
			{
				return $default;
			}

			return form_prep($_POST[$field], $field);
		}

		return form_prep($obj->set_value($field, $default), $field);
	}
}

// ------------------------------------------------------------------------

/**
 * Set Select
 *
 * Let's you set the selected value of a <select> menu via data in the POST array.
 * If Form Validation is active it retrieves the info from the validation class
 *
 * @access	public
 * @param	string
 * @param	string
 * @param	bool
 * @return	string
 */
if ( ! function_exists('set_select'))
{
	function set_select($field = '', $value = '', $default = FALSE)
	{
		$obj =& _get_validation_object();

		if ($obj === FALSE)
		{
			if ( ! isset($_POST[$field]))
			{
				if (count($_POST) === 0 AND $default == TRUE)
				{
					return ' selected="selected"';
				}
				return '';
			}

			$field = $_POST[$field];

			if (is_array($field))
			{
				if ( ! in_array($value, $field))
				{
					return '';
				}
			}
			else
			{
				if (($field == '' OR $value == '') OR ($field != $value))
				{
					return '';
				}
			}

			return ' selected="selected"';
		}

		return $obj->set_select($field, $value, $default);
	}
}

// ------------------------------------------------------------------------

/**
 * Set Checkbox
 *
 * Let's you set the selected value of a checkbox via the value in the POST array.
 * If Form Validation is active it retrieves the info from the validation class
 *
 * @access	public
 * @param	string
 * @param	string
 * @param	bool
 * @return	string
 */
if ( ! function_exists('set_checkbox'))
{
	function set_checkbox($field = '', $value = '', $default = FALSE)
	{
		$obj =& _get_validation_object();

		if ($obj === FALSE)
		{
			if ( ! isset($_POST[$field]))
			{
				if (count($_POST) === 0 AND $default == TRUE)
				{
					return ' checked="checked"';
				}
				return '';
			}

			$field = $_POST[$field];

			if (is_array($field))
			{
				if ( ! in_array($value, $field))
				{
					return '';
				}
			}
			else
			{
				if (($field == '' OR $value == '') OR ($field != $value))
				{
					return '';
				}
			}

			return ' checked="checked"';
		}

		return $obj->set_checkbox($field, $value, $default);
	}
}

// ------------------------------------------------------------------------

/**
 * Set Radio
 *
 * Let's you set the selected value of a radio field via info in the POST array.
 * If Form Validation is active it retrieves the info from the validation class
 *
 * @access	public
 * @param	string
 * @param	string
 * @param	bool
 * @return	string
 */
if ( ! function_exists('set_radio'))
{
	function set_radio($field = '', $value = '', $default = FALSE)
	{
		$obj =& _get_validation_object();

		if ($obj === FALSE)
		{
			if ( ! isset($_POST[$field]))
			{
				if (count($_POST) === 0 AND $default == TRUE)
				{
					return ' checked="checked"';
				}
				return '';
			}

			$field = $_POST[$field];

			if (is_array($field))
			{
				if ( ! in_array($value, $field))
				{
					return '';
				}
			}
			else
			{
				if (($field == '' OR $value == '') OR ($field != $value))
				{
					return '';
				}
			}

			return ' checked="checked"';
		}

		return $obj->set_radio($field, $value, $default);
	}
}

// ------------------------------------------------------------------------

/**
 * Form Error
 *
 * Returns the error for a specific form field.  This is a helper for the
 * form validation class.
 *
 * @access	public
 * @param	string
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_error'))
{
	function form_error($field = '', $prefix = '', $suffix = '')
	{
		if (FALSE === ($obj =& _get_validation_object()))
		{
			return '';
		}

		return $obj->error($field, $prefix, $suffix);
	}
}

// ------------------------------------------------------------------------

/**
 * Validation Error String
 *
 * Returns all the errors associated with a form submission.  This is a helper
 * function for the form validation class.
 *
 * @access	public
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('validation_errors'))
{
	function validation_errors($prefix = '', $suffix = '')
	{
		if (FALSE === ($obj =& _get_validation_object()))
		{
			return '';
		}

		return $obj->error_string($prefix, $suffix);
	}
}

// ------------------------------------------------------------------------

/**
 * Parse the form attributes
 *
 * Helper function used by some of the form helpers
 *
 * @access	private
 * @param	array
 * @param	array
 * @return	string
 */
if ( ! function_exists('_parse_form_attributes'))
{
	function _parse_form_attributes($attributes, $default, $attNameNiceEnd = "")
	{
		if (is_array($attributes))
		{
			foreach ($default as $key => $val)
			{
				if (isset($attributes[$key]))
				{
					/**
					 * adding attruibute name check
					 */
					if ($key == "name"){
						$attributes[$key] = _process_form_name($attributes[$key], $attNameNiceEnd);
					}
					
					$default[$key] = $attributes[$key];
					unset($attributes[$key]);
				}
			}

			if (count($attributes) > 0)
			{
				$default = array_merge($default, $attributes);
			}
		}

		$att = '';

		foreach ($default as $key => $val)
		{
			if ($key == 'value')
			{
				$val = form_prep($val, $default['name']);
			}

			$att .= $key . '="' . $val . '" ';
		}

		return $att;
	}
}

// ------------------------------------------------------------------------

/**
 * Attributes To String
 *
 * Helper function used by some of the form helpers
 *
 * @access	private
 * @param	mixed
 * @param	bool
 * @return	string
 */
if ( ! function_exists('_attributes_to_string'))
{
	function _attributes_to_string($attributes, $formtag = FALSE)
	{
		if (is_string($attributes) AND strlen($attributes) > 0)
		{
			if ($formtag == TRUE AND strpos($attributes, 'method=') === FALSE)
			{
				$attributes .= ' method="post"';
			}
			
			if ($formtag == TRUE AND strpos($attributes, 'accept-charset=') === FALSE)
			{
				$attributes .= ' accept-charset="'.strtolower(config_item('charset')).'"';
			}

		return ' '.$attributes;
		}

		if (is_object($attributes) AND count($attributes) > 0)
		{
			$attributes = (array)$attributes;
		}

		if (is_array($attributes) AND count($attributes) > 0)
		{
			$atts = '';

			if ( ! isset($attributes['method']) AND $formtag === TRUE)
			{
				$atts .= ' method="post"';
			}
			
			/**
			 * adding atribute name check
			 */
			if ( isset($attributes['name']) )
			{
				if ($formtag === TRUE)
					$atts .= ' name="'. _process_form_name($attributes['name'], "FORM") .'"';
				else
					$atts .= ' name="'. _process_form_name($attributes['name']) .'"';
			}

			if ( ! isset($attributes['accept-charset']) AND $formtag === TRUE)
			{
				$atts .= ' accept-charset="'.strtolower(config_item('charset')).'"';
			}

			foreach ($attributes as $key => $val)
			{
				$atts .= ' '.$key.'="'.$val.'"';
			}

			return $atts;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * Validation Object
 *
 * Determines what the form validation class was instantiated as, fetches
 * the object and returns it.
 *
 * @access	private
 * @return	mixed
 */
if ( ! function_exists('_get_validation_object'))
{
	function &_get_validation_object()
	{
		$ci =& get_instance();

		// We set this as a variable since we're returning by reference
		$return = FALSE;

		if ( ! isset($ci->load->_ci_classes) OR  ! isset($ci->load->_ci_classes['form_validation']))
		{
			return $return;
		}

		$object = $ci->load->_ci_classes['form_validation'];

		if ( ! isset($ci->$object) OR ! is_object($ci->$object))
		{
			return $return;
		}

		return $ci->$object;
	}
}

// ------------------------------------------------------------------------

/**
 * Process Form Name
 *
 * Determines if the name has dots then it will convert the name into a data format to be eaily
 * serialized from javascript.
 *
 * @access	private
 * @param string
 * @param string
 * @return	string
 */
if ( ! function_exists('_process_form_name'))
{
	function _process_form_name($name, $niceEnd = "")
	{
		$arrayName = explode('.', $name);
		
		if ( count($arrayName) <= 1 )
			return $name;
		
		$newName = 'data';
		foreach ($arrayName as $portionName) {
			$newName .= '['.$portionName.']';
		}
		
        switch ($niceEnd) {
            case "ARRAY" : $newName .= '[]'; break;
            default : 
                if ($niceEnd) {
                    $newName .= '_'.strtolower($niceEnd);
                }; 
                break;
        }
		
		return $newName;
	}
}

/* End of file form_helper.php */
/* Location: ./application/helpers/form_helper.php */