<?php

require_once __DIR__ . '/../core/Database.php';

class UtilisateurModel
{
    public static function creer(string $nom, string $email, string $motDePasseHache): int
    {
        $pdo = Database::getConnexion();
        $requete = $pdo->prepare(
            'INSERT INTO utilisateur (nom, email, mot_de_passe, date_consentement)
             VALUES (:nom, :email, :mot_de_passe, NOW())'
        );
        $requete->execute([
            'nom' => $nom,
            'email' => $email,
            'mot_de_passe' => $motDePasseHache,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function trouverParEmail(string $email): ?array
    {
        $pdo = Database::getConnexion();
        $requete = $pdo->prepare('SELECT * FROM utilisateur WHERE email = :email');
        $requete->execute(['email' => $email]);
        $utilisateur = $requete->fetch();

        if ($utilisateur === false) {
            return null;
        }

        return $utilisateur;
    }
}
