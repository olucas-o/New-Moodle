<?php
defined('MOODLE_INTERNAL') || die();

function theme_celebra_get_main_scss_content($theme) {
    $scss = '';
    $scss .= file_get_contents(__DIR__ . '/scss/base.scss');
    return $scss;
}

function theme_celebra_page_init(moodle_page $page) {

    if ($page->pagetype === 'site-index') {
        $page->requires->js('/theme/celebra/javascript/frontpage.js');
    }

}