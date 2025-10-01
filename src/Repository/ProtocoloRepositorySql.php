<?php

namespace Mateus\ProtocolTracker\Repository;

use Mateus\ProtocolTracker\Model\Protocolo;
use DateTimeImmutable;
use DateTimeZone;
use Mateus\ProtocolTracker\Interface\ProtocoloRepositorySqlInterface;
use PDO;
use PDOStatement;

final class ProtocoloRepositorySql implements ProtocoloRepositorySqlInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Busca todos os protocolo ordenados por data decrescente
     * @return Protocolo[]
     */
    public function all(): array
    {
        //Consulta SQL
        $sqlQuery = "SELECT * FROM protocolos ORDER BY criado_em DESC;";
        $stmt = $this->connection->query($sqlQuery);
        
        $listaDeProtocolos = $this->hidrataListaDeProtocolos($stmt);

        return $listaDeProtocolos;
    }

    public function allByUser(int $id_usuario): array
    {
        $sqlQuery = "SELECT * FROM protocolos WHERE id_usuario = :id_usuario AND deletado_em IS NULL ORDER BY criado_em DESC;";
        
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id_usuario', $id_usuario);
        $stmt->execute();

        $listaDeProtocolos = $this->hidrataListaDeProtocolos($stmt);

        return $listaDeProtocolos;
    }

    /**
     * Busca protocolos com múltipos filtros opcionais
     * @param string|null $numero Número do protocolo
     * @param DateTimeImmutable|null $dataInicio Data de início para o filtro
     * @param DateTimeImmutable|null $dataFim Data final para o filtro
     * @return Protocolo[] Um array de objetos Protocolo que correspondem aos filtros
     */
    public function search(?int $id_usuario = null, ?string $numero = null, ?DateTimeImmutable $dataInicio = null, ?DateTimeImmutable $dataFim = null): array
    {
        $sqlConditions = [];
        $parametros = [];
        $sqlConditions[] = 'deletado_em IS NULL';

        if ($id_usuario !== null) {
            $sqlConditions[] = 'id_usuario = :id_usuario';
            $parametros[':id_usuario'] = $id_usuario;
        }
        if (!empty($numero)) {
            $sqlConditions[] = 'numero = :numero';
            $parametros[':numero'] = $numero;
        }
        if ($dataInicio !== null) {
            $sqlConditions[] = 'criado_em >= :dataInicio';
            $parametros[':dataInicio'] = $dataInicio->format('Y-m-d H:i:s');
        }
        if ($dataFim !== null) {
            $sqlConditions[] = 'criado_em <= :dataFim';
            $parametros[':dataFim'] = $dataFim->format('Y-m-d H:i:s');
        }

        // 4. Monta a query final.
        $sqlQuery = 'SELECT * FROM protocolos WHERE ' . implode(' AND ', $sqlConditions) . ' ORDER BY criado_em DESC;';

        // 5. Prepara, executa e retorna a lista.
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->execute($parametros);

        return $this->hidrataListaDeProtocolos($stmt);
    }

    /**
     * Busca um único protocolo pelo número
     * @param string $numero Número do protocolo
     * @return Protocolo|null
     */
    public function buscaPorNumero(string $numero): ?Protocolo
    {
        $sqlQuery = "SELECT * FROM protocolos WHERE numero = :numero;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':numero', $numero);
        $stmt->execute();

        $protocolo = $stmt->fetch();

        if ($protocolo === false) {
            return null;
        }

        return Protocolo::fromArray($protocolo);
    }

    /**
     * Busca um único protocolo pelo id
     * @param string $id Id do protocolo
     * @return Protocolo|null
     */
    public function buscaPorId(string $id): ?Protocolo
    {
        $sqlQuery = "SELECT * FROM protocolos WHERE id = :id;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $protocolo = $stmt->fetch();

        if ($protocolo === false) {
            return null;
        }

        return Protocolo::fromArray($protocolo);
    }

    /**
     * Insere um novo protocolo
     * @param Protocolo $novoProtocolo Protocolo que vai ser inserido
     * @return void
     */
    public function add(Protocolo $novoProtocolo): void
    {
        $dadosNovoProtocolo = $novoProtocolo->toArray();

        $sqlQuery = "INSERT INTO protocolos (id, id_usuario, numero, quantidade_paginas, observacoes, criado_em) VALUES (:id, :id_usuario, :numero, :quantidade_paginas, :observacoes, :criado_em);";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id', $dadosNovoProtocolo['id']);
        $stmt->bindValue(':id_usuario', $dadosNovoProtocolo['id_usuario']);
        $stmt->bindValue(':numero', $dadosNovoProtocolo['numero']);
        $stmt->bindValue(':quantidade_paginas', $dadosNovoProtocolo['quantidade_paginas']);
        $stmt->bindValue(':observacoes', $dadosNovoProtocolo['observacoes']);
        $stmt->bindValue(':criado_em', $dadosNovoProtocolo['criado_em']);
        $stmt->execute();
    }

    /**
     * Futuramente as funções add() e update() serão private e vão ser chamadas através da função pública save(), a função save() vai olhar os dados e decidir se é um INSERT ou um UPDATE.
     * Será preciso mudar as regras da ProtocoloRepositoryInterface, então isso só será feito ao final, quando a migração for acontecer, para não quebrar a ProtocoloRepository que EXTENDS ProtocoloRepositoryInterface e está em funcionamento atualmente
     */

    /**
     * Atualiza um protocolo existente
     * @param Protocolo $protocoloParaAtualizar Protocolo que vai ser atualizado
     * @return void
     */
    public function update(Protocolo $protocoloParaAtualizar): void
    {
        $dadosProtocoloParaAtualizar = $protocoloParaAtualizar->toArray();

        $sqlQuery = "UPDATE protocolos SET numero = :numero, quantidade_paginas = :quantidade_paginas, observacoes = :observacoes, alterado_em = :alterado_em WHERE id = :id;";
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':numero', $dadosProtocoloParaAtualizar['numero']);
        $stmt->bindValue(':quantidade_paginas', $dadosProtocoloParaAtualizar['quantidade_paginas']);
        $stmt->bindValue(':observacoes', $dadosProtocoloParaAtualizar['observacoes']);
        $stmt->bindValue(':alterado_em', $dadosProtocoloParaAtualizar['alterado_em']);
        $stmt->bindValue(':id', $dadosProtocoloParaAtualizar['id']);

        $stmt->execute();
    }

    /**
     * Realiza um soft delete em um protocolo
     * @param string $id Id do protocolo que vai ser deletado
     * @return void
     */
    public function desativar(string $id): void
    {
        $sqlQuery = "UPDATE protocolos SET deletado_em = :deletado_em WHERE id = :id;";
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':deletado_em', new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande'))->format('Y-m-d H:i:s'));
        $stmt->bindValue(':id', $id);

        $stmt->execute();
    }

    /**
     * Restaura um protocolo deletado
     * @param string $id Id do protocolo que vai ser restaurado
     * @return void
     */
    public function reativar(string $id): void
    {
        $sqlQuery = "UPDATE protocolos SET deletado_em = :deletado_em WHERE id = :id;";
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':deletado_em', null);
        $stmt->bindValue(':id', $id);

        $stmt->execute();
    }

    /**
     * Transforma um PDOStatement em array de Protocolos
     * @param PDOStatement $stmt Statement que vai ser convertido
     * @return array
     */
    private function hidrataListaDeProtocolos(PDOStatement $stmt): array
    {
        $listaDeProtocolos = [];

        while($protocoloDados = $stmt->fetch()){
            $listaDeProtocolos[] = Protocolo::fromArray($protocoloDados);
        }

        return $listaDeProtocolos;
    }
}