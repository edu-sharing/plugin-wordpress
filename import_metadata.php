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
    <link rel="stylesheet" href="css/import_metadata_style.css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap" rel="stylesheet">
</head>
<body>

<div class="h5p-header">
    <h1>Import metadata from an edu-sharing repository</h1>
</div>

<div class="wrap">
<?php

if (!current_user_can('manage_options')) {
    echo 'Access denied!';
    exit();
}

if(isset($_POST['repoReg'])){
    callRepo($_POST['repoAdmin'], $_POST['repoPwd']);
    exit();
}

if (isset($_POST['mdataurl'])) {
    edusharing_import_metadata($_POST['mdataurl']);
    echo getRepoForm();
    exit();
}

echo get_form();
echo getRepoForm();

echo '</div></body></html>';
exit();

function callRepo($user, $pwd){

    $repo_url = get_option('es_repo_url');
    $apiUrl = $repo_url.'rest/admin/v1/applications?url='.plugins_url().'/edusharing/metadata.php';
    $auth = $user.':'.$pwd;
    $answer = json_decode(callMetadataRepoAPI('PUT', $apiUrl, null, $auth), true);
    if ( isset($answer['appid']) ){
        echo('<h3 class="edu_success">Successfully registered the edusharing-moodle-plugin at: '.$repo_url.'</h3>');
    }else{
        echo('<h3 class="edu_error">ERROR: Could not register the edusharing-moodle-plugin at: '.$repo_url.'</h3>');
        if ( isset($answer['message']) ){
            echo '<p class="edu_error">'.$answer['message'].'</p>';
        }
        echo '<h3>Register the Moodle-Plugin in the Repository manually:</h3>';
        echo '
            <p class="edu_metadata"> To register the Moodle-PlugIn manually got to the 
            <a href="'.$repo_url.'" target="_blank">Repository</a> and open the "APPLICATIONS"-tab of the "Admin-Tools" interface.<br>
            Only the system administrator may use this tool.<br>
            Enter the URL of the Moodle you want to connect. The URL should look like this:  
            „[Moodle-install-directory]/mod/edusharing/metadata.php".<br>
            Click on "CONNECT" to register the LMS. You will be notified with a feedback message and your LMS instance 
            will appear as an entry in the list of registered applications.<br>
            If the automatic registration failed due to a connection issue caused by a proxy-server, you also need to 
            add the proxy-server IP-address as a "host_aliases"-attribute.
            </p>
        ';
    }
}

function getRepoForm(){
    $repo_url = get_option('es_repo_url');
    if (!empty($repo_url)){
        return '
            <form class="repo-reg" action="import_metadata.php" method="post">
                <h3>Try to register the edu-sharing moodle-plugin with a repository:</h3>
                <p>If your moodle is behind a proxy-server, this might not work and you have to register the plugin manually.</p>
                <div class="edu_metadata">
                    <div class="repo_input">
                        <p>Repo-URL:</p><input type="text" value="'.$repo_url.'" name=repoUrl />
                    </div>
                    <div class="repo_input">
                        <p>Repo-Admin-User:</p><input class="short_input" type="text" name="repoAdmin">
                        <p>Repo-Admin-Password:</p><input class="short_input" type="password" name="repoPwd">
                    </div>
                    <input class="btn" type="submit" value="Register Repo" name="repoReg">
                </div>            
            </form>
         ';
    }else{
        return false;
    }

}

/**
 * Form for importing repository properties
 * @param string $url The url to retrieve repository metadata
 * @return string
 *
 */
function get_form() {
    return '
        <form action="import_metadata.php" method="post" name="mdform">
            <h3>Enter your metadata endpoint here:</h3>
            <p>Hint: Just click on the example to copy it into the input-field.</p>
            <div class="edu_metadata">                
                <div class="edu_endpoint">
                    <p>Metadata-Endpoint:</p>
                    <input type="text" id="metadata" name="mdataurl" value="">
                    <input class="btn" type="submit" value="Import">
                </div>
                <div class="edu_example">
                    <p>(Example: <a href="javascript:void();"
                                   onclick="document.forms[0].mdataurl.value=\'http://your-server-name/edu-sharing/metadata?format=lms\'">
                                   http://your-server-name/edu-sharing/metadata?format=lms</a>)
                   </p>
                </div>
            </div>
        </form>
        <p>To export the edu-sharing plugin metadata use the following url: <a class="edu_export" target="_blank" href="' . plugins_url() . '/edusharing/metadata.php">' . plugins_url() . '/edusharing/metadata.php</a></p>';
}

