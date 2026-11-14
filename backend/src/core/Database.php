<?php

class Database
{
    private static ?PDO $connexion = null;

    public static function getConnexion(): PDO
    {
        if (self::$connexion === null) {
            $hote = getenv('DB_HOST');
            $nom = getenv('DB_NAME');
            $utilisateur = getenv('DB_USER');
            $motDePasse = getenv('DB_PASSWORD');

            $dsn = 'mysql:host=' . $hote . ';dbname=' . $nom . ';charset=utf8mb4';

            self::$connexion = new PDO($dsn, $utilisateur, $motDePasse, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return self::$connexion;

    }
}

