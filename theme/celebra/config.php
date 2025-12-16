<?php
defined('MOODLE_INTERNAL') || die();

$THEME->name = 'celebra';

$THEME->parents = ['boost']; 
$THEME->sheets = [];

$THEME->scss = function($theme) {
    return theme_celebra_get_main_scss_content($theme);
};

$THEME->rendererfactory = 'theme_overridden_renderer_factory';

$THEME->layouts = [
    'frontpage' => [
        'file' => 'frontpage.php',
        'regions' => []
    ],
    'standard' => [
        'file' => 'standard.php',
        'regions' => []
    ],
];
