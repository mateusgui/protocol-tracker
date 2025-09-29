<?php

namespace Mateus\ProtocolTracker\Controller;

use DateTimeImmutable;
use Mateus\ProtocolTracker\Repository\ProtocoloRepositoryInterface;
use Mateus\ProtocolTracker\Service\DashboardService;
use Mateus\ProtocolTracker\Service\ProtocoloService;
use Exception;
use Mateus\ProtocolTracker\Repository\UsuarioRepository;

class WebController {
    
    public function __construct(
        private ProtocoloRepositoryInterface $repositorio,
        private DashboardService $dashboardService,
        private ProtocoloService $protocoloService,
        private UsuarioRepository $usuarioRepository
    ) {}

    /**
     * URL_PATH = /
     * REQUEST_METHOD = GET
     */
    public function home()
    {
        $tituloDaPagina = "Controle de Protocolos";

        $idUsuario = $_SESSION['usuario_logado_id'] ?? null;
        $usuarioLogado = $idUsuario ? $this->usuarioRepository->buscaPorId($idUsuario) : null;

        require __DIR__ . '/../../templates/home.php';
    }

    /**
     * URL_PATH = /
     * REQUEST_METHOD = POST
     */
    public function salvarNovoProtocolo()
    {
        try {
            // Pega os dados do formulário de forma segura
            $id_usuario = $_SESSION['usuario_logado_id'] ?? null;
            $numero = $_POST['numero'] ?? '';
            $quantidade_paginas = (int)($_POST['paginas'] ?? 0);
            $observacoes = $_POST['observacoes'] ?? '';
                
            // Com os valores que vieram da requisição POST, chama o método registrarNovoProtocolo para tentar registrar esse protocolo
            $this->protocoloService->registrarNovoProtocolo($id_usuario, $numero, $quantidade_paginas, $observacoes);

            $_SESSION['mensagem_sucesso'] = "Protocolo adicionado com sucesso!";

            // Se o registro foi bem-sucedido, redireciona para a página inicial
            header('Location: /home');
            exit();

        } catch (Exception $e) {
            // Se a Service lançou uma exceção (erro de validação), guardamos a mensagem
            $erro = $e->getMessage();
            $tituloDaPagina = "Controle de Protocolos";

            $idUsuario = $_SESSION['usuario_logado_id'] ?? null;
            $usuarioLogado = $idUsuario ? $this->usuarioRepository->buscaPorId($idUsuario) : null;
            
            //Se der erro chama a home.php novamente, porém agora com o erro carregado
            require __DIR__ . '/../../templates/home.php';
        }
    }

    /**
     * URL_PATH = /busca
     * REQUEST_METHOD = GET
     */
    public function buscaProtocolo()
    {
        try {
            $numero = $_GET['numero'] ?? null;
            $dataInicio = !empty($_GET['data_inicio']) ? new DateTimeImmutable($_GET['data_inicio'] . ' 00:00:00') : null;
            $dataFim = !empty($_GET['data_fim']) ? new DateTimeImmutable($_GET['data_fim'] . ' 23:59:59') : null;

            $erro = null;
            $listaDeProtocolos = [];

            $listaDeProtocolos = $this->repositorio->search($numero, $dataInicio, $dataFim);

            $tituloDaPagina = "Buscar Protocolos";

            $idUsuario = $_SESSION['usuario_logado_id'] ?? null;
            $usuarioLogado = $idUsuario ? $this->usuarioRepository->buscaPorId($idUsuario) : null;
            
            require __DIR__ . '/../../templates/busca.php';

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $listaDeProtocolos = $this->repositorio->all();
            $tituloDaPagina = "Buscar Protocolos";

            $idUsuario = $_SESSION['usuario_logado_id'] ?? null;
            $usuarioLogado = $idUsuario ? $this->usuarioRepository->buscaPorId($idUsuario) : null;
            
            //Se der erro chama a home.php, porém agora com o erro carregado
            require __DIR__ . '/../../templates/busca.php';
        }
    }

    /**
     * URL_PATH = /editar
     * REQUEST_METHOD = POST
     */
    public function editarProtocolo()
    {
        try {
            $id_usuario = $_SESSION['usuario_logado_id'] ?? null;
            $id = $_POST['id'] ?? '';
            $numero = $_POST['numero'] ?? '';
            $quantidade_paginas = (int)($_POST['paginas'] ?? 0);
            $observacoes = $_POST['observacoes'] ?? '';

            $this->protocoloService->editarProtocolo($id_usuario, $id, $numero, $quantidade_paginas, $observacoes);

            $_SESSION['mensagem_sucesso'] = "Protocolo atualizado com sucesso!";

            $idUsuario = $_SESSION['usuario_logado_id'] ?? null;
            $usuarioLogado = $idUsuario ? $this->usuarioRepository->buscaPorId($idUsuario) : null;

            header('Location: /busca');
            exit();

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $listaDeProtocolos = $this->repositorio->all(); //Carrega lista de protocolos pois é preciso para chamar a view busca.php
            $tituloDaPagina = "Busca de Protocolos";

            $idUsuario = $_SESSION['usuario_logado_id'] ?? null;
            $usuarioLogado = $idUsuario ? $this->usuarioRepository->buscaPorId($idUsuario) : null;

            //Se der erro chama a busca.php novamente, porém agora com o erro carregado
            require __DIR__ . '/../../templates/busca.php';
        }
    }

    /**
     * URL_PATH = /editar
     * REQUEST_METHOD = GET
     */
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

        $tituloDaPagina = "Editando Protocolo";

        $idUsuario = $_SESSION['usuario_logado_id'] ?? null;
        $usuarioLogado = $idUsuario ? $this->usuarioRepository->buscaPorId($idUsuario) : null;
        
        require __DIR__ . '/../../templates/editar.php';
    }

    /**
     * URL_PATH = /excluir
     * REQUEST_METHOD = POST
     */
    public function alteraStatusProtocolo()
    {
        try {
            $id_usuario = $_SESSION['usuario_logado_id'] ?? null;
            $id = $_POST['id'] ?? '';
            
            $this->protocoloService->alteraStatusProtocolo($id_usuario, $id);

            $_SESSION['mensagem_sucesso'] = "Status alterado com sucesso!";

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

    /**
     * URL_PATH = /dashboard
     * REQUEST_METHOD = GET
     */
    public function exibirDashboard()
    {
        try {
            $metricas = $this->dashboardService->getTodasAsMetricas();
            $tituloDaPagina = "Dashboard de Produtividade";

            require __DIR__ . '/../../templates/dashboard.php';
            
        } catch (Exception $e) {
            $erro = $e->getMessage();
            $tituloDaPagina = "Controle de Protocolos";
            
            //Se der erro chama a home.php, porém agora com o erro carregado
            require __DIR__ . '/../../templates/home.php';
        }
    }

public function notFound()
{
    http_response_code(404);

    $tituloDaPagina = "Página Não Encontrada";
    $mainClass = 'pagina-404'; 

    require __DIR__ . '/../../templates/404.php';
}
}