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

    const CONTEXT_VIEWER = 0;
    const CONTEXT_EDITOR = 1;

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
    public function edusharing_authentication_get_ticket($context = self::CONTEXT_VIEWER) {

        global $USER;

        if(!isset($USER->edusharing_userticket_context))
            $USER = new stdClass();
            $USER->edusharing_userticket_context = self::CONTEXT_VIEWER;

        // Ticket available and has the right context.
        if (isset($USER->edusharing_userticket) && $USER->edusharing_userticket_context >= $context) {

            // Ticket is younger than 10s, we must not check.
            if (isset($USER->edusharing_userticketvalidationts)
                    && time() - $USER->edusharing_userticketvalidationts < 10) {
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
                        "ticket"  => session_id(), "ssoData"  => edusharing_get_auth_data($context));
        try {
            $client = new mod_edusharing_sig_soap_client($this->authenticationservicewsdl);
            $return = $client->authenticateByTrustedApp($paramstrusted);
            $ticket = $return->authenticateByTrustedAppReturn->ticket;
            $USER->edusharing_userticket = $ticket;
            $USER->edusharing_userticketvalidationts = time();
            $USER->edusharing_userticket_context = $context;
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

    global $USER, $SESSION;

    $user = wp_get_current_user();

    //$edusharing->user = $user->user_login;
    //$edusharing->userMail = $user->user_email;


    // Set by external sso script.
    if (isset($SESSION -> edusharing_sso) && !empty($SESSION -> edusharing_sso)) {
        $eduauthparamnameuserid = $user->ID;
        return $SESSION -> edusharing_sso[$eduauthparamnameuserid];
    }

    $guestoption = get_option('es_guest_option');
    if (!empty($guestoption)) {
        $guestid = get_option('es_guest_id');
        if (empty($guestid)) {
            $guestid = 'esguest';
        }

        return $guestid;
    }

    $eduauthkey = get_option('es_auth_key');
    //echo '<script>console.log("user: '.$USER->username.'")</script>';

    if($eduauthkey == 'id')
        return $user->user_login;
    if($eduauthkey == 'idnumber')
        return $user->ID;
    if($eduauthkey == 'email')
        return $user->user_email;
    if(isset($USER->profile[$eduauthkey]))
        return $USER->profile[$eduauthkey];
    return $user->user_login;
    //return $eduauthkey;
}


/**
 * Return data for authByTrustedApp
 *
 * @return array
 */
function edusharing_get_auth_data($context) {

    global $USER, $CFG, $SESSION;

    $user = wp_get_current_user();
    //var_dump($user->ID);die();

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
        //echo '<script>console.log("user: '.$eduauthparamnameuserid.'")</script>';

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

        $guestoption = get_option('es_guest_option');
        //echo '<script>console.log("guest-option: '.$guestoption.'")</script>';
        //if ($guestoption == 1 || $context == mod_edusharing_web_service_factory::CONTEXT_VIEWER) {
        if ($guestoption == 1 || $user->ID == 0) {
            $guestid = get_option('es_guest_id');
            if (empty($guestid)) {
                $guestid = 'esguest';
            }

            echo '<script>console.log("guest?: '.$guestid.'")</script>';

            $authparams = array(
                array('key'  => $eduauthparamnameuserid, 'value'  => $guestid),
                array('key'  => $eduauthparamnamelastname, 'value'  => ''),
                array('key'  => $eduauthparamnamefirstname, 'value'  => ''),
                array('key'  => $eduauthparamnameemail, 'value'  => ''),
                array('key'  => 'affiliation', 'value'  => $eduauthaffiliation),
                array('key'  => 'affiliationname', 'value' => $eduauthaffiliationname)
            );
        } else {

            //echo '<script>console.log("user?: '.edusharing_get_auth_key().'")</script>';
            $authparams = array(
                array('key'  => $eduauthparamnameuserid, 'value'  => edusharing_get_auth_key()),
                array('key'  => $eduauthparamnamelastname, 'value'  => $user->user_lastname),
                array('key'  => $eduauthparamnamefirstname, 'value'  => $user->user_firstname),
                array('key'  => $eduauthparamnameemail, 'value'  => $user->user_email),
                array('key'  => 'affiliation', 'value'  => $eduauthaffiliation),
                array('key'  => 'affiliationname', 'value' => $eduauthaffiliationname)
            );
        }
    }

    /*if (get_config('edusharing', 'EDU_AUTH_CONVEYGLOBALGROUPS') == 'yes' ||
        get_config('edusharing', 'EDU_AUTH_CONVEYGLOBALGROUPS') == '1') {
        $authparams[] = array('key'  => 'globalgroups', 'value'  => edusharing_get_user_cohorts());
    }*/
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
    //error_log(print_r('Signatur-Fehler: '.get_option('es_privateKey'), TRUE));
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
        //var_dump($params);die();
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
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param stdClass $edusharing An object from the form in mod_form.php
 * @return boolean Success/Fail
 */
function edusharing_update_instance(stdClass $edusharing) {

    global $CFG, $COURSE, $DB, $SESSION, $USER;

    // FIX: when editing a moodle-course-module the $edusharing->id will be named $edusharing->instance
    if ( ! empty($edusharing->instance) ) {
        $edusharing->id = $edusharing->instance;
    }

    $edusharing->timemodified = time();

    // Load previous state.
    $memento = $DB->get_record(EDUSHARING_TABLE, array('id'  => $edusharing->id));
    if ( ! $memento ) {
        throw new Exception(get_string('error_loading_memento', 'edusharing'));
    }

    // You may have to add extra stuff in here.
    $edusharing = edusharing_postprocess($edusharing);

    $xml = edusharing_get_usage_xml($edusharing);

    try {
        $connectionurl = get_config('edusharing', 'repository_usagewebservice_wsdl');
        if (!$connectionurl) {
            trigger_error(get_string('error_missing_usagewsdl', 'edusharing'), E_USER_WARNING);
        }

        $client = new mod_edusharing_sig_soap_client($connectionurl, array());

        $params = array(
            "eduRef"  => $edusharing->object_url,
            "user"  => edusharing_get_auth_key(),
            "lmsId"  => get_config('edusharing', 'application_appid'),
            "courseId"  => $edusharing->course,
            "userMail"  => $edusharing->userMail,
            "fromUsed"  => '2002-05-30T09:00:00',
            "toUsed"  => '2222-05-30T09:00:00',
            "distinctPersons"  => '0',
            "version"  => $memento->object_version,
            "resourceId"  => $edusharing->id,
            "xmlParams"  => $xml,
        );

        $setusage = $client->setUsage($params);
        $edusharing->object_version = $memento->object_version;
        // Throws exception on error, so no further checking required.
        $DB->update_record(EDUSHARING_TABLE, $edusharing);
    } catch (SoapFault $exception) {
        // Roll back.
        $DB->update_record(EDUSHARING_TABLE, $memento);

        trigger_error($exception->getMessage(), E_USER_WARNING);

        return false;
    }

    return true;
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
    /*$data4xml[1]["specific"]['courseId'] = $edusharing->course;
    $data4xml[1]["specific"]['courseFullname'] = $course->fullname;
    $data4xml[1]["specific"]['courseShortname'] = $course->shortname;
    $data4xml[1]["specific"]['courseSummary'] = $course->summary;
    $data4xml[1]["specific"]['categoryId'] = $course->category;
    $data4xml[1]["specific"]['categoryName'] = $category->name;*/
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
    $url .= '&locale=' . urlencode(get_locale()); //repository
    $url .= '&language=' . urlencode(get_locale()); //rendering service

    $url .= '&u='. rawurlencode(base64_encode(edusharing_encrypt_with_repo_public(edusharing_get_auth_key())));

    return $url;
}