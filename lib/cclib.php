<?php

/**
 * Handle some webservice functions
 *
 * @package    mod_edusharing
 * @copyright  metaVentis GmbH — http://metaventis.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__).'/../sigSoapClient.php');

/**
 * Handle some webservice functions
 *
 * @copyright  metaVentis GmbH — http://metaventis.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_edusharing_web_service_factory {

    /**
     * The url to authentication-service's WSDL.
     *
     * @var string
     */
    private $authenticationservicewsdl = '';

    /**
     * Get repository properties and set auth service url
     *
     * @throws Exception
     */
    public function __construct() {
        $this->authenticationservicewsdl = get_option('es_repo_authenticationwebservice_wsdl');
        if ( empty($this->authenticationservicewsdl) ) {
            echo "FAILURE: construct ";
    }
    }

    /**
     * Get repository ticket
     * Check existing ticket vor validity
     * Request a new one if existing ticket is invalid
     * @param string $context
     */
    public function edusharing_authentication_get_ticket() {

        //$USER = wp_get_current_user();
        global $USER;

        if(!isset($USER->edusharing_userticket)){
            $USER = new stdClass();
        }

        // Ticket available
        if (isset($USER->edusharing_userticket)) {

            // Ticket is younger than 10s, we must not check.
            if (isset($USER->edusharing_userticketvalidationts) && time() - $USER->edusharing_userticketvalidationts < 10) {
                return $USER->edusharing_userticket;
            }
            try {
                $eduservice = new mod_edusharing_sig_soap_client($this->authenticationservicewsdl, array());
            } catch (Exception $e) {
                echo "FAILURE: soap_client " . $e->getMessage();
            }

            try {
                // Ticket is older than 10s.
                $params = array(
                    "username"  => edusharing_get_auth_key(),
                    "ticket"  => $USER->edusharing_userticket
                );

                $alfreturn = $eduservice->checkTicket($params);

                if ($alfreturn->checkTicketReturn) {
                    $USER->edusharing_userticketvalidationts = time();
                    return $USER->edusharing_userticket;
                }
            } catch (Exception $e) {
                echo "FAILURE: ticket_available " . $e->getMessage();
            }

        }

        // No or invalid ticket available - request new ticket.
        $paramstrusted = array("applicationId"  => get_option('es_appID'),
                                "ticket"  => session_id(), "ssoData"  => edusharing_get_auth_data());
        try {
            $client = new mod_edusharing_sig_soap_client($this->authenticationservicewsdl);
            $return = $client->authenticateByTrustedApp($paramstrusted);
            $ticket = $return->authenticateByTrustedAppReturn->ticket;
            $USER->edusharing_userticket = $ticket;
            $USER->edusharing_userticketvalidationts = time();
            return $ticket;
        } catch (Exception $e) {
            echo "FAILURE: new_ticket: " . $e->getMessage();
        }
        return false;
    }
}

/**
 * Get the parameter for authentication
 * @return string
 */
function edusharing_get_auth_key() {

    global $SESSION;

    $user = wp_get_current_user();

    // Set by external sso script.
    if (isset($SESSION -> edusharing_sso) && !empty($SESSION -> edusharing_sso)) {
        $eduauthparamnameuserid = $user->ID;
        return $SESSION -> edusharing_sso[$eduauthparamnameuserid];
    }

    if (!empty(get_option('es_guest_option')) || empty($user->ID)) {
        return get_option('es_guest_id', 'esguest');
    }

    $eduauthkey = get_option('es_auth_key');
    if ($eduauthkey == 'id') {
        return $user->user_login;
    }
    if ($eduauthkey == 'idnumber') {
        return $user->ID;
    }
    if ($eduauthkey == 'email') {
        return $user->user_email;
    }

    return $user->user_login;
}


/**
 * Return data for authByTrustedApp
 *
 * @return array
 */
function edusharing_get_auth_data() {

    global $SESSION;

    $user = wp_get_current_user();

    // Set by external sso script.
    if (isset($SESSION -> edusharing_sso) && !empty($SESSION -> edusharing_sso)) {
        $authparams = array();
        foreach ($SESSION -> edusharing_sso as $key => $value) {
            $authparams[] = array('key'  => $key, 'value'  => $value);
        }
    } else {
        // Keep defaults in sync with settings.php.
        $eduauthparamnameuserid = get_option('es_auth_userid');
        if (empty($eduauthparamnameuserid)) {
            $eduauthparamnameuserid = '';
        }

        $eduauthparamnamelastname = get_option('es_auth_lastname');
        if (empty($eduauthparamnamelastname)) {
            $eduauthparamnamelastname = '';
        }

        $eduauthparamnamefirstname = get_option('es_auth_firstname');
        if (empty($eduauthparamnamefirstname)) {
            $eduauthparamnamefirstname = '';
        }

        $eduauthparamnameemail = get_option('es_auth_mail');
        if (empty($eduauthparamnameemail)) {
            $eduauthparamnameemail = '';
        }

        $eduauthaffiliation = get_option('es_auth_affiliation');
        $eduauthaffiliationname = get_option('es_auth_affiliation_name');

        if(empty($user->user_lastname)){
            $user_lastname = $user->user_login;
        }else{
            $user_lastname = $user->user_lastname;
        }

        $guestoption = get_option('es_guest_option');
        if ($guestoption == 1 || $user->ID == 0) {
            $guestid = get_option('es_guest_id');
            if (empty($guestid)) {
                $guestid = 'esguest';
            }
            $authparams = array(
                array('key'  => $eduauthparamnameuserid, 'value'  => $guestid),
                array('key'  => $eduauthparamnamelastname, 'value'  => 'Guest'),
                array('key'  => $eduauthparamnamefirstname, 'value'  => 'ES'),
                array('key'  => $eduauthparamnameemail, 'value'  => ''),
                array('key'  => 'affiliation', 'value'  => $eduauthaffiliation),
                array('key'  => 'affiliationname', 'value' => $eduauthaffiliationname)
            );
        } else {
            $authparams = array(
                array('key'  => $eduauthparamnameuserid, 'value'  => edusharing_get_auth_key()),
                array('key'  => $eduauthparamnamelastname, 'value'  => $user_lastname),
                array('key'  => $eduauthparamnamefirstname, 'value'  => $user->user_firstname),
                array('key'  => $eduauthparamnameemail, 'value'  => $user->user_email),
                array('key'  => 'affiliation', 'value'  => $eduauthaffiliation),
                array('key'  => 'affiliationname', 'value' => $eduauthaffiliationname)
            );
        }
    }
    return $authparams;
}

/**
 * Get the repository-id from object-url.
 * E.g. "homeRepository" for "ccrep://homeRepository/abc-123-xyz-456789"
 *
 * @param string $objecturl
 * @throws Exception
 * @return string
 */
function edusharing_get_repository_id_from_url($objecturl) {
    $repid = parse_url($objecturl, PHP_URL_HOST);
    if ( ! $repid ) {
        throw new Exception('Error: get repository-id from object-url');
    }

    return $repid;
}

/**
 * Get the object-id from object-url.
 * E.g. "abc-123-xyz-456789" for "ccrep://homeRepository/abc-123-xyz-456789"
 *
 * @param string $objecturl
 * @throws Exception
 * @return string
 */
function edusharing_get_object_id_from_url($objecturl) {
    $objectid = parse_url($objecturl, PHP_URL_PATH);
    if ( ! $objectid ) {
        trigger_error(('Error: get_object_id_from_url'), E_USER_WARNING);
        return false;
    }
    $objectid = str_replace('/', '', $objectid);
    return $objectid;
}

/**
 * Return openssl encrypted data
 * Uses repositorys openssl public key
 *
 * @param string $data
 * @return string
 */
function edusharing_encrypt_with_repo_public($data) {
    $crypted = '';
    $key = openssl_get_publickey(get_option('es_repo_public_key'));
    openssl_public_encrypt($data ,$crypted, $key);
    if($crypted === false) {
        trigger_error('Error: encrypt_with_repo_public', E_USER_WARNING);
        return false;
    }
    return $crypted;
}

/**
 * Generate ssl signature
 *
 * @param string $data
 * @return string
 */
function edusharing_get_signature($data) {
    $privkey = get_option('es_privateKey');
    $pkeyid = openssl_get_privatekey($privkey);
    openssl_sign($data, $signature, $pkeyid);
    $signature = base64_encode($signature);
    openssl_free_key($pkeyid);
    return $signature;
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param stdClass $edusharing An object from the form in mod_form.php
 * @return int The id of the newly inserted edusharing record
 */
function edusharing_add_instance($objectVersion, $objectUrl, $post_ID, $postTitle, $resourceId) {

    $edusharing = new stdClass;
    $edusharing->timecreated = time();
    $edusharing->timemodified = time();
    $edusharing->object_version = $objectVersion;
    $edusharing->object_url = $objectUrl;
    $edusharing->course = $post_ID;
    $edusharing->postTitle = $postTitle;
    $edusharing->id = $resourceId;

    if(!$resourceId){
        $resourceId = $post_ID;
        echo '<script>console.log("add_instance no resourceId")</script>';
    }

    $user = wp_get_current_user();

    $edusharing->user = $user->user_login;
    $edusharing->userMail = $user->user_email;

        if (isset($edusharing->object_version)) {
            if ($edusharing->object_version == 1) {
                $updateversion = true;
                $edusharing->object_version = '';
            } else {
                $edusharing->object_version = 0;
            }
        } else {
            if (isset($edusharing->window_versionshow) && $edusharing->window_versionshow == 'current') {
                $edusharing->object_version = $edusharing->window_version;
            } else {
                $edusharing->object_version = 0;
            }
        }

    $id = $resourceId;
    $soapclientparams = array();
    $client = new mod_edusharing_sig_soap_client(get_option('es_repo_usagewebservice_wsdl'), $soapclientparams);
    $xml = edusharing_get_usage_xml($edusharing);

    try {
        $params = array(
            "eduRef"  => $edusharing->object_url,
            "user"  => edusharing_get_auth_key(),
            "lmsId"  => get_option('es_appID'),
            "courseId"  => $post_ID,
            "userMail"  => $edusharing->userMail,
            "fromUsed"  => '2002-05-30T09:00:00',
            "toUsed"  => '2222-05-30T09:00:00',
            "distinctPersons"  => '0',
            "version"  => $edusharing->object_version,
            "resourceId"  => $resourceId,
            "xmlParams"  => $xml,
        );
        $setusage = $client->setUsage($params);
        if (isset($updateversion) && $updateversion === true) {
            $edusharing->object_version = $setusage->setUsageReturn->usageVersion;
            $edusharing->id = $id;
        }

    } catch (Exception $e) {
        error_log(print_r($e, true));
        trigger_error($e->getMessage());
        return false;
    }
    return $id;
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function edusharing_delete_instance($objectUrl, $post_ID, $resourceId) {
    try {
        if(!$resourceId){
            $resourceId = $post_ID;
            echo '<script>console.log("delete_instance no resourceId: '.$resourceId.'")</script>';
        }
        $connectionurl = get_option('es_repo_usagewebservice_wsdl');
        if ( ! $connectionurl ) {
            throw new Exception('error_missing_usagewsdl');
        }
        $ccwsusage = new mod_edusharing_sig_soap_client($connectionurl, array());
        $params = array(
            'eduRef'  => $objectUrl,
            'user'  => edusharing_get_auth_key(),
            'lmsId'  => get_option('es_appID'),
            'courseId'  => $post_ID,
            'resourceId'  => $resourceId
        );
        $ccwsusage->deleteUsage($params);

    } catch (Exception $exception) {
        trigger_error($exception->getMessage(), E_USER_WARNING);
    }
    return true;
}

/**
 * Get additional usage information
 *
 * @param stdClass $edusharing
 * @return string
 */
function edusharing_get_usage_xml($edusharing) {

    $data4xml = array("usage");

    $data4xml[1]["general"]['referencedInName'] = $edusharing->postTitle;
    $data4xml[1]["general"]['referencedInType'] = 'Page';
    $data4xml[1]["general"]['referencedInInstance'] = $edusharing->course;

    $data4xml[1]["specific"]['type'] = 'wordpress';
    $myxml  = new mod_edusharing_render_parameter();
    $xml = $myxml->edusharing_get_xml($data4xml);
    return $xml;
}

function get_repo_ticket(){
    // Authenticate to assure requesting user exists in home-repository.
    try {
        $servicefactory = new mod_edusharing_web_service_factory();
        $ticket = $servicefactory->edusharing_authentication_get_ticket();
    } catch (Exception $exception) {
        trigger_error($exception->getMessage(), E_USER_WARNING);
        return false;
    }
    return $ticket;
}

/**
 * Generate redirection-url
 *
 * @param stdClass $edusharing
 * @param string $displaymode
 *
 * @return string
 */

function edusharing_get_redirect_url($objectUrl, $displaymode, $postID, $objectVersion, $resourceId = NULL) {

    if(!$resourceId){
        $resourceId = $postID;
        echo '<script>console.log("get_redirect_url: no resourceId")</script>';
    }

    $url = get_option('es_repo_url') . 'renderingproxy';
    $url .= '?app_id='.urlencode(get_option('es_appID'));
    $url .= '&session='.urlencode(session_id());
    $repid = edusharing_get_repository_id_from_url($objectUrl);
    $url .= '&rep_id='.urlencode($repid);
    $url .= '&objectUrl='.urlencode($objectUrl);
    $url .= '&obj_id='.urlencode(edusharing_get_object_id_from_url($objectUrl));
    $url .= '&resource_id='.urlencode($resourceId);
    $url .= '&course_id='.urlencode($postID);
    $url .= '&display='.urlencode($displaymode);
    $url .= '&version=' . urlencode($objectVersion);
    $url .= '&locale=' . urlencode(substr(get_locale(), 0, 2)); //repository
    $url .= '&language=' . urlencode(substr(get_locale(), 0, 2)); //rendering service
    $url .= '&u='. rawurlencode(base64_encode(edusharing_encrypt_with_repo_public(edusharing_get_auth_key())));

    return $url;
}

function edusharing_import_metadata($metadataurl){
    try {

        $xml = new DOMDocument();

        libxml_use_internal_errors(true);

        $curlhandle = curl_init($metadataurl);
        curl_setopt($curlhandle, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curlhandle, CURLOPT_HEADER, 0);
        curl_setopt($curlhandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlhandle, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curlhandle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlhandle, CURLOPT_SSL_VERIFYHOST, false);
        $properties = curl_exec($curlhandle);
        if ($xml->loadXML($properties) == false) {
            echo ('<p style="background: #FF8170">could not load ' . $metadataurl .
                    ' please check url') . "<br></p>";
            echo get_form($metadataurl);
            exit();
        }
        curl_close($curlhandle);
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $entrys = $xml->getElementsByTagName('entry');
        foreach ($entrys as $entry) {
            $optionKey = 'es_repo_' . $entry->getAttribute('key');
            if (get_option($optionKey) === false) {
                // Not defined as an option; we don't need this value.
                continue;
            }
            if ($entry->getAttribute('key') == 'usagewebservice_wsdl'){
                update_option('es_repo_url', substr_replace($entry->nodeValue,'', -20));
            }
            update_option($optionKey, $entry->nodeValue);
        }
        echo '<h3 class="edu_success">Import successful. Please reload your settings page.</h3>';
        return true;
    } catch (Exception $e) {
        echo '<h3 class="edu_error">'.$e->getMessage().'</h3>';
        return false;
    }
}


function callMetadataRepoAPI($method, $url, $ticket=NULL, $auth=NULL, $data=NULL){
    $curl = curl_init();
    switch ($method){
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data){
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data){
                $fields = array(
                    'file[0]' => new CURLFile($data, 'text/xml', 'metadata.xml')
                );
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
            break;
        default:
            if ($data){
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
    }
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERPWD, $auth);
    if (empty($ticket)){
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
        ));
    }else{
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: EDU-TICKET '.$ticket,
            'Accept: application/json',
            'Content-Type: application/json',
        ));
    }

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    // EXECUTE:
    try{
        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($result === false) {
            trigger_error(curl_error($curl), E_USER_WARNING);
        }
        if ($httpcode === 401){
            $result = json_encode(array('message' => 'Error 401: Unauthorized. Please check your credentials.'));
        }
    } catch (Exception $e) {
        error_log('error: '.$e->getMessage());
        trigger_error($e->getMessage(), E_USER_WARNING);
    }
    if(!$result){
        $result = "Connection Failure";
    }
    curl_close($curl);
    return $result;
}
