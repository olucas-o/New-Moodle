<?php
define('AJAX_SCRIPT', true);

require_once(__DIR__ . '/../../../config.php');
require_login();

require_sesskey();

$cmid = required_param('cmid', PARAM_INT);

$cm = get_coursemodule_from_id(null, $cmid, 0, false, MUST_EXIST);
$course = get_course($cm->course);

$context = context_module::instance($cm->id);
require_capability('mod/' . $cm->modname . ':view', $context);

// Completion
$completion = new completion_info($course);

if (!$completion->is_enabled($cm)) {
    throw new moodle_exception('completionnotenabled', 'completion');
}

// Marca como concluído
$completion->update_state($cm, COMPLETION_COMPLETE);

echo json_encode([
    'status' => 'ok',
    'cmid' => $cmid
]);
