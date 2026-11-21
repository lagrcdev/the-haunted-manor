<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/core/Database.php';

abstract class TestApi extends TestCase {
    protected string $fichierCookies;
    private array $fichiersCookies = [];
    private array $emails = [];

    protected function setUp(): void {
        $this->inscrireEtConnecter();
    }

    protected function tearDown(): void {
        $pdo = Database::getConnexion();

        foreach ($this->emails as $email) {
            $suppressionReservations = $pdo->prepare(
                'DELETE FROM reservation WHERE utilisateur_id IN (SELECT id FROM utilisateur WHERE email = :email)'
            );
            $suppressionReservations->execute(['email' => $email]);

            $suppressionUtilisateur = $pdo->prepare('DELETE FROM utilisateur WHERE email = :email');
            $suppressionUtilisateur->execute(['email' => $email]);
        }

        foreach ($this->fichiersCookies as $fichier) {
            unlink($fichier);
        }
    }

    protected function inscrireEtConnecter(bool $administrateur = false): void {
        $this->fichierCookies = tempnam(sys_get_temp_dir(), 'cookies');
        $this->fichiersCookies[] = $this->fichierCookies;

        $email = 'test-' . bin2hex(random_bytes(4)) . '@exemple.fr';
        $this->emails[] = $email;

        $this->appeler('POST', '/api/inscription', [
            'nom' => 'Testeur',
            'email' => $email,
            'mot_de_passe' => 'motdepassedetest',
            'consentement' => true,
        ]);

        if ($administrateur) {
            $changement = Database::getConnexion()->prepare('UPDATE utilisateur SET role = \'admin\' WHERE email = :email');
            $changement->execute(['email' => $email]);
        }

        $this->appeler('POST', '/api/connexion', [
            'email' => $email,
            'mot_de_passe' => 'motdepassedetest',
        ]);
    }

    protected function appeler(string $methode, string $adresse, array $donnees = []): array {
        $jeton = '';
        if ($methode !== 'GET') {
            $reponseJeton = $this->envoyer('GET', '/api/csrf', []);
            $jeton = $reponseJeton['corps']['jeton'];
        }

        return $this->envoyer($methode, $adresse, $donnees, $jeton);
    }

    protected function envoyer(string $methode, string $adresse, array $donnees, string $jeton = ''): array {
        $curl = curl_init('http://localhost' . $adresse);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $methode);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->fichierCookies);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->fichierCookies);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'X-CSRF-Token: ' . $jeton]);

        if ($methode !== 'GET') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($donnees));
        }

        $texte = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        return ['code' => $code, 'corps' => json_decode($texte, true)];
    }

    protected function envoyerFichier(string $adresse, string $cheminFichier, string $nomFichier): array {
        $reponseJeton = $this->envoyer('GET', '/api/csrf', []);
        $jeton = $reponseJeton['corps']['jeton'];

        $curl = curl_init('http://localhost' . $adresse);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->fichierCookies);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->fichierCookies);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-CSRF-Token: ' . $jeton]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, ['photo' => new CURLFile($cheminFichier, 'image/jpeg', $nomFichier)]);

        $texte = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        return ['code' => $code, 'corps' => json_decode($texte, true)];
    }
}
