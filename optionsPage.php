<?php

//Options-Page
function es_register_settings() {
    //App Options
    add_option( 'es_appID', 'Wordpress_'.uniqid());
    register_setting( 'es_app_group', 'es_appID');
    add_option( 'es_publicKey');
    register_setting( 'es_app_group', 'es_publicKey');
    add_option( 'es_privateKey');
    register_setting( 'es_app_group', 'es_privateKey');
    add_option( 'es_repo_host', $_SERVER['SERVER_ADDR']);
    register_setting( 'es_app_group', 'es_repo_host');
    //Repo Options
    add_option( 'es_repo_public_key', 'publicKey');
    register_setting( 'es_repo_group', 'es_repo_public_key');

    add_option( 'es_repo_port', '8080');
    register_setting( 'es_repo_group', 'es_repo_port');

    add_option( 'es_repo_clientPort', '8080');
    register_setting( 'es_repo_group', 'es_repo_clientPort');

    add_option( 'es_repo_domain', 'repo_domain');
    register_setting( 'es_repo_group', 'es_repo_domain');

    add_option( 'es_repo_url', 'repo_url');
    register_setting( 'es_repo_group', 'es_repo_url');

    add_option( 'es_repo_authenticationwebservice_wsdl', 'authenticationwebservice_wsdl');
    register_setting( 'es_repo_group', 'es_repo_authenticationwebservice_wsdl');

    add_option( 'es_repo_usagewebservice_wsdl', 'es_repo_usagewebservice_wsdl');
    register_setting( 'es_repo_group', 'es_repo_usagewebservice_wsdl');

    add_option( 'es_repo_protocol', 'http');
    register_setting( 'es_repo_group', 'es_repo_protocol');    

    add_option( 'es_repo_version', '4.2');
    register_setting( 'es_repo_group', 'es_repo_version');
    //Auth Options
    add_option( 'es_auth_key', 'username');
    register_setting( 'es_auth_group', 'es_auth_key');
    add_option( 'es_auth_userid', 'userid');
    register_setting( 'es_auth_group', 'es_auth_userid');
    add_option( 'es_auth_lastname', 'lastname');
    register_setting( 'es_auth_group', 'es_auth_lastname');
    add_option( 'es_auth_firstname', 'firstname');
    register_setting( 'es_auth_group', 'es_auth_firstname');
    add_option( 'es_auth_mail', 'email');
    register_setting( 'es_auth_group', 'es_auth_mail');
    add_option( 'es_auth_affiliation', 'affiliation');
    register_setting( 'es_auth_group', 'es_auth_affiliation');
    add_option( 'es_auth_affiliation_name', 'affiliation_name');
    register_setting( 'es_auth_group', 'es_auth_affiliation_name');
    //Guest Options
    add_option( 'es_guest_option');
    register_setting( 'es_guest_group', 'es_guest_option');
    add_option( 'es_guest_id');
    register_setting( 'es_guest_group', 'es_guest_id');

}
add_action( 'admin_init', 'es_register_settings' );


if(!get_option('es_publicKey')) {
    $sslkeypair = edusharing_get_ssl_keypair();
    if (empty($sslkeypair['privateKey'])) { //is this usefull?
        update_option('es_publicKey', 'Failed to generate SSL-key');
        update_option('es_privateKey', 'Failed to generate SSL-key');
    }else{
        update_option('es_publicKey', $sslkeypair['publicKey']);
        update_option('es_privateKey', $sslkeypair['privateKey']);
    }
}

function es_register_options_page() {
    add_options_page(__( 'Edu-Sharing Einstellungen', 'edusharing' ), 'Einstellungen', 'manage_options', 'es-options', 'es_options_page');
}
//add_action('admin_menu', 'es_register_options_page');

function es_menu() {
    $page_title = __( 'Edu-Sharing Einstellungen', 'edusharing');
    $menu_title = 'edu-sharing';
    $capability = 'manage_options'; // which menu.
    $menu_slug  = 'es-menu'; // unique identifier.
    $callback   = 'es_options_page';
    $icon       = plugin_dir_url(__FILE__) . '/img/icon.svg';
    $position   = 81;
    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $callback, $icon, $position );
}
add_action('admin_menu', 'es_menu');

function es_options_page() {
?>
    <div class="es-settings">
    <?php screen_icon(); ?>
    <h1><img src="<?php echo plugin_dir_url(__FILE__) . '/img/icon.svg'  ?>"><?php _e( 'Edu-Sharing Einstellungen', 'edusharing' ) ?></h1>
        <div class="es-connect-repo">
            <div>
                <h3><?php _e( 'Mit Heimat-Repositorium verbinden:', 'edusharing' ); ?></h3>
                <p><?php _e( 'Dies füllt automatisch viele der Einstellungen aus.', 'edusharing' ); ?></p>
            </div>
            <a href="<?php echo admin_url('admin.php?page=es-import');  ?>"><?php _e( 'Repositorium verbinden', 'edusharing' ); ?></a>
        </div>
        <div class="es-forms">
    <form method="post" action="options.php">
        <?php settings_fields( 'es_app_group' ); ?>
        <h3><?php _e( 'Plugin Einstellungen', 'edusharing' ); ?></h3>
        <table>
            <tr>
                <th scope="row"><label for="es_appID">AppID</label></th>
                <td><input type="text" id="es_appID" name="es_appID" value="<?php echo get_option('es_appID'); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="es_publicKey">Public Key</label></th>
                <td><textarea id="es_publicKey" name="es_publicKey" rows="10" cols="30"/><?php echo get_option('es_publicKey'); ?></textarea></td>
            </tr>
            <tr>
                <th scope="row"><label for="es_privateKey">Private Key</label></th>
                <td><textarea id="es_privateKey" name="es_privateKey" rows="10" cols="30"/><?php echo get_option('es_privateKey'); ?></textarea></td>
            </tr>
            <tr>
                <th scope="row"><label for="es_repo_host">Host</label></th>
                <td><input type="text" id="es_repo_host" name="es_repo_host" value="<?php echo get_option('es_repo_host'); ?>" /></td>
            </tr>
        </table>
        <?php  submit_button(); ?>
        </form>

        <form method="post" action="options.php">
        <?php settings_fields( 'es_repo_group' ); ?>
        <h3><?php _e( 'Repository Einstellungen', 'edusharing' ); ?></h3>
        <table>
            <tr>
                <th scope="row"><label for="es_repo_public_key">Public Key</label></th>
                <td><textarea id="es_repo_public_key" name="es_repo_public_key" rows="10" cols="30"/><?php echo get_option('es_repo_public_key'); ?></textarea></td>
            </tr>
            <!-- <tr>
                <th scope="row"><label for="es_repo_port">Port</label></th>
                <td><input type="text" id="es_repo_port" name="es_repo_port" value="<?php echo get_option('es_repo_port'); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="es_repo_clientPort">Client Port</label></th>
                <td><input type="text" id="es_repo_clientPort" name="es_repo_clientPort" value="<?php echo get_option('es_repo_clientPort'); ?>" /></td>
            </tr> -->
            <tr>
                <th scope="row"><label for="es_repo_domain">Domain</label></th>
                <td><input type="text" id="es_repo_domain" name="es_repo_domain" value="<?php echo get_option('es_repo_domain'); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="es_repo_url">URL</label></th>
                <td><input type="text" id="es_repo_url" name="es_repo_url" value="<?php echo get_option('es_repo_url'); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="es_repo_authenticationwebservice_wsdl">authenticationwebservice_wsdl</label></th>
                <td><input type="text" id="es_repo_authenticationwebservice_wsdl" name="es_repo_authenticationwebservice_wsdl" value="<?php echo get_option('es_repo_authenticationwebservice_wsdl'); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="es_repo_usagewebservice_wsdl">es_repo_usagewebservice_wsdl</label></th>
                <td><input type="text" id="es_repo_usagewebservice_wsdl" name="es_repo_usagewebservice_wsdl" value="<?php echo get_option('es_repo_usagewebservice_wsdl'); ?>" /></td>
            </tr>
            <!-- <tr>
                <th scope="row"><label for="es_repo_protocol">Protocol</label></th>
                <td><input type="text" id="es_repo_protocol" name="es_repo_protocol" value="<?php echo get_option('es_repo_protocol'); ?>" /></td>
            </tr>            
            <tr>
                <th scope="row"><label for="es_repo_version">Version</label></th>
                <td><input type="text" id="es_repo_version" name="es_repo_version" value="<?php echo get_option('es_repo_version'); ?>" /></td>
            </tr> -->
        </table>
            <?php  submit_button(); ?>
        </form>

        <form method="post" action="options.php">
        <?php settings_fields( 'es_auth_group' ); ?>
        <h3><?php _e( 'Authentication properties', 'edusharing' ); ?></h3>
        <table>
            <tr>
                <th scope="row"><label for="es_auth_key">EDU_AUTH_KEY</label></th>
                <td><input type="text" id="es_auth_key" name="es_auth_key" value="<?php echo get_option('es_auth_key'); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="es_auth_userid">PARAM_NAME_USERID</label></th>
                <td><input type="text" id="es_auth_userid" name="es_auth_userid" value="<?php echo get_option('es_auth_userid'); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="es_auth_lastname">PARAM_NAME_LASTNAME</label></th>
                <td><input type="text" id="es_auth_lastname" name="es_auth_lastname" value="<?php echo get_option('es_auth_lastname'); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="es_auth_firstname">PARAM_NAME_FIRSTNAME</label></th>
                <td><input type="text" id="es_auth_firstname" name="es_auth_firstname" value="<?php echo get_option('es_auth_firstname'); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="es_auth_mail">PARAM_NAME_EMAIL</label></th>
                <td><input type="text" id="es_auth_mail" name="es_auth_mail" value="<?php echo get_option('es_auth_mail'); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="es_auth_affiliation">AFFILIATION</label></th>
                <td><input type="text" id="es_auth_affiliation" name="es_auth_affiliation" value="<?php echo get_option('es_auth_affiliation'); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="es_auth_affiliation_name">AFFILIATION_NAME</label></th>
                <td><input type="text" id="es_auth_affiliation_name" name="es_auth_affiliation_name" value="<?php echo get_option('es_auth_affiliation_name'); ?>" /></td>
            </tr>
        </table>
        <?php  submit_button(); ?>
        </form>

        <form method="post" action="options.php">
        <?php settings_fields( 'es_guest_group' ); ?>
        <h3><?php _e( 'Gast Einstellungen', 'edusharing' ); ?></h3>
        <table>
            <tr>
                <th scope="row"><label for="es_guest_option"><?php _e( 'Gast-Option', 'edusharing' ); ?></label></th>
                <td><input type="checkbox" name="es_guest_option" value="1" <?php checked(1, get_option('es_guest_option'), true); ?> /></td>
            </tr>
            <tr>
                <th scope="row"><label for="es_guest_id">guest_ID</label></th>
                <td><input type="text" id="es_guest_id" name="es_guest_id" value="<?php echo get_option('es_guest_id'); ?>" /></td>
            </tr>
        </table>
            <?php  submit_button(); ?>
    </form>
        </div>
    </div>

    <?php
}

/**
 * Get ssl private and public key from app configuration
 * @return array $sslkeypair
 */
function edusharing_get_ssl_keypair() {
    $sslkeypair = array();
    $res = openssl_pkey_new();
    openssl_pkey_export($res, $privatekey);
    $publickey = openssl_pkey_get_details($res);
    $publickey = $publickey["key"];
    $sslkeypair['privateKey'] = $privatekey;
    $sslkeypair['publicKey'] = $publickey;
    return $sslkeypair;
}
