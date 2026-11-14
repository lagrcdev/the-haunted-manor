<?php

require_once __DIR__ . '/../core/Database.php';

class ChambreModel
{
    public static function getAll(): array
    {
        $pdo = Database::getConnexion();
        $requete = $pdo->query('SELECT * FROM chambre');

        return $requete->fetchAll();
    }
}
