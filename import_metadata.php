<?php

require_once("../../../wp-load.php");

/**
 * Get repository properties and generate app properties - put them to configuration
 *
 * @package mod_edusharing
 * @copyright metaVentis GmbH — http://metaventis.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
?>
<html>
<head>
<title>edu-sharing metadata import</title>
<style type="text/css" id="vbulletin_css">
body {
    background: #e4f3f9;
    color: #000000;
    font: 11pt verdana, geneva, lucida, 'lucida grande', arial, helvetica,
    sans-serif;
    margin: 5px 10px 10px 10px;
    padding: 0px;
}

table {
    background: #e4f3f9;
    color: #000000;
    font: 10pt verdana, geneva, lucida, 'lucida grande', arial, helvetica,
    sans-serif;
    margin: 5px 10px 10px 10px;
    padding: 0px;
}

p {
    margin: 10px;
    padding: 20px;
    background: #AEF2AC;
}

fieldset {
    margin: 10px;
    border: 1px solid #ddd;
}
</style>
</head>
<body>
<?php

if (!current_user_can('manage_options')) {
    echo 'Access denied!';
    exit();
}

/**
 * Form for importing repository properties
 * @param string $url The url to retrieve repository metadata
 * @return string
 *
 */
function get_form($url) {
    $form = '
        <form action="import_metadata.php" method="post" name="mdform">
            <fieldset>
                <legend>
                    Import application metadata
                </legend>
                <table>
                    <tr>
                        <td colspan="2"> Example metadata endpoints:
                        <br>
                        <table>
                            <tr>
                                <td>Repository: </td><td><a href="javascript:void();"
                                    onclick="document.forms[0].mdataurl.value=\'http://your-server-name/edu-sharing/metadata?format=lms\'">
                                    http://edu-sharing-server/edu-sharing/metadata?format=lms</a>
                                <br>
                                </td>
                            </tr>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="metadata">Metadata endpoint:</label></td>
                        <td>
                        <input type="text" size="80" id="metadata" name="mdataurl" value="' . $url . '">
                        <input type="submit" value="import">
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>';

    return $form;
}

$filename = '';

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


$metadataurl = get_param('mdataurl');
if (!empty($metadataurl)) {

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

        $metadataUrl = plugins_url() . '/edusharing/metadata.php';
        echo 'Import sucessfull. Please reload your settings page.<br>';
        echo 'Link to register wordpess in the edusharing repository: ';
        echo "<a href=\"$metadataUrl\">$metadataUrl</a>";
        exit();
    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }
}

echo get_form('');
exit();
