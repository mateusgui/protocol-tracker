<?php

namespace Mateus\ProtocolTracker\Model;

use DateTimeImmutable;
use Exception;

class Protocolo {

    public function __construct(
        private readonly string $id,
        private string $numero,
        private int $quantidadeDePaginas,
        private readonly DateTimeImmutable $data
    ) {}

    /* DESCOMENTAR NA MIGRAÇÃO
    public function __construct(
        private readonly string $id,
        private int $idUsuario,
        private string $numero,
        private int $quantidadeDePaginas,
        private readonly DateTimeImmutable $data,
        private ?string $observacoes = null,
        private ?DateTimeImmutable $alteradoEm = null
        private ?DateTimeImmutable $deletadoEm = null
    ) {} */

    //GETTERS
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
     * @return int quantidadeDePaginas
     */
    public function paginas(): int {
        return $this->quantidadeDePaginas;
    }

    /**
     * @return DateTimeImmutable data
     */
    public function data(): DateTimeImmutable {
        return $this->data;
    }

    /* DESCOMENTAR NA MIGRAÇÃO
    
    public function idUsuario(): int {
        return $this->idUsuario;
    }

    public function observacoes(): ?string {
        return $this->observacoes;
    }

    public function alteradoEm(): ?DateTimeImmutable {
        return $this->alteradoEm;
    }

    public function deletadoEm(): ?DateTimeImmutable {
        return $this->deletadoEm;
    }
    */

    /**
     * Converte um array associativo em Protocolo
     * @param array $dados Array associativo
     * @return Protocolo
     */
    public static function fromArray(array $dados): self
    {
        try {
            $data = new DateTimeImmutable($dados['data']);
        } catch (Exception $e) {
            throw new Exception("Falha ao criar data a partir do valor: " . ($dados['data'] ?? 'N/A'));
        }

        /* DESCOMENTAR NA MIGRAÇÃO e passar para o bloco TRY
            $alteradoEm = isset($dados['alterado_em']) ? new DateTimeImmutable($dados['alterado_em']) : null; // DESCOMENTAR NA MIGRAÇÃO
        */

        return new self(
            $dados['id'],
            $dados['numero'],
            $dados['quantidadeDePaginas'],
            $data
        );

        /* DESCOMENTAR NA MIGRAÇÃO
        return new self(
            $dados['id'],
            $dados['id_usuario'],
            $dados['numero'],
            $dados['quantidade_paginas'],
            $data,
            $dados['observacoes'] ?? null,
            $alteradoEm,
            $deletadoEm
        ); */
    }

    /**
     * Converte um Protocolo em array associativo
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'quantidadeDePaginas' => $this->quantidadeDePaginas,
            'data' => $this->data->format('Y-m-d H:i:s')
        ];

        /* DESCOMENTAR NA MIGRAÇÃO
        return [
            'id' => $this->id,
            'id_usuario' => $this->idUsuario,
            'numero' => $this->numero,
            'quantidade_paginas' => $this->quantidadeDePaginas,
            'criado_em' => $this->criadoEm->format('Y-m-d H:i:s'),
            'observacoes' => $this->observacoes,
            'alterado_em' => $this->alteradoEm?->format('Y-m-d H:i:s'),
            'deletado_em' => $this->deletadoEm?->format('Y-m-d H:i:s')
        ];
        */
    }

}