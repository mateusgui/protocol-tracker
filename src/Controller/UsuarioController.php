<?php

namespace Mateus\ProtocolTracker\Controller;

use DateTimeImmutable;
use Mateus\ProtocolTracker\Repository\UsuarioRepository;
use Mateus\ProtocolTracker\Service\LoginService;
use Mateus\ProtocolTracker\Service\UsuarioService;
use Exception;

class UsuarioController {

    public function __construct(
        private UsuarioRepository $repositorio,
        private UsuarioService $usuarioService,
        private LoginService $loginService,
    ) {}

    // Exibe o formulário de login (GET)
    public function exibirFormularioLogin()
    {
        // ... lógica para carregar o template login.php ...
    }

    // Processa o formulário de login (POST)
    public function processarLogin()
    {
        // ... lógica de try/catch para chamar $this->loginService->autenticar() ...
    }

    // Processa o logout
    public function logout()
    {
        // ... lógica para destruir a sessão e redirecionar ...
    }

    // Exibe o formulário de cadastro de usuário (GET)
    public function exibirFormularioCadastro()
    {
        // ...
    }

    // Processa o formulário de cadastro (POST)
    public function salvarNovoUsuario()
    {
        // ...
    }
}