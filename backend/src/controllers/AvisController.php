<?php

require_once __DIR__ . '/../core/Reponse.php';
require_once __DIR__ . '/../core/Requete.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/AvisModel.php';

class AvisController {
    public static function liste(): void {
        Reponse::json(AvisModel::derniers(50));
    }

    public static function creer(): void {
        Auth::exigerConnexion();

        $donnees = Requete::corps();
        $note = $donnees['note'] ?? null;
        $texte = trim(Requete::texte($donnees, 'texte'));

        if (!is_int($note) || $note < 1 || $note > 5) {
            Reponse::json(['erreur' => 'La note doit être un nombre entier de 1 à 5'], 400);
        }

        if ($texte === '' || mb_strlen($texte) > 500) {
            Reponse::json(['erreur' => 'Le texte doit faire entre 1 et 500 caractères'], 400);
        }

        AvisModel::creer($_SESSION['nom'], $note, $texte);

        Reponse::json(['message' => 'Avis ajouté'], 201);
    }
}
