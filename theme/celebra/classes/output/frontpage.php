<?php
namespace theme_celebra\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use templatable;
use renderer_base;
use core_course\external\course_summary_exporter;

class frontpage implements renderable, templatable {

    public function export_for_template(renderer_base $output) {
        global $USER, $CFG;

        require_once($CFG->libdir . '/completionlib.php');

        // Cursos do usuário
        $courses = enrol_get_users_courses($USER->id, true, '*');

        $mycourses = [];

        foreach ($courses as $course) {

            // URL do curso
            $courseurl = new \moodle_url('/course/view.php', [
                'id' => $course->id
            ]);

            // Imagem do curso (ou fallback)
            $courseimage = course_get_course_image($course);
            if (!$courseimage) {
                $courseimage = $output->image_url(
                    'course-default',
                    'theme'
                )->out(false);
            }

            // Progresso do curso
            $progress = 0;
            if (\core_completion\progress::is_course_completeable($course)) {
                $progress = (int)\core_completion\progress::get_course_progress_percentage($course);
            }

            $mycourses[] = [
                'id'       => $course->id,
                'fullname' => format_string($course->fullname),
                'url'      => $courseurl->out(false),
                'image'    => $courseimage,
                'progress' => $progress
            ];
        }

        return [
            'mycourses'   => $mycourses,
            'recommended'=> [] // entraremos depois (marketing / catálogo)
        ];
    }
}
