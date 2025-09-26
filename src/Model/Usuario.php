<?php

namespace Mateus\ProtocolTracker\Model;

use DateTimeImmutable;

class Usuario {

    public function __construct(
        private readonly int $id,
        private string $nome,
        private string $email,
        private string $cpf,
        private string $senhaHash,
        private readonly DateTimeImmutable $criadoEm
    ) {}

    //GETTERS
    /**
     * 
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * 
     */
    public function nome(): string
    {
        return $this->nome;
    }

    /**
     * 
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * 
     */
    public function cpf(): string
    {
        return $this->cpf;
    }

    /**
     * 
     */
    public function senhaHash(): string
    {
        return $this->senhaHash();
    }

    /**
     * 
     */
    public function criadoEm(): DateTimeImmutable
    {
        return $this->criadoEm();
    }

}