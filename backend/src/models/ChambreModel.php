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

        public static function getById(int $id): ?array
    {
        $pdo = Database::getConnexion();
        $requete = $pdo->prepare('SELECT * FROM chambre WHERE id = :id');
        $requete->execute(['id' => $id]);
        $chambre = $requete->fetch();

        if ($chambre === false) {
            return null;
        }

        return $chambre;
    }

}
