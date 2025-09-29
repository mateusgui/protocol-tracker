<?php

session_start();

$usuarioEstaLogado = isset($_SESSION['usuario_logado_id']);

// CARREGAMENTO E CONFIGURAÇÃO
require __DIR__ . '/../vendor/autoload.php';

use Mateus\ProtocolTracker\Controller\UsuarioController;
use Mateus\ProtocolTracker\Controller\WebController;
use Mateus\ProtocolTracker\Infrastructure\Persistence\ConnectionCreator;
use Mateus\ProtocolTracker\Repository\ProtocoloRepository;
use Mateus\ProtocolTracker\Repository\UsuarioRepository;
use Mateus\ProtocolTracker\Service\DashboardService;
use Mateus\ProtocolTracker\Service\LoginService;
use Mateus\ProtocolTracker\Service\ProtocoloService;
use Mateus\ProtocolTracker\Service\UsuarioService;

try {
    // CONEXÃO COM O BANCO
    $connection = ConnectionCreator::createConnection();

    $caminhoJson = __DIR__ . '/../data/protocolos.json'; //Caminho do meu JSON de dados
    $repositorio = new ProtocoloRepository($caminhoJson); //Instanciação de ProtocoloRepository
    $dashboardService = new DashboardService($repositorio); //Instanciação de DashboardSerivce usando o $repositorio que é uma instância de ProtocoloRepository
    $protocoloService = new ProtocoloService($repositorio); //Instanciação de ProtocoloService usando o $repositorio que é uma instância de ProtocoloRepository
    $webController = new WebController($repositorio, $dashboardService, $protocoloService); //Instanciação de webController usando instâncias de: repositorio, dashboardService e protocoloService

    $usuarioRepositorio = new UsuarioRepository($connection);
    $usuarioService = new UsuarioService($usuarioRepositorio);
    $loginService = new LoginService($usuarioRepositorio);
    $usuarioController = new UsuarioController($usuarioRepositorio, $usuarioService, $loginService);

    // Variável para guardar possíveis erros de validação do formulário
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
            // REQUEST_METHOD = POST - Executa o bloco if
            
            // ----- ROTA AUTENTICADA -----
            // ----- REMOVER NA MIGRAÇÃO -----
            /*rotaAutenticada($usuarioEstaLogado)*/

            if ($method === 'POST') {
                $webController->salvarNovoProtocolo(); //Registro de novo protocolo
            } else {
                $webController->home(); //Carregamento padrão da home com o dashboard
            }
            break;
        
        // ----- ROTA DE BUSCA '/busca' -----
        case '/busca':

            // ----- ROTA AUTENTICADA -----
            // ----- REMOVER NA MIGRAÇÃO -----
            /*rotaAutenticada($usuarioEstaLogado)*/

            $webController->buscaProtocolo();
            break;

        // ----- ROTA DO DASHBOARD '/dashboard' -----
        case '/dashboard':

            // ----- ROTA AUTENTICADA -----
            // ----- REMOVER NA MIGRAÇÃO -----
            /*rotaAutenticada($usuarioEstaLogado)*/

            $webController->exibirDashboard();
            break;

        // ----- ROTA DE EDIÇÃO '/editar' -----
        case '/editar':

            // ----- ROTA AUTENTICADA -----
            // ----- REMOVER NA MIGRAÇÃO -----
            /*rotaAutenticada($usuarioEstaLogado)*/

            if($method === 'POST'){
                $webController->editarProtocolo();
            } else {
                $webController->exibirFormularioEdicao();
            }
            break;

        // ----- ROTA DE EXCLUSÃO '/excluir' -----
        case '/excluir':

            // ----- ROTA AUTENTICADA -----
            // ----- REMOVER NA MIGRAÇÃO -----
            /*rotaAutenticada($usuarioEstaLogado)*/

            if($method === 'POST'){
                $webController->deletarProtocolo();
            } else {
                header('Location: /busca');
            }
            break;

        case '/cadastro-usuario':

            // ----- ROTA AUTENTICADA -----
            // ----- REMOVER NA MIGRAÇÃO -----
            /*rotaAutenticada($usuarioEstaLogado)*/

            if ($method === 'POST'){
                $usuarioController->salvarNovoUsuario();
            } else {
                $usuarioController->exibirFormularioCadastro();
            }
            break;

        case '/editar-cadastro':

            // ----- ROTA AUTENTICADA -----
            // ----- REMOVER NA MIGRAÇÃO -----
            /*rotaAutenticada($usuarioEstaLogado)*/

            if ($method === 'POST'){
                $usuarioController->atualizarDadosCadastrais();
            } else {
                $usuarioController->exibirFormularioEdicaoCadastro();
            }
            break;

        case '/editar-status':

            // ----- ROTA AUTENTICADA -----
            // ----- REMOVER NA MIGRAÇÃO -----
            /*rotaAutenticada($usuarioEstaLogado)*/

            $usuarioController->alterarStatusUsuario();
            break;

        case '/editar-senha':

            // ----- ROTA AUTENTICADA -----
            // ----- REMOVER NA MIGRAÇÃO -----
            /*rotaAutenticada($usuarioEstaLogado)*/

            if ($method === 'POST'){
                $usuarioController->alterarSenhaUsuario();
            } else {
                $usuarioController->exibirFormularioEditarSenha();
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


