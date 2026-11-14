<?php

require_once __DIR__ . '/../core/Reponse.php';
require_once __DIR__ . '/../models/UtilisateurModel.php';

class AuthController
{
    private static function lireCorps(): array
    {
        $corps = json_decode(file_get_contents('php://input'), true);

        if (!is_array($corps)) {
            return [];
        }

        return $corps;
    }

    private static function champ(array $donnees, string $cle): string
    {
        if (isset($donnees[$cle]) && is_string($donnees[$cle])) {
            return $donnees[$cle];
        }

        return '';
    }

    public static function inscription(): void
    {
        $donnees = self::lireCorps();
        $nom = trim(self::champ($donnees, 'nom'));
        $email = trim(self::champ($donnees, 'email'));
        $motDePasse = self::champ($donnees, 'mot_de_passe');
        $consentement = $donnees['consentement'] ?? false;

        if ($nom === '' || strlen($nom) > 100) {
            Reponse::json(['erreur' => 'Nom invalide'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
            Reponse::json(['erreur' => 'Email invalide'], 400);
        }

        if (strlen($motDePasse) < 12) {
            Reponse::json(['erreur' => 'Le mot de passe doit faire 12 caractères minimum'], 400);
        }

        if ($consentement !== true) {
            Reponse::json(['erreur' => 'Le consentement est obligatoire'], 400);
        }

        if (UtilisateurModel::trouverParEmail($email) !== null) {
            Reponse::json(['erreur' => 'Cet email est déjà utilisé'], 409);
        }

        $motDePasseHache = password_hash($motDePasse, PASSWORD_DEFAULT);
        $id = UtilisateurModel::creer($nom, $email, $motDePasseHache);

        Reponse::json(['id' => $id, 'nom' => $nom], 201);
    }

    public static function connexion(): void
    {
        $donnees = self::lireCorps();
        $email = trim(self::champ($donnees, 'email'));
        $motDePasse = self::champ($donnees, 'mot_de_passe');

        $utilisateur = UtilisateurModel::trouverParEmail($email);

        if ($utilisateur === null || !password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            Reponse::json(['erreur' => 'Email ou mot de passe incorrect'], 401);
        }

        session_regenerate_id(true);
        $_SESSION['utilisateur_id'] = (int) $utilisateur['id'];
        $_SESSION['nom'] = $utilisateur['nom'];
        $_SESSION['role'] = $utilisateur['role'];

        Reponse::json([
            'id' => (int) $utilisateur['id'],
            'nom' => $utilisateur['nom'],
            'role' => $utilisateur['role'],
        ]);
    }

    public static function deconnexion(): void
    {
        $_SESSION = [];
        session_destroy();

        Reponse::json(['message' => 'Déconnecté']);
    }

    public static function moi(): void
    {
        if (!isset($_SESSION['utilisateur_id'])) {
            Reponse::json(['erreur' => 'Non connecté'], 401);
        }

        Reponse::json([
            'id' => $_SESSION['utilisateur_id'],
            'nom' => $_SESSION['nom'],
            'role' => $_SESSION['role'],
        ]);
    }
}
