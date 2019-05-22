<?php

/**
 * Plugin Name: Edusharing
 * Plugin URI:  https://example.com/plugins/the-basics/
 * Description: Adds a Edusharing-Block
 * Version:     1.0
 * Author:      metaVentis GmbH
 * Author URI:  http://metaventis.com
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
function es_block_enqueue()
{
    wp_enqueue_script(
        'es-edusharing-block', // Unique handle.
        plugins_url('/build/index.js', __FILE__), // Block.js: We register the block here.
        array('wp-editor', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components'), // Dependencies, defined above.
        filemtime(plugin_dir_path(__FILE__) . '/build/index.js') // filemtime — Gets file modification time.
    );


}
add_action('enqueue_block_editor_assets', 'es_block_enqueue');


function es_block_styles_example_enqueue()
{
    wp_enqueue_style(
        'es-block-styles-example-style', // Handle.
        plugins_url('style.css', __FILE__), // style.css: This file styles the block on the frontend.
        array(), // Dependencies, defined above.
        filemtime(plugin_dir_path(__FILE__) . 'style.css') // filemtime — Gets file modification time.
    );
}
add_action('enqueue_block_assets', 'es_block_styles_example_enqueue');

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

    $post_ID = get_the_ID();
    $postTitle = get_the_title();

    update_post_meta( get_the_ID(), 'es_repo_domain', get_option('es_repo_url') );
    update_post_meta( get_the_ID(), 'es_repo_ticket', get_repo_ticket() );
}
add_action( 'rest_api_init', 'es_register_meta' );


//register render-callback for the frontend
register_block_type('es/edusharing-block', array(
        'render_callback' => 'es_render_callback',
    )
);

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

    //check for data
    if ($nodeID){
        $url = edusharing_get_redirect_url($objectUrl, $displayMode, $post_ID, $objectVersion, get_option('es_repo_url'), get_option('es_appID'), get_locale());
        $url .=  '&height=' . urlencode($objectHeight) . '&width=' . urlencode($objectWidth);

        $inline = '<div class="eduContainer" data-type="esObject" data-url="' . get_site_url() .
            '/wp-content/plugins/edusharing/proxy.php?URL=' . urlencode($url) . '&resId=' .
            $nodeID . '&title=' . urlencode($objectTitle) . '&repoURL=' . urlencode(get_option('es_repo_url')) .
            '&mimetype=' . urlencode($mimeType) .
            '&mediatype=' . urlencode($mediaType) .
            '&caption=' . urlencode($objectCaption) .
            '"><div class="edusharing_spinner_inner"><div class="edusharing_spinner1"></div></div>' .
            '<div class="edusharing_spinner_inner"><div class="edusharing_spinner2"></div></div>'.
            '<div class="edusharing_spinner_inner"><div class="edusharing_spinner3"></div></div>'.
            'edu sharing object</div>';

        return '<div class="eduObject">
                <div class="'.$attributes['objectAlign'].'">'.$inline.'</div>
            </div>';
    }
    return false;
}
