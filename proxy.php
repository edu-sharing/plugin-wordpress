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
            $inline = wp_remote_retrieve_body( wp_remote_get( $url ) );
            if($inline === false) {
                trigger_error('no inline content!', E_USER_WARNING);
            }
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
        return $inline;
    }

    /**
     * Prepare rendered object for display
     *
     * @param string $html
     * @param $url
     * @param $ticket
     */
    public function filter_edusharing_display($html, $url, $ticket) {
        $parts = parse_url($url);
        parse_str($parts['query'], $param);
        $resid = $param['resource_id'];
        $objectUrl = $param['objectUrl'];
        $displaymode = $param['display'];
        $postID = $param['course_id'];
        $objectVersion = $param['version'];

        $html = str_replace(array("\n", "\r", "\n"), '', $html);

        /*
         * replaces {{{LMS_INLINE_HELPER_SCRIPT}}}
         */
        $html = str_replace("{{{LMS_INLINE_HELPER_SCRIPT}}}",plugin_dir_url( __FILE__ ) . "inlineHelper.php?resId=" . $resid .
            "&objectURL=" . $objectUrl . "&display=" . $displaymode . "&postID=" . $postID . "&objectVersion=" . $objectVersion . "&", $html);
        $html = str_replace("{{{TICKET}}}", $ticket, $html);

        $html = preg_replace("/<es:title[^>]*>.*<\/es:title>/Uims", get_param('title'), $html);
        $caption = get_param('caption');
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
$ticket = get_param('ticket');
$parts = parse_url($url);
parse_str($parts['query'], $query);

$ts = $timestamp = round(microtime(true) * 1000);
$url .= '&ts=' . $ts;
$url .= '&sig=' . urlencode(edusharing_get_signature(get_option('es_appID') . $ts . $query['obj_id']));
$url .= '&signed=' . urlencode(get_option('es_appID') . $ts . $query['obj_id']);
$url .= '&videoFormat=' . $videoFormat;

$e = new filter_edusharing_edurender();
$html = $e->filter_edusharing_get_render_html($url);
$e->filter_edusharing_display($html, $url, $ticket);
