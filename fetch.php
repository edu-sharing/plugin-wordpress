<?php

require_once(dirname(__FILE__).'/lib/cclib.php');
require_once("../../../wp-load.php");

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$useCase = $json_obj['useCase'];

if($useCase == 'setUsage'){
    $post_ID = $json_obj['post_id'];
    $postTitle = $json_obj['post_title'];
    $objectUrl = $json_obj['objectUrl'];
    $objectVersion = $json_obj['$objectVersion'];
    $id = edusharing_add_instance($objectVersion, $objectUrl, $post_ID, $postTitle);
    if ( ! $id ) {
        throw new Exception('Error: set_usage');
    }
    echo 'Usage set';
}elseif ($useCase == 'deleteUsage'){
    $post_ID = $json_obj['post_id'];
    $objectUrl = $json_obj['objectUrl'];
    edusharing_delete_instance($objectUrl, $post_ID);
    echo 'Usage deleted';
}elseif ($useCase == 'getTicket'){
    $post_ID = $json_obj['post_id'];
    update_post_meta( $post_ID, 'es_repo_ticket', get_repo_ticket() );
    echo 'Ticket for: '.$post_ID;
}else{
    echo 'Error: No useCase';
}
exit();



