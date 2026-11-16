<?php

require_once __DIR__ . '/../core/Database.php';

class ChambreModel {
    public static function getAll(): array {
        $pdo = Database::getConnexion();
        $requete = $pdo->query('SELECT * FROM chambre');

        return $requete->fetchAll();
    }

    public static function getById(int $id): ?array {
        $pdo = Database::getConnexion();
        $requete = $pdo->prepare('SELECT * FROM chambre WHERE id = :id');
        $requete->execute(['id' => $id]);
        $chambre = $requete->fetch();

        if ($chambre === false) {
            return null;
        }

        return $chambre;
    }

    public static function creer(string $nom, string $fantome, string $description, int $niveauFrayeur, float $prixNuit): int {
        $pdo = Database::getConnexion();

        $requete = $pdo->prepare(
            'INSERT INTO chambre (nom, fantome, description, niveau_frayeur, prix_nuit)
             VALUES (:nom, :fantome, :description, :niveau_frayeur, :prix_nuit)'
        );
        $requete->execute([
            'nom' => $nom,
            'fantome' => $fantome,
            'description' => $description,
            'niveau_frayeur' => $niveauFrayeur,
            'prix_nuit' => $prixNuit,
        ]);

        return (int) $pdo->lastInsertId();
    }


}
