<?php

namespace Mateus\ProtocolTracker\Repository;

use DateTimeImmutable;

interface AuditRepositoryInterface
{
    public function listaAuditoria(): array;
    public function registraAlteracao(string $protocolo_id, int $usuario_id, string $numero_protocolo, string $acao, DateTimeImmutable $data_acao): void;
}