<?php
namespace theme_celebra\output;

use renderable;
use templatable;
use renderer_base;

defined('MOODLE_INTERNAL') || die();

class course implements renderable, templatable {

    protected $course;

    public function __construct(\stdClass $course) {
        $this->course = $course;
    }

    public function export_for_template(renderer_base $output) {
        global $USER, $DB;

        $modules = [];

        // busca atividades do curso
        $modinfo = get_fast_modinfo($this->course, $USER->id);

        foreach ($modinfo->cms as $cm) {
            if (!$cm->uservisible) {
                continue;
            }

            // só atividades com conclusão
            if (!$cm->completion) {
                continue;
            }

            // progresso real do módulo
            $completion = new \completion_info($this->course);
            $state = $completion->get_data($cm, false, $USER->id);
            $progress = ($state && $state->completionstate == COMPLETION_COMPLETE) ? 100 : 0;

            // Vimeo ID vem da descrição (campo customizado ou padrão)
            $vimeoid = '';
            if (!empty($cm->content)) {
                if (preg_match('/vimeo\.com\/(\d+)/', $cm->content, $m)) {
                    $vimeoid = $m[1];
                }
            }

            $modules[] = [
                'cmid'        => $cm->id,
                'name'        => $cm->name,
                'progress'    => $progress,
                'vimeoid'     => $vimeoid,
                'description' => strip_tags($cm->content ?? ''),
            ];
        }

        return [
            'coursefullname' => $this->course->fullname,
            'introvideo'     => $this->get_intro_vimeo(),
            'modules'        => $modules,
        ];
    }

    private function get_intro_vimeo(): string {
        if (preg_match('/vimeo\.com\/(\d+)/', $this->course->summary, $m)) {
            return $m[1];
        }
        return '';
    }
}
