<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// ------------------------------------------------------------------------

if (!function_exists('check_login')) {

    /**
     *
     * @param object $user
     * @return boolean 
     */
    function check_login($user)
    {
        if (isset($user->idResource))
            return TRUE;
        return FALSE;
    }

}

// --------------------------------------------------------------------

if (!function_exists('html_message')) {

    /**
     *
     * @param string $message the message
     * @param string $messageType message type (alert, info, notice and success) if typer does not match with the previou list it will just get the message with no html format.
     * @return string 
     */
    function html_message($message, $messageType = 'alert')
    {
        $html = "<div class='ui-widget'><div class='ui-state-highlight ui-corner-all' style='margin-top: 1px; padding: 0pt 0.7em;'><p><span class='ui-icon ui-icon-alert' style='float: left; margin-right: 0.3em;'></span>";
        switch ($messageType) {
            case 'alert':
                return $html . '<strong>Warning!</strong> - ' . $message . '</p></div></div><br />';
                break;
            case 'info':
                return $html . '<strong>Info!</strong> - ' . $message . '</p></div></div><br />';
                break;
            case 'notice':
                return $html . '<strong>Notice!</strong> - ' . $message . '</p></div></div><br />';
                break;
            case 'success':
                return $html . '<strong>Success!</strong> - ' . $message . '</p></div></div><br />';
                break;
            default:
                return $message;
        }
    }

}

// --------------------------------------------------------------------

if (!function_exists('paint_anchor_button')) {

    /**
     *
     * @param string $href
     * @param string $text
     * @param string $class
     * @param boolean $showText
     * @param string $extra
     * @return string 
     */
    function paint_anchor_button($href, $text, $class = 'ui-icon-carat-1-n', $showText = TRUE, $extra = "")
    {
        $showTextClass = "ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary";
        $anchor = '
            <a class="' . str_replace(" ", "_", strtolower($text)) . ' ui-button ui-widget ui-state-default ui-corner-all ' . ($showText ? 'ui-button-text-icon-primary' : 'ui-button-icon-only') . '" href="' . $href . '" role="button" title="' . $text . '" ' . $extra . '>
                <span class="ui-button-icon-primary ui-icon ' . $class . '"></span>
                <span class="ui-button-text">' . $text . '</span>
            </a>';
        return $anchor;
    }

}

// --------------------------------------------------------------------

if (!function_exists('escape_trtool')) {

    /**
     * Escapes especial characteras and sdd slashes to quotes.
     * @param string $string
     * @return string 
     */
    function escape_trtool($string)
    {
        $string = addslashes($string);
        $string = htmlentities($string);

        return $string;
    }

}

// --------------------------------------------------------------------

if (!function_exists('get_rounded')) {

    /**
     * gets a float value and return it with only two decimals by rounding it.
     * @param float $float
     * @return float 
     */
    function get_rounded($float)
    {
        //if (strlen($float) > 9)
        $float = substr((string) $float, 0, 10);
        $floatval = floatval($float);
        return round($floatval, 2);
    }

}

// --------------------------------------------------------------------

if (!function_exists('validate_date')) {

    /**
     * Helper function validate a date and check if format is valid
     * @param string $start_date
     * @return boolean
     */
    function validate_date($date)
    {
        if ($date == NULL)
            return FALSE;

        if (!is_string($date))
            return FALSE;

        if ($date != NULL) {
            try {
                $tmpDate = new DateTime($date);
            } catch (Exception $e) {
                return FALSE;
            }
        }
        return TRUE;
    }

}