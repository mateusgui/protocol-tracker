<?php

namespace Mateus\ProtocolTracker\Repository;

use Mateus\ProtocolTracker\Model\Protocolo;
use DateTimeImmutable;
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
        return $listaDeProtocolos; //Retorno PADRÃO
    }

    /**
     * Busca um único protocolo pelo id
     * @param string $id Id do protocolo
     * @return Protocolo|null
     */
    public function buscaPorId(string $id): ?Protocolo
    {
        return $listaDeProtocolos; //Retorno PADRÃO
    }

    /**
     * Insere um novo protocolo
     * @param Protocolo $novoProtocolo Protocolo que vai ser inserido
     * @return void
     */
    public function add(Protocolo $novoProtocolo): void
    {
        
    }

    /**
     * Futuramente as funções add() e update() serão private e vão ser chamadas através da função pública save(), a função save() vai olhar os dados e decidir se é um INSERT ou um UPDATE.
     * Será preciso mudar as regras da ProtocoloRepositoryInterface, então isso só será feito ao final, quando a migração for acontecer, para não quebrar a ProtocoloRepository que EXTENDS ProtocoloRepositoryInterface e está em funcionamento atualmente
     */

    /**
     * Atualiza um protocolo existente
     * @param Protocolo $protocoloParaAtualizar Protocolo que vai ser atualizado
     * @return bool
     */
    public function update(Protocolo $protocoloParaAtualizar): bool
    {
        return true; //Retorno PADRÃO
    }

    /**
     * Busca todos os protocolo ordenados por data decrescente
     * @param string $id Id do protocolo que vai ser deletado
     * @return bool
     */
    public function delete(string $id): bool
    {
        // ----- Aqui vai ser um soft delete agora -----
        return true; //Retorno PADRÃO
    }

    private function hidrataListaDeProtocolos(PDOStatement $stmt): array
    {
        $listaDeProtocolos = [];

        while($protocoloDados = $stmt->fetch()){
            $listaDeProtocolos[] = Protocolo::fromArray($protocoloDados); //Será preciso modificar o método fromArray quando a migração de BD ocorrer, para receber os novos dados
        }

        return $listaDeProtocolos;
    }
}