<?php

use DateTimeImmutable;

class Protocolo {

    public function __construct(
        private string $numero,
        private int $quantidadeDePaginas,
        private DateTimeImmutable $data
    ) {
    }

    public function getNumero(): string {
        return $this->numero;
    }

    public function getPaginas(){
        return $this->quantidadeDePaginas;
    }

    public function getData(){
        return $this->data;
    }

}