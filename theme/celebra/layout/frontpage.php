<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/coursecatlib.php');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/');
$PAGE->set_pagelayout('frontpage');

$renderer = $PAGE->get_renderer('theme_celebra');

echo $OUTPUT->header();
echo $renderer->render_frontpage();
echo $OUTPUT->footer();
