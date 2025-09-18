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
// REQUEST_METHOD = POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Pega os dados do formulário de forma segura
        $numero = $_POST['numero'] ?? '';
        $paginas = isset($_POST['paginas']) ? (int)$_POST['paginas'] : 0;
        
        // Delega a lógica de negócio para a Service
        $protocoloService->registrarNovoProtocolo($numero, $paginas);

        // Se o registro foi bem-sucedido, redireciona para a página inicial
        // Isso evita o reenvio do formulário se o usuário atualizar a página (Padrão PRG)
        header('Location: /');
        exit();

    } catch (Exception $e) {
        // Se a Service lançou uma exceção (erro de validação), guardamos a mensagem
        $erro = $e->getMessage();
    }
}

// REQUEST_METHOD = GET - Executa tudo abaixo
//Chamando os cálculos das métricas e armazenando para renderizar o dashboard na home
$metricas = $dashboardService->getTodasAsMetricas();

$tituloDaPagina = "Controle de Protocolos";


// -- RENDERIZAÇÃO DA VIEW --
// Todas as variáveis necessárias ($listaDeProtocolos, $metricas) já foram preparadas.
// Incluo o template 'home.php' para renderizar o HTML final.
require __DIR__ . '/../templates/home.php';

/* $uri = $_SERVER['REQUEST_URI'];
//BARRANDO REQUISIÇÕES QUE NÃO SEJAM PARA A RAIZ
if ($uri !== '/' && pathinfo($uri, PATHINFO_EXTENSION) !== '') {
    return;
}

require __DIR__ . '/../vendor/autoload.php';

use Mateus\ProtocolTracker\Model\Protocolo;
use Mateus\ProtocolTracker\Repository\ProtocoloRepository;

$protocolo_id = uniqid('protocolo_');

$protocolo = new Protocolo($protocolo_id, '123456', 30, new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande')));

echo "ID: " . $protocolo->id() . " | Número do protocolo: " . $protocolo->numero() . " | Quantidade de páginas: " . $protocolo->paginas() . " | Data de criação: " . $protocolo->data()->format('d/m/Y H:i');

$protocoloRepository = new ProtocoloRepository(__DIR__ . '/../data/protocolos.json');

$protocoloRepository->add($protocolo); */