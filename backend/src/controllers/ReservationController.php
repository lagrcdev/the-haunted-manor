<?php

require_once __DIR__ . '/../core/Reponse.php';
require_once __DIR__ . '/../core/Requete.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/ReservationModel.php';
require_once __DIR__ . '/../domain/Sejour.php';

class ReservationController {
    public static function creer(): void {
        $utilisateurId = Auth::exigerConnexion();

        $donnees = Requete::corps();
        $chambreId = $donnees['chambre_id'] ?? null;
        $arrivee = Requete::texte($donnees, 'date_arrivee');
        $depart = Requete::texte($donnees, 'date_depart');

        if (!is_int($chambreId) || $chambreId <= 0) {
            Reponse::json(['erreur' => 'Chambre invalide'], 400);
        }

        $dateArrivee = DateTimeImmutable::createFromFormat('!Y-m-d', $arrivee);
        $dateDepart = DateTimeImmutable::createFromFormat('!Y-m-d', $depart);

        if ($dateArrivee === false || $dateDepart === false
            || $dateArrivee->format('Y-m-d') !== $arrivee
            || $dateDepart->format('Y-m-d') !== $depart) {
            Reponse::json(['erreur' => 'Dates invalides'], 400);
        }

        if ($dateArrivee < new DateTimeImmutable('today')) {
            Reponse::json(['erreur' => 'La date d\'arrivée est déjà passée'], 400);
        }

        try {
            $sejour = new Sejour($dateArrivee, $dateDepart);
        } catch (InvalidArgumentException $erreur) {
            Reponse::json(['erreur' => 'Le départ doit être après l\'arrivée'], 400);
        }

        $resultat = ReservationModel::creer($utilisateurId, $chambreId, $arrivee, $depart, $sejour->nombreDeNuits());

        if (!$resultat['ok']) {
            if ($resultat['raison'] === 'chambre_introuvable') {
                Reponse::json(['erreur' => 'Chambre introuvable'], 404);
            }

            Reponse::json(['erreur' => 'Chambre indisponible à ces dates'], 409);
        }

        Reponse::json([
            'id' => $resultat['id'],
            'nuits' => $resultat['nuits'],
            'prix_total' => $resultat['prix_total'],
        ], 201);
    }
    public static function mesReservations(): void {
        $utilisateurId = Auth::exigerConnexion();

        Reponse::json(ReservationModel::reservationsDeUtilisateur($utilisateurId));
    }

    public static function annuler(int $id): void {
        $utilisateurId = Auth::exigerConnexion();

        $resultat = ReservationModel::annuler($id, $utilisateurId);

        if ($resultat === 'introuvable') {
            Reponse::json(['erreur' => 'Réservation introuvable'], 404);
        }

        if ($resultat === 'commence') {
            Reponse::json(['erreur' => 'Le séjour a déjà commencé : l\'annulation n\'est plus possible'], 409);
        }

        Reponse::json(['message' => 'Réservation annulée']);
    }

    public static function annulerParAdmin(int $id): void {
        Auth::exigerAdmin();

        if (!ReservationModel::annulerParAdmin($id)) {
            Reponse::json(['erreur' => 'Réservation introuvable ou déjà annulée'], 404);
        }

        Reponse::json(['message' => 'Réservation annulée']);
    }

    public static function toutes(): void {
        Auth::exigerAdmin();

        Reponse::json(ReservationModel::toutes());
    }


}
