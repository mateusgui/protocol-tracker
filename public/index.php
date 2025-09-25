<?php

session_start();

// CARREGAMENTO E CONFIGURAÇÃO
require __DIR__ . '/../vendor/autoload.php';

use Mateus\ProtocolTracker\Controller\WebController;
use Mateus\ProtocolTracker\Infrastructure\Persistence\ConnectionCreator;
use Mateus\ProtocolTracker\Model\Protocolo;
use Mateus\ProtocolTracker\Repository\ProtocoloRepository;
use Mateus\ProtocolTracker\Service\DashboardService;
use Mateus\ProtocolTracker\Service\ProtocoloService;

try {
    // CONEXÃO COM O BANCO
    $connection = ConnectionCreator::createConnection();

    $caminhoJson = __DIR__ . '/../data/protocolos.json'; //Caminho do meu JSON de dados
    $repositorio = new ProtocoloRepository($caminhoJson); //Instanciação de ProtocoloRepository
    $dashboardService = new DashboardService($repositorio); //Instanciação de DashboardSerivce usando o $repositorio que é uma instância de ProtocoloRepository
    $protocoloService = new ProtocoloService($repositorio); //Instanciação de ProtocoloService usando o $repositorio que é uma instância de ProtocoloRepository
    $webController = new WebController($repositorio, $dashboardService, $protocoloService); //Instanciação de webController usando instâncias de: repositorio, dashboardService e protocoloService

    // Variável para guardar possíveis erros de validação do formulário
    $erro = null;

    // LÓGICA DE ROTEAMENTO 
    // ---------------------
    $uri = $_SERVER['REQUEST_URI'];
    $uri = parse_url($uri, PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($uri) {
        // ----- ROTA BASE '/' -----
        case '/':
            // REQUEST_METHOD = POST - Executa o bloco if
            if ($method === 'POST') {
                $webController->salvarNovoProtocolo(); //Registro de novo protocolo
            } else{
                $webController->home(); //Carregamento padrão da home com o dashboard
            }
            break;
        
        // ----- ROTA DE BUSCA '/busca' -----
        case '/busca':
            $webController->buscaProtocolo();
            break;

        // ----- ROTA DO DASHBOARD '/busca' -----
        case '/dashboard':
            $webController->exibirDashboard();
            break;

        // ----- ROTA DE EDIÇÃO '/editar' -----
        case '/editar':
            if($method === 'POST'){
                $webController->editarProtocolo();
            } else {
                $webController->exibirFormularioEdicao();
            }
            break;

        // ----- ROTA DE EXCLUSÃO '/excluir' (Ainda a ser criada) -----
        case '/excluir':
            if($method === 'POST'){
                $webController->deletarProtocolo();
            } else {
                header('Location: /busca');
            }
            break;

        // Rota p
        default:
            $webController->notFound();
            break;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo "<h1>Erro na conexão com o banco de dados.</h1>";
    echo "<p>Por favor, tente novamente mais tarde.</p>";
}


