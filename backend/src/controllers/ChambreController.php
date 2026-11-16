<?php

require_once __DIR__ . '/../core/Reponse.php';
require_once __DIR__ . '/../models/ChambreModel.php';
require_once __DIR__ . '/../core/Requete.php';
require_once __DIR__ . '/../core/Auth.php';

class ChambreController {
    public static function liste(): void {
        $chambres = ChambreModel::getAll();
        Reponse::json($chambres);
    }

    public static function detail(int $id): void {
        $chambre = ChambreModel::getById($id);

        if ($chambre === null) {
            Reponse::json(['erreur' => 'Chambre introuvable'], 404);
        }

        Reponse::json($chambre);
    }

    private static function lireDonnees(): array {
        $donnees = Requete::corps();

        $nom = trim(Requete::texte($donnees, 'nom'));
        $fantome = trim(Requete::texte($donnees, 'fantome'));
        $description = trim(Requete::texte($donnees, 'description'));
        $niveauFrayeur = $donnees['niveau_frayeur'] ?? null;
        $prixNuit = $donnees['prix_nuit'] ?? null;

        if ($nom === '' || strlen($nom) > 100) {
            Reponse::json(['erreur' => 'Nom invalide'], 400);
        }

        if ($fantome === '' || strlen($fantome) > 100) {
            Reponse::json(['erreur' => 'Nom du fantôme invalide'], 400);
        }

        if ($description === '') {
            Reponse::json(['erreur' => 'Description obligatoire'], 400);
        }

        if (!is_int($niveauFrayeur) || $niveauFrayeur < 1 || $niveauFrayeur > 5) {
            Reponse::json(['erreur' => 'Le niveau de frayeur doit être un nombre entier de 1 à 5'], 400);
        }

        if (!is_numeric($prixNuit) || $prixNuit <= 0 || $prixNuit >= 100000) {
            Reponse::json(['erreur' => 'Le prix doit être un nombre supérieur à 0'], 400);
        }

        return [
            'nom' => $nom,
            'fantome' => $fantome,
            'description' => $description,
            'niveau_frayeur' => $niveauFrayeur,
            'prix_nuit' => round((float) $prixNuit, 2),
        ];
    }

    public static function creer(): void {
        Auth::exigerAdmin();

        $c = self::lireDonnees();
        $id = ChambreModel::creer($c['nom'], $c['fantome'], $c['description'], $c['niveau_frayeur'], $c['prix_nuit']);

        Reponse::json(['id' => $id], 201);
    }

}
