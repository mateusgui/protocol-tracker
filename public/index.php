<?php

$uri = $_SERVER['REQUEST_URI'];
//BARRANDO REQUISIÇÕES QUE NÃO SEJAM PARA A RAIZ
if ($uri !== '/' && pathinfo($uri, PATHINFO_EXTENSION) !== '') {
    return;
}

// CARREGAMENTO E CONFIGURAÇÃO
require __DIR__ . '/../vendor/autoload.php';
use Mateus\ProtocolTracker\Repository\ProtocoloRepository;
use Mateus\ProtocolTracker\Service\DashboardService;

// ... e outras classes

// LÓGICA DE NEGÓCIO
$caminhoJson = __DIR__ . '/../data/protocolos.json';
$repositorio = new ProtocoloRepository($caminhoJson);

$dashboardService = new DashboardService($repositorio);

// Busca os dados para exibir na página
$listaDeProtocolos = $repositorio->all();
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