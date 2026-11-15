<?php

require_once __DIR__ . '/Reponse.php';

class Auth
{
    public static function exigerConnexion(): int
    {
        if (!isset($_SESSION['utilisateur_id'])) {
            Reponse::json(['erreur' => 'Non connecté'], 401);
        }

        return (int) $_SESSION['utilisateur_id'];
    }
}
