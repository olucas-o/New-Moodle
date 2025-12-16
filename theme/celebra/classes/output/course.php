<?php
namespace theme_celebra\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use templatable;
use renderer_base;

class course implements renderable, templatable {

    protected $course;

    public function __construct(\stdClass $course) {
        $this->course = $course;
    }

    public function export_for_template(renderer_base $output) {
        global $USER, $CFG;

        require_once($CFG->libdir . '/completionlib.php');

        $course = $this->course;
        $modinfo = get_fast_modinfo($course, $USER->id);
        $completion = new \completion_info($course);

        $modules = [];

        foreach ($modinfo->get_cms() as $cm) {

            // Ignora itens invisíveis ou sem visualização
            if (!$cm->uservisible) {
                continue;
            }

            // Estado de conclusão
            $progress = 0;
            if ($completion->is_enabled($cm)) {
                $data = $completion->get_data($cm, false, $USER->id);
                $progress = ($data->completionstate == COMPLETION_COMPLETE) ? 100 : 0;
            }

            $modules[] = [
                'cmid'     => $cm->id,
                'name'     => format_string($cm->name),
                'progress' => $progress
            ];
        }

        return [
            'courseid' => $course->id,
            'fullname' => format_string($course->fullname),
            'modules'  => $modules
        ];
    }
}
