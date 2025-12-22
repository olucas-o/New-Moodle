<?php
namespace theme_celebra\output;

use renderer_base;
use renderable;
use templatable;

class frontpage implements renderable, templatable {

    public function export_for_template(renderer_base $output) {
        global $USER, $CFG;

        $courses = enrol_get_users_courses($USER->id, true);
        $mycourses = [];

        foreach ($courses as $course) {
            $progress = \core_completion\progress::get_course_progress_percentage($course);

            $mycourses[] = [
                'fullname' => $course->fullname,
                'image'    => course_get_course_image($course),
                'progress' => round($progress ?? 0),
                'url'      => $CFG->wwwroot . '/course/view.php?id=' . $course->id
            ];
        }

        return [
            'mycourses' => $mycourses,
            'recommended' => [] // lógica entra depois (como combinamos)
        ];
    }
}
