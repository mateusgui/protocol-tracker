<?php

// CARREGAMENTO E CONFIGURAÇÃO
require __DIR__ . '/../vendor/autoload.php';

use Mateus\ProtocolTracker\Model\Protocolo;
use Mateus\ProtocolTracker\Repository\ProtocoloRepository;
use Mateus\ProtocolTracker\Service\DashboardService;
use Mateus\ProtocolTracker\Service\ProtocoloService;

$caminhoJson = __DIR__ . '/../data/protocolos.json'; //Caminho do meu JSON de dados
$repositorio = new ProtocoloRepository($caminhoJson); //Instanciação de ProtocoloRepository
$dashboardService = new DashboardService($repositorio); //Instanciação de DashboardSerivce usando o $repositorio que é uma instância de ProtocoloRepository
$protocoloService = new ProtocoloService($repositorio); //Instanciação de ProtocoloService usando o $repositorio que é uma instância de ProtocoloRepository

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
            //chama salvarNovoProtocolo();
        }

        //chama home();
        break;
    
    // ----- ROTA DE BUSCA '/busca' -----
    case '/busca':
        echo "Página para buscas de protocolos";
        $tituloDaPagina = "Página de busca";
        $listaDeProtocolos = $repositorio->all();

        // -- RENDERIZAÇÃO DA VIEW --
        require __DIR__ . '/../templates/busca.php';
        break;

    default:
        http_response_code(404);
        echo "Página não encontrada.";
        break;
}
