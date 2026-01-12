<?php
defined('MOODLE_INTERNAL') || die();

function theme_celebra_get_main_scss_content($theme) {
    global $CFG;

    // 1. Carrega o SCSS do tema pai (Boost) primeiro
    // Isso garante que variáveis do Bootstrap e mixins existam
    $scss = theme_boost_get_main_scss_content($theme);

    // 2. Concatena os seus arquivos SCSS customizados
    // Usamos file_exists para evitar erros se algum arquivo faltar
    $files = [
        '/theme/celebra/scss/celebra.scss',
        '/theme/celebra/scss/_frontpage.scss',
        '/theme/celebra/scss/_course.scss',
        '/theme/celebra/scss/_login.scss'
    ];

    foreach ($files as $file) {
        if (file_exists($CFG->dirroot . $file)) {
            $scss .= file_get_contents($CFG->dirroot . $file);
        }
    }

    return $scss;
}