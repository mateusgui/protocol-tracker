<?php

namespace Mateus\ProtocolTracker\Repository;

use Mateus\ProtocolTracker\Model\Protocolo;
use DateTimeImmutable;
use PDO;

final class ProtocoloRepositorySql implements ProtocoloRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function all(): array
    {
        //Consulta SQL
        $sqlQuery = "SELECT * FROM protocolos ORDER BY criado_em DESC;";
        $stmt = $this->connection->query($sqlQuery);
        $listaAssociativa = $stmt->fetchAll();

        if (!is_array($listaAssociativa)) {
            return [];
        }

        $listaDeProtocolos = []; 
        foreach ($listaAssociativa as $dadosDeUmProtocolo) {
            $protocoloObjeto = Protocolo::fromArray($dadosDeUmProtocolo); //recriando objetos Protocolo 
            $listaDeProtocolos[] = $protocoloObjeto;
        }

        return $listaDeProtocolos;
    }

    public function search(?string $numero, ?DateTimeImmutable $dataInicio, ?DateTimeImmutable $dataFim): array
    {
        return []; //Retorno PADRÃO
    }

    public function buscaPorPeriodo(?DateTimeImmutable $dataInicio = null, ?DateTimeImmutable $dataFim = null): array
    {
        return []; //Retorno PADRÃO
    }

    public function buscaPorNumero(string $numero): ?Protocolo
    {
        return $listaDeProtocolos; //Retorno PADRÃO
    }

    public function buscaPorId(string $id): ?Protocolo
    {
        return $listaDeProtocolos; //Retorno PADRÃO
    }

    public function add(Protocolo $novoProtocolo): void
    {
        
    }

    public function update(Protocolo $protocoloParaAtualizar): bool
    {
        return true; //Retorno PADRÃO
    }

    public function delete(string $id): bool
    {
        return true; //Retorno PADRÃO
    }

}