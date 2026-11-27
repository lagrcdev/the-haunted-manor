<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/domain/Sejour.php';

class SejourTest extends TestCase {
    public function testDuDixAuDouzeCalculeDeuxNuits(): void {
        $sejour = new Sejour(new DateTimeImmutable('2026-12-10'), new DateTimeImmutable('2026-12-12'));

        $this->assertSame(2, $sejour->nombreDeNuits());
    }

    public function testUnDepartLeJourDeLArriveeEstRefuse(): void {
        $this->expectException(InvalidArgumentException::class);

        new Sejour(new DateTimeImmutable('2026-12-10'), new DateTimeImmutable('2026-12-10'));
    }
}
