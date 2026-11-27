<?php

require_once __DIR__ . '/TestApi.php';

class ValidationTest extends TestApi {
    private function ajouterChambreAvecPrix($prix): array {
        return $this->appeler('POST', '/api/admin/chambres', [
            'nom' => 'Chambre de test',
            'fantome' => 'Fantome de test',
            'description' => 'Ne doit jamais etre creee',
            'niveau_frayeur' => 3,
            'prix_nuit' => $prix,
        ]);
    }

    public function testUnPrixTropPetitEstRefuse(): void {
        $this->inscrireEtConnecter(true);

        $reponse = $this->ajouterChambreAvecPrix(0.001);
        $this->assertSame(400, $reponse['code']);
    }

    public function testUnPrixAvecPlusDeDeuxDecimalesEstRefuse(): void {
        $this->inscrireEtConnecter(true);

        $reponse = $this->ajouterChambreAvecPrix(119.999);
        $this->assertSame(400, $reponse['code']);
    }

    public function testUnIdentifiantDeChambreDecimalEstRefuse(): void {
        $reponse = $this->appeler('POST', '/api/reservations', [
            'chambre_id' => 2.9,
            'date_arrivee' => '2030-09-10',
            'date_depart' => '2030-09-12',
        ]);

        $this->assertSame(400, $reponse['code']);
    }

    public function testUnIdentifiantDeChambreEnTexteEstRefuse(): void {
        $reponse = $this->appeler('POST', '/api/reservations', [
            'chambre_id' => '2',
            'date_arrivee' => '2030-09-10',
            'date_depart' => '2030-09-12',
        ]);

        $this->assertSame(400, $reponse['code']);
    }
}
