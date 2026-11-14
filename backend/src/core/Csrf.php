<?php

require_once __DIR__ . '/Reponse.php';

class Csrf
{
    public static function jeton(): string
    {
        if (!isset($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf'];
    }

    public static function verifier(): void
    {
        $envoye = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $attendu = $_SESSION['csrf'] ?? '';

        if ($attendu === '' || !hash_equals($attendu, $envoye)) {
            Reponse::json(['erreur' => 'Jeton CSRF invalide'], 403);
        }
    }
}
