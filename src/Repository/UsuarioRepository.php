<?php

namespace Mateus\ProtocolTracker\Repository;

use DateTimeImmutable;
use DateTimeZone;
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

    //Novo usuario - recebe instancia de Usuario para ser adicionado
    public function add(Usuario $usuario)
    {
        
    }

    //Editar dados do usuario - recebe instancia de Usuario com dados para edição

    //Deletar usuario - soft delete - recebe id do usuario que será deletado

}