<?php

session_start();

// CARREGAMENTO E CONFIGURAÇÃO
require __DIR__ . '/../vendor/autoload.php';

use Mateus\ProtocolTracker\Controller\AdminController;
use Mateus\ProtocolTracker\Controller\UsuarioController;
use Mateus\ProtocolTracker\Controller\WebController;
use Mateus\ProtocolTracker\Infrastructure\Persistence\ConnectionCreator;
use Mateus\ProtocolTracker\Repository\AuditRepository;
use Mateus\ProtocolTracker\Repository\ProtocoloRepositorySql;
use Mateus\ProtocolTracker\Repository\UsuarioRepository;
use Mateus\ProtocolTracker\Service\AuditService;
use Mateus\ProtocolTracker\Service\DashboardService;
use Mateus\ProtocolTracker\Service\LoginService;
use Mateus\ProtocolTracker\Service\ProtocoloService;
use Mateus\ProtocolTracker\Service\UsuarioService;

try {
    // CONEXÃO COM O BANCO
    $connection = ConnectionCreator::createConnection();

    $auditRepository = new AuditRepository($connection);
    $usuarioRepositorio = new UsuarioRepository($connection);
    $repositorio = new ProtocoloRepositorySql($connection);

    $auditService = new AuditService($auditRepository);
    $dashboardService = new DashboardService($repositorio);
    $protocoloService = new ProtocoloService($repositorio, $auditService);
    $usuarioService = new UsuarioService($usuarioRepositorio);
    $loginService = new LoginService($usuarioRepositorio);

    $usuarioController = new UsuarioController($usuarioRepositorio, $usuarioService, $loginService);
    $webController = new WebController($repositorio, $dashboardService, $protocoloService, $usuarioRepositorio);
    $adminController = new AdminController($usuarioRepositorio, $usuarioService, $repositorio);

    $usuarioEstaLogado = isset($_SESSION['usuario_logado_id']);
    $idUsuarioLogado = $_SESSION['usuario_logado_id'] ?? null;
    
    $usuarioLogado = null;
    $permissao = null;

    if ($idUsuarioLogado) {
        $usuarioLogado = $usuarioRepositorio->buscaPorId($idUsuarioLogado);
        if ($usuarioLogado) {
            $permissao = $usuarioLogado->permissao();
        }
    }

    $erro = null;

    // LÓGICA DE ROTEAMENTO 
    // ---------------------
    $uri = $_SERVER['REQUEST_URI'];
    $uri = parse_url($uri, PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    function rotaAutenticada($usuarioEstaLogado): void
    {
        if (!$usuarioEstaLogado) {
            header('Location: /login');
            exit();
        }
    }

    function rotaAdmin($permissao): void
    {
        if ($permissao !== 'administrador') {
            header('Location: /home');
            exit();
        }
    }

    switch ($uri) {
        case '/':
            if ($usuarioEstaLogado) {
                header('Location: /home');
            } else {
                header('Location: /login');
            }
            exit();

        case '/login':
            if ($method === 'POST'){
                $usuarioController->processarLogin();
            } else {
                $usuarioController->exibirFormularioLogin();
            }
            break;

        case '/logout':
            $usuarioController->logout();
            break;
        
        case '/home':
            
            rotaAutenticada($usuarioEstaLogado);

            if ($method === 'POST') {
                $webController->salvarNovoProtocolo(); //Registro de novo protocolo
            } else {
                $webController->home(); //Carregamento padrão da home com o dashboard
            }
            break;
        
        // ----- ROTA DE BUSCA '/busca' -----
        case '/busca':

            rotaAutenticada($usuarioEstaLogado);

            $webController->buscaProtocolo();
            break;

        // ----- ROTA DO DASHBOARD '/dashboard' -----
        case '/dashboard':

            rotaAutenticada($usuarioEstaLogado);

            $webController->exibirDashboard();
            break;

        // ----- ROTA DE EDIÇÃO '/editar' -----
        case '/editar':

            rotaAutenticada($usuarioEstaLogado);

            if($method === 'POST'){
                $webController->editarProtocolo();
            } else {
                $webController->exibirFormularioEdicao();
            }
            break;

        // ----- ROTA DE EXCLUSÃO '/excluir' -----
        case '/excluir':

            rotaAutenticada($usuarioEstaLogado);

            if($method === 'POST'){
                $webController->alteraStatusProtocolo();
            } else {
                header('Location: /busca');
            }
            break;

        case '/cadastro-usuario':

            if ($method === 'POST'){
                $usuarioController->salvarNovoUsuario();
            } else {
                $usuarioController->exibirFormularioCadastro();
            }
            break;

        case '/editar-cadastro':

            rotaAutenticada($usuarioEstaLogado);

            if ($method === 'POST'){
                $usuarioController->atualizarDadosCadastrais();
            } else {
                $usuarioController->exibirFormularioEdicaoCadastro();
            }
            break;

        case '/editar-status':

            rotaAutenticada($usuarioEstaLogado);

            $usuarioController->alterarStatusUsuario();
            break;

        case '/editar-senha':

            rotaAutenticada($usuarioEstaLogado);

            if ($method === 'POST'){
                $usuarioController->alterarSenhaUsuario();
            } else {
                $usuarioController->exibirFormularioEditarSenha();
            }
            break;

        case '/admin/protocolos':

            rotaAutenticada($usuarioEstaLogado);
            rotaAdmin($permissao);

            $adminController->exibirPainelAdmin();

            break;

        case '/admin/usuarios':

            rotaAutenticada($usuarioEstaLogado);
            rotaAdmin($permissao);

            $adminController->listaUsuarios();

            break;

        case '/admin/usuarios/editar-cadastro':

            rotaAutenticada($usuarioEstaLogado);
            rotaAdmin($permissao);

            if ($method === 'POST'){
                $adminController->atualizarDadosUsuario();
            } else {
                $adminController->exibirFormularioEdicaoCadastro();
            }
            break;

        // ----- ROTA NÃO ENCONTRADA - 404 NOT FOUND -----
        default:
            $webController->notFound();
            break;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo "<p>Mensagem do Erro: " . $e->getMessage() . "</p>";
    echo "<p>Por favor, tente novamente mais tarde.</p>";
}


