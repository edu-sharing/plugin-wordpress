<?php

/**
 * Return app properties as XML
 *
 * @package mod_edusharing
 * @copyright metaVentis GmbH — http://metaventis.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include '../../../wp-load.php';

$xml = new SimpleXMLElement(
    '<?xml version="1.0" encoding="utf-8" ?><!DOCTYPE properties SYSTEM "http://java.sun.com/dtd/properties.dtd"><properties></properties>');

$entry = $xml->addChild('entry', get_option('es_appID'));
$entry->addAttribute('key', 'appid');
$entry = $xml->addChild('entry', 'CMS');
$entry->addAttribute('key', 'type');
$entry = $xml->addChild('entry', 'wordpress');
$entry->addAttribute('key', 'subtype');
$entry = $xml->addChild('entry', get_site_url());
$entry->addAttribute('key', 'domain');
$entry = $xml->addChild('entry', 'application_host');
$entry->addAttribute('key', 'host');
$entry = $xml->addChild('entry', 'true');
$entry->addAttribute('key', 'trustedclient');
$entry = $xml->addChild('entry', 'moodle:course/update');
$entry->addAttribute('key', 'hasTeachingPermission');
$entry = $xml->addChild('entry', get_option('es_publicKey'));
$entry->addAttribute('key', 'public_key');
$entry = $xml->addChild('entry', 'EDU_AUTH_AFFILIATION_NAME');
$entry->addAttribute('key', 'appcaption');

header('Content-type: text/xml');
print(html_entity_decode($xml->asXML()));
exit();
