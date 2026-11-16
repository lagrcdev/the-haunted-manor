<?php

require_once __DIR__ . '/Reponse.php';

class Auth {
    public static function exigerConnexion(): int {
        if (!isset($_SESSION['utilisateur_id'])) {
            Reponse::json(['erreur' => 'Non connecté'], 401);
        }

        return (int) $_SESSION['utilisateur_id'];
    }

    public static function exigerAdmin(): int {
        $utilisateurId = self::exigerConnexion();

        if (($_SESSION['role'] ?? '') !== 'admin') {
            Reponse::json(['erreur' => 'Accès interdit'], 403);
        }

        return $utilisateurId;
    }

}
