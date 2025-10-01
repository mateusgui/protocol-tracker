<?php

namespace Mateus\ProtocolTracker\Service;

use DateTimeImmutable;
use DateTimeZone;
use Mateus\ProtocolTracker\Interface\AuditRepositoryInterface;

final class AuditService
{
    public function __construct(
        private AuditRepositoryInterface $repositorio
    ) {}

    /**
     * Busca todos os registros da tabela de auditoria
     * @return array Array associativo
     */
    public function listaAuditoria(): array
    {
        return $this->repositorio->listaAuditoria();
    }

    /**
     * Cria um novo registro na tabela de auditoria
     * @param string $protocolo_id
     * @param int $usuario_id
     * @param string $numero_protocolo
     * @param string $acao
     * @return void
     */
    public function registraAlteracao(string $protocolo_id, int $usuario_id, string $numero_protocolo, string $acao): void
    {
        $data_acao = new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande'));
        $this->repositorio->registraAlteracao($protocolo_id, $usuario_id, $numero_protocolo, $acao, $data_acao);
    }
}