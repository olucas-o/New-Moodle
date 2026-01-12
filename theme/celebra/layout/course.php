<?php
defined('MOODLE_INTERNAL') || die();

// Configurações básicas da página
$PAGE->set_heading($COURSE->fullname);
$PAGE->set_pagelayout('course');

// 1. Imprime o Cabeçalho (Navbar, CSS, Scripts iniciais)
echo $OUTPUT->header();

// 2. Área de Conteúdo Principal
echo '<div class="container-fluid py-4 celebra-course-wrapper">';

    // Verifica se a classe renderer existe para evitar erro fatal
    if (class_exists('\theme_celebra\output\course')) {
        $courseoutput = new \theme_celebra\output\course($COURSE);
        echo $OUTPUT->render($courseoutput);
    } else {
        // Mensagem de erro amigável se o renderer falhar
        echo $OUTPUT->notification('Erro: Classe \theme_celebra\output\course não encontrada.', 'warning');
        echo $OUTPUT->main_content();
    }

echo '</div>';

// 3. Imprime o Rodapé (Scripts finais, debug)
echo $OUTPUT->footer();