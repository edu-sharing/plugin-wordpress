<?php

add_action('rest_api_init', function () {
    register_rest_route( 'edusharing/v1', 'setUsage/',array(
        'methods'  => 'GET',
        'callback' => 'setUsage',
        'args' => array(),
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        },
    ));
});
function setUsage(WP_REST_Request $request) {
    $post_ID = $request->get_param( 'post_id' );
    $postTitle = $request->get_param( 'post_title' );
    $objectUrl = $request->get_param( 'objectUrl' );
    $objectVersion = $request->get_param( 'objectVersion' );
    $resourceId = $request->get_param( 'resourceId' );

    $id = edusharing_add_instance($objectVersion, $objectUrl, $post_ID, $postTitle, $resourceId);
    if ( ! $id ) {
        throw new Exception('Error: set_usage');
    }
    return 'Usage set for resourceId: '.$resourceId;
}

add_action('rest_api_init', function () {
    register_rest_route( 'edusharing/v1', 'deleteUsage/',array(
        'methods'  => 'GET',
        'callback' => 'deleteUsage',
        'args' => array(),
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        },
    ));
});
function deleteUsage(WP_REST_Request $request) {
    $post_ID = $request->get_param( 'post_id' );
    $objectUrl = $request->get_param( 'objectUrl' );
    $resourceId = $request->get_param( 'resourceId' );

    edusharing_delete_instance($objectUrl, $post_ID, $resourceId);
    return 'Usage deleted for resourceId: '.$resourceId;
}

add_action('rest_api_init', function () {
    register_rest_route( 'edusharing/v1', 'getTicket/',array(
        'methods'  => 'GET',
        'callback' => 'getTicket',
        'args' => array(),
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        },
    ));
});
function getTicket(WP_REST_Request $request) {
    $post_ID = $request->get_param( 'post_id' );

    update_post_meta( $post_ID, 'es_repo_ticket', get_repo_ticket() );
    return 'Ticket for: '.$post_ID;
}

add_action('rest_api_init', function () {
    register_rest_route( 'edusharing/v1', 'previewImg/',array(
        'methods'  => 'GET',
        'callback' => 'previewImg',
        'args' => array(),
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        },
    ));
});
function previewImg(WP_REST_Request $request) {
    $post_id = $request->get_param( 'post_id' );
    $objectUrl = $request->get_param( 'objectUrl' );
    $objectVersion = $request->get_param( 'objectVersion' );
    $repoID = $request->get_param( 'repoID' );
    $resourceId = $request->get_param( 'resourceId' );


    if(substr(get_option('es_repo_url') , -1) == '/'){
        $previewservice = get_option('es_repo_url') . 'preview';
    }else{
        $previewservice = get_option('es_repo_url') . '/' . 'preview';
    }
    $time = round(microtime(true) * 1000);
    $ticket = $request->get_param( 'ticket' );

    $url = $previewservice;
    $url .= '?appId=' . get_option('es_appID');
    $url .= '&courseId=' . $post_id;
    $url .= '&repoId=' . edusharing_get_repository_id_from_url($objectUrl);
    $url .= '&proxyRepId=' . $repoID;
    $url .= '&nodeId=' . edusharing_get_object_id_from_url($objectUrl);
    $url .= '&resourceId=' . $resourceId;
    $url .= '&version=' . $objectVersion;
    $sigdata = get_option('es_appID') . $time . edusharing_get_object_id_from_url($objectUrl);
    $sig = urlencode(edusharing_get_signature($sigdata));
    $url .= '&sig=' . $sig;
    $url .= '&signed=' . $sigdata;
    $url .= '&ts=' . $time;

    try {
        $response   = wp_remote_get( $url );
        $mimetype   = wp_remote_retrieve_header( $response, 'Content-Type' );
        $output     = wp_remote_retrieve_body( $response );
    } catch (Exception $e) {
        error_log('curl-error: '.$e->getMessage());
        trigger_error($e->getMessage(), E_USER_WARNING);
    }
    return $output;
}
