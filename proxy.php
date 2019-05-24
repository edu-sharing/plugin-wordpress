<?php

/**
 * Proxy script for ajax based rendering
 *
 * @package filter_edusharing
 * @copyright metaVentis GmbH — http://metaventis.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__).'/lib/cclib.php');
require_once("../../../wp-load.php");


/**
 * Class for ajax based rendering
 *
 * @copyright metaVentis GmbH — http://metaventis.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class filter_edusharing_edurender {

    /**
     * Get rendered object via curl
     *
     * @param string $url
     * @return string
     * @throws Exception
     */
    public function filter_edusharing_get_render_html($url) {
        $inline = "";
        try {
            $curlhandle = curl_init($url);
            curl_setopt($curlhandle, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curlhandle, CURLOPT_HEADER, 0);
            // DO NOT RETURN HTTP HEADERS
            curl_setopt($curlhandle, CURLOPT_RETURNTRANSFER, 1);
            // RETURN THE CONTENTS OF THE CALL
            curl_setopt($curlhandle, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curlhandle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curlhandle, CURLOPT_SSL_VERIFYHOST, false);
            $inline = curl_exec($curlhandle);
            if($inline === false) {
                trigger_error(curl_error($curlhandle), E_USER_WARNING);
            }
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
        curl_close($curlhandle);
        return $inline;
    }

    /**
     * Prepare rendered object for display
     *
     * @param string $html
     */
    public function filter_edusharing_display($html, $url) {

        //error_reporting(0);


        $parts = parse_url($url);
        parse_str($parts['query'], $param);

        $resid = $param['resource_id'];
        $objectUrl = $param['objectUrl'];
        $displaymode = $param['display'];
        $postID = $param['resource_id'];
        $objectVersion = $param['version'];

        //var_dump($url);die();


        $html = str_replace(array("\n", "\r", "\n"), '', $html);

        /*
         * replaces {{{LMS_INLINE_HELPER_SCRIPT}}}
         */

        $html = str_replace("{{{LMS_INLINE_HELPER_SCRIPT}}}",plugin_dir_url( __FILE__ ) . "inlineHelper.php?resId=" . $resid .
            "&objectURL=" . $objectUrl . "&display=" . $displaymode . "&postID=" . $postID . "&objectVersion=" . $objectVersion . "&", $html);


        //var_dump('<!--'.$html.'-->');die();
        //echo '<script>console.log("'.$html.'")</script>';

        $html = preg_replace("/<es:title[^>]*>.*<\/es:title>/Uims", get_param('title'), $html);

        $caption = get_param('caption');//utf8_decode(optional_param('caption', '', PARAM_TEXT));
        //$caption = $objectCaption;
        if($caption)
            $html .= '<p class="caption">' . $caption . '</p>';

        echo $html;
        exit();
    }
}


function get_param($parname) {

    // POST has precedence.
    if (isset($_POST[$parname])) {
        $param = $_POST[$parname];
    } else if (isset($_GET[$parname])) {
        $param = $_GET[$parname];
    } else {
        return '';
    }
    return $param;
}


$url = get_param('URL');
$videoFormat = get_param('videoFormat');
$title = get_param('title');

//$url = required_param('URL', PARAM_NOTAGS);
$parts = parse_url($url);
parse_str($parts['query'], $query);


$ts = $timestamp = round(microtime(true) * 1000);
$url .= '&ts=' . $ts;

$url .= '&sig=' . urlencode(edusharing_get_signature(get_option('es_appID') . $ts . $query['obj_id']));
$url .= '&signed=' . urlencode(get_option('es_appID') . $ts . $query['obj_id']);
$url .= '&videoFormat=' . $videoFormat;

$e = new filter_edusharing_edurender();
$html = $e->filter_edusharing_get_render_html($url);
$e->filter_edusharing_display($html, $url);
