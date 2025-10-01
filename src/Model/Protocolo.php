<?php

namespace Mateus\ProtocolTracker\Model;

use DateTimeImmutable;
use Exception;

class Protocolo {

    public function __construct(
        private readonly string $id,
        private int $id_usuario,
        private string $numero,
        private int $quantidade_paginas,
        private readonly DateTimeImmutable $criado_em,
        private ?string $observacoes = null,
        private ?DateTimeImmutable $alterado_em = null,
        private ?DateTimeImmutable $deletado_em = null
    ) {}

    /**
     * @return string id
     */
    public function id(): string {
        return $this->id;
    }

    /**
     * @return string numero
     */
    public function numero(): string {
        return $this->numero;
    }

    /**
     * @return int quantidade_paginas
     */
    public function paginas(): int {
        return $this->quantidade_paginas;
    }

    /**
     * @return DateTimeImmutable data
     */
    public function data(): DateTimeImmutable {
        return $this->criado_em;
    }
    
    /**
     * @return int id_usuario
     */
    public function idUsuario(): int {
        return $this->id_usuario;
    }

    /**
     * @return string|null observacoes
     */
    public function observacoes(): ?string {
        return $this->observacoes;
    }

    /**
     * @return DateTimeImmutable alterado_em
     */
    public function alteradoEm(): ?DateTimeImmutable {
        return $this->alterado_em;
    }

    /**
     * @return DateTimeImmutable deletado_em
     */
    public function deletadoEm(): ?DateTimeImmutable {
        return $this->deletado_em;
    }

    /**
     * Converte um array associativo em Protocolo
     * @param array $dados Array associativo
     * @return Protocolo
     */
    public static function fromArray(array $dados): self
    {
        try {
            $criado_em = new DateTimeImmutable($dados['criado_em']);
            $alterado_em = isset($dados['alterado_em']) ? new DateTimeImmutable($dados['alterado_em']) : null; // DESCOMENTAR NA MIGRAÇÃO
            $deletado_em = isset($dados['deletado_em']) ? new DateTimeImmutable($dados['deletado_em']) : null; // DESCOMENTAR NA MIGRAÇÃO
        } catch (Exception $e) {
            throw new Exception("Falha ao criar data");
        }
            
        return new self(
            $dados['id'],
            $dados['id_usuario'],
            $dados['numero'],
            $dados['quantidade_paginas'],
            $criado_em,
            $dados['observacoes'] ?? null,
            $alterado_em,
            $deletado_em
        );
    }

    /**
     * Converte um Protocolo em array associativo
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'id_usuario' => $this->id_usuario,
            'numero' => $this->numero,
            'quantidade_paginas' => $this->quantidade_paginas,
            'criado_em' => $this->criado_em->format('Y-m-d H:i:s'),
            'observacoes' => $this->observacoes ?? '',
            'alterado_em' => $this->alterado_em?->format('Y-m-d H:i:s'),
            'deletado_em' => $this->deletado_em?->format('Y-m-d H:i:s')
        ];
    }
}