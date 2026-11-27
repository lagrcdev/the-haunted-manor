<?php

require_once __DIR__ . '/TestApi.php';

class LivreDOrTest extends TestApi {
    private function trouverAvis(string $texte): ?array {
        $liste = $this->appeler('GET', '/api/avis');

        foreach ($liste['corps'] as $avis) {
            if ($avis['texte'] === $texte) {
                return $avis;
            }
        }

        return null;
    }

    public function testUnClientPeutPublierPuisLireSonAvis(): void {
        $texte = 'avis de test ' . bin2hex(random_bytes(4));

        $publication = $this->appeler('POST', '/api/avis', ['note' => 4, 'texte' => $texte]);
        $this->assertSame(201, $publication['code']);

        $avis = $this->trouverAvis($texte);
        $this->assertNotNull($avis);
        $this->assertTrue($avis['mien']);

        $suppression = $this->appeler('DELETE', '/api/avis/' . $avis['id']);
        $this->assertSame(200, $suppression['code']);
        $this->assertNull($this->trouverAvis($texte));
    }

    public function testUnClientNePeutPasSupprimerLAvisDUnAutreClient(): void {
        $texte = 'avis de test ' . bin2hex(random_bytes(4));

        $this->appeler('POST', '/api/avis', ['note' => 5, 'texte' => $texte]);
        $avisDuPremier = $this->trouverAvis($texte);
        $cookiesDuPremier = $this->fichierCookies;

        $this->inscrireEtConnecter();

        $avisVuParLAutre = $this->trouverAvis($texte);
        $this->assertFalse($avisVuParLAutre['mien']);

        $suppression = $this->appeler('DELETE', '/api/avis/' . $avisDuPremier['id']);
        $this->assertSame(404, $suppression['code']);

        $this->fichierCookies = $cookiesDuPremier;
        $nettoyage = $this->appeler('DELETE', '/api/avis/' . $avisDuPremier['id']);
        $this->assertSame(200, $nettoyage['code']);
    }
}
