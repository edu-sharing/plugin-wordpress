<?php

/**
 * Get repository properties and generate app properties - put them to configuration
 *
 * @package mod_edusharing
 * @copyright metaVentis GmbH — http://metaventis.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */



function es_import_metadata_menu() {
    $menu_slug2 = 'es-import'; // unique identifier.
    $capability = 'manage_options';
    $callback2  = 'es_import_metadata_page';
    $hook = add_submenu_page( 'es-menu', 'edu-sharing metadata import', 'Metadaten Importieren', $capability, $menu_slug2, $callback2 );

    add_action( 'load-' . $hook, 'es_enqueue_import_css' );
    function es_enqueue_import_css() {
        wp_enqueue_style('es_import_css', plugins_url('/css/import_metadata_style.css', __FILE__));
    }
}
add_action('admin_menu', 'es_import_metadata_menu');


function es_import_metadata_page() {
?>
    <div class="es-header">
        <h1>Import metadata from an edu-sharing repository</h1>
    </div>

    <div class="wrap">
    <?php

    if (!current_user_can('manage_options')) {
        echo 'Access denied!';
        exit();
    }

    if(isset($_POST['repoReg'])){
        $auth = base64_encode( sanitize_text_field($_POST['repoAdmin']) . ':' . $_POST['repoPwd']);
        callRepo($auth);
        exit();
    }

    if (isset($_POST['mdataurl'])) {
        edusharing_import_metadata(esc_url($_POST['mdataurl']));
        echo getRepoForm();
        exit();
    }

    echo get_form();
    echo getRepoForm();

    echo '</div>';
}

function callRepo($auth){
    $repo_url = get_option('es_repo_url');
    $data = createXmlMetadata();

    $answer = json_decode(registerWithRepo($repo_url, $auth, $data), true);
    if ( isset($answer['appid']) ){
        echo('<h3 class="edu_success">Successfully registered the edusharing-WordPress-plugin at: '.$repo_url.'</h3>');
    }else{
        echo('<h3 class="edu_error">ERROR: Could not register the edusharing-WordPress-plugin at: '.$repo_url.'</h3>');
        if ( isset($answer['message']) ){
            echo '<p class="edu_error">'.$answer['message'].'</p>';
        }
        echo '<br>';
        echo '<h3>Register the WordPress-Plugin in the Repository manually:</h3>';
        echo '
            <p class="edu_metadata"> To register the WordPress-PlugIn manually got to the 
            <a href="'.$repo_url.'" target="_blank">Repository</a> and open the "APPLICATIONS"-tab of the "Admin-Tools" interface.<br>
            Only the system administrator may use this tool.<br>
            Enter the URL of the WordPress you want to connect. The URL should look like this:  
            „[WordPress-install-directory]/wp-content/plugins/edusharing/metadata.php".<br>
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
            <form class="repo-reg" method="post">
                <h3>Try to register the edu-sharing WordPress-plugin with a repository:</h3>
                <p>If your WordPress is behind a proxy-server, this might not work and you have to register the plugin manually.</p>
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
        <form method="post" name="mdform">
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
        <p>To export the edu-sharing plugin metadata use the following url: <a class="edu_export" target="_blank" href="' . home_url() . '/?feed=edusharing_metadata">' . home_url() . '/?feed=edusharing_metadata</a></p>';
}

