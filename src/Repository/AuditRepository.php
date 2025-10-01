<?php

namespace Mateus\ProtocolTracker\Repository;

use DateTimeImmutable;
use Mateus\ProtocolTracker\Interface\AuditRepositoryInterface;
use PDO;

final class AuditRepository implements AuditRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Busca todos os registros da tabela de auditoria
     * @return array Array associativo
     */
    public function listaAuditoria(): array
    {
        $sqlQuery = "SELECT audit.numero_protocolo, audit.acao, audit.data_acao, user.nome, user.permissao
        FROM protocolos_auditoria AS audit
        JOIN usuarios AS user
        ON audit.usuario_id = user.id
        ORDER BY numero_protocolo, data_acao;";

        $stmt = $this->connection->query($sqlQuery);

        return $stmt->fetchAll();
    }

    /**
     * Cria um novo registro na tabela de auditoria
     * @return void
     */
    public function registraAlteracao(string $protocolo_id, int $usuario_id, string $numero_protocolo, string $acao, DateTimeImmutable $data_acao): void
    {
        $sqlQuery = "INSERT INTO protocolos_auditoria (protocolo_id, usuario_id, numero_protocolo, acao, data_acao) VALUES (:protocolo_id, :usuario_id, :numero_protocolo, :acao, :data_acao);";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':protocolo_id', $protocolo_id);
        $stmt->bindValue(':usuario_id', $usuario_id);
        $stmt->bindValue(':numero_protocolo', $numero_protocolo);
        $stmt->bindValue(':acao', $acao);
        $stmt->bindValue(':data_acao', $data_acao->format('Y-m-d H:i:s'));
        $stmt->execute();
    }
}