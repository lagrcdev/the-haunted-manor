<?php

require_once __DIR__ . '/../core/Mongo.php';

class AvisModel {
    private const COLLECTION = 'haunted_manor.avis';

    public static function creer(int $utilisateurId, string $nom, int $note, string $texte): void {
        $ecriture = new MongoDB\Driver\BulkWrite();
        $ecriture->insert([
            'utilisateur_id' => $utilisateurId,
            'nom' => $nom,
            'note' => $note,
            'texte' => $texte,
            'date' => date('Y-m-d H:i:s'),
        ]);

        Mongo::getManager()->executeBulkWrite(self::COLLECTION, $ecriture);
    }

    public static function supprimer(string $id, int $utilisateurId): bool {
        $ecriture = new MongoDB\Driver\BulkWrite();
        $ecriture->delete(
            ['_id' => new MongoDB\BSON\ObjectId($id), 'utilisateur_id' => $utilisateurId],
            ['limit' => 1]
        );

        $resultat = Mongo::getManager()->executeBulkWrite(self::COLLECTION, $ecriture);

        return $resultat->getDeletedCount() > 0;
    }

    public static function derniers(int $nombre, ?int $utilisateurId): array {
        $requete = new MongoDB\Driver\Query([], ['sort' => ['date' => -1], 'limit' => $nombre]);
        $curseur = Mongo::getManager()->executeQuery(self::COLLECTION, $requete);

        $avis = [];

        /** @var object $document */
        foreach ($curseur as $document) {
            $avis[] = [
                'id' => (string) $document->_id,
                'nom' => $document->nom,
                'note' => $document->note,
                'texte' => $document->texte,
                'date' => $document->date,
                'mien' => $utilisateurId !== null
                    && isset($document->utilisateur_id)
                    && $document->utilisateur_id === $utilisateurId,
            ];
        }

        return $avis;
    }
}
