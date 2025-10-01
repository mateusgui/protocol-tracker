<?php

namespace Mateus\ProtocolTracker\Repository;

use DateTimeImmutable;
use DateTimeZone;
use PDO;
use PDOStatement;

final class AuditRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function listaAuditoria(): array
    {
        $sqlQuery = "SELECT * FROM protocolos_auditoria ORDER BY numero_protocolo, data_acao;";

        $stmt = $this->connection->query($sqlQuery);

        return $stmt->fetchAll();//RETORNA O RESULTADO DA CONSULTA COMO ARRAY ASSOCIATIVO
    }

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