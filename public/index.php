<?php

// CARREGAMENTO E CONFIGURAÇÃO
require __DIR__ . '/../vendor/autoload.php';

use Mateus\ProtocolTracker\Controller\WebController;
use Mateus\ProtocolTracker\Model\Protocolo;
use Mateus\ProtocolTracker\Repository\ProtocoloRepository;
use Mateus\ProtocolTracker\Service\DashboardService;
use Mateus\ProtocolTracker\Service\ProtocoloService;

$caminhoJson = __DIR__ . '/../data/protocolos.json'; //Caminho do meu JSON de dados
$repositorio = new ProtocoloRepository($caminhoJson); //Instanciação de ProtocoloRepository
$dashboardService = new DashboardService($repositorio); //Instanciação de DashboardSerivce usando o $repositorio que é uma instância de ProtocoloRepository
$protocoloService = new ProtocoloService($repositorio); //Instanciação de ProtocoloService usando o $repositorio que é uma instância de ProtocoloRepository
$webController = new WebController($repositorio, $dashboardService, $protocoloService);

// Variável para guardar possíveis erros de validação do formulário
$erro = null;

// LÓGICA DE ROTEAMENTO 
// ---------------------
$uri = $_SERVER['REQUEST_URI'];

switch ($uri) {
    // ----- ROTA BASE '/' -----
    case '/':
        // REQUEST_METHOD = POST - Executa o bloco if
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $webController->salvarNovoProtocolo(); //Registro de novo protocolo
        } else{
            $webController->home(); //Carregamento padrão da home com o dashboard
        }
        break;
    
    // ----- ROTA DE BUSCA '/busca' -----
    case '/busca':
        $webController->buscaProtocolo();
        break;

    // ----- ROTA DE EDIÇÃO '/editar' -----
    case '/editar':
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $webController->editarProtocolo();
        } else {
            $webController->exibirFormularioEdicao();
        }
        break;

    // ----- ROTA DE EXCLUSÃO '/excluir' (Ainda a ser criada) -----
    case '/excluir':
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
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
