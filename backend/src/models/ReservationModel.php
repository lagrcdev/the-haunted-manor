<?php

require_once __DIR__ . '/../core/Database.php';

class ReservationModel
{
    public static function creer(int $utilisateurId, int $chambreId, string $arrivee, string $depart): array
    {
        $pdo = Database::getConnexion();
        $pdo->beginTransaction();

        try {
            $requete = $pdo->prepare('SELECT prix_nuit FROM chambre WHERE id = :id FOR UPDATE');
            $requete->execute(['id' => $chambreId]);
            $chambre = $requete->fetch();

            if ($chambre === false) {
                $pdo->rollBack();
                return ['ok' => false, 'raison' => 'chambre_introuvable'];
            }

            $requete = $pdo->prepare(
                "SELECT COUNT(*) FROM reservation
                 WHERE chambre_id = :chambre_id
                 AND statut = 'confirmee'
                 AND date_arrivee < :depart
                 AND date_depart > :arrivee"
            );
            $requete->execute([
                'chambre_id' => $chambreId,
                'depart' => $depart,
                'arrivee' => $arrivee,
            ]);

            if ((int) $requete->fetchColumn() > 0) {
                $pdo->rollBack();
                return ['ok' => false, 'raison' => 'indisponible'];
            }

            $nuits = (new DateTime($arrivee))->diff(new DateTime($depart))->days;
            $prixTotal = round($nuits * (float) $chambre['prix_nuit'], 2);

            $requete = $pdo->prepare(
                'INSERT INTO reservation (utilisateur_id, chambre_id, date_arrivee, date_depart, prix_total)
                 VALUES (:utilisateur_id, :chambre_id, :arrivee, :depart, :prix_total)'
            );
            $requete->execute([
                'utilisateur_id' => $utilisateurId,
                'chambre_id' => $chambreId,
                'arrivee' => $arrivee,
                'depart' => $depart,
                'prix_total' => $prixTotal,
            ]);
            $id = (int) $pdo->lastInsertId();

            $pdo->commit();

            return ['ok' => true, 'id' => $id, 'nuits' => $nuits, 'prix_total' => $prixTotal];
        } catch (Exception $erreur) {
            $pdo->rollBack();
            throw $erreur;
        }
    }
}
