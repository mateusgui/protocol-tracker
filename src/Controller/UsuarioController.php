<?php

namespace Mateus\ProtocolTracker\Controller;

use Mateus\ProtocolTracker\Repository\UsuarioRepository;
use Mateus\ProtocolTracker\Service\LoginService;
use Mateus\ProtocolTracker\Service\UsuarioService;
use Exception;
use Mateus\ProtocolTracker\Model\Usuario;

class UsuarioController
{

    private ?Usuario $usuarioLogado = null;
    private bool $isAdmin = false;

    public function __construct(
        private UsuarioRepository $repositorio,
        private UsuarioService $usuarioService,
        private LoginService $loginService,
    ) {
        $id_usuario = $_SESSION['usuario_logado_id'] ?? null;
        if ($id_usuario) {
            $this->usuarioLogado = $this->repositorio->buscaPorId($id_usuario);
        }
        
        if ($this->usuarioLogado && $this->usuarioLogado->permissao() === 'administrador') {
            $this->isAdmin = true;
        }
    }

    /**
     * URL_PATH = /login
     * REQUEST_METHOD = GET
     */
    public function exibirFormularioLogin()
    {
        $tituloDaPagina = "Login Protocol Tracker";
        $usuarioLogado = $this->usuarioLogado;
        $isAdmin = $this->isAdmin;

        require __DIR__ . '/../../templates/login.php';
    }

    /**
     * URL_PATH = /login
     * REQUEST_METHOD = POST
     */
    public function processarLogin()
    {
        try {
            $cpf = $_POST['cpf'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $this->loginService->login($cpf, $senha);

            header('Location: /home');
            exit();

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $tituloDaPagina = "Login Protocol Tracker";
            
            require __DIR__ . '/../../templates/login.php';
        }
    }

    /**
     * URL_PATH = /logout
     * REQUEST_METHOD = GET/POST
     */
    public function logout()
    {
        session_unset(); // Remove variáveis de sessão
        session_destroy(); // Destrói a sessão
        
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

    /**
     * URL_PATH = /cadastro-usuario
     * REQUEST_METHOD = GET
     */
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

    /**
     * URL_PATH = /cadastro-usuario
     * REQUEST_METHOD = POST
     */
    public function salvarNovoUsuario()
    {
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
            $dadosDoFormulario = $_POST;
            
            require __DIR__ . '/../../templates/cadastroUsuario.php';
        }
    }

    /**
     * URL_PATH = /editar-cadastro
     * REQUEST_METHOD = GET
     */
    public function exibirFormularioEdicaoCadastro()
    {
        try {
            $tituloDaPagina = "Editar cadastro";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            $id_usuario = $this->usuarioLogado?->id(); 

            $usuario = $this->repositorio->buscaPorId($id_usuario);

            require __DIR__ . '/../../templates/editarCadastro.php';

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $tituloDaPagina = "Controle de Protocolos";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;
            
            require __DIR__ . '/../../templates/home.php';
        }
    }

    /**
     * URL_PATH = /editar-cadastro
     * REQUEST_METHOD = POST
     */
    public function atualizarDadosCadastrais()
    {
        try {
            $id_usuario = $this->usuarioLogado?->id(); 
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $cpf = $_POST['cpf'] ?? '';

            $this->usuarioService->atualizarDadosCadastrais($id_usuario, $nome, $email, $cpf);

            $_SESSION['mensagem_sucesso'] = "Cadastro atualizado com sucesso!";

            header('Location: /home');
            exit();

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $tituloDaPagina = "Editar cadastro";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;
            $dadosDoFormulario = $_POST;

            require __DIR__ . '/../../templates/editarCadastro.php';
        }
    }

    /**
     * URL_PATH = /editar-status
     * REQUEST_METHOD = GET/POST
     */
    public function alterarStatusUsuario()
    {
        try {
            $id_usuario = isset($_POST['id']) ? (int)$_POST['id'] : null;

            $this->usuarioService->alterarStatusUsuario($id_usuario);

            $_SESSION['mensagem_sucesso'] = "Status alterado com sucesso!";

            header('Location: /admin/usuarios');
            exit();

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $tituloDaPagina = "Editar cadastro";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            require __DIR__ . '/../../templates/editarCadastro.php';
        }
    }

    /**
     * URL_PATH = /editar-senha
     * REQUEST_METHOD = GET
     */
    public function exibirFormularioEditarSenha()
    {
        try {
            $tituloDaPagina = "Alterar senha";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            require __DIR__ . '/../../templates/editarSenha.php';

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $tituloDaPagina = "Editar cadastro";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            require __DIR__ . '/../../templates/editarCadastro.php';
        }
    }

    /**
     * URL_PATH = /editar-senha
     * REQUEST_METHOD = POST
     */
    public function alterarSenhaUsuario()
    {
        try {
            $id_usuario = $this->usuarioLogado?->id();
            $novaSenha = $_POST['novaSenha'] ?? '';
            $confirmaSenha = $_POST['confirmaSenha'] ?? '';

            $this->usuarioService->alterarSenhaUsuario($id_usuario, $novaSenha, $confirmaSenha);

            $_SESSION['mensagem_sucesso'] = "Senha alterada com sucesso";

            header('Location: /home');
            exit();

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $tituloDaPagina = "Alterar senha";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            require __DIR__ . '/../../templates/editarSenha.php';
        }
    }
}