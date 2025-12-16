<?php
namespace theme_celebra\output;

defined('MOODLE_INTERNAL') || die();

use renderer_base;
use stdClass;

class renderer extends renderer_base {

    /**
     * Renderiza a Página Inicial (Frontpage)
     */
    public function render_frontpage() {
        global $USER;

        $data = new stdClass();

        // =========================
        // MEUS CURSOS (matriculados)
        // =========================
        $enrolled = enrol_get_users_courses(
            $USER->id,
            true,
            '*',
            'visible DESC, sortorder ASC'
        );

        $data->mycourses = [];

        foreach ($enrolled as $course) {

            // ignora o "curso site"
            if ($course->id == SITEID) {
                continue;
            }

            $data->mycourses[] = $this->export_course($course, true);
        }

        // =========================
        // RECOMENDADOS (não matriculados)
        // =========================
        $data->recommended = [];

        $allcourses = get_courses('all', 'c.sortorder ASC');

        foreach ($allcourses as $course) {

            if ($course->id == SITEID) {
                continue;
            }

            if (!$course->visible) {
                continue;
            }

            if (isset($enrolled[$course->id])) {
                continue;
            }

            $data->recommended[] = $this->export_course($course, false);

            // limite visual (igual demo)
            if (count($data->recommended) >= 5) {
                break;
            }
        }

        return $this->render_from_template(
            'theme_celebra/frontpage',
            $data
        );
    }

    /**
     * Prepara dados do curso para o template
     */
    private function export_course($course, $isenrolled) {
        $image = $this->get_course_image($course);

        $progress = 0;
        if ($isenrolled) {
            $p = \core_completion\progress::get_course_progress_percentage($course);
            $progress = is_null($p) ? 0 : (int)$p;
        }

        return [
            'id'       => $course->id,
            'fullname' => format_string($course->fullname),
            'image'    => $image,
            'url'      => (new \moodle_url('/course/view.php', ['id' => $course->id]))->out(),
            'progress' => $progress
        ];
    }

    /**
     * Recupera imagem do curso (com fallback)
     */
    private function get_course_image($course) {
        global $CFG;

        $context = \context_course::instance($course->id);
        $fs = get_file_storage();

        $files = $fs->get_area_files(
            $context->id,
            'course',
            'overviewfiles',
            false,
            'sortorder, itemid',
            false
        );

        if (!empty($files)) {
            foreach ($files as $file) {
                return \moodle_url::make_pluginfile_url(
                    $context->id,
                    'course',
                    'overviewfiles',
                    $file->get_itemid(),
                    $file->get_filepath(),
                    $file->get_filename()
                )->out();
            }
        }

        return $CFG->wwwroot . '/theme/celebra/pix/default-course.jpg';
    }
}
