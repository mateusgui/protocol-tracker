<?php

namespace Mateus\ProtocolTracker\Model;

use DateTimeImmutable;

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

    public function paginas(){
        return $this->quantidadeDePaginas;
    }

    public function data(){
        return $this->data;
    }

    //TRADUTORES
    /**
     * Função que recebe um array, trata os dados e chama o construtor padrão para retornar um objeto do tipo Protocolo.
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            $dados['id'],
            $dados['numero'],
            $dados['quantidadeDePaginas'],
            new DateTimeImmutable($dados['data'])
        );
    }

    /**
     * Função que retorna o objeto utilizado para chama-la em um array associativo.
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