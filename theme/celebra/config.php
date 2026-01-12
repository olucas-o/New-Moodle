<?php
defined('MOODLE_INTERNAL') || die();

$THEME->name = 'theme_celebra';
$THEME->parents = ['boost'];
$THEME->doctype = 'html5';
$THEME->enable_dock = false;
$THEME->editor_sheets = [];

// Aponta para a função customizada no lib.php que carrega seu CSS
$THEME->scss = function($theme) {
    return theme_celebra_get_main_scss_content($theme);
};

$THEME->layouts = [
    // Base: Obrigatório. Usamos o drawers.php do Boost (padrão moderno)
    'base' => [
        'file' => 'drawers.php',
        'regions' => [],
    ],

    // Standard: Páginas genéricas. Forçamos o tema 'boost' para segurança
    'standard' => [
        'theme' => 'boost',
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],

    // Admin: Forçamos o tema 'boost' para o painel de administração não quebrar
    'admin' => [
        'theme' => 'boost',
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],

    // Dashboard: Por segurança, usamos o padrão por enquanto
    'mydashboard' => [
        'theme' => 'boost',
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
        'options' => ['langmenu' => true],
    ],

    // Página inicial: Seu layout customizado
    'frontpage' => [
        'file' => 'frontpage.php',
        'regions' => [],
    ],

    // Página interna do curso: Seu layout customizado
    'course' => [
        'file' => 'course.php',
        'regions' => [],
        'options' => ['nonavbar' => false], // Mantém a navbar para navegação
    ],

    // Login: Seu layout customizado
    'login' => [
        'file' => 'login.php',
        'regions' => [],
        'options' => ['langmenu' => true],
    ],
];