<?php

/**
 * Plugin Name: Edusharing
 * Description: Adds a Edusharing-Block to the Gutenberg-Editor
 * Version:     1.0
 * Author:      metaVentis GmbH
 * Author URI:  http://metaventis.com
 * Text Domain: edusharing
 * Domain Path: /languages
 * License:     GNU GPL v3 or later
 * License URI: http://www.gnu.org/copyleft/gpl.html
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once(dirname(__FILE__).'/lib/cclib.php');
require_once(dirname(__FILE__).'/lib/RenderParameter.php');
require_once(dirname(__FILE__).'/optionsPage.php');

$post_ID = Null;
$postTitle = Null;

wp_register_script( 'edu', plugins_url('/edu.js', __FILE__), array('jquery'));
wp_enqueue_script( 'edu' );

/**
 * Enqueue the block's assets for the editor.
 *
 * wp-blocks:  The registerBlockType() function to register blocks.
 * wp-element: The wp.element.createElement() function to create elements.
 * wp-i18n:    The __() function for internationalization.
 *
 * @since 1.0.0
 */
function es_block_init() {
    wp_register_script(
        'es-edusharing-block',
        plugins_url('/build/index.js', __FILE__),
        array( 'wp-editor', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components' ),
        filemtime(plugin_dir_path(__FILE__) . '/build/index.js')
    );

    register_block_type( 'es/edusharing-block', array(
        'render_callback' => 'es_render_callback',
        'editor_script' => 'es-edusharing-block'
    ) );
}
add_action( 'init', 'es_block_init' );

//load translations
function edusharing_load_plugin_textdomain() {
    load_plugin_textdomain( 'edusharing', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'edusharing_load_plugin_textdomain' );

function es_set_script_translations()
{
    wp_set_script_translations( 'es-edusharing-block', 'edusharing', plugin_dir_path( __FILE__ ) . 'languages' );
}
add_action('init', 'es_set_script_translations');

function es_block_styles_enqueue()
{
    wp_enqueue_style(
        'es-block-styles-example-style', // Handle.
        plugins_url('/css/style.css', __FILE__), // style.css: This file styles the block.
        array(),
        filemtime(plugin_dir_path(__FILE__) . '/css/style.css') // filemtime — Gets file modification time.
    );
}
add_action('enqueue_block_assets', 'es_block_styles_enqueue');

function es_admin_style() {
    wp_enqueue_style('es_admin_css', plugins_url('/css/admin.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'es_admin_style');

// register custom meta tag field
function es_register_meta() {
    global $post_ID;
    global $postTitle;

    register_meta( 'post', 'es_repo_domain', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ) );
    register_meta( 'post', 'es_repo_ticket', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ) );
    register_meta( 'post', 'es_plugin_url', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ) );

    $post_ID = get_the_ID();
    $postTitle = get_the_title();

    update_post_meta( get_the_ID(), 'es_repo_domain', get_option('es_repo_url') );
    update_post_meta( get_the_ID(), 'es_repo_ticket', get_repo_ticket() );
    update_post_meta( get_the_ID(), 'es_plugin_url', plugins_url() );
}
add_action( 'rest_api_init', 'es_register_meta' );

//render frontend
function es_render_callback($attributes)
{
    $post_ID = get_the_ID();
    $nodeID = $attributes['nodeID'];
    $objectUrl = $attributes['objectUrl'];
    $objectVersion = $attributes['objectVersion'];
    $displayMode = 'inline';
    $objectTitle = $attributes['objectTitle'];
    $mimeType = $attributes['mimeType'];
    $mediaType = $attributes['mediaType'];
    $objectHeight = $attributes['objectHeight'];
    $objectWidth = $attributes['objectWidth'];
    $objectCaption = $attributes['objectCaption'];
    $resourceId = $attributes['resourceId'];
    $align = $attributes['align'];

    //check for data then generate the inline-html
    if ($nodeID){

        // Ensure that user exists in repository.
        if (is_user_logged_in()) {
            $ccauth = new mod_edusharing_web_service_factory();
            $ticket = $ccauth->edusharing_authentication_get_ticket();
        }

        $url = edusharing_get_redirect_url($objectUrl, $displayMode, $post_ID, $objectVersion, $resourceId);
        $url .=  '&height=' . urlencode($objectHeight) . '&width=' . urlencode($objectWidth);
        if ($mediaType == 'saved_search') {
            $url .= '&maxItems=' . $attributes['maxItems']
                . '&sortBy=' . $attributes['sortBy']
                . '&sortAscending=false'
                . '&view=list'; // . $attributes['view'];
        }

        $inline = '<div class="eduContainer" data-type="esObject" data-url="' . get_site_url() .
            '/wp-content/plugins/edusharing/proxy.php?URL=' . urlencode($url) . '&resId=' .
            $nodeID . '&title=' . urlencode($objectTitle) . '&repoURL=' . urlencode(get_option('es_repo_url')) .
            '&mimetype=' . urlencode($mimeType) .
            '&mediatype=' . urlencode($mediaType) .
            '&caption=' . urlencode($objectCaption) .
            '&width=' . urlencode($objectWidth) .
            '&ticket=' . $ticket .
            '"><div class="edusharing_spinner_inner"><div class="edusharing_spinner1"></div></div>' .
            '<div class="edusharing_spinner_inner"><div class="edusharing_spinner2"></div></div>'.
            '<div class="edusharing_spinner_inner"><div class="edusharing_spinner3"></div></div>'.
            'edu sharing object</div>';

        if($mediaType == 'link' || $mediaType == 'file-pdf'){
            return '<div class="eduObject">
                <div class="esLink '.$align.'">'.$inline.'</div>
            </div>';
        }
        return '<div class="eduObject">
                <div class="'.$align.'">'.$inline.'</div>
            </div>';
    }
    return false;
}

//if page or post is deleted permanently, delete the usage of each edusharing-object
function delete_usage_on_post_delete($postid){
    //check if post is autosafe
    if(!wp_is_post_autosave( $postid )){
        $blocks = parse_blocks(get_post($postid)->post_content);
        foreach ($blocks as $block) {
            if ('es/edusharing-block' === $block['blockName']) {
                $objectUrl = $block['attrs']['objectUrl'];
                $resourceId = $block['attrs']['resourceId'];
                edusharing_delete_instance($objectUrl, $postid, $resourceId);
            }
        }
    }
    return;
}
add_action('before_delete_post', 'delete_usage_on_post_delete');


function edusharing_activate() {

    // Fill this to automatically register the plugin with the repo upon installation
    //$metadataurl = 'http://localhost:8080/edu-sharing/metadata?format=lms';
    $metadataurl = null;
    $repo_admin = 'admin';
    $repo_pw = 'pw';

    $auth = $repo_admin.':'.$repo_pw;

    if (!empty($metadataurl) && get_option( 'es_autoinstall' ) != 'installed'){
        if (edusharing_import_metadata($metadataurl)){
            error_log('Successfully imported metadata from '.$metadataurl);
            $repo_url = get_config('edusharing', 'application_cc_gui_url');
            $apiUrl = $repo_url.'rest/admin/v1/applications?url='.plugins_url().'/edusharing/metadata.php';
            $answer = json_decode(callMetadataRepoAPI('PUT', $apiUrl, null, $auth), true);
            if (isset($answer['appid'])){
                add_option( 'es_autoinstall', 'installed');
                error_log('Successfully registered the edusharing-moodle-plugin at: '.$repo_url);
            }else{
                error_log('INSTALL ERROR: Could not register the edusharing-moodle-plugin at: '.$repo_url.' because: '.$answer['message']);
            }
        }else{
            error_log('INSTALL ERROR: Could not import metadata from '.$metadataurl);
        }
    }

}
register_activation_hook( __FILE__, 'edusharing_activate' );
