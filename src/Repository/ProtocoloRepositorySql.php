<?php

namespace Mateus\ProtocolTracker\Repository;

use Mateus\ProtocolTracker\Model\Protocolo;
use DateTimeImmutable;
use DateTimeZone;
use PDO;
use PDOStatement;

final class ProtocoloRepositorySql implements ProtocoloRepositoryInterface
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

    /**
     * Busca protocolos com múltipos filtros opcionais
     * @param string|null $numero Número do protocolo
     * @param DateTimeImmutable|null $dataInicio Data de início para o filtro
     * @param DateTimeImmutable|null $dataFim Data final para o filtro
     * @return Protocolo[] Um array de objetos Protocolo que correspondem aos filtros
     */
    public function search(?string $numero, ?DateTimeImmutable $dataInicio, ?DateTimeImmutable $dataFim): array
    {
        $sqlQuery = "SELECT * FROM protocolos";
        $parametros = [];

        if(!empty($numero) || $dataInicio !== null || $dataFim !== null){
            $sqlQuery .= " WHERE";

            $contaParametros = 0;

            if(!empty($numero)){
                $sqlQuery .= " numero = :numero";
                $parametros[':numero'] = $numero;
                $contaParametros++;
            }

            if($dataInicio !== null){
                if($contaParametros > 0){
                    $sqlQuery .= " AND";
                }
                $sqlQuery .= " criado_em >= :dataInicio";
                $parametros[':dataInicio'] = $dataInicio->format('Y-m-d H:i:s');
                $contaParametros++;
            }

            if($dataFim !== null){
                if($contaParametros > 0){
                    $sqlQuery .= " AND";
                }
                $sqlQuery .= " criado_em <= :dataFim";
                $parametros[':dataFim'] = $dataFim->format('Y-m-d H:i:s');
                $contaParametros++;
            }
        }

        $sqlQuery .= " ORDER BY criado_em DESC;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->execute($parametros);

        $listaDeProtocolos = $this->hidrataListaDeProtocolos($stmt);

        return $listaDeProtocolos;
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
        $dadosNovoProtocolo = $novoProtocolo->toArray(); //salvou dados do objeto no array

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
    public function update(Protocolo $protocoloParaAtualizar): bool // MIGRAÇÃO = public function update(Protocolo $protocoloParaAtualizar): void
    {
        $dadosProtocoloParaAtualizar = $protocoloParaAtualizar->toArray();

        $sqlQuery = "UPDATE protocolos SET numero = :numero, quantidade_paginas = :quantidade_paginas, observacoes = :observacoes, alterado_em = :alterado_em WHERE id = :id_para_atualizar;";
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':numero', $dadosProtocoloParaAtualizar['numero']);
        $stmt->bindValue(':quantidade_paginas', $dadosProtocoloParaAtualizar['quantidade_paginas']);
        $stmt->bindValue(':observacoes', $dadosProtocoloParaAtualizar['observacoes']);
        $stmt->bindValue(':alterado_em', $dadosProtocoloParaAtualizar['alterado_em']);
        $stmt->bindValue(':id_para_atualizar', $dadosProtocoloParaAtualizar['id']);

        return $stmt->execute(); // MIGRAÇÃO = $stmt->execute();
    }

    /**
     * Busca todos os protocolo ordenados por data decrescente
     * @param string $id Id do protocolo que vai ser deletado
     * @return void
     */
    public function delete(string $id): bool // MIGRAÇÃO = public function delete(string $id): void
    {
        $sqlQuery = "UPDATE protocolos SET deletado_em = :deletado_em WHERE id = :id;";
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':deletado_em', new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande'))->format('Y-m-d H:i:s'));
        $stmt->bindValue(':id', $id);

        return $stmt->execute(); // MIGRAÇÃO = $stmt->execute();
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
            $listaDeProtocolos[] = Protocolo::fromArray($protocoloDados); //Será preciso modificar o método fromArray quando a migração de BD ocorrer, para receber os novos dados
        }

        return $listaDeProtocolos;
    }
}