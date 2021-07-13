<?php

/**
 * Proxy script for ajax based rendering
 *
 * @package filter_edusharing
 * @copyright metaVentis GmbH — http://metaventis.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

add_action('rest_api_init', function () {
    register_rest_route( 'edusharing/v1', 'proxy/',array(
        'methods'  => 'GET',
        'callback' => 'edusharing_proxy',
        'args' => array(),
        'permission_callback' => '__return_true',
    ));
});

function edusharing_proxy(WP_REST_Request $request) {

    $url = $request->get_param( 'URL' );
    $videoFormat = $request->get_param( 'videoFormat' );
    $ticket = $request->get_param( 'ticket' );
    $title = $request->get_param( 'title' );
    $caption = $request->get_param('caption');
    $parts = parse_url($url);
    parse_str($parts['query'], $query);

    $ts = $timestamp = round(microtime(true) * 1000);
    $url .= '&ts=' . $ts;
    $url .= '&sig=' . urlencode(edusharing_get_signature(get_option('es_appID') . $ts . $query['obj_id']));
    $url .= '&signed=' . urlencode(get_option('es_appID') . $ts . $query['obj_id']);
    $url .= '&videoFormat=' . $videoFormat;

    $e = new filter_edusharing_edurender();
    $html = $e->filter_edusharing_get_render_html($url);
    return $e->filter_edusharing_display($html, $url, $ticket, $title, $caption);
}

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
    public function filter_edusharing_display($html, $url, $ticket, $title, $caption) {
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
        $html = str_replace('href="{{{LMS_INLINE_HELPER_SCRIPT}}}&closeOnBack=true','href="" onclick="inlineHelper'.$resid.'();return false;"', $html);
        $html = str_replace("{{{TICKET}}}", $ticket, $html);

        $html = preg_replace("/<es:title[^>]*>.*<\/es:title>/Uims", $title, $html);
        if(!empty($caption)){
            $html .= '<p class="caption">' . $caption . '</p>';
        }
        if (strpos($html, 'inlineHelper') !== false) {
            $html = str_replace("&closeOnBack=true","", $html);
            $html .= "    <script>
                            function inlineHelper".$resid."() {                                
                                let url = '".get_rest_url(null, 'edusharing/v1/inlineHelper/')."?resId=" . $resid . "&objectURL=" . $objectUrl . "&display=" . $displaymode . "&postID=" . $postID . "&objectVersion=" . $objectVersion . "';
                                fetch(url)
                                  .then(response => response.json())
                                  .then(data => {
                                      window.open(data,'_blank');
                                    });
                            }
                        </script>";
        }

        return $html;
    }
}

add_action('rest_api_init', function () {
    register_rest_route( 'edusharing/v1', 'inlineHelper/',array(
        'methods'  => 'GET',
        'callback' => 'edusharing_inlineHelper',
        'args' => array(),
        'permission_callback' => '__return_true',
    ));
});

function edusharing_inlineHelper(WP_REST_Request $request) {
    $resid = $request->get_param( 'resId' ); // edusharing instance ID
    $childobject_id = $request->get_param( 'childobject_id' );

    if ($resid) {
        //$edusharing  = $DB->get_record(EDUSHARING_TABLE, array('id'  => $resid), '*', MUST_EXIST);
    } else {
        trigger_error('InlineHelper: error_missing_instance_id');
    }

    $redirecturl = edusharing_get_redirect_url(
        $request->get_param( 'objectURL' ),
        'window',
        $request->get_param('postID'),
        $request->get_param('objectVersion'),
        $request->get_param('resId')
    );

    $ts = $timestamp = round(microtime(true) * 1000);
    $redirecturl .= '&ts=' . $ts;
    $data = get_option('es_appID') . $ts . edusharing_get_object_id_from_url(get_param('objectURL'));
    $redirecturl .= '&sig=' . urlencode(edusharing_get_signature($data));
    $redirecturl .= '&signed=' . urlencode($data);
    $redirecturl .= '&closeOnBack=true';
    $cclib = new mod_edusharing_web_service_factory();
    $redirecturl .= '&ticket=' . urlencode(base64_encode(edusharing_encrypt_with_repo_public($cclib -> edusharing_authentication_get_ticket())));

    if($childobject_id){
        $redirecturl .= '&childobject_id=' . $childobject_id;
    }

    return $redirecturl ;
}


