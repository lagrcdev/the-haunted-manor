<?php

require_once __DIR__ . '/TestApi.php';

class SecuriteTest extends TestApi {
    public function testUnMauvaisMotDePasseEstRefuse(): void {
        $reponse = $this->appeler('POST', '/api/connexion', [
            'email' => 'inconnu@exemple.fr',
            'mot_de_passe' => 'unmauvaismotdepasse',
        ]);

        $this->assertSame(401, $reponse['code']);
    }

    public function testUneRequeteSansJetonCsrfEstRefusee(): void {
        $reponse = $this->envoyer('POST', '/api/reservations', [
            'chambre_id' => 1,
            'date_arrivee' => '2030-08-10',
            'date_depart' => '2030-08-12',
        ]);

        $this->assertSame(403, $reponse['code']);
    }

    public function testUnClientNePeutPasAjouterUneChambre(): void {
        $reponse = $this->appeler('POST', '/api/admin/chambres', [
            'nom' => 'Chambre pirate',
            'fantome' => 'Personne',
            'description' => 'Ne doit pas etre creee',
            'niveau_frayeur' => 3,
            'prix_nuit' => 100,
        ]);

        $this->assertSame(403, $reponse['code']);
    }

    public function testUnFichierQuiNEstPasUneImageEstRefuse(): void {
        $this->inscrireEtConnecter(true);

        $faux = tempnam(sys_get_temp_dir(), 'faux');
        file_put_contents($faux, '<?php echo "pirate"; ?>');

        $reponse = $this->envoyerFichier('/api/admin/chambres/1/photo', $faux, 'photo.jpg');
        unlink($faux);

        $this->assertSame(400, $reponse['code']);
    }
}
