<?php
require_once(__DIR__ . '/../../../config.php');

require_login();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'));
$cmid = (int)($data->cmid ?? 0);

if (!$cmid) {
    http_response_code(400);
    exit;
}

$cm = get_coursemodule_from_id(null, $cmid, 0, false, MUST_EXIST);
$course = get_course($cm->course);

$completion = new completion_info($course);
$completion->update_state($cm, COMPLETION_COMPLETE);

echo json_encode(['status' => 'ok']);
