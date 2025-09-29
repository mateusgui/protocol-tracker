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
            
            require __DIR__ . '/../../templates/login.php';
        }
    }

    // Processa o logout
    public function logout()
    {
        // Remove variáveis de sessão
        session_unset();

        // Destrói a sessão
        session_destroy();
        
        // Limpa os cookies de sessão do navegador.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        header('Location: /login');
        exit();
    }

    // Exibe o formulário de cadastro de usuário (GET)
    public function exibirFormularioCadastro()
    {
        try {
            $tituloDaPagina = "Cadastrar novo usuário";

            require __DIR__ . '/../../templates/cadastroUsuario.php';

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $tituloDaPagina = "Login Protocol Tracker";
            
            require __DIR__ . '/../../templates/login.php';
        }
    }

    // Processa o formulário de cadastro (POST)
    public function salvarNovoUsuario()
    {
        //public function registrarNovoUsuario(string $nome, string $email, string $cpf, string $senha, string $confirmaSenha): Usuario
        try {
            // Pega os dados do formulário de forma segura
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $cpf = $_POST['cpf'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $confirmaSenha = $_POST['confirmaSenha'] ?? '';

            $this->usuarioService->registrarNovoUsuario($nome, $email, $cpf, $senha, $confirmaSenha);

            $_SESSION['mensagem_sucesso'] = "Usuario criado com sucesso!";

            header('Location: /login');
            exit();

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $tituloDaPagina = "Cadastrar novo usuário";

            $dadosDoFormulario = $_POST; // Será utilizad para recuperar os dados digitados e repopular o formulário de cadastro
            
            require __DIR__ . '/../../templates/cadastroUsuario.php';
        }
    }
}