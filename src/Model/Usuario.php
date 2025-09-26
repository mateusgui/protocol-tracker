<?php

namespace Mateus\ProtocolTracker\Model;

use DateTimeImmutable;
use Exception;

class Usuario {

    public function __construct(
        private readonly int $id,
        private string $nome,
        private string $email,
        private string $cpf,
        private string $senha,
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
    public function senha(): string
    {
        return $this->senha();
    }

    /**
     * 
     */
    public function criadoEm(): DateTimeImmutable
    {
        return $this->criadoEm();
    }

    /**
     * Converte um array associativo em Usuario
     * @param array $dados Array associativo
     * @return Usuario
     */
    public static function fromArray(array $dados): self
    {
        try {
            $data = new DateTimeImmutable($dados['criado_em']);
        } catch (Exception $e) {
            throw new Exception("Falha ao criar data a partir do valor: " . ($dados['criado_em'] ?? 'N/A'));
        }

        return new self(
            $dados['id'],
            $dados['nome'],
            $dados['email'],
            $dados['cpf'],
            $dados['senha'],
            $data
        );
        /*
        private readonly int $id,
        private string $nome,
        private string $email,
        private string $cpf,
        private string $senha,
        private readonly DateTimeImmutable $criadoEm
        */
    }

    /**
     * Converte um Usuario em array associativo
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'senha' => $this->senha,
            'criado_em' => $this->criadoEm->format('Y-m-d H:i:s')
        ];
    }

}