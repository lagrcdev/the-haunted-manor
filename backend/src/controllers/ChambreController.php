<?php

require_once __DIR__ . '/../core/Reponse.php';
require_once __DIR__ . '/../models/ChambreModel.php';

class ChambreController
{
    public static function liste(): void
    {
        $chambres = ChambreModel::getAll();
        Reponse::json($chambres);
    }

    public static function detail(int $id): void
    {
        $chambre = ChambreModel::getById($id);

        if ($chambre === null) {
            Reponse::json(['erreur' => 'Chambre introuvable'], 404);
        }

        Reponse::json($chambre);
    }
}
