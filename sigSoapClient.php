<?php

/**
 * Extend PHP SoapClient with some header information
 *
 * @package    mod_edusharing
 * @copyright  metaVentis GmbH — http://metaventis.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Extend PHP SoapClient with some header information
 *
 * @copyright  metaVentis GmbH — http://metaventis.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
class mod_edusharing_sig_soap_client extends SoapClient {

    /**
     * Set app properties and soap headers
     *
     * @param string $wsdl
     * @param array $options
     */
    public function __construct($wsdl, $options = array()) {
        ini_set('default_socket_timeout', 15);
        parent::__construct($wsdl, $options);
        $this->edusharing_set_soap_headers();
    }

    /**
     * Set soap headers
     *
     * @throws Exception
     */
    private function edusharing_set_soap_headers() {
        try {
            $timestamp = round(microtime(true) * 1000);
            $signdata = get_option('es_appID') . $timestamp;
            $privkey = get_option('es_privateKey');
            $pkeyid = openssl_get_privatekey($privkey);
            openssl_sign($signdata, $signature, $pkeyid);
            $signature = base64_encode($signature);
            openssl_free_key($pkeyid);
            $headers = array();
            $headers[] = new SOAPHeader('http://webservices.edu_sharing.org',
                    'appId', get_option('es_appID'));
            $headers[] = new SOAPHeader('http://webservices.edu_sharing.org', 'timestamp', $timestamp);
            $headers[] = new SOAPHeader('http://webservices.edu_sharing.org', 'signature', $signature);
            $headers[] = new SOAPHeader('http://webservices.edu_sharing.org', 'signed', $signdata);
            parent::__setSoapHeaders($headers);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
