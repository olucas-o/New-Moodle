<?php
defined('MOODLE_INTERNAL') || die();

function theme_celebra_get_main_scss_content($theme) {
    global $CFG;

    $scss  = file_get_contents($CFG->dirroot . '/theme/celebra/scss/celebra.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/celebra/scss/_frontpage.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/celebra/scss/_course.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/celebra/scss/_login.scss');

    return $scss;
}
