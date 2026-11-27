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

        if ($nom === '' || mb_strlen($nom) > 100) {
            Reponse::json(['erreur' => 'Nom invalide'], 400);
        }

        if ($fantome === '' || mb_strlen($fantome) > 100) {
            Reponse::json(['erreur' => 'Nom du fantôme invalide'], 400);
        }

        if ($description === '') {
            Reponse::json(['erreur' => 'Description obligatoire'], 400);
        }

        if (!is_int($niveauFrayeur) || $niveauFrayeur < 1 || $niveauFrayeur > 5) {
            Reponse::json(['erreur' => 'Le niveau de frayeur doit être un nombre entier de 1 à 5'], 400);
        }

        if (!is_numeric($prixNuit) || $prixNuit < 1 || $prixNuit > 99999.99) {
            Reponse::json(['erreur' => 'Le prix doit être compris entre 1 et 99999,99 €'], 400);
        }

        if (round((float) $prixNuit, 2) !== (float) $prixNuit) {
            Reponse::json(['erreur' => 'Le prix ne peut pas avoir plus de deux décimales'], 400);
        }

        return [
            'nom' => $nom,
            'fantome' => $fantome,
            'description' => $description,
            'niveau_frayeur' => $niveauFrayeur,
            'prix_nuit' => round((float) $prixNuit, 2),
        ];
    }

    private static function supprimerFichierPhoto(?string $nom): void {
        if ($nom === null || !preg_match('/^[0-9a-f]{32}\.(jpg|png)$/', $nom)) {
            return;
        }

        $chemin = __DIR__ . '/../../public/uploads/' . $nom;

        if (is_file($chemin)) {
            unlink($chemin);
        }
    }

    public static function creer(): void {
        Auth::exigerAdmin();

        $c = self::lireDonnees();
        $id = ChambreModel::creer($c['nom'], $c['fantome'], $c['description'], $c['niveau_frayeur'], $c['prix_nuit']);

        Reponse::json(['id' => $id], 201);
    }

        public static function modifier(int $id): void {
        Auth::exigerAdmin();

        if (ChambreModel::getById($id) === null) {
            Reponse::json(['erreur' => 'Chambre introuvable'], 404);
        }

        $c = self::lireDonnees();
        ChambreModel::modifier($id, $c['nom'], $c['fantome'], $c['description'], $c['niveau_frayeur'], $c['prix_nuit']);

        Reponse::json(['message' => 'Chambre modifiée']);
    }

    public static function supprimer(int $id): void {
        Auth::exigerAdmin();

        $chambre = ChambreModel::getById($id);

        if ($chambre === null) {
            Reponse::json(['erreur' => 'Chambre introuvable'], 404);
        }

        if (ChambreModel::nombreReservations($id) > 0) {
            Reponse::json(['erreur' => 'Cette chambre a des réservations, elle ne peut pas être supprimée'], 409);
        }

        ChambreModel::supprimer($id);
        self::supprimerFichierPhoto($chambre['photo']);

        Reponse::json(['message' => 'Chambre supprimée']);
    }

    public static function envoyerPhoto(int $id): void {
        Auth::exigerAdmin();

        $chambre = ChambreModel::getById($id);

        if ($chambre === null) {
            Reponse::json(['erreur' => 'Chambre introuvable'], 404);
        }

        $fichier = $_FILES['photo'] ?? null;

        if ($fichier === null || $fichier['error'] === UPLOAD_ERR_NO_FILE) {
            Reponse::json(['erreur' => 'Aucune photo reçue'], 400);
        }

        if ($fichier['error'] === UPLOAD_ERR_INI_SIZE || $fichier['size'] > 2 * 1024 * 1024) {
            Reponse::json(['erreur' => 'La photo est trop lourde (2 Mo maximum)'], 400);
        }

        if ($fichier['error'] !== UPLOAD_ERR_OK) {
            Reponse::json(['erreur' => 'L\'envoi de la photo a échoué'], 400);
        }

        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        $type = (new finfo(FILEINFO_MIME_TYPE))->file($fichier['tmp_name']);

        if (!isset($extensions[$type]) || getimagesize($fichier['tmp_name']) === false) {
            Reponse::json(['erreur' => 'Format non accepté (JPG ou PNG seulement)'], 400);
        }

        $nom = bin2hex(random_bytes(16)) . '.' . $extensions[$type];

        if (!move_uploaded_file($fichier['tmp_name'], __DIR__ . '/../../public/uploads/' . $nom)) {
            throw new RuntimeException('Impossible d\'enregistrer la photo');
        }

        ChambreModel::modifierPhoto($id, $nom);
        self::supprimerFichierPhoto($chambre['photo']);

        Reponse::json(['photo' => $nom]);
    }
}
