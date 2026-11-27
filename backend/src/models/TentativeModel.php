<?php

require_once __DIR__ . '/../core/Database.php';

class TentativeModel {
    public static function echecsRecents(string $email): int {
        $pdo = Database::getConnexion();
        $requete = $pdo->prepare(
            'SELECT COUNT(*) FROM tentative_connexion
             WHERE email = :email AND date_tentative > (NOW() - INTERVAL 15 MINUTE)'
        );
        $requete->execute(['email' => $email]);

        return (int) $requete->fetchColumn();
    }

    public static function enregistrerEchec(string $email): void {
        $pdo = Database::getConnexion();
        $requete = $pdo->prepare('INSERT INTO tentative_connexion (email) VALUES (:email)');
        $requete->execute(['email' => $email]);
    }

    public static function effacer(string $email): void {
        $pdo = Database::getConnexion();
        $requete = $pdo->prepare('DELETE FROM tentative_connexion WHERE email = :email');
        $requete->execute(['email' => $email]);
    }
}
