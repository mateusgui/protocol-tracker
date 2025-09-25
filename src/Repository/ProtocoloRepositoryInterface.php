<?php

namespace Mateus\ProtocolTracker\Repository;

use DateTimeImmutable;
use Mateus\ProtocolTracker\Model\Protocolo;

interface ProtocoloRepositoryInterface
{
    public function all(): array;
    public function search(?string $numero, ?DateTimeImmutable $dataInicio, ?DateTimeImmutable $dataFim): array;
    public function buscaPorNumero(string $numero): ?Protocolo;
    public function buscaPorId(string $id): ?Protocolo;
    public function add(Protocolo $novoProtocolo): void;
    public function update(Protocolo $protocoloParaAtualizar): bool;
    public function delete(string $id): bool;
}