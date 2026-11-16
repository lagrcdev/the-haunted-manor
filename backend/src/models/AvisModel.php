<?php

require_once __DIR__ . '/../core/Mongo.php';

class AvisModel {
    private const COLLECTION = 'haunted_manor.avis';

    public static function creer(string $nom, int $note, string $texte): void {
        $ecriture = new MongoDB\Driver\BulkWrite();
        $ecriture->insert([
            'nom' => $nom,
            'note' => $note,
            'texte' => $texte,
            'date' => date('Y-m-d H:i:s'),
        ]);

        Mongo::getManager()->executeBulkWrite(self::COLLECTION, $ecriture);
    }

    public static function derniers(int $nombre): array {
        $requete = new MongoDB\Driver\Query([], ['sort' => ['date' => -1], 'limit' => $nombre]);
        $curseur = Mongo::getManager()->executeQuery(self::COLLECTION, $requete);

        $avis = [];

        foreach ($curseur as $document) {
            $avis[] = [
                'id' => (string) $document->_id,
                'nom' => $document->nom,
                'note' => $document->note,
                'texte' => $document->texte,
                'date' => $document->date,
            ];
        }

        return $avis;
    }
}
