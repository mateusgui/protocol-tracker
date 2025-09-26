<?php

namespace Mateus\ProtocolTracker\Repository;

use Mateus\ProtocolTracker\Model\Usuario;
use PDO;
use PDOStatement;

final class UsuarioRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Registra um novo usuario no banco
     * @return Usuario[]
     */
    public function all(): array
    {
        $sqlQuery = "SELECT * FROM usuarios;";
        $stmt = $this->connection->query($sqlQuery);

        $listaDeUsuarios = $this->hidrataListaDeUsuarios($stmt);

        return $listaDeUsuarios;
    }

    public function buscaPorId(int $id): ?Usuario
    {
        $sqlQuery = "SELECT * FROM usuarios WHERE id = :id;";
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        $dadosUsuario = $stmt->fetch();
        if ($dadosUsuario === false) {
            return null;
        }

        return Usuario::fromArray($dadosUsuario);
    }

    /**
     * Busca um Usuario pelo e-mail
     * @param string $email
     * @return Usuario|null
     */
    public function buscaPorEmail(string $email): ?Usuario
    {
        $sqlQuery = "SELECT * FROM usuarios WHERE email = :email;";
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $dadosUsuario = $stmt->fetch();
        if ($dadosUsuario === false) {
            return null;
        }

        return Usuario::fromArray($dadosUsuario);
    }

    /**
     * Busca um Usuario pelo CPF
     * @param string $cpf
     * @return Usuario|null
     */
    public function buscaPorCpf(string $cpf): ?Usuario
    {
        $sqlQuery = "SELECT * FROM usuarios WHERE cpf = :cpf;";
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':cpf', $cpf);
        $stmt->execute();


        $dadosUsuario = $stmt->fetch();
        if ($dadosUsuario === false) {
            return null;
        }

        return Usuario::fromArray($dadosUsuario);
    }

    /**
     * Registra um novo usuario no banco
     * @param Usuario $usuario Usuario que será adicionado
     * @return Usuario Usuario criado
     */
    public function add(Usuario $usuario): Usuario
    {
        $dadosNovoUsuario = $usuario->toArray();

        $sqlQuery = "INSERT INTO usuarios (nome, email, cpf, senha, criado_em, ativo) VALUES (:nome, :email, :cpf, :senha, :criado_em, :ativo);";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':nome', $dadosNovoUsuario['nome']);
        $stmt->bindValue(':email', $dadosNovoUsuario['email']);
        $stmt->bindValue(':cpf', $dadosNovoUsuario['cpf']);
        $stmt->bindValue(':senha', $dadosNovoUsuario['senha']);
        $stmt->bindValue(':criado_em', $dadosNovoUsuario['criado_em']);
        $stmt->bindValue(':ativo', $dadosNovoUsuario['ativo']);

        $stmt->execute();

        $novoId = (int) $this->connection->lastInsertId();

        return $this->buscaPorId($novoId);
    }

/**
 *  -----------------------------------------------
 *  -----------------------------------------------
 *  IMPORTANTE: NA SERVICE VAI TER O MÉTODO save() QUE DECIDE QUAL DOS MÉTODOS ABAIXO VAI CHAMAR, PARA: UPDATE, DESATIVAR USUÁRIO OU ATIVAR USUÁRIO
 *  -----------------------------------------------
 *  -----------------------------------------------
 */

    /**
     * Edita um Usuario no banco
     * @param Usuario $usuario Usuario que será editado
     * @return void
     */
    public function update(Usuario $usuario): void
    {
        $dadosUsuarioParaAtualizar = $usuario->toArray();

        $sqlQuery = "UPDATE usuarios SET nome = :nome, email = :email, cpf = :cpf, senha = :senha WHERE id = :id_para_atualizar;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':nome', $dadosUsuarioParaAtualizar['nome']);
        $stmt->bindValue(':email', $dadosUsuarioParaAtualizar['email']);
        $stmt->bindValue(':cpf', $dadosUsuarioParaAtualizar['cpf']);
        $stmt->bindValue(':senha', $dadosUsuarioParaAtualizar['senha']);
        $stmt->bindValue(':id_para_atualizar', $dadosUsuarioParaAtualizar['id']);

        $stmt->execute();
    }

    /** 
     * Desativa um Usuario no banco
     * @param int $id Id do Usuario que será desativado
     * @return void
     */
    public function desativarUsuario(int $id): void
    {
        $sqlQuery = "UPDATE usuarios SET ativo = :ativo WHERE id = :id;";
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':ativo', 0);
        $stmt->bindValue(':id', $id);

        $stmt->execute();
    }

    /**
     * Ativa um Usuario no banco
     * @param int $id Id do Usuario que será ativado
     * @return void
     */
    public function ativarUsuario(int $id): void
    {
        $sqlQuery = "UPDATE usuarios SET ativo = :ativo WHERE id = :id;";
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':ativo', 1);
        $stmt->bindValue(':id', $id);

        $stmt->execute();
    }

    /**
     * Transforma um PDOStatement em array de Protocolos
     * @param PDOStatement $stmt Statement que vai ser convertido
     * @return array
     */
    private function hidrataListaDeUsuarios(PDOStatement $stmt): array
    {
        $listaDeUsuarios = [];

        while($usuarioDados = $stmt->fetch()){
            $listaDeUsuarios[] = Usuario::fromArray($usuarioDados);
        }

        return $listaDeUsuarios;
    }
}