<?php

use PHPUnit\Framework\TestCase;

class AccesTest extends TestCase {
    private function codeReponse(string $adresse): int {
        $curl = curl_init('http://localhost' . $adresse);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        return $code;
    }

    public function testVisiteurNonConnecteNePeutPasVoirSesReservations(): void {
        $this->assertSame(401, $this->codeReponse('/api/reservations'));
    }

    public function testVisiteurNonConnecteNePeutPasVoirLesReservationsDesClients(): void {
        $this->assertSame(401, $this->codeReponse('/api/admin/reservations'));
    }

    public function testToutLeMondePeutVoirLaListeDesChambres(): void {
        $this->assertSame(200, $this->codeReponse('/api/chambres'));
    }

    public function testUneAdresseInconnueRenvoieUneErreur404(): void {
        $this->assertSame(404, $this->codeReponse('/api/nimporte-quoi'));
    }
}
