<?php
namespace theme_celebra\service;

defined('MOODLE_INTERNAL') || die();

class recommendation {

    /**
     * Mapa de recomendação baseado no último curso do aluno
     * ORDEM IMPORTA (1º é o mais estratégico)
     */
    protected static array $map = [

        // Exemplo (use os IDs reais dos cursos)
        101 => [103, 105, 104], // Intervenção Precoce
        102 => [103, 105, 104], // Introdução ao Autismo
        103 => [104, 105, 102], // Aplicador ABA
        104 => [105, 103, 102], // PCM
        105 => [103, 104, 102], // Gestão de comportamentos
    ];

    /**
     * Retorna até 3 cursos recomendados
     */
    public static function get_recommended_courses(int $lastcourseid): array {
        if (!isset(self::$map[$lastcourseid])) {
            return [];
        }

        $ordered = self::$map[$lastcourseid];

        // 1️⃣ Curso principal (sempre entra)
        $primary = array_shift($ordered);

        // 2️⃣ Pool ponderado
        $weighted = [];

        if (isset($ordered[0])) {
            $weighted = array_merge($weighted, array_fill(0, 3, $ordered[0])); // peso 3
        }

        if (isset($ordered[1])) {
            $weighted = array_merge($weighted, array_fill(0, 2, $ordered[1])); // peso 2
        }

        shuffle($weighted);

        // Seleciona 2 distintos
        $secondary = [];
        foreach ($weighted as $cid) {
            if (!in_array($cid, $secondary, true)) {
                $secondary[] = $cid;
            }
            if (count($secondary) === 2) {
                break;
            }
        }

        // Junta tudo
        $final = array_merge([$primary], $secondary);

        // Embaralha posições (o principal muda de lugar)
        shuffle($final);

        return $final;
    }
}
