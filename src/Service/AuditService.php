<?php

namespace Mateus\ProtocolTracker\Service;

use DateTimeImmutable;
use DateTimeZone;
use Exception; // Usaremos para reportar erros de validação
use Mateus\ProtocolTracker\Repository\AuditRepository;

final class AuditService
{
    // Construtor padrão da classe, recebe um objeto do tipo ProtocoloRepository para inicializar a classe ProtocoloService
    public function __construct(
        private AuditRepository $repositorio
    ) {}

    public function registraAlteracao(string $protocolo_id, int $usuario_id, string $numero_protocolo, string $acao): void
    {
        $data_acao = new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande'));
        $this->repositorio->registraAlteracao($protocolo_id, $usuario_id, $numero_protocolo, $acao, $data_acao);
    }
}