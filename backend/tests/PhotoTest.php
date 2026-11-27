<?php

require_once __DIR__ . '/TestApi.php';

class PhotoTest extends TestApi {
    private function cheminPhoto(string $nom): string {
        return __DIR__ . '/../public/uploads/' . $nom;
    }

    private function nomPhotoDeLaChambre(int $id): string {
        $reponse = $this->appeler('GET', '/api/chambres/' . $id);

        return $reponse['corps']['photo'];
    }

    public function testUneNouvellePhotoRemplaceLAncienneEtSupprimerLaChambreEffaceLeFichier(): void {
        $this->inscrireEtConnecter(true);

        $creation = $this->appeler('POST', '/api/admin/chambres', [
            'nom' => 'Chambre de test',
            'fantome' => 'Fantome de test',
            'description' => 'Chambre creee par un test',
            'niveau_frayeur' => 2,
            'prix_nuit' => 50,
        ]);
        $this->assertSame(201, $creation['code']);
        $id = $creation['corps']['id'];

        $modele = __DIR__ . '/../public/uploads/chambre-mariee.jpg';

        $premier = $this->envoyerFichier('/api/admin/chambres/' . $id . '/photo', $modele, 'photo.jpg');
        $this->assertSame(200, $premier['code']);
        $anciennePhoto = $this->nomPhotoDeLaChambre($id);
        $this->assertFileExists($this->cheminPhoto($anciennePhoto));

        $second = $this->envoyerFichier('/api/admin/chambres/' . $id . '/photo', $modele, 'photo.jpg');
        $this->assertSame(200, $second['code']);
        $nouvellePhoto = $this->nomPhotoDeLaChambre($id);

        $this->assertNotSame($anciennePhoto, $nouvellePhoto);
        $this->assertFileDoesNotExist($this->cheminPhoto($anciennePhoto));
        $this->assertFileExists($this->cheminPhoto($nouvellePhoto));

        $suppression = $this->appeler('DELETE', '/api/admin/chambres/' . $id);
        $this->assertSame(200, $suppression['code']);
        $this->assertFileDoesNotExist($this->cheminPhoto($nouvellePhoto));
    }
}
