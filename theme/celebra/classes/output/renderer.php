<?php
namespace theme_celebra\output;

use renderable;
use templatable;
use renderer_base;

class frontpage implements renderable, templatable {

    public function export_for_template(renderer_base $output) {
        global $USER;

        $courses = enrol_get_users_courses($USER->id, true);

        $mycourses = [];
        foreach ($courses as $course) {
            $mycourses[] = [
                'id' => $course->id,
                'fullname' => $course->fullname,
                'image' => course_get_course_image($course),
                'progress' => \core_completion\progress::get_course_progress_percentage($course)
            ];
        }

        return [
            'mycourses' => $mycourses,
            'recommended' => [] // entraremos depois
        ];
    }
}
