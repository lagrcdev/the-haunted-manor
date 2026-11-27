<?php

class Mongo {
    private static ?MongoDB\Driver\Manager $manager = null;

    public static function getManager(): MongoDB\Driver\Manager {
        if (self::$manager === null) {
            $utilisateur = rawurlencode((string) getenv('MONGO_APP_USER'));
            $motDePasse = rawurlencode((string) getenv('MONGO_APP_PASSWORD'));

            self::$manager = new MongoDB\Driver\Manager(
                'mongodb://' . $utilisateur . ':' . $motDePasse . '@mongo:27017/?authSource=haunted_manor'
            );
        }

        return self::$manager;
    }
}
