<?php

class Requete {
    public static function corps(): array {
        $corps = json_decode(file_get_contents('php://input'), true);

        if (!is_array($corps)) {
            return [];
        }

        return $corps;
    }

    public static function texte(array $donnees, string $cle): string {
        if (isset($donnees[$cle]) && is_string($donnees[$cle])) {
            return $donnees[$cle];
        }

        return '';
    }
}
