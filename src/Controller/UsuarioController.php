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
        $tituloDaPagina = "Login Protocol Tracker";

        require __DIR__ . '/../../templates/login.php';
    }

    // Processa o formulário de login (POST)
    public function processarLogin()
    {
        try {
            $cpf = $_POST['cpf'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $this->loginService->login($cpf, $senha);

            header('Location: /');
            exit();

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $tituloDaPagina = "Login Protocol Tracker";
            
            //Se der erro chama a home.php novamente, porém agora com o erro carregado
            require __DIR__ . '/../../templates/login.php';
        }
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