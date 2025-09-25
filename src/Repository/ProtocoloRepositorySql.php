<?php

namespace Mateus\ProtocolTracker\Repository;

use Mateus\ProtocolTracker\Model\Protocolo;
use DateTimeImmutable;

final class ProtocoloRepositorySql implements ProtocoloRepositoryInterface
{
    public function all(): array
    {
        //Consulta SQL
        //Converte cada registro retornado na consulta para um objeto do tipo Protocolo
        //Salva todos os protocolos em um array de Protocolo
        return []; //Retorno PADRÃO
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