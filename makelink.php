<?php

/**
 * Callback script for repo
 *
 * Called from repository after selecting a node/resource in the opened popup window
 * Transfers the node-id into the Location field of the opener (edit resource window)
 *
 * @package    mod_edusharing
 * @copyright  metaVentis GmbH — http://metaventis.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$PAGE->set_url($CFG->wwwroot.$SCRIPT);
$PAGE->set_context(context_system::instance() );

echo $OUTPUT->header();

$eduresource = addslashes_js(optional_param('nodeId', '', PARAM_RAW));
$title = addslashes_js(optional_param('title', '', PARAM_RAW));
echo <<<content
<script type="text/javascript">
        window.top.setNode('$eduresource', '$title');
</script>
content;

