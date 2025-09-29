<?php

namespace Mateus\ProtocolTracker\Service;

use Exception;
use Mateus\ProtocolTracker\Repository\UsuarioRepository;

class LoginService
{
        public function __construct(
        private UsuarioRepository $repositorio
    ) {}

    public function login(string $cpf, string $senha): void
    {
        $usuario = $this->repositorio->buscaPorCpf($cpf);

        $senhaCorreta = password_verify($senha, $usuario?->senha() ?? '');

        if ($usuario === null || !$senhaCorreta) {
            throw new Exception("CPF ou senha inválidos.");
        }

        session_regenerate_id(true);

        $_SESSION['usuario_logado_id'] = $usuario->id();
    }
}