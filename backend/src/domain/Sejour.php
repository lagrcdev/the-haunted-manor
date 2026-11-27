<?php

final class Sejour {
    private DateTimeImmutable $arrivee;
    private DateTimeImmutable $depart;

    public function __construct(DateTimeImmutable $arrivee, DateTimeImmutable $depart) {
        $this->arrivee = $arrivee->setTime(0, 0);
        $this->depart = $depart->setTime(0, 0);

        if ($this->depart <= $this->arrivee) {
            throw new InvalidArgumentException('Dates incohérentes');
        }
    }

    public function nombreDeNuits(): int {
        return (int) $this->arrivee->diff($this->depart)->days;
    }
}
