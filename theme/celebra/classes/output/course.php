<?php
namespace theme_celebra\output;

use renderer_base;
use renderable;
use templatable;

class course implements renderable, templatable {

    public function export_for_template(renderer_base $output) {
        global $COURSE;

        $modinfo = get_fast_modinfo($COURSE);
        $modules = [];

        foreach ($modinfo->cms as $cm) {
            if (!$cm->uservisible) continue;

            $modules[] = [
                'cmid' => $cm->id,
                'name' => $cm->name,
                'progress' => $cm->completion == COMPLETION_COMPLETE ? 100 : 0,
                'vimeoid' => '',
                'description' => ''
            ];
        }

        return [
            'coursefullname' => $COURSE->fullname,
            'introvideo' => '',
            'modules' => $modules
        ];
    }
}
