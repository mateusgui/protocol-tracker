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
            try {
                // Pega os dados do formulário de forma segura
                $numero = $_POST['numero'] ?? ''; // Armazena o valor de $_POST['numero']; se não existir ou for nulo, usa '' (string vazia) como padrão.
                $paginas = (int)($_POST['paginas'] ?? 0); // Armazena o valor de $_POST['páginas']; se não existir ou for nulo, usa 0 como padrão.
                
                // Com os valores que vieram da requisição POST, chama o método registrarNovoProtocolo para tentar registrar esse protocolo
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

        // -- RENDERIZAÇÃO DA VIEW --
        $tituloDaPagina = "Controle de Protocolos";
        require __DIR__ . '/../templates/home.php';
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
