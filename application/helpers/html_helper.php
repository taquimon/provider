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
 * CodeIgniter HTML Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/html_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Heading
 *
 * Generates an HTML heading tag.  First param is the data.
 * Second param is the size of the heading tag.
 *
 * @access	public
 * @param	string
 * @param	integer
 * @return	string
 */
if ( ! function_exists('heading'))
{
	function heading($data = '', $h = '1')
	{
		return "<h".$h.">".$data."</h".$h.">"."\n";
	}
}

// ------------------------------------------------------------------------

/**
 * Unordered List
 *
 * Generates an HTML unordered list from an single or multi-dimensional array.
 *
 * @access	public
 * @param	array
 * @param	mixed
 * @return	string
 */
if ( ! function_exists('ul'))
{
	function ul($list, $attributes = '')
	{
		return _list('ul', $list, $attributes);
	}
}

// ------------------------------------------------------------------------

/**
 * Ordered List
 *
 * Generates an HTML ordered list from an single or multi-dimensional array.
 *
 * @access	public
 * @param	array
 * @param	mixed
 * @return	string
 */
if ( ! function_exists('ol'))
{
	function ol($list, $attributes = '')
	{
		return _list('ol', $list, $attributes);
	}
}

// ------------------------------------------------------------------------

/**
 * Generates the list
 *
 * Generates an HTML ordered list from an single or multi-dimensional array.
 *
 * @access	private
 * @param	string
 * @param	mixed
 * @param	mixed
 * @param	integer
 * @return	string
 */
if ( ! function_exists('_list'))
{
	function _list($type = 'ul', $list, $attributes = '', $depth = 0)
	{
		// If an array wasn't submitted there's nothing to do...
		if ( ! is_array($list))
		{
			return $list;
		}

		// Set the indentation based on the depth
		$out = str_repeat(" ", $depth);

		// Were any attributes submitted?  If so generate a string
		if (is_array($attributes))
		{
			$atts = '';
			foreach ($attributes as $key => $val)
			{
				$atts .= ' ' . $key . '="' . $val . '"';
			}
			$attributes = $atts;
		}

		// Write the opening list tag
		$out .= "<".$type.$attributes.">\n";

		// Cycle through the list elements.  If an array is
		// encountered we will recursively call _list()

		static $lastListItem = '';
		foreach ($list as $key => $val)
		{
			$lastListItem = $key;

			$out .= str_repeat(" ", $depth + 2);
			$out .= "<li>";

			if ( ! is_array($val))
			{
				$out .= $val;
			}
			else
			{
				$out .= $lastListItem."\n";
				$out .= _list($type, $val, '', $depth + 4);
				$out .= str_repeat(" ", $depth + 2);
			}

			$out .= "</li>\n";
		}

		// Set the indentation for the closing tag
		$out .= str_repeat(" ", $depth);

		// Write the closing list tag
		$out .= "</".$type.">\n";

		return $out."\n";
	}
}

// ------------------------------------------------------------------------

/**
 * Generates HTML BR tags based on number supplied
 *
 * @access	public
 * @param	integer
 * @return	string
 */
if ( ! function_exists('br'))
{
	function br($num = 1)
	{
		return str_repeat("<br />"."\n", $num);
	}
}

// ------------------------------------------------------------------------

/**
 * Image
 *
 * Generates an <img /> element
 *
 * @access	public
 * @param	mixed
 * @return	string
 */
if ( ! function_exists('img'))
{
	function img($src = '', $indexPage = FALSE)
	{
		if ( ! is_array($src) )
		{
			$src = array('src' => $src);
		}

		// If there is no alt attribute defined, set it to an empty string
		if ( ! isset($src['alt']))
		{
			$src['alt'] = '';
		}

		$img = '<img';

		foreach ($src as $k=>$v)
		{

			if ($k == 'src' AND strpos($v, '://') === FALSE)
			{
				$ci =& get_instance();

				if ($indexPage === TRUE)
				{
					$img .= ' src="'.$ci->config->site_url($v).'"';
				}
				else
				{
					$img .= ' src="'.$ci->config->slash_item('base_url').$v.'"';
				}
			}
			else
			{
				$img .= " $k=\"$v\"";
			}
		}

		$img .= '/>';

		return $img."\n";
	}
}

// ------------------------------------------------------------------------

/**
 * Doctype
 *
 * Generates a page document type declaration
 *
 * Valid options are xhtml-11, xhtml-strict, xhtml-trans, xhtml-frame,
 * html4-strict, html4-trans, and html4-frame.  Values are saved in the
 * doctypes config file.
 *
 * @access	public
 * @param	string	type	The doctype to be generated
 * @return	string
 */
if ( ! function_exists('doctype'))
{
	function doctype($type = 'xhtml1-strict')
	{
		global $doctypes;

		if ( ! is_array($doctypes))
		{
			if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/doctypes'.EXT))
			{
				include(APPPATH.'config/'.ENVIRONMENT.'/doctypes'.EXT);
			}
			elseif (is_file(APPPATH.'config/doctypes'.EXT))
			{
				include(APPPATH.'config/doctypes'.EXT);
			}

			if ( ! is_array($doctypes))
			{
				return FALSE;
			}
		}

		if (isset($doctypes[$type]))
		{
			return $doctypes[$type];
		}
		else
		{
			return FALSE;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * Link
 *
 * Generates link to a CSS file
 *
 * @access	public
 * @param	mixed	stylesheet hrefs or an array
 * @param	string	rel
 * @param	string	type
 * @param	string	title
 * @param	string	media
 * @param	boolean	should index_page be added to the css path
 * @return	string
 */
if ( ! function_exists('link_tag'))
{
	function link_tag($href = '', $rel = 'stylesheet', $type = 'text/css', $title = '', $media = '', $indexPage = FALSE)
	{
		$ci =& get_instance();

		$link = '<link ';

		if (is_array($href))
		{
			foreach ($href as $k=>$v)
			{
				if ($k == 'href' AND strpos($v, '://') === FALSE)
				{
					if ($indexPage === TRUE)
					{
						$link .= 'href="'.$ci->config->site_url($v).'" ';
					}
					else
					{
						$link .= 'href="'.$ci->config->slash_item('base_url').$v.'" ';
					}
				}
				else
				{
					$link .= "$k=\"$v\" ";
				}
			}

			$link .= "/>";
		}
		else
		{
			if ( strpos($href, '://') !== FALSE)
			{
				$link .= 'href="'.$href.'" ';
			}
			elseif ($indexPage === TRUE)
			{
				$link .= 'href="'.$ci->config->site_url($href).'" ';
			}
			else
			{
				$link .= 'href="'.$ci->config->slash_item('base_url').$href.'" ';
			}

			$link .= 'rel="'.$rel.'" type="'.$type.'" ';

			if ($media	!= '')
			{
				$link .= 'media="'.$media.'" ';
			}

			if ($title	!= '')
			{
				$link .= 'title="'.$title.'" ';
			}

			$link .= '/>';
		}


		return $link."\n";
	}
}

// ------------------------------------------------------------------------

/**
 * Generates meta tags from an array of key/values
 *
 * @access	public
 * @param	array
 * @return	string
 */
if ( ! function_exists('meta'))
{
	function meta($name = '', $content = '', $type = 'name', $newline = "\n")
	{
		// Since we allow the data to be passes as a string, a simple array
		// or a multidimensional one, we need to do a little prepping.
		if ( ! is_array($name))
		{
			$name = array(array('name' => $name, 'content' => $content, 'type' => $type, 'newline' => $newline));
		}
		else
		{
			// Turn single array into multidimensional
			if (isset($name['name']))
			{
				$name = array($name);
			}
		}

		$str = '';
		foreach ($name as $meta)
		{
			$type		= ( ! isset($meta['type']) OR $meta['type'] == 'name') ? 'name' : 'http-equiv';
			$name		= ( ! isset($meta['name']))		? ''	: $meta['name'];
			$content	= ( ! isset($meta['content']))	? ''	: $meta['content'];
			$newline	= ( ! isset($meta['newline']))	? "\n"	: $meta['newline'];

			$str .= '<meta '.$type.'="'.$name.'" content="'.$content.'" />'.$newline;
		}

		return $str."\n";
	}
}

// ------------------------------------------------------------------------

/**
 * Generates non-breaking space entities based on number supplied
 *
 * @access	public
 * @param	integer
 * @return	string
 */
if ( ! function_exists('nbs'))
{
	function nbs($num = 1)
	{
		return str_repeat("&nbsp;", $num);
	}
}

// ------------------------------------------------------------------------
 /**
 * strong helper
 *
 * Generates <strong VAR="VAL">CONTENT</strong>
 *
 * @access public
 * @param string
 * @param mixed
 * @return string
 */
if ( ! function_exists('strong'))
{
    function strong($innerHtml = '', $params = FALSE)
    {
        return element($innerHtml, $params, 'strong');
    }
}

/**
 * h1 helper
 *
 * Generates <h1 VAR="VAL">CONTENT</h1>
 *
 * @access public
 * @param string
 * @param mixed
 * @return string
 */
if ( ! function_exists('h1'))
{
    function h1($innerHtml = '', $params = FALSE)
    {
        return element($innerHtml, $params, 'h1');
    }
}

/**
 * h2 helper
 *
 * Generates <h2 VAR="VAL">CONTENT</h2>
 *
 * @access public
 * @param string
 * @param mixed
 * @return string
 */
if ( ! function_exists('h2'))
{
    function h2($innerHtml = '', $params = FALSE)
    {
        return element($innerHtml, $params, 'h2');
    }
}

/**
 * h3 helper
 *
 * Generates <h3 VAR="VAL">CONTENT</h3>
 *
 * @access public
 * @param string
 * @param mixed
 * @return string
 */
if ( ! function_exists('h3'))
{
    function h3($innerhtml = '', $params = FALSE)
    {
        return element($innerhtml, $params, 'h3');
    }
}

/**
 * iframe helper
 *
 * Generates <element VAR="VAL">CONTENT</element>
 *
 * @access public
 * @param mixed
 * @return string
 */
if ( ! function_exists('iframe'))
{
    function iframe($params)
    {
        if ( ! is_array($params))
        {
            $params = array('src'=>$params);
        }

        $ci =& get_instance();

        return element(p($ci->lang->line('iframe_nosupport')), $params, 'iframe');
    }
}

/**
 * div helper
 *
 * Generates <div VAR="VAL">CONTENT</div>
 *
 * @access public
 * @param string
 * @param mixed
 * @return string
 */
if ( ! function_exists('div'))
{
    function div($innerHtml = '', $params = FALSE)
    {
        return element($innerHtml, $params, 'div');
    }
}

/**
 * Generates <div style='display: none;' id='loading<idName>' class='loading'></div>
 * 
 * @access public
 * @param string
 * @return string
 */
if ( !function_exists('div_loading'))
{
    function div_loading($idName)
    {
        $params = array(
            "id" => "loading".$idName,
            "class" => "loading",
            "style" => "display: none;"
        );
        
        return element("", $params, "div");
    }
}

/**
 * Generates div dialog format
 * 
 * @access public
 * @param string
 * @return string
 */
if ( !function_exists('div_dialog'))
{
    /**
     * 
     * @param string $innerHtml html text data to be displayed into the format
     * @param string $level message level (alert, success, info) default is 'alert'
     * @return string
     */
    function div_dialog($innerHtml = '', $title = '', $id = "dialog-id" )
    {
		$style = '
        <div id="'.$id.'" title="'.$title.'" style="display: none; text-align: left">
            <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'.
            $innerHtml
            .'</p>
        </div>';
		        
        return $style;
    }
}

/**
 * Generates div message format
 * 
 * @access public
 * @param string
 * @return string
 */
if ( !function_exists('div_message'))
{
    /**
     * 
     * @param string $innerHtml html text data to be displayed into the format
     * @param string $level message level (alert, success, info) default is 'alert'
     * @return string
     */
    function div_message($innerHtml = '', $level = "alert")
    {
		$style = '';
		
		switch ($level){
			case "info" : $style .= '<div class="jInfo"><strong>Info!</strong> '.$innerHtml.'</div>'; break;
			case "success" : $style .= '<div class="jSuccess"><strong>Exito!!!</strong> '.$innerHtml.'</div>'; break;
            case "validation" : $style .= '<div class="jValidation"><strong>Validacion!</strong> '.$innerHtml.'</div>'; break;
            case "warning" : $style .= '<div class="jWarning"><strong>Advertencia!!!</strong> '.$innerHtml.'</div>'; break;
			default : $style .= '<div class="jFatalError"><strong>Alerta!</strong> '.$innerHtml.'</div>'; break;
		}
		        
        return $style;
    }
}

/**
 * span helper
 *
 * Generates <span VAR="VAL">CONTENT</span>
 *
 * @access public
 * @param string
 * @param mixed
 * @return string
 */
if ( ! function_exists('span'))
{
    function span($innerHtml = '', $params = NULL)
    {
        return element($innerHtml, $params, 'span');
    }
}

/**
 * a helper
 *
 * Generates <a  href="" VAR="VAL">CONTENT</a>
 *
 * @access public
 * @param string
 * @param mixed
 * @return string
 */
if ( ! function_exists('a'))
{
    function a($innerHtml = '', $params = FALSE)
    {
        if ( ! is_array($params))
        {
            $params = array('href'=>$params);
        }

        if ( ! isset($params['href']))
        {
            $params['href'] = '#';
        }

        return element($innerHtml, $params, 'a');
    }
}

/**
 * P helper
 *
 * Generates <p VAR="VAL">CONTENT</p>
 *
 * @access public
 * @param string
 * @param mixed
 * @return string
 */
if ( ! function_exists('p'))
{
    function p($innerHtml = '', $params = FALSE)
    {
        return element($innerHtml, $params, 'p');
    }
}

/**
 * Label helper
 *
 * Generates <label VAR="VAL">CONTENT</label>
 *
 * @access public
 * @param string
 * @param mixed
 * @return string
 */
if ( ! function_exists('label'))
{
    function label($innerHtml = '', $params = FALSE)
    {
        return element($innerHtml, $params, 'label');
    }
}

/**
 * element helper
 *
 * Generates <element VAR="VAL">CONTENT</element>
 *
 * @access public
 * @param string
 * @param mixed
 * @param string
 * @return string
 */
if ( ! function_exists('element'))
{
    function element($innerHtml = '', $params = FALSE,  $typ = 'div')
    {
        // uses class if not array but set
        if (is_string($params))
        {
            $params = array('class'=>$params);
        }
        if ( ! is_array($params))
        {
            $params = array();
        }

        $html = "<$typ";
        foreach ($params as $k=>$v)
        {
            if ($k != 'html')
            {
                $html .= " $k=\"$v\"";
            }
        }
        $html .= ">$innerHtml</$typ>";

        return $html."\n";
    }
}  

//------------------------------------------------------------------------------

if ( ! function_exists('array_to_form'))
{
    function array_to_form($array, $genericFormName)
    {
		$html = "";
		if (is_array($array)) {			
			$html .= "<table class='list'>";
				foreach ($array as $key => $value) {
					if (is_array($value)) {
						$html .= "
							<tr>
								<td align='right'>"
									.ucwords(str_replace("_", " ", $key)).' : '.
								"</td>
								<td align='left'></td>
							</tr>
							<tr>
								<td align='right'></td>
								<td align='left'>"
									.array_to_form($value,$genericFormName.'['.$key.']').
								"</td>
							</tr>";
					} else {
						$html .= 
						'<tr>
							<td align="right">'
								.ucwords(str_replace("_", " ", $key)).' : '.
							'</td>
							<td align="left">
								<input type="text" id="'.str_replace(array("[","]"), "", $genericFormName).'_'.$key.'" name="data'.$genericFormName.'['.$key.']" value="'.$value.'" style="width:350px"/>
							</td>
						</tr>';
					}
				}
			$html .= "</table>";
		}
        return $html."\n";
    }
}  

// ------------------------------------------------------------------------

/**
 * Script
 *
 * Generates script tag
 *
 * @access	public
 * @param	mixed	src
 * @param	string	type
 * @param	string	content
 * @return	string
 */
if ( ! function_exists('script_tag'))
{
	function script_tag($src = '', $type = 'text/javascript', $content = '')
	{
		$ci =& get_instance();

		$script = '<script ';

		$script .= 'type="'.$type.'" ';
		
		if ( strpos($src, '://') !== FALSE)
		{
			$script .= 'src="'.$src.'" ';
		}
		else
		{
			$script .= 'src="'.$ci->config->slash_item('base_url').$src.'" ';
		}

		$script .= '>'.$content.'</script>';


		return $script."\n";
	}
}

/**
 * center helper
 *
 * Generates <label VAR="VAL">CONTENT</label>
 *
 * @access public
 * @param string
 * @param mixed
 * @return string
 */
if ( ! function_exists('center'))
{
    function center($innerHtml = '', $params = FALSE)
    {
        return element($innerHtml, $params, 'center');
    }
}

/**
 * b helper
 *
 * Generates <label VAR="VAL">CONTENT</label>
 *
 * @access public
 * @param string
 * @param mixed
 * @return string
 */
if ( ! function_exists('b'))
{
    function b($innerHtml = '', $params = FALSE)
    {
        return element($innerHtml, $params, 'b');
    }
}

// --------------------------------------------------------------------

if (!function_exists('anchor_ui')) {

    /**
     *
     * @param string $href
     * @param string $text
     * @param string $class
     * @param boolean $showText
     * @param string $extra
     * @return string 
     */
    function anchor_ui($href, $text, $class = 'ui-icon-carat-1-n', $showText = TRUE, $extra = "")
    {
        $anchor = '
            <a class="' . str_replace(" ", "_", strtolower($text)) . ' ui-button ui-widget ui-state-default ui-corner-all ' . ($showText ? 'ui-button-text-icon-primary' : 'ui-button-icon-only') . '" href="' . $href . '" role="button" title="' . $text . '" ' . $extra . '>
                <span class="ui-button-icon-primary ui-icon ' . $class . '"></span>
                <span class="ui-button-text">' . $text . '</span>
            </a>';
        return $anchor;
    }

}