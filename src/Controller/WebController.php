<?php

namespace Mateus\ProtocolTracker\Controller;

use Mateus\ProtocolTracker\Repository\ProtocoloRepository;
use Mateus\ProtocolTracker\Service\DashboardService;
use Mateus\ProtocolTracker\Service\ProtocoloService;
use Exception;

class WebController {
    private ProtocoloRepository $repositorio;
    private DashboardService $dashboardService;
    private ProtocoloService $protocoloService;

    public function __construct()
    {
        $caminhoJson = __DIR__ . '/../../data/protocols.json';
        
        $this->repositorio = new ProtocoloRepository($caminhoJson);
        $this->dashboardService = new DashboardService($this->repositorio);
        $this->protocoloService = new ProtocoloService($this->repositorio);
    }

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

    /* busca() (para exibir a página de busca e os resultados filtrados) - ⏳ A Fazer

    exibirFormularioEdicao() (para exibir a página com o formulário de um protocolo específico) - ⏳ A Fazer

    deletarProtocolo() (para tratar o POST do botão de exclusão) - ⏳ A Fazer

    notFound() (para páginas não encontradas) - ⏳ A Fazer */

    public function deletarProtocolo()
    {

    }

    public function buscaProtocolo()
    {

    }
}