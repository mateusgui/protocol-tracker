<?php

namespace Mateus\ProtocolTracker\Interface;

use DateTimeImmutable;
use Mateus\ProtocolTracker\Model\Protocolo;

interface ProtocoloRepositorySqlInterface
{
    public function all(): array;
    public function allByUser(int $id_usuario): array;
    public function search(?int $id_usuario = null, ?string $numero = null, ?DateTimeImmutable $dataInicio = null, ?DateTimeImmutable $dataFim = null): array;
    public function buscaPorNumero(string $numero): ?Protocolo;
    public function buscaPorId(string $id): ?Protocolo;
    public function add(Protocolo $novoProtocolo): void;
    public function update(Protocolo $protocoloParaAtualizar): void;
    public function desativar(string $id): void;
    public function reativar(string $id): void;
}