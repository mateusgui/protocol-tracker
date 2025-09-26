<?php

namespace Mateus\ProtocolTracker\Service;

use DateTimeImmutable;
use DateTimeZone;
use Exception; // Usaremos para reportar erros de validação
use Mateus\ProtocolTracker\Repository\UsuarioRepository;

final class ProtocoloService
{
    public function __construct(
        private UsuarioRepository $repositorio
    ) {}

    public function registrarNovouUsuario()
    {

    }

    // public function update(Usuario $usuario): void
    // public function desativarUsuario(int $id): void
    // public function ativarUsuario(int $id): void
    //nome, email, cpf, senha, ativo
    public function salvarUsuario()
    {

    }
}