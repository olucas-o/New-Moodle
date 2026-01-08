<?php
defined('MOODLE_INTERNAL') || die();

$THEME->name = 'celebra';
$THEME->parents = ['boost'];
$THEME->sheets = [];
$THEME->editor_sheets = [];
$THEME->usefallback = true;

$THEME->layouts = [
    'frontpage' => [
        'file' => 'frontpage.php',
        'regions' => [],
        'defaultregion' => 'content',
    ],
    'course' => [
        'file' => 'course.php',
        'regions' => [],
        'defaultregion' => 'content',
    ],
    'standard' => [
        'file' => 'standard.php',
        'regions' => [],
        'defaultregion' => 'content',
    ],
];

$THEME->scss = function($theme) {
    return theme_celebra_get_main_scss_content($theme);
};

$THEME->javascript = [];
