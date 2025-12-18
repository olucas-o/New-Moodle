<?php
namespace theme_celebra\output;

use renderable;
use templatable;
use renderer_base;

defined('MOODLE_INTERNAL') || die();

class frontpage implements renderable, templatable {

    public function export_for_template(renderer_base $output) {
        global $USER;

        // =========================
        // MEUS CURSOS
        // =========================
        $courses = enrol_get_users_courses($USER->id, true);
        $mycourses = [];
        $ownedcourseids = [];

        foreach ($courses as $course) {
            if ($course->id == SITEID) {
                continue;
            }

            $progress = \core_completion\progress::get_course_progress_percentage($course);

            $mycourses[] = [
                'id'       => $course->id,
                'fullname' => $course->fullname,
                'image'    => course_get_course_image($course),
                'progress' => is_null($progress) ? 0 : round($progress),
                'url'      => new \moodle_url('/course/view.php', ['id' => $course->id]),
            ];

            $ownedcourseids[] = $course->id;
        }

        // Último curso do aluno (critério simples e seguro)
        $lastcourseid = !empty($ownedcourseids) ? end($ownedcourseids) : null;

        // =========================
        // MAPA: CURSO → URL DE VENDA
        // =========================
        $courseSalesMap = [
            22 => 'https://institutocelebra.com.br/produto/introducao-ao-autismo/',
            17 => 'https://institutocelebra.com.br/produto/aplicador-aba/',
            10 => 'https://institutocelebra.com.br/produto/intervencao-precoce-baseada-no-modelo-denver/',
            8  => 'https://institutocelebra.com.br/produto/sistema-profissional-de-gerenciamento-de-crises-pcm-janeiro/',
            28 => 'https://institutocelebra.com.br/produto/curso-de-recertificacao-do-sistema-profissional-de-gerenciamento-de-crises-pcm/',
            4  => 'https://institutocelebra.com.br/produto/entendendo-o-autismo-manejo-de-comportamentos-interferentes-birra-x-crises-no-tea/',
            24 => 'https://institutocelebra.com.br/produto/caa/',
            7  => 'https://institutocelebra.com.br/produto/seletividade-alimentar/',
        ];

        // =========================
        // REGRAS DE RECOMENDAÇÃO
        // =========================
        $recommendationOrder = [
            22 => [17, 10, 4, 24, 7],
            17 => [10, 22, 8, 4],
            10 => [17, 22, 24],
            8  => [28, 17],
            4  => [22, 17],
        ];

        $recommended = [];

        if ($lastcourseid && isset($recommendationOrder[$lastcourseid])) {

            // Remove cursos que o aluno já possui
            $candidates = array_values(array_diff(
                $recommendationOrder[$lastcourseid],
                $ownedcourseids
            ));

            if (!empty($candidates)) {

                // 1️⃣ Primeiro sempre entra
                $first = array_shift($candidates);
                $final = [$first];

                // 2️⃣ Pesos (segundo = peso 3, terceiro = peso 2)
                $weighted = [];

                if (isset($candidates[0])) {
                    $weighted = array_merge($weighted, array_fill(0, 3, $candidates[0]));
                }
                if (isset($candidates[1])) {
                    $weighted = array_merge($weighted, array_fill(0, 2, $candidates[1]));
                }

                shuffle($weighted);

                $others = array_unique(array_slice($weighted, 0, 2));
                $final = array_merge($final, $others);

                shuffle($final);
                $final = array_slice($final, 0, 3);

                foreach ($final as $cid) {
                    if (!isset($courseSalesMap[$cid])) {
                        continue;
                    }

                    $course = get_course($cid);

                    $recommended[] = [
                        'fullname' => $course->fullname,
                        'image'    => course_get_course_image($course),
                        'url'      => $courseSalesMap[$cid], // 🔗 e-commerce
                    ];
                }
            }
        }

        return [
            'mycourses'   => $mycourses,
            'recommended' => $recommended,
        ];
    }
}
