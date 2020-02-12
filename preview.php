<?php

/**
 * Fetches object preview from repository
 *
 * @package    editor_edusharing
 * @copyright  metaVentis GmbH — http://metaventis.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// create preview link with signature
require_once(dirname(__FILE__).'/lib/cclib.php');
require_once("../../../wp-load.php");

$user = wp_get_current_user()->ID;
if($user === 0){
    echo 'no permission';
    exit();
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

$post_id = get_param('post_id');
$objectUrl = get_param('objectUrl');
$objectVersion = get_param('objectVersion');
$repoID = get_param('repoID');
$resourceId = get_param('resourceId');
if(substr(get_option('es_repo_url') , -1) == '/'){
    $previewservice = get_option('es_repo_url') . 'preview';
}else{
    $previewservice = get_option('es_repo_url') . '/' . 'preview';
}
$time = round(microtime(true) * 1000);
$ticket = get_param('ticket');

$url = $previewservice;
$url .= '?appId=' . get_option('es_appID');
$url .= '&courseId=' . $post_id;
$url .= '&repoId=' . edusharing_get_repository_id_from_url($objectUrl);
$url .= '&proxyRepId=' . $repoID;
$url .= '&nodeId=' . edusharing_get_object_id_from_url($objectUrl);
$url .= '&resourceId=' . $resourceId;
$url .= '&version=' . $objectVersion;
$sigdata = get_option('es_appID') . $time . edusharing_get_object_id_from_url($objectUrl);
$sig = urlencode(edusharing_get_signature($sigdata));
$url .= '&sig=' . $sig;
$url .= '&signed=' . $sigdata;
$url .= '&ts=' . $time;

error_log($url);

$curlhandle = curl_init($url);
curl_setopt($curlhandle, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curlhandle, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curlhandle, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curlhandle, CURLOPT_HEADER, 0);
curl_setopt($curlhandle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlhandle, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
$output = curl_exec($curlhandle);
$mimetype = curl_getinfo($curlhandle, CURLINFO_CONTENT_TYPE);
curl_close($curlhandle);
header('Content-type: ' . $mimetype);
echo $output;
exit();
