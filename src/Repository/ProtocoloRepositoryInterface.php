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
    public function update(Protocolo $protocoloParaAtualizar): bool; // MIGRAÇÃO = public function update(Protocolo $protocoloParaAtualizar): void;
    public function desativar(string $id): bool; // MIGRAÇÃO = public function desativar(string $id): void;
    //public function reativar(string $id): void;
}