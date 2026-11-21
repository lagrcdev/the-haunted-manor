<?php

require_once __DIR__ . '/TestApi.php';

class ReservationTest extends TestApi {
    private function reserver(int $chambre, string $arrivee, string $depart): array {
        return $this->appeler('POST', '/api/reservations', [
            'chambre_id' => $chambre,
            'date_arrivee' => $arrivee,
            'date_depart' => $depart,
        ]);
    }

    public function testUneChambreDejaReserveeNePeutPasEtreReserveeAuxMemesDates(): void {
        $premiere = $this->reserver(1, '2030-06-10', '2030-06-15');
        $this->assertSame(201, $premiere['code']);

        $chevauchement = $this->reserver(1, '2030-06-12', '2030-06-14');
        $this->assertSame(409, $chevauchement['code']);
    }

    public function testUnClientNePeutPasAnnulerLaReservationDUnAutreClient(): void {
        $reservation = $this->reserver(1, '2030-07-10', '2030-07-15');
        $this->assertSame(201, $reservation['code']);
        $idReservation = $reservation['corps']['id'];

        $this->inscrireEtConnecter();

        $annulation = $this->appeler('DELETE', '/api/reservations/' . $idReservation);
        $this->assertSame(404, $annulation['code']);
    }

    public function testUneDateDArriveeDansLePasseEstRefusee(): void {
        $reponse = $this->reserver(1, '2020-01-10', '2020-01-12');
        $this->assertSame(400, $reponse['code']);
    }
}
