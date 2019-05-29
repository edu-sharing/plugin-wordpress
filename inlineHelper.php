<?php

/**
 * Prints a particular instance of edusharing
 *
 * @package    filter_edusharing
 * @copyright  metaVentis GmbH — http://metaventis.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__).'/lib/cclib.php');
require_once("../../../wp-load.php");

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

$resid = get_param('resId'); // edusharing instance ID
$childobject_id = get_param('childobject_id');

if ($resid) {
    //$edusharing  = $DB->get_record(EDUSHARING_TABLE, array('id'  => $resid), '*', MUST_EXIST);
} else {
    trigger_error('InlineHelper: error_missing_instance_id');
}

$redirecturl = edusharing_get_redirect_url(get_param('objectURL'), 'window', get_param('postID'), get_param('objectVersion'));

$ts = $timestamp = round(microtime(true) * 1000);
$redirecturl .= '&ts=' . $ts;
$data = get_option('es_appID') . $ts . edusharing_get_object_id_from_url(get_param('objectURL'));
$redirecturl .= '&sig=' . urlencode(edusharing_get_signature($data));
$redirecturl .= '&signed=' . urlencode($data);
$redirecturl .= '&closeOnBack=true';
$cclib = new mod_edusharing_web_service_factory();
$redirecturl .= '&ticket=' . urlencode(base64_encode(edusharing_encrypt_with_repo_public($cclib -> edusharing_authentication_get_ticket())));

if($childobject_id)
    $redirecturl .= '&childobject_id=' . $childobject_id;

wp_redirect( $redirecturl );
exit;

