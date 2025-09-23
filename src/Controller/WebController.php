<?php

namespace Mateus\ProtocolTracker\Controller;

use DateTimeImmutable;
use Mateus\ProtocolTracker\Repository\ProtocoloRepositoryInterface;
use Mateus\ProtocolTracker\Service\DashboardService;
use Mateus\ProtocolTracker\Service\ProtocoloService;
use Exception;

class WebController {
    
    public function __construct(
        private ProtocoloRepositoryInterface $repositorio,
        private DashboardService $dashboardService,
        private ProtocoloService $protocoloService
    ) {}

    // --------------
    //Lida com a exibição da página inicial (requisições GET)
    // --------------
    public function home()
    {
        $metricas = $this->dashboardService->getTodasAsMetricas();
        $tituloDaPagina = "Controle de Protocolos";

        require __DIR__ . '/../../templates/home.php';
    }

    // --------------
    //Lida com requisição POST para adicionar um novo protocolo
    // --------------
    public function salvarNovoProtocolo()
    {
        try {
            // Pega os dados do formulário de forma segura
            $numero = $_POST['numero'] ?? ''; // Armazena o valor de $_POST['numero']; se não existir ou for nulo, usa '' (string vazia) como padrão.
            $paginas = (int)($_POST['paginas'] ?? 0); // Armazena o valor de $_POST['páginas']; se não existir ou for nulo, usa 0 como padrão.
                
            // Com os valores que vieram da requisição POST, chama o método registrarNovoProtocolo para tentar registrar esse protocolo
            $this->protocoloService->registrarNovoProtocolo($numero, $paginas);

            // Se o registro foi bem-sucedido, redireciona para a página inicial
            header('Location: /');
            exit();

        } catch (Exception $e) {
            // Se a Service lançou uma exceção (erro de validação), guardamos a mensagem
            $erro = $e->getMessage();
            $metricas = $this->dashboardService->getTodasAsMetricas(); //Carrega metricas pois é preciso para chamar a view home.php
            $tituloDaPagina = "Controle de Protocolos";
            
            //Se der erro chama a home.php novamente, porém agora com o erro carregado
            require __DIR__ . '/../../templates/home.php';
        }
    }

    //public function buscaPorNumero(string $numero): ?Protocolo
    //public function buscaPorPeriodo(?DateTimeImmutable $dataInicio = null, ?DateTimeImmutable $dataFim = null): array
    public function buscaProtocolo()
    {
        $numero = $_GET['numero'] ?? null;
        $dataInicio = !empty($_GET['data_inicio']) ? new DateTimeImmutable($_GET['data_inicio'] . ' 00:00:00') : null;
        $dataFim = !empty($_GET['data_fim']) ? new DateTimeImmutable($_GET['data_fim'] . ' 23:59:59') : null;

        $erro = null;
        $listaDeProtocolos = [];

        $listaDeProtocolos = $this->repositorio->search($numero, $dataInicio, $dataFim);

        $tituloDaPagina = "Buscar Protocolos";
        
        require __DIR__ . '/../../templates/busca.php';
    }

    public function editarProtocolo()
    {
        try {
            $id = $_POST['id'] ?? '';
            $numero = $_POST['numero'] ?? '';
            $quantidadeDePaginas = (int)($_POST['paginas'] ?? 0);

            $this->protocoloService->editarProtocolo($id, $numero, $quantidadeDePaginas);

            header('Location: /busca');
            exit();

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $listaDeProtocolos = $this->repositorio->all(); //Carrega lista de protocolos pois é preciso para chamar a view busca.php
            $tituloDaPagina = "Busca de Protocolos";

            //Se der erro chama a busca.php novamente, porém agora com o erro carregado
            require __DIR__ . '/../../templates/busca.php';
        }
    }

    public function exibirFormularioEdicao()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->notFound(); // Mostra a página de erro 404.
            return; // Para a execução.
        }
        $protocolo = $this->repositorio->buscaPorId($id);

        // Verifica se achou ou não um protocolo
        if ($protocolo === null) {
            $this->notFound();
            return;
        }

        // Se o código chegou até aqui, temos 100% de certeza
        // que $protocolo é um objeto e não é null.
        $tituloDaPagina = "Editando Protocolo";
        
        // Agora é seguro chamar o template.
        require __DIR__ . '/../../templates/editar.php';
    }

    // USE  public function deletarProtocolo(string $id): void
    public function deletarProtocolo()
    {
        try {
            $id = $_POST['id'] ?? '';
            $this->protocoloService->deletarProtocolo($id);

            header('Location: /busca');
            exit();

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $listaDeProtocolos = $this->repositorio->all(); //Carrega lista de protocolos pois é preciso para chamar a view busca.php
            $tituloDaPagina = "Busca de Protocolos";

            //Se der erro chama a busca.php novamente, porém agora com o erro carregado
            require __DIR__ . '/../../templates/busca.php';
        }
    }

    public function notFound()
    {
        http_response_code(404);
        echo "404 Not Found";
    }
}