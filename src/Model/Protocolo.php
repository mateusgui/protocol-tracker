<?php

namespace Mateus\ProtocolTracker\Model;

use DateTimeImmutable;
use Exception;

class Protocolo {

    public function __construct(
        private readonly string $id, //id único de cada protocolo
        private string $numero,
        private int $quantidadeDePaginas,
        private DateTimeImmutable $data
    ) {
    }

    //GETTERS
    public function id(): string {
        return $this->id;
    }

    public function numero(): string {
        return $this->numero;
    }

    public function paginas(): int {
        return $this->quantidadeDePaginas;
    }

    public function data(): DateTimeImmutable {
        return $this->data;
    }

    //TRADUTORES
    /**
     * Função que recebe um array, trata os dados e chama o construtor padrão para retornar um objeto do tipo Protocolo.
     */
    public static function fromArray(array $dados): self
    {
        //Tenta criar um DateTimeImmutable usando a data informada pelo usuário
        try {
            $data = new DateTimeImmutable($dados['data']);
        } catch (Exception $e) {
            throw new Exception("Falha ao criar data a partir do valor: " . $dados['data']);
        }

        //Chamada para o construtor da classe
        return new self(
            $dados['id'],
            $dados['numero'],
            $dados['quantidadeDePaginas'],
            $data
        );
    }

    /**
     * Função que retorna o objeto utilizado para chama-la como um array associativo.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'quantidadeDePaginas' => $this->quantidadeDePaginas,
            'data' => $this->data->format(\DATE_ATOM),
        ];
    }

}